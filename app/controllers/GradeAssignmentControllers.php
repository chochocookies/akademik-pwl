<?php
// =============================================
// GRADE CONTROLLER
// =============================================
class GradeController extends Controller {

    public function index(): void {
        Middleware::role('admin', 'guru');

        $user    = Auth::user();
        $classes = [];
        $teacherId = null;

        if (Auth::is('guru')) {
            $teacher   = (new TeacherModel())->findByUserId($user['id']);
            $teacherId = $teacher['id'];
            $classes   = (new TeacherModel())->getClasses($teacherId);
        } else {
            $classes = (new ClassModel())->allWithDetails();
        }

        $this->view('grades.index', compact('classes', 'teacherId'));
    }

    public function byClass(string $classId): void {
        Middleware::role('admin', 'guru');

        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error', 'Kelas tidak ditemukan.'); redirect('/grades'); }

        $user     = Auth::user();
        $teacher  = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $subjects = $teacher 
            ? (new TeacherModel())->getSubjectsByClass($teacher['id'], (int)$classId)
            : (new SubjectModel())->all('nama_mapel');

        $selectedSubject = $this->get('subject_id') ? (int)$this->get('subject_id') : ($subjects[0]['id'] ?? null);
        $semester        = $this->get('semester', SEMESTER);
        $tahunAjaran     = TAHUN_AJARAN;

        $grades   = $selectedSubject 
            ? (new GradeModel())->getClassGrades((int)$classId, $selectedSubject, $semester, $tahunAjaran)
            : [];

        $this->view('grades.by_class', compact('class', 'subjects', 'selectedSubject', 'grades', 'semester', 'teacher'));
    }

    public function create(string $classId): void {
        Middleware::role('admin', 'guru');
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        $students = (new StudentModel())->byClass((int)$classId);
        $user     = Auth::user();
        $teacher  = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $subjects = $teacher
            ? (new TeacherModel())->getSubjectsByClass($teacher['id'], (int)$classId)
            : (new SubjectModel())->all('nama_mapel');

        $this->view('grades.create', compact('class', 'students', 'subjects', 'teacher'));
    }

