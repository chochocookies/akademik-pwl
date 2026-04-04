<?php
// =============================================
// APPLICATION CONFIGURATION
// =============================================

define('APP_NAME', 'SiAkad SD');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/akademik/public');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'akademik_sd');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session Configuration
define('SESSION_NAME', 'akademik_session');
define('SESSION_LIFETIME', 3600); // 1 hour

// Upload Configuration
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']);

// Tahun Ajaran & Semester aktif
define('TAHUN_AJARAN', '2024/2025');
define('SEMESTER', '1');

// Timezone
date_default_timezone_set('Asia/Jakarta');
