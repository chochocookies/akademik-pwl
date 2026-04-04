<?php

// =============================================
// SUBJECT CONTROLLER
// =============================================
class SubjectController extends Controller {

    public function index(): void {
        Middleware::admin();
        $subjects = $this->db->fetchAll("
            SELECT s.*, COUNT(DISTINCT ts.teacher_id) as total_guru,
                   COUNT(DISTINCT g.id) as total_nilai
            FROM subjects s
            LEFT JOIN teacher_subjects ts ON s.id = ts.subject_id
            LEFT JOIN grades g ON s.id = g.subject_id
            GROUP BY s.id ORDER BY s.nama_mapel
        ");
        $this->view('subjects.index', compact('subjects'));
    }

    public function create(): void {
        Middleware::admin();
        $this->view('subjects.create');
    }

    public function store(): void {
        Middleware::admin();
        verify_csrf();
        $v = Validator::make($_POST, [
            'nama_mapel'  => 'required|min:2',
            'kode_mapel'  => 'required|min:2|max:10',
        ]);
        if ($v->fails()) {
            Flash::set('error', $v->firstError());
            redirect('/subjects/create');
        }
        (new SubjectModel())->create([
            'nama_mapel' => $this->post('nama_mapel'),
            'kode_mapel' => strtoupper($this->post('kode_mapel')),
            'deskripsi'  => $this->post('deskripsi'),
        ]);
        Flash::set('success', 'Mata pelajaran berhasil ditambahkan.');
        redirect('/subjects');
    }

    public function edit(string $id): void {
        Middleware::admin();
        $subject = (new SubjectModel())->find((int)$id);
        if (!$subject) { Flash::set('error', 'Mapel tidak ditemukan.'); redirect('/subjects'); }
        $this->view('subjects.edit', compact('subject'));
    }

    public function update(string $id): void {
        Middleware::admin();
        verify_csrf();
        (new SubjectModel())->update((int)$id, [
            'nama_mapel' => $this->post('nama_mapel'),
            'kode_mapel' => strtoupper($this->post('kode_mapel')),
            'deskripsi'  => $this->post('deskripsi'),
        ]);
        Flash::set('success', 'Mata pelajaran diperbarui.');
        redirect('/subjects');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();
        (new SubjectModel())->delete((int)$id);
        Flash::set('success', 'Mata pelajaran dihapus.');
        redirect('/subjects');
    }
}

// =============================================
// USER CONTROLLER (admin)
// =============================================
class UserController extends Controller {

    public function index(): void {
        Middleware::admin();
        $users = $this->db->fetchAll("
            SELECT u.*,
                   COALESCE(t.nip, s.nis) as identifier,
                   c.nama_kelas
            FROM users u
            LEFT JOIN teachers t ON u.id = t.user_id
            LEFT JOIN students s ON u.id = s.user_id
            LEFT JOIN classes c ON s.class_id = c.id
            ORDER BY u.role, u.name
        ");
        $stats = (new UserModel())->stats();
        $this->view('users.index', compact('users', 'stats'));
    }

    public function toggleActive(string $id): void {
        Middleware::admin();
        verify_csrf();
        if ((int)$id === Auth::id()) {
            Flash::set('error', 'Tidak bisa menonaktifkan akun sendiri.');
            redirect('/users');
        }
        $user = (new UserModel())->find((int)$id);
        if ($user) {
            (new UserModel())->update((int)$id, ['is_active' => $user['is_active'] ? 0 : 1]);
            Flash::set('success', 'Status akun diubah.');
        }
        redirect('/users');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();
        if ((int)$id === Auth::id()) {
            Flash::set('error', 'Tidak bisa menghapus akun sendiri.');
            redirect('/users');
        }
        (new UserModel())->delete((int)$id);
        Flash::set('success', 'User dihapus.');
        redirect('/users');
    }
}

// =============================================
// PROFILE CONTROLLER
// =============================================
class ProfileController extends Controller {

    public function show(): void {
        Middleware::auth();
        $user    = Auth::user();
        $full    = (new UserModel())->find($user['id']);
        $extra   = null;
        if (Auth::is('guru'))  $extra = (new TeacherModel())->findByUserId($user['id']);
        if (Auth::is('murid')) $extra = (new StudentModel())->findByUserId($user['id']);
        $this->view('profile.show', compact('full', 'extra'));
    }

    public function update(): void {
        Middleware::auth();
        verify_csrf();
        $user = Auth::user();
        $v = Validator::make($_POST, [
            'name'  => 'required|min:3',
            'email' => 'required|email',
        ]);
        if ($v->fails()) {
            Flash::set('error', $v->firstError());
            redirect('/profile');
        }
        (new UserModel())->update($user['id'], [
            'name'  => $this->post('name'),
            'email' => $this->post('email'),
        ]);
        // Update session
        Auth::start();
        $_SESSION['user_name']  = $this->post('name');
        $_SESSION['user_email'] = $this->post('email');

        Flash::set('success', 'Profil berhasil diperbarui.');
        redirect('/profile');
    }

    public function changePassword(): void {
        Middleware::auth();
        verify_csrf();
        $user = Auth::user();
        $full = (new UserModel())->find($user['id']);

        $current = $this->post('current_password');
        $new     = $this->post('new_password');
        $confirm = $this->post('confirm_password');

        if (!password_verify($current, $full['password'])) {
            Flash::set('error', 'Password lama tidak sesuai.');
            redirect('/profile');
        }
        if (strlen($new) < 6) {
            Flash::set('error', 'Password baru minimal 6 karakter.');
            redirect('/profile');
        }
        if ($new !== $confirm) {
            Flash::set('error', 'Konfirmasi password tidak sesuai.');
            redirect('/profile');
        }

        (new UserModel())->update($user['id'], [
            'password' => password_hash($new, PASSWORD_DEFAULT),
        ]);
        Flash::set('success', 'Password berhasil diubah.');
        redirect('/profile');
    }
}