    public function store(): void {
        Middleware::role('admin', 'guru');
        verify_csrf();

        $user      = Auth::user();
        $teacher   = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $teacherId = $teacher['id'] ?? (int)$this->post('teacher_id');
        $subjectId = (int)$this->post('subject_id');
        $semester  = $this->post('semester', SEMESTER);
        $classId   = (int)$this->post('class_id');

        $studentIds = $this->post('student_ids', []);
        $gradeModel = new GradeModel();

        try {
            $this->db->beginTransaction();
            foreach ($studentIds as $sid) {
                $sid = (int)$sid;
                $gradeModel->upsert([
                    'student_id'   => $sid,
                    'teacher_id'   => $teacherId,
                    'subject_id'   => $subjectId,
                    'nilai_harian' => (float)($_POST['nilai_harian'][$sid] ?? 0),
                    'nilai_uts'    => (float)($_POST['nilai_uts'][$sid] ?? 0),
                    'nilai_uas'    => (float)($_POST['nilai_uas'][$sid] ?? 0),
                    'semester'     => $semester,
                    'tahun_ajaran' => TAHUN_AJARAN,
                    'catatan'      => $_POST['catatan'][$sid] ?? null,
                ]);
            }
            $this->db->commit();
            Flash::set('success', 'Nilai berhasil disimpan untuk ' . count($studentIds) . ' siswa.');
        } catch (Exception $e) {
            $this->db->rollback();
            Flash::set('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }

        redirect('/grades/' . $classId);
    }

    public function myGrades(): void {
        Middleware::murid();
        $user    = Auth::user();
        $student = (new StudentModel())->findByUserId($user['id']);
        $grades  = (new GradeModel())->getStudentGrades($student['id'], SEMESTER, TAHUN_AJARAN);
        $this->view('grades.my_grades', compact('student', 'grades'));
    }
}

// =============================================
// ASSIGNMENT CONTROLLER
// =============================================
class AssignmentController extends Controller {

    public function index(): void {
        Middleware::auth();

        $user = Auth::user();

        if (Auth::is('murid')) {
            $student     = (new StudentModel())->findByUserId($user['id']);
            $assignments = (new AssignmentModel())->byClass($student['class_id'] ?? 0);
            $submissions = (new SubmissionModel())->getByStudent($student['id']);
            $submittedIds = array_column($submissions, 'assignment_id');
            $this->view('assignments.murid_index', compact('assignments', 'submissions', 'submittedIds', 'student'));
            return;
        }

        if (Auth::is('guru')) {
            $teacher     = (new TeacherModel())->findByUserId($user['id']);
            $assignments = (new AssignmentModel())->allWithDetails($teacher['id']);
            $this->view('assignments.guru_index', compact('assignments', 'teacher'));
            return;
        }

        // Admin
        $assignments = (new AssignmentModel())->allWithDetails();
        $this->view('assignments.admin_index', compact('assignments'));
    }

    public function create(): void {
        Middleware::guru();
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $classes = (new TeacherModel())->getClasses($teacher['id']);
        $this->view('assignments.create', compact('teacher', 'classes'));
    }

    public function getSubjectsByClass(): void {
        Middleware::guru();
        $classId = (int)$this->get('class_id');
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $subjects = (new TeacherModel())->getSubjectsByClass($teacher['id'], $classId);
        $this->json($subjects);
    }

    public function store(): void {
        Middleware::guru();
        verify_csrf();

        $v = Validator::make($_POST, [
            'judul'      => 'required|min:5',
            'class_id'   => 'required',
            'subject_id' => 'required',
            'deadline'   => 'required',
        ]);

        if ($v->fails()) {
            Flash::set('error', $v->firstError());
            redirect('/assignments/create');
        }

        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);

        (new AssignmentModel())->create([
            'teacher_id'  => $teacher['id'],
            'class_id'    => (int)$this->post('class_id'),
            'subject_id'  => (int)$this->post('subject_id'),
            'judul'       => $this->post('judul'),
            'deskripsi'   => $this->post('deskripsi'),
            'deadline'    => $this->post('deadline'),
            'max_nilai'   => (int)$this->post('max_nilai', 100),
        ]);

        Flash::set('success', 'Tugas berhasil dibuat.');
        redirect('/assignments');
    }

    public function show(string $id): void {
        Middleware::auth();
        $assignment  = (new AssignmentModel())->findWithDetails((int)$id);
        if (!$assignment) { Flash::set('error', 'Tugas tidak ditemukan.'); redirect('/assignments'); }

        $submissions = (new SubmissionModel())->getByAssignment((int)$id);
        $mySubmission = null;

        if (Auth::is('murid')) {
            $user    = Auth::user();
            $student = (new StudentModel())->findByUserId($user['id']);
            $mySubmission = (new SubmissionModel())->findByAssignmentAndStudent((int)$id, $student['id']);
        }

        $this->view('assignments.show', compact('assignment', 'submissions', 'mySubmission'));
    }

    public function edit(string $id): void {
        Middleware::guru();
        $assignment = (new AssignmentModel())->findWithDetails((int)$id);
        if (!$assignment) { Flash::set('error', 'Tugas tidak ditemukan.'); redirect('/assignments'); }
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $classes = (new TeacherModel())->getClasses($teacher['id']);
        $subjects = (new SubjectModel())->all('nama_mapel');
        $this->view('assignments.edit', compact('assignment', 'teacher', 'classes', 'subjects'));
    }

    public function update(string $id): void {
        Middleware::guru();
        verify_csrf();
        (new AssignmentModel())->update((int)$id, [
            'judul'      => $this->post('judul'),
            'deskripsi'  => $this->post('deskripsi'),
            'deadline'   => $this->post('deadline'),
            'max_nilai'  => (int)$this->post('max_nilai', 100),
        ]);
        Flash::set('success', 'Tugas berhasil diperbarui.');
        redirect('/assignments');
    }

    public function destroy(string $id): void {
        Middleware::guru();
        verify_csrf();
        (new AssignmentModel())->delete((int)$id);
        Flash::set('success', 'Tugas berhasil dihapus.');
        redirect('/assignments');
    }

    // BONUS: Murid submit tugas
    public function submit(string $id): void {
        Middleware::murid();
        verify_csrf();

        $user    = Auth::user();
        $student = (new StudentModel())->findByUserId($user['id']);
        $assignment = (new AssignmentModel())->find((int)$id);
        if (!$assignment) { Flash::set('error', 'Tugas tidak ditemukan.'); redirect('/assignments'); }

        $existing = (new SubmissionModel())->findByAssignmentAndStudent((int)$id, $student['id']);
        $isLate   = isDeadlinePassed($assignment['deadline']);

        $filePath = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, UPLOAD_ALLOWED_TYPES)) {
                Flash::set('error', 'Format file tidak didukung.');
                redirect('/assignments/' . $id);
            }
            $filename = uniqid('sub_') . '.' . $ext;
            $uploadPath = UPLOAD_PATH . $filename;
            if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
            move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
            $filePath = $filename;
        }

        $data = [
            'assignment_id' => (int)$id,
            'student_id'    => $student['id'],
            'catatan'       => $this->post('catatan'),
            'file_path'     => $filePath,
            'status'        => $isLate ? 'late' : 'submitted',
        ];

        if ($existing) {
            (new SubmissionModel())->update($existing['id'], $data);
        } else {
            (new SubmissionModel())->create($data);
        }

        Flash::set('success', $isLate ? 'Tugas dikumpulkan (terlambat).' : 'Tugas berhasil dikumpulkan!');
        redirect('/assignments/' . $id);
    }

    // Guru: beri nilai submission
    public function gradeSubmission(string $submissionId): void {
        Middleware::guru();
        verify_csrf();
        $nilai = (float)$this->post('nilai');
        (new SubmissionModel())->update((int)$submissionId, [
            'nilai'      => $nilai,
            'status'     => 'graded',
            'graded_at'  => date('Y-m-d H:i:s'),
        ]);
        Flash::set('success', 'Nilai submission berhasil disimpan.');
        $assignment_id = $this->post('assignment_id');
        redirect('/assignments/' . $assignment_id);
    }
}
