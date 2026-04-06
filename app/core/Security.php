<?php
/**
 * Security helpers: rate limiting, remember me, audit logs, 
 * session fingerprinting, math captcha
 */
class Security {

    // ── Rate Limiting (brute force protection) ─────────────────────
    public static function checkLoginAttempts(string $ip, string $email, int $maxAttempts = 5, int $windowMinutes = 15): bool {
        $db  = Database::getInstance();
        $since = date('Y-m-d H:i:s', time() - $windowMinutes * 60);
        $count = $db->count(
            "SELECT COUNT(*) FROM login_attempts WHERE ip=? AND email=? AND attempted_at > ?",
            [$ip, $email, $since]
        );
        return $count < $maxAttempts;
    }

    public static function recordLoginAttempt(string $ip, string $email): void {
        Database::getInstance()->insert('login_attempts', ['ip'=>$ip,'email'=>$email]);
    }

    public static function clearLoginAttempts(string $ip, string $email): void {
        Database::getInstance()->delete('login_attempts','ip=? AND email=?',[$ip,$email]);
    }

    public static function getRemainingAttempts(string $ip, string $email, int $max=5, int $window=15): int {
        $db    = Database::getInstance();
        $since = date('Y-m-d H:i:s', time() - $window * 60);
        $used  = $db->count("SELECT COUNT(*) FROM login_attempts WHERE ip=? AND email=? AND attempted_at>?",[$ip,$email,$since]);
        return max(0, $max - $used);
    }

    // ── Remember Me ────────────────────────────────────────────────
    public static function createRememberToken(int $userId): string {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 30 * 86400); // 30 days
        Database::getInstance()->query(
            "DELETE FROM remember_tokens WHERE user_id=?", [$userId]
        );
        Database::getInstance()->insert('remember_tokens', [
            'user_id'    => $userId,
            'token'      => $token,
            'expires_at' => $expires,
        ]);
        setcookie('remember_token', $token, time() + 30*86400, '/', '', false, true);
        return $token;
    }

    public static function checkRememberToken(): ?array {
        $token = $_COOKIE['remember_token'] ?? null;
        if (!$token) return null;
        $db  = Database::getInstance();
        $row = $db->fetch(
            "SELECT rt.*, u.* FROM remember_tokens rt JOIN users u ON rt.user_id=u.id
             WHERE rt.token=? AND rt.expires_at > NOW() AND u.is_active=1",
            [$token]
        );
        if (!$row) { setcookie('remember_token','',time()-3600,'/'); return null; }
        return $row;
    }

    public static function clearRememberToken(): void {
        $token = $_COOKIE['remember_token'] ?? null;
        if ($token) {
            Database::getInstance()->delete('remember_tokens','token=?',[$token]);
            setcookie('remember_token','',time()-3600,'/');
        }
    }

    // ── Audit Log ──────────────────────────────────────────────────
    public static function audit(string $aksi, string $tabel='', int $recordId=0, array $dataLama=[], array $dataBaru=[]): void {
        try {
            Database::getInstance()->insert('audit_logs', [
                'user_id'    => Auth::id(),
                'aksi'       => $aksi,
                'tabel'      => $tabel,
                'record_id'  => $recordId ?: null,
                'data_lama'  => $dataLama ? json_encode($dataLama, JSON_UNESCAPED_UNICODE) : null,
                'data_baru'  => $dataBaru ? json_encode($dataBaru, JSON_UNESCAPED_UNICODE) : null,
                'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ]);
        } catch (\Exception $e) {
            // Silent fail — don't break main flow
        }
    }

    // ── Session Fingerprinting ─────────────────────────────────────
    public static function setFingerprint(): void {
        Auth::start();
        $_SESSION['_fingerprint'] = self::makeFingerprint();
    }

    public static function checkFingerprint(): bool {
        Auth::start();
        if (empty($_SESSION['_fingerprint'])) return true; // Not set yet, allow
        return $_SESSION['_fingerprint'] === self::makeFingerprint();
    }

    private static function makeFingerprint(): string {
        return hash('sha256',
            ($_SERVER['HTTP_USER_AGENT'] ?? '') .
            ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '')
            // Note: NOT using IP to avoid issues with mobile/VPN users
        );
    }

    // ── Math Captcha (for SD students) ────────────────────────────
    public static function generateCaptcha(): array {
        $ops = ['+','+','+','-'];
        $op  = $ops[array_rand($ops)];
        if ($op === '+') {
            $a = rand(1, 20); $b = rand(1, 20);
            $answer = $a + $b;
        } else {
            $a = rand(5, 20); $b = rand(1, $a);
            $answer = $a - $b;
        }
        Auth::start();
        $_SESSION['captcha_answer'] = $answer;
        return ['question' => "$a $op $b = ?", 'answer' => $answer];
    }

    public static function verifyCaptcha(string $input): bool {
        Auth::start();
        $expected = $_SESSION['captcha_answer'] ?? null;
        unset($_SESSION['captcha_answer']);
        return $expected !== null && (int)$input === (int)$expected;
    }
}
