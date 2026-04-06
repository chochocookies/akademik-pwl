-- =============================================
-- SISTEM MANAJEMEN AKADEMIK SEKOLAH DASAR
-- Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS akademik_sd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE akademik_sd;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','guru','murid') NOT NULL DEFAULT 'murid',
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Classes table
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    tingkat ENUM('1','2','3','4','5','6') NOT NULL,
    wali_kelas_id INT DEFAULT NULL,
    tahun_ajaran VARCHAR(10) NOT NULL DEFAULT '2024/2025',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (wali_kelas_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Teachers table
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nip VARCHAR(20) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    class_id INT DEFAULT NULL,
    nis VARCHAR(20) UNIQUE NOT NULL,
    gender ENUM('L','P') NOT NULL,
    birth_date DATE,
    parent_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL
);

-- Subjects table
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mapel VARCHAR(100) NOT NULL,
    kode_mapel VARCHAR(10) UNIQUE NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Teacher-Subject-Class pivot
CREATE TABLE teacher_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    class_id INT NOT NULL,
    UNIQUE KEY unique_assignment (teacher_id, subject_id, class_id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Grades table
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    nilai_harian DECIMAL(5,2) DEFAULT 0,
    nilai_uts DECIMAL(5,2) DEFAULT 0,
    nilai_uas DECIMAL(5,2) DEFAULT 0,
    nilai_akhir DECIMAL(5,2) GENERATED ALWAYS AS ((nilai_harian * 0.3) + (nilai_uts * 0.3) + (nilai_uas * 0.4)) STORED,
    semester ENUM('1','2') NOT NULL DEFAULT '1',
    tahun_ajaran VARCHAR(10) NOT NULL DEFAULT '2024/2025',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_grade (student_id, subject_id, semester, tahun_ajaran),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Assignments table
CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    deadline DATETIME NOT NULL,
    max_nilai INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Submissions table (BONUS)
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    catatan TEXT,
    file_path VARCHAR(255),
    nilai DECIMAL(5,2) DEFAULT NULL,
    status ENUM('submitted','graded','late') DEFAULT 'submitted',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    graded_at TIMESTAMP NULL,
    UNIQUE KEY unique_submission (assignment_id, student_id),
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- =============================================
-- SEED DATA
-- =============================================

-- Default admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'budi@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
('Siti Rahayu', 'siti@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
('Ahmad Fauzi', 'ahmad@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
('Dewi Lestari', 'dewi@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
('Rizki Pratama', 'rizki@sekolah.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid');

-- Teachers
INSERT INTO teachers (user_id, nip) VALUES (2, '198501012010011001'), (3, '199002022012012002');

-- Classes
INSERT INTO classes (nama_kelas, tingkat, wali_kelas_id, tahun_ajaran) VALUES
('Kelas 4A', '4', 2, '2024/2025'),
('Kelas 5B', '5', 3, '2024/2025'),
('Kelas 6A', '6', 2, '2024/2025');

-- Students
INSERT INTO students (user_id, class_id, nis, gender, birth_date, parent_name) VALUES
(4, 1, '2024001', 'L', '2013-05-15', 'Fauzi Sr.'),
(5, 1, '2024002', 'P', '2013-08-22', 'Lestari Sr.'),
(6, 2, '2024003', 'L', '2012-11-10', 'Pratama Sr.');

-- Subjects
INSERT INTO subjects (nama_mapel, kode_mapel) VALUES
('Matematika', 'MTK'),
('Bahasa Indonesia', 'BIN'),
('IPA', 'IPA'),
('IPS', 'IPS'),
('Pendidikan Agama Islam', 'PAI'),
('PJOK', 'PJOK'),
('Seni Budaya', 'SBK');

-- Teacher-Subject assignments
INSERT INTO teacher_subjects (teacher_id, subject_id, class_id) VALUES
(1, 1, 1), (1, 2, 1), (1, 3, 1),
(2, 1, 2), (2, 2, 2), (2, 4, 2);

-- Sample grades
INSERT INTO grades (student_id, teacher_id, subject_id, nilai_harian, nilai_uts, nilai_uas, semester, tahun_ajaran) VALUES
(1, 1, 1, 85, 80, 88, '1', '2024/2025'),
(1, 1, 2, 90, 85, 92, '1', '2024/2025'),
(2, 1, 1, 78, 82, 80, '1', '2024/2025'),
(2, 1, 2, 88, 90, 87, '1', '2024/2025'),
(3, 2, 1, 92, 88, 95, '1', '2024/2025');

-- Sample assignments
INSERT INTO assignments (teacher_id, class_id, subject_id, judul, deskripsi, deadline) VALUES
(1, 1, 1, 'Latihan Soal Pecahan', 'Kerjakan soal pecahan halaman 45-47', DATE_ADD(NOW(), INTERVAL 7 DAY)),
(1, 1, 2, 'Menulis Karangan', 'Tulis karangan tentang liburanmu min. 200 kata', DATE_ADD(NOW(), INTERVAL 5 DAY)),
(2, 2, 1, 'PR Perkalian Bersusun', 'Kerjakan 20 soal perkalian bersusun', DATE_ADD(NOW(), INTERVAL 3 DAY));

-- =============================================
-- ABSENSI TABLES (New Feature)
-- =============================================

CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_session (class_id, subject_id, tanggal),
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('hadir','sakit','izin','alpha') NOT NULL DEFAULT 'hadir',
    catatan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (session_id, student_id),
    FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- =============================================
-- RAPOR DIGITAL
-- =============================================
CREATE TABLE IF NOT EXISTS report_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    semester ENUM('1','2') NOT NULL,
    tahun_ajaran VARCHAR(10) NOT NULL,
    catatan_wali TEXT,
    catatan_kepala TEXT,
    predikat_sikap ENUM('A','B','C','D') DEFAULT 'B',
    predikat_keterampilan ENUM('A','B','C','D') DEFAULT 'B',
    ranking INT DEFAULT NULL,
    created_by INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_note (student_id, semester, tahun_ajaran),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- =============================================
-- KALENDER AKADEMIK
-- =============================================
CREATE TABLE IF NOT EXISTS academic_calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    judul VARCHAR(200) NOT NULL,
    tipe ENUM('libur','ujian','event','lainnya') NOT NULL DEFAULT 'event',
    deskripsi TEXT,
    warna VARCHAR(7) DEFAULT '#3B82F6',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    hari ENUM('senin','selasa','rabu','kamis','jumat','sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    ruangan VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_slot (class_id, hari, jam_mulai),
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Seed kalender
INSERT INTO academic_calendar (tanggal_mulai, tanggal_selesai, judul, tipe, warna, created_by) VALUES
('2024-08-19', '2024-08-19', 'Hari Pertama Sekolah', 'event', '#10B981', 1),
('2024-10-14', '2024-10-19', 'Ujian Tengah Semester 1', 'ujian', '#F59E0B', 1),
('2024-12-02', '2024-12-14', 'Ujian Akhir Semester 1', 'ujian', '#EF4444', 1),
('2024-12-25', '2024-12-25', 'Hari Natal', 'libur', '#6366F1', 1),
('2025-01-01', '2025-01-01', 'Tahun Baru 2025', 'libur', '#6366F1', 1),
('2025-01-06', '2025-01-06', 'Awal Semester 2', 'event', '#10B981', 1);

-- =============================================
-- JURNAL MENGAJAR
-- =============================================
CREATE TABLE IF NOT EXISTS teaching_journals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendance_session_id INT DEFAULT NULL,
    teacher_id INT NOT NULL,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    tanggal DATE NOT NULL,
    materi_pokok VARCHAR(200) NOT NULL,
    materi_detail TEXT,
    metode VARCHAR(200),
    media VARCHAR(200),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attendance_session_id) REFERENCES attendance_sessions(id) ON DELETE SET NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- =============================================
-- NOTIFIKASI IN-APP
-- =============================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipe ENUM('tugas','nilai','absensi','pengumuman','lainnya') NOT NULL DEFAULT 'lainnya',
    judul VARCHAR(200) NOT NULL,
    pesan TEXT,
    url VARCHAR(500),
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read),
    INDEX idx_created (created_at)
);

-- =============================================
-- PENGUMUMAN SEKOLAH
-- =============================================
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    target_role ENUM('all','guru','murid') NOT NULL DEFAULT 'all',
    is_pinned TINYINT(1) DEFAULT 0,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expired_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed pengumuman
INSERT INTO announcements (user_id, judul, konten, target_role, is_pinned) VALUES
(1, 'Selamat Datang di SiAkad SD', 'Sistem Informasi Akademik Sekolah Dasar telah resmi diluncurkan. Semua guru dan siswa dapat menggunakan sistem ini untuk mengelola kegiatan akademik.', 'all', 1),
(1, 'Jadwal Ujian Tengah Semester', 'UTS Semester 1 akan dilaksanakan pada 14-19 Oktober 2024. Harap semua siswa mempersiapkan diri dengan baik.', 'murid', 0);

-- =============================================
-- EXPORT (no extra tables needed)
-- =============================================

-- =============================================
-- ACADEMIC YEARS
-- =============================================
CREATE TABLE IF NOT EXISTS academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran VARCHAR(10) NOT NULL,
    semester ENUM('1','2') NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_period (tahun_ajaran, semester)
);
INSERT INTO academic_years (tahun_ajaran,semester,tanggal_mulai,tanggal_selesai,is_active) VALUES
('2024/2025','1','2024-07-15','2024-12-20',1),
('2024/2025','2','2025-01-06','2025-06-30',0);

-- =============================================
-- PROMOTIONS (no extra table, uses existing)
-- =============================================
ALTER TABLE students ADD COLUMN IF NOT EXISTS status ENUM('aktif','alumni','pindah') NOT NULL DEFAULT 'aktif';
ALTER TABLE students ADD COLUMN IF NOT EXISTS angkatan VARCHAR(10) DEFAULT NULL;

-- =============================================
-- DISCUSSIONS
-- =============================================
CREATE TABLE IF NOT EXISTS discussions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    assignment_id INT DEFAULT NULL,
    user_id INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    is_pinned TINYINT(1) DEFAULT 0,
    reply_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS discussion_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    discussion_id INT NOT NULL,
    user_id INT NOT NULL,
    konten TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (discussion_id) REFERENCES discussions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- SPP
-- =============================================
CREATE TABLE IF NOT EXISTS spp_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran VARCHAR(10) NOT NULL,
    kelas_tingkat ENUM('1','2','3','4','5','6') NOT NULL,
    jumlah_per_bulan DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_setting (tahun_ajaran, kelas_tingkat)
);

CREATE TABLE IF NOT EXISTS spp_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    bulan TINYINT NOT NULL COMMENT '1-12',
    tahun INT NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    status ENUM('lunas','belum','cicil') NOT NULL DEFAULT 'belum',
    tanggal_bayar DATE DEFAULT NULL,
    keterangan VARCHAR(200),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_payment (student_id, bulan, tahun),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

INSERT INTO spp_settings (tahun_ajaran, kelas_tingkat, jumlah_per_bulan) VALUES
('2024/2025','1',50000),('2024/2025','2',50000),('2024/2025','3',60000),
('2024/2025','4',60000),('2024/2025','5',70000),('2024/2025','6',70000);

-- =============================================
-- SECURITY
-- =============================================
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    email VARCHAR(100) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_time (ip, attempted_at),
    INDEX idx_email_time (email, attempted_at)
);

CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    aksi VARCHAR(50) NOT NULL,
    tabel VARCHAR(50),
    record_id INT DEFAULT NULL,
    data_lama JSON DEFAULT NULL,
    data_baru JSON DEFAULT NULL,
    ip VARCHAR(45),
    user_agent VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_tabel (tabel, record_id),
    INDEX idx_created (created_at)
);
