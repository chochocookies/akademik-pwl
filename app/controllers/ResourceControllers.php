<?php
// =============================================
// STUDENT CONTROLLER
// =============================================
class StudentController extends Controller {

    public function index(): void {
        Middleware::role('admin', 'guru');
        $students = (new StudentModel())->allWithDetails();
        $this->view('students.index', compact('students'));
    }

    public function create(): void {
        Middleware::admin();
        $classes = (new ClassModel())->all('nama_kelas');
        $this->view('students.create', compact('classes'));
    }

    public function store(): void {
        Middleware::admin();
        verify_csrf();

        $v = Validator::make($_POST, [
            'name'     => 'required|min:3',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'nis'      => 'required',
            'gender'   => 'required',
        ]);

        if ($v->fails()) {
            $this->storeOld();
            Flash::set('error', $v->firstError());
            redirect('/students/create');
        }

        try {
            $this->db->beginTransaction();

            $userId = (new UserModel())->create([
                'name'     => $this->post('name'),
                'email'    => $this->post('email'),
                'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                'role'     => 'murid',
            ]);

            (new StudentModel())->create([
                'user_id'     => $userId,
                'class_id'    => $this->post('class_id') ?: null,
                'nis'         => $this->post('nis'),
                'gender'      => $this->post('gender'),
                'birth_date'  => $this->post('birth_date') ?: null,
                'parent_name' => $this->post('parent_name'),
                'phone'       => $this->post('phone'),
                'address'     => $this->post('address'),
            ]);

            $this->db->commit();
            Flash::set('success', 'Data siswa berhasil ditambahkan.');
            redirect('/students');
        } catch (Exception $e) {
            $this->db->rollback();
            Flash::set('error', 'Gagal menyimpan data: ' . $e->getMessage());
            redirect('/students/create');
        }
    }

    public function show(string $id): void {
        Middleware::role('admin', 'guru');
        $student = (new StudentModel())->findWithDetails((int)$id);
        if (!$student) { Flash::set('error', 'Siswa tidak ditemukan.'); redirect('/students'); }
        $grades = (new GradeModel())->getStudentGrades((int)$id);
        $this->view('students.show', compact('student', 'grades'));
    }

    public function edit(string $id): void {
        Middleware::admin();
        $student = (new StudentModel())->findWithDetails((int)$id);
        if (!$student) { Flash::set('error', 'Siswa tidak ditemukan.'); redirect('/students'); }
        $classes = (new ClassModel())->all('nama_kelas');
        $this->view('students.edit', compact('student', 'classes'));
    }

    public function update(string $id): void {
        Middleware::admin();
        verify_csrf();

        $student = (new StudentModel())->find((int)$id);
        if (!$student) { Flash::set('error', 'Siswa tidak ditemukan.'); redirect('/students'); }

        (new UserModel())->update($student['user_id'], [
            'name'  => $this->post('name'),
            'email' => $this->post('email'),
        ]);

        (new StudentModel())->update((int)$id, [
            'class_id'    => $this->post('class_id') ?: null,
            'nis'         => $this->post('nis'),
            'gender'      => $this->post('gender'),
            'birth_date'  => $this->post('birth_date') ?: null,
            'parent_name' => $this->post('parent_name'),
            'phone'       => $this->post('phone'),
            'address'     => $this->post('address'),
        ]);

        Flash::set('success', 'Data siswa berhasil diperbarui.');
        redirect('/students');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();

        $student = (new StudentModel())->find((int)$id);
        if ($student) {
            (new UserModel())->delete($student['user_id']); // cascade deletes student
            Flash::set('success', 'Data siswa berhasil dihapus.');
        }
        redirect('/students');
    }
}

// =============================================
// TEACHER CONTROLLER
// =============================================
class TeacherController extends Controller {

    public function index(): void {
        Middleware::admin();
        $teachers = (new TeacherModel())->allWithDetails();
        $this->view('teachers.index', compact('teachers'));
    }

    public function create(): void {
        Middleware::admin();
        $classes  = (new ClassModel())->all('nama_kelas');
        $subjects = (new SubjectModel())->all('nama_mapel');
        $this->view('teachers.create', compact('classes', 'subjects'));
    }

