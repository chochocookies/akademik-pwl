<?php
// =============================================
// MIDDLEWARE
// =============================================
class Middleware {
    public static function admin(): void   { Auth::requireRole('admin'); }
    public static function guru(): void    { Auth::requireRole('guru', 'admin'); }
    public static function murid(): void   { Auth::requireRole('murid'); }
    public static function auth(): void    { Auth::requireLogin(); }
    public static function role(string ...$roles): void { Auth::requireRole(...$roles); }
    public static function guest(): void {
        if (Auth::check()) redirect('/dashboard');
    }
}

// =============================================
// FLASH MESSAGES
// =============================================
class Flash {
    public static function set(string $type, string $message): void {
        Auth::start();
        $_SESSION['flash'][$type] = $message;
    }

    public static function get(string $type): ?string {
        Auth::start();
        $msg = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $msg;
    }

    public static function has(string $type): bool {
        return !empty($_SESSION['flash'][$type]);
    }

    public static function all(): array {
        Auth::start();
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    public static function render(): string {
        $html = '';
        foreach (self::all() as $type => $message) {
            $colors = [
                'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                'error'   => 'bg-red-50 border-red-200 text-red-800',
                'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
                'info'    => 'bg-blue-50 border-blue-200 text-blue-800',
            ];
            $icons = [
                'success' => '✓',
                'error'   => '✕',
                'warning' => '⚠',
                'info'    => 'ℹ',
            ];
            $cls  = $colors[$type] ?? $colors['info'];
            $icon = $icons[$type] ?? $icons['info'];
            $html .= "<div class=\"flash-message border rounded-xl px-4 py-3 mb-3 flex items-center gap-3 $cls\" 
                           role=\"alert\" data-auto-dismiss>
                        <span class=\"text-lg font-bold\">$icon</span>
                        <span class=\"text-sm font-medium\">" . htmlspecialchars($message) . "</span>
                        <button onclick=\"this.parentElement.remove()\" class=\"ml-auto text-current opacity-60 hover:opacity-100 text-lg leading-none\">&times;</button>
                      </div>";
        }
        return $html;
    }
}

// =============================================
// VALIDATOR
// =============================================
class Validator {
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public static function make(array $data, array $rules): self {
        $v = new self($data);
        foreach ($rules as $field => $ruleStr) {
            $v->validate($field, $ruleStr);
        }
        return $v;
    }

    private function validate(string $field, string $ruleStr): void {
        $rules = explode('|', $ruleStr);
        $value = $this->data[$field] ?? null;
        $label = ucfirst(str_replace('_', ' ', $field));

        foreach ($rules as $rule) {
            [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);
            match($ruleName) {
                'required' => (!isset($value) || $value === '') && $this->addError($field, "$label wajib diisi"),
                'email'    => $value && !filter_var($value, FILTER_VALIDATE_EMAIL) && $this->addError($field, "$label harus berformat email valid"),
                'min'      => $value && strlen($value) < (int)$param && $this->addError($field, "$label minimal $param karakter"),
                'max'      => $value && strlen($value) > (int)$param && $this->addError($field, "$label maksimal $param karakter"),
                'numeric'  => $value && !is_numeric($value) && $this->addError($field, "$label harus berupa angka"),
                'between'  => (function() use ($field, $value, $param, $label) {
                    [$min, $max] = explode(',', $param);
                    if ($value !== '' && $value !== null && ($value < $min || $value > $max)) {
                        $this->addError($field, "$label harus antara $min dan $max");
                    }
                })(),
                default    => null
            };
        }
    }

    private function addError(string $field, string $msg): void {
        $this->errors[$field] = $msg;
    }

    public function fails(): bool { return !empty($this->errors); }
    public function passes(): bool { return empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function firstError(): ?string { return array_values($this->errors)[0] ?? null; }
}

// =============================================
// GLOBAL HELPER FUNCTIONS
// =============================================
function redirect(string $path): never {
    $url = strpos($path, 'http') === 0 ? $path : APP_URL . $path;
    header("Location: $url");
    exit;
}

function view(string $template, array $data = []): void {
    extract($data);
    $file = __DIR__ . '/../../views/' . str_replace('.', '/', $template) . '.php';
    if (!file_exists($file)) {
        die("View not found: $template ($file)");
    }
    require $file;
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, mixed $default = ''): string {
    Auth::start();
    return e($_SESSION['old'][$key] ?? $default);
}

function csrf_token(): string {
    Auth::start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}

function verify_csrf(): void {
    Auth::start();
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        Flash::set('error', 'Token keamanan tidak valid. Silakan coba lagi.');
        redirect('/dashboard');
    }
}

function method_field(string $method): string {
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}

function number_format_id(float $num, int $decimals = 2): string {
    return number_format($num, $decimals, ',', '.');
}

function grade_color(float $nilai): string {
    return match(true) {
        $nilai >= 90 => 'text-emerald-600',
        $nilai >= 80 => 'text-blue-600',
        $nilai >= 70 => 'text-amber-600',
        default      => 'text-red-600'
    };
}

function grade_badge(float $nilai): string {
    return match(true) {
        $nilai >= 90 => '<span class="badge badge-success">A</span>',
        $nilai >= 80 => '<span class="badge badge-info">B</span>',
        $nilai >= 70 => '<span class="badge badge-warning">C</span>',
        default      => '<span class="badge badge-danger">D</span>'
    };
}

function timeAgo(string $datetime): string {
    $time = time() - strtotime($datetime);
    return match(true) {
        $time < 60     => 'baru saja',
        $time < 3600   => floor($time/60) . ' menit lalu',
        $time < 86400  => floor($time/3600) . ' jam lalu',
        $time < 604800 => floor($time/86400) . ' hari lalu',
        default        => date('d M Y', strtotime($datetime))
    };
}

function formatDate(string $date, string $format = 'd M Y'): string {
    return date($format, strtotime($date));
}

function isDeadlinePassed(string $deadline): bool {
    return strtotime($deadline) < time();
}

function asset(string $path): string {
    return APP_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string {
    return APP_URL . '/' . ltrim($path, '/');
}
