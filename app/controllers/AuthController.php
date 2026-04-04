<?php
class AuthController extends Controller {

    public function loginForm(): void {
        Middleware::guest();
        $this->view('auth.login');
    }

    public function login(): void {
        Middleware::guest();
        verify_csrf();

        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');

        $v = Validator::make(['email' => $email, 'password' => $password], [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($v->fails()) {
            $this->storeOld();
            Flash::set('error', $v->firstError());
            redirect('/login');
        }

        $user = (new UserModel())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->storeOld();
            Flash::set('error', 'Email atau password salah.');
            redirect('/login');
        }

        if (!$user['is_active']) {
            Flash::set('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
            redirect('/login');
        }

        Auth::login($user);
        Flash::set('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        match($user['role']) {
            'admin' => redirect('/dashboard/admin'),
            'guru'  => redirect('/dashboard/guru'),
            'murid' => redirect('/dashboard/murid'),
            default => redirect('/dashboard')
        };
    }

    public function logout(): void {
        Auth::logout();
        Flash::set('success', 'Anda berhasil logout.');
        redirect('/login');
    }

    public function dashboard(): void {
        Auth::requireLogin();
        match(Auth::role()) {
            'admin' => redirect('/dashboard/admin'),
            'guru'  => redirect('/dashboard/guru'),
            'murid' => redirect('/dashboard/murid'),
            default => redirect('/login')
        };
    }
}