    public function store(): void {
        Middleware::admin();
        verify_csrf();

        $v = Validator::make($_POST, [
            'name'     => 'required|min:3',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($v->fails()) {
            $this->storeOld();
            Flash::set('error', $v->firstError());
            redirect('/teachers/create');
        }

        try {
            $this->db->beginTransaction();
            $userId = (new UserModel())->create([
                'name'     => $this->post('name'),
                'email'    => $this->post('email'),
                'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                'role'     => 'guru',
            ]);
            $teacherId = (new TeacherModel())->create([
                'user_id' => $userId,
                'nip'     => $this->post('nip'),
                'phone'   => $this->post('phone'),
                'address' => $this->post('address'),
            ]);

            // Assign subjects to classes
            $classIds   = $this->post('class_ids', []);
            $subjectIds = $this->post('subject_ids', []);
            foreach ($classIds as $cId) {
                foreach ($subjectIds as $sId) {
                    $this->db->query(
                        "INSERT IGNORE INTO teacher_subjects (teacher_id, subject_id, class_id) VALUES (?,?,?)",
                        [$teacherId, $sId, $cId]
                    );
                }
            }

            $this->db->commit();
            Flash::set('success', 'Data guru berhasil ditambahkan.');
            redirect('/teachers');
        } catch (Exception $e) {
            $this->db->rollback();
            Flash::set('error', 'Gagal menyimpan: ' . $e->getMessage());
            redirect('/teachers/create');
        }
    }

    public function show(string $id): void {
        Middleware::admin();
        $teacher = (new TeacherModel())->findWithDetails((int)$id);
        if (!$teacher) { Flash::set('error', 'Guru tidak ditemukan.'); redirect('/teachers'); }
        $classes = (new TeacherModel())->getClasses((int)$id);
        $this->view('teachers.show', compact('teacher', 'classes'));
    }

    public function edit(string $id): void {
        Middleware::admin();
        $teacher  = (new TeacherModel())->findWithDetails((int)$id);
        if (!$teacher) { Flash::set('error', 'Guru tidak ditemukan.'); redirect('/teachers'); }
        $classes  = (new ClassModel())->all('nama_kelas');
        $subjects = (new SubjectModel())->all('nama_mapel');
        $this->view('teachers.edit', compact('teacher', 'classes', 'subjects'));
    }

    public function update(string $id): void {
        Middleware::admin();
        verify_csrf();
        $teacher = (new TeacherModel())->find((int)$id);
        if (!$teacher) { Flash::set('error', 'Guru tidak ditemukan.'); redirect('/teachers'); }

        (new UserModel())->update($teacher['user_id'], [
            'name'  => $this->post('name'),
            'email' => $this->post('email'),
        ]);
        (new TeacherModel())->update((int)$id, [
            'nip'     => $this->post('nip'),
            'phone'   => $this->post('phone'),
            'address' => $this->post('address'),
        ]);
        Flash::set('success', 'Data guru berhasil diperbarui.');
        redirect('/teachers');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();
        $teacher = (new TeacherModel())->find((int)$id);
        if ($teacher) {
            (new UserModel())->delete($teacher['user_id']);
            Flash::set('success', 'Data guru berhasil dihapus.');
        }
        redirect('/teachers');
    }
}

// =============================================
// CLASS CONTROLLER
// =============================================
class ClassController extends Controller {

    public function index(): void {
        Middleware::role('admin', 'guru');
        $classes = (new ClassModel())->allWithDetails();
        $this->view('classes.index', compact('classes'));
    }

    public function create(): void {
        Middleware::admin();
        $teachers = (new UserModel())->allWithRole('guru');
        $this->view('classes.create', compact('teachers'));
    }

    public function store(): void {
        Middleware::admin();
        verify_csrf();

        $v = Validator::make($_POST, ['nama_kelas' => 'required', 'tingkat' => 'required']);
        if ($v->fails()) {
            Flash::set('error', $v->firstError());
            redirect('/classes/create');
        }

        (new ClassModel())->create([
            'nama_kelas'     => $this->post('nama_kelas'),
            'tingkat'        => $this->post('tingkat'),
            'wali_kelas_id'  => $this->post('wali_kelas_id') ?: null,
            'tahun_ajaran'   => TAHUN_AJARAN,
        ]);

        Flash::set('success', 'Kelas berhasil dibuat.');
        redirect('/classes');
    }

    public function show(string $id): void {
        Middleware::role('admin', 'guru');
        $class    = (new ClassModel())->findWithDetails((int)$id);
        if (!$class) { Flash::set('error', 'Kelas tidak ditemukan.'); redirect('/classes'); }
        $students    = (new StudentModel())->byClass((int)$id);
        $assignments = (new AssignmentModel())->byClass((int)$id);
        $this->view('classes.show', compact('class', 'students', 'assignments'));
    }

    public function edit(string $id): void {
        Middleware::admin();
        $class    = (new ClassModel())->find((int)$id);
        if (!$class) { Flash::set('error', 'Kelas tidak ditemukan.'); redirect('/classes'); }
        $teachers = (new UserModel())->allWithRole('guru');
        $this->view('classes.edit', compact('class', 'teachers'));
    }

    public function update(string $id): void {
        Middleware::admin();
        verify_csrf();
        (new ClassModel())->update((int)$id, [
            'nama_kelas'    => $this->post('nama_kelas'),
            'tingkat'       => $this->post('tingkat'),
            'wali_kelas_id' => $this->post('wali_kelas_id') ?: null,
        ]);
        Flash::set('success', 'Kelas berhasil diperbarui.');
        redirect('/classes');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();
        (new ClassModel())->delete((int)$id);
        Flash::set('success', 'Kelas berhasil dihapus.');
        redirect('/classes');
    }
}
