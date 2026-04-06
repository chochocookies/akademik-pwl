<?php
class AuthController extends Controller {

    public function loginForm(): void {
        Middleware::guest();
        // Check remember me cookie first
        if (!Auth::check()) {
            $remembered = Security::checkRememberToken();
            if ($remembered) {
                Auth::login($remembered);
                Security::setFingerprint();
                redirect('/dashboard');
            }
        }
        $captcha = Security::generateCaptcha();
        $this->view('auth.login', compact('captcha'));
    }

    public function login(): void {
        Middleware::guest();
        verify_csrf();

        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');
        $captchaInput = $this->post('captcha','');
        $ip       = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // ── Captcha check ────────────────────────────────────────
        if (!Security::verifyCaptcha($captchaInput)) {
            $this->storeOld();
            Flash::set('error', 'Jawaban soal matematika salah. Coba lagi!');
            redirect('/login');
        }

        // ── Rate limiting ─────────────────────────────────────────
        if (!Security::checkLoginAttempts($ip, $email)) {
            $remaining = 15; // wait 15 minutes
            Flash::set('error', "Terlalu banyak percobaan login. Coba lagi dalam {$remaining} menit.");
            redirect('/login');
        }

        // ── Validation ────────────────────────────────────────────
        $v = Validator::make(['email'=>$email,'password'=>$password], [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($v->fails()) {
            $this->storeOld();
            Flash::set('error', $v->firstError());
            redirect('/login');
        }

        // ── Find user ─────────────────────────────────────────────
        $user = (new UserModel())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Security::recordLoginAttempt($ip, $email);
            $remaining = Security::getRemainingAttempts($ip, $email);
            $this->storeOld();
            $msg = $remaining <= 2
                ? "Email atau password salah. Sisa percobaan: {$remaining}"
                : 'Email atau password salah.';
            Flash::set('error', $msg);
            redirect('/login');
        }

        if (!$user['is_active']) {
            Flash::set('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
            redirect('/login');
        }

        // ── Success ───────────────────────────────────────────────
        Security::clearLoginAttempts($ip, $email);
        Auth::login($user);
        Security::setFingerprint();

        // Remember me
        if ($this->post('remember_me')) {
            Security::createRememberToken($user['id']);
        }

        // Audit log
        Security::audit('LOGIN', 'users', $user['id']);

        Flash::set('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        match($user['role']) {
            'admin' => redirect('/dashboard/admin'),
            'guru'  => redirect('/dashboard/guru'),
            'murid' => redirect('/dashboard/murid'),
            default => redirect('/dashboard')
        };
    }

    public function logout(): void {
        if (Auth::check()) {
            Security::audit('LOGOUT', 'users', Auth::id());
            Security::clearRememberToken();
        }
        Auth::logout();
        Flash::set('success', 'Anda berhasil logout.');
        redirect('/login');
    }

    public function dashboard(): void {
        Auth::requireLogin();
        // Check session fingerprint
        if (!Security::checkFingerprint()) {
            Auth::logout();
            Flash::set('error', 'Sesi Anda tidak valid. Silakan login kembali.');
            redirect('/login');
        }
        match(Auth::role()) {
            'admin' => redirect('/dashboard/admin'),
            'guru'  => redirect('/dashboard/guru'),
            'murid' => redirect('/dashboard/murid'),
            default => redirect('/login')
        };
    }
}
