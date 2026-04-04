<?php
class Auth {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params(SESSION_LIFETIME);
            session_start();
        }
    }

    public static function login(array $user): void {
        self::start();
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email']= $user['email'];
        session_regenerate_id(true);
    }

    public static function logout(): void {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        self::start();
        if (!self::check()) return null;
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'role'  => $_SESSION['user_role'],
            'email' => $_SESSION['user_email'],
        ];
    }

    public static function id(): ?int {
        return self::check() ? (int)$_SESSION['user_id'] : null;
    }

    public static function role(): ?string {
        return self::check() ? $_SESSION['user_role'] : null;
    }

    public static function is(string ...$roles): bool {
        return in_array(self::role(), $roles, true);
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            Flash::set('error', 'Silakan login terlebih dahulu.');
            redirect('/login');
        }
    }

    public static function requireRole(string ...$roles): void {
        self::requireLogin();
        if (!self::is(...$roles)) {
            Flash::set('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('/dashboard');
        }
    }
}
