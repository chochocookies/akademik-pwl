<?php
// =============================================
// BASE CONTROLLER
// =============================================
abstract class Controller {
    protected Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function view(string $template, array $data = []): void {
        view($template, $data);
    }

    protected function redirect(string $path): never {
        redirect($path);
    }

    protected function json(mixed $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function input(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function post(string $key = null, mixed $default = null): mixed {
        if ($key === null) return $_POST;
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key = null, mixed $default = null): mixed {
        if ($key === null) return $_GET;
        return $_GET[$key] ?? $default;
    }

    protected function storeOld(): void {
        Auth::start();
        $_SESSION['old'] = $_POST;
    }

    protected function clearOld(): void {
        Auth::start();
        unset($_SESSION['old']);
    }
}

// =============================================
// BASE MODEL  
// =============================================
abstract class Model {
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?", [$id]);
    }

    public function all(string $orderBy = 'id', string $dir = 'ASC'): array {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY $orderBy $dir");
    }

    public function create(array $data): int {
        return $this->db->insert($this->table, $data);
    }

    public function update(int $id, array $data): int {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = ?", [$id]);
    }

    public function delete(int $id): int {
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    public function count(): int {
        return $this->db->count("SELECT COUNT(*) FROM {$this->table}");
    }

    public function where(string $column, mixed $value): array {
        return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE $column = ?", [$value]);
    }

    public function findWhere(string $column, mixed $value): ?array {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE $column = ?", [$value]);
    }
}
