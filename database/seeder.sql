-- =============================================
-- SiAkad SD — SEEDER FILE
-- Jalankan SETELAH schema.sql
-- =============================================
USE akademik_sd;

-- ── Pastikan data dasar ada ──────────────────
-- Reset counters aman
SET FOREIGN_KEY_CHECKS = 0;

-- ── Users (jika belum ada) ───────────────────
INSERT IGNORE INTO users (id, name, email, password, role) VALUES
(1, 'Administrator',  'admin@sekolah.sch.id',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'Budi Santoso',   'budi@sekolah.sch.id',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
(3, 'Siti Rahayu',    'siti@sekolah.sch.id',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
(4, 'Ahmad Fauzi',    'ahmad@sekolah.sch.id',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
(5, 'Dewi Lestari',   'dewi@sekolah.sch.id',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
(6, 'Rizki Pratama',  'rizki@sekolah.sch.id',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
(7, 'Rina Wulandari', 'rina@sekolah.sch.id',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid'),
(8, 'Fajar Nugroho',  'fajar@sekolah.sch.id',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'murid');

-- ── Kelas ────────────────────────────────────
INSERT IGNORE INTO classes (id, nama_kelas, tingkat, wali_kelas_id, tahun_ajaran) VALUES
(1, 'Kelas 4A', '4', 2, '2024/2025'),
(2, 'Kelas 5B', '5', 3, '2024/2025'),
(3, 'Kelas 6A', '6', 2, '2024/2025');

-- ── Guru ─────────────────────────────────────
INSERT IGNORE INTO teachers (id, user_id, nip, phone) VALUES
(1, 2, '198501012010011001', '081234567890'),
(2, 3, '199002022012012002', '081234567891');

-- ── Siswa ────────────────────────────────────
INSERT IGNORE INTO students (id, user_id, class_id, nis, gender, birth_date, parent_name, phone) VALUES
(1, 4, 1, '2024001', 'L', '2013-05-15', 'Fauzi Sr.',    '081111111111'),
(2, 5, 1, '2024002', 'P', '2013-08-22', 'Lestari Sr.',  '081111111112'),
(3, 6, 2, '2024003', 'L', '2012-11-10', 'Pratama Sr.',  '081111111113'),
(4, 7, 2, '2024004', 'P', '2012-07-18', 'Wulan Sr.',    '081111111114'),
(5, 8, 3, '2024005', 'L', '2011-03-25', 'Nugroho Sr.',  '081111111115');

-- ── Mata Pelajaran ────────────────────────────
INSERT IGNORE INTO subjects (id, nama_mapel, kode_mapel) VALUES
(1, 'Matematika',           'MTK'),
(2, 'Bahasa Indonesia',     'BIN'),
(3, 'IPA',                  'IPA'),
(4, 'IPS',                  'IPS'),
(5, 'Pendidikan Agama Islam','PAI'),
(6, 'PJOK',                 'PJOK'),
(7, 'Seni Budaya',          'SBK');

-- ── Guru-Mapel-Kelas ──────────────────────────
INSERT IGNORE INTO teacher_subjects (teacher_id, subject_id, class_id) VALUES
(1,1,1),(1,2,1),(1,3,1),(1,4,1),
(1,1,3),(1,2,3),(1,3,3),
(2,1,2),(2,2,2),(2,4,2),(2,5,2);

-- ── Nilai ─────────────────────────────────────
INSERT IGNORE INTO grades (student_id, teacher_id, subject_id, nilai_harian, nilai_uts, nilai_uas, semester, tahun_ajaran) VALUES
-- Kelas 4A - Ahmad
(1,1,1, 85,80,88,'1','2024/2025'),
(1,1,2, 90,85,92,'1','2024/2025'),
(1,1,3, 78,75,80,'1','2024/2025'),
(1,1,4, 82,80,85,'1','2024/2025'),
-- Kelas 4A - Dewi
(2,1,1, 92,88,95,'1','2024/2025'),
(2,1,2, 88,90,87,'1','2024/2025'),
(2,1,3, 85,82,88,'1','2024/2025'),
(2,1,4, 90,88,92,'1','2024/2025'),
-- Kelas 5B - Rizki
(3,2,1, 75,70,78,'1','2024/2025'),
(3,2,2, 80,78,82,'1','2024/2025'),
(3,2,4, 72,68,75,'1','2024/2025'),
-- Kelas 5B - Rina
(4,2,1, 88,85,90,'1','2024/2025'),
(4,2,2, 92,90,94,'1','2024/2025'),
(4,2,4, 85,82,87,'1','2024/2025'),
-- Kelas 6A - Fajar
(5,1,1, 78,75,82,'1','2024/2025'),
(5,1,2, 85,80,88,'1','2024/2025'),
(5,1,3, 72,70,75,'1','2024/2025');

-- ── Tugas ─────────────────────────────────────
INSERT IGNORE INTO assignments (id, teacher_id, class_id, subject_id, judul, deskripsi, deadline, max_nilai) VALUES
(1,1,1,1,'Latihan Soal Pecahan','Kerjakan soal pecahan halaman 45-47 buku paket',DATE_ADD(NOW(),INTERVAL 7 DAY),100),
(2,1,1,2,'Menulis Karangan','Tulis karangan tentang liburanmu min. 200 kata',DATE_ADD(NOW(),INTERVAL 5 DAY),100),
(3,2,2,1,'PR Perkalian Bersusun','Kerjakan 20 soal perkalian bersusun di buku latihan',DATE_ADD(NOW(),INTERVAL 3 DAY),100),
(4,1,3,2,'Puisi Kemerdekaan','Buat puisi bertema kemerdekaan, minimal 3 bait',DATE_ADD(NOW(),INTERVAL 10 DAY),100);

-- ── Sesi Absensi ──────────────────────────────
INSERT IGNORE INTO attendance_sessions (id, class_id, teacher_id, subject_id, tanggal, keterangan) VALUES
(1,1,1,1,DATE_SUB(CURDATE(),INTERVAL 2 DAY),'Pertemuan ke-10: Pecahan Biasa'),
(2,1,1,2,DATE_SUB(CURDATE(),INTERVAL 1 DAY),'Pertemuan ke-9: Teks Deskripsi'),
(3,2,2,1,DATE_SUB(CURDATE(),INTERVAL 2 DAY),'Pertemuan ke-8: Operasi Campuran'),
(4,3,1,1,DATE_SUB(CURDATE(),INTERVAL 1 DAY),'Pertemuan ke-12: Bilangan Bulat');

-- ── Absensi ───────────────────────────────────
INSERT IGNORE INTO attendances (session_id, student_id, status) VALUES
-- Sesi 1 (Kelas 4A MTK)
(1,1,'hadir'),(1,2,'hadir'),
-- Sesi 2 (Kelas 4A BIN)
(2,1,'hadir'),(2,2,'sakit'),
-- Sesi 3 (Kelas 5B MTK)
(3,3,'hadir'),(3,4,'izin'),
-- Sesi 4 (Kelas 6A MTK)
(4,5,'hadir');

-- ── Report Notes (PENTING — mencegah null error) ──
INSERT IGNORE INTO report_notes (student_id, semester, tahun_ajaran, catatan_wali, catatan_kepala, predikat_sikap, predikat_keterampilan, ranking, created_by) VALUES
(1,'1','2024/2025','Ahmad menunjukkan perkembangan yang baik. Tetap semangat belajar!','Pertahankan prestasi yang sudah dicapai.','A','B',2,1),
(2,'1','2024/2025','Dewi sangat rajin dan aktif di kelas. Pertahankan!','Siswa berprestasi, terus tingkatkan.','A','A',1,1),
(3,'1','2024/2025','Rizki perlu lebih giat belajar, terutama matematika.', NULL,'B','B',3,1),
(4,'1','2024/2025','Rina sangat aktif dalam diskusi kelas.','Prestasi baik, layak dipertahankan.','A','B',1,1),
(5,'1','2024/2025','Fajar menunjukkan kemajuan yang konsisten.', NULL,'B','B',1,1);

-- ── Kalender Akademik ─────────────────────────
INSERT IGNORE INTO academic_calendar (id, tanggal_mulai, tanggal_selesai, judul, tipe, warna, created_by) VALUES
(1,'2024-08-19','2024-08-19','Hari Pertama Sekolah','event','#10B981',1),
(2,'2024-10-14','2024-10-19','Ujian Tengah Semester 1','ujian','#F59E0B',1),
(3,'2024-12-02','2024-12-14','Ujian Akhir Semester 1','ujian','#EF4444',1),
(4,'2024-12-25','2024-12-25','Hari Natal','libur','#6366F1',1),
(5,'2025-01-01','2025-01-01','Tahun Baru 2025','libur','#6366F1',1),
(6,'2025-01-06','2025-01-06','Awal Semester 2','event','#10B981',1),
(7,DATE_ADD(CURDATE(),INTERVAL 14 DAY),DATE_ADD(CURDATE(),INTERVAL 18 DAY),'Penilaian Harian Terpadu','ujian','#F59E0B',1),
(8,DATE_ADD(CURDATE(),INTERVAL 30 DAY),DATE_ADD(CURDATE(),INTERVAL 30 DAY),'Rapat Wali Murid','event','#3B82F6',1);

-- ── Jadwal Pelajaran ──────────────────────────
INSERT IGNORE INTO schedules (class_id, teacher_id, subject_id, hari, jam_mulai, jam_selesai, ruangan) VALUES
(1,1,1,'senin',   '07:00:00','07:45:00','Ruang 4A'),
(1,1,2,'senin',   '07:45:00','08:30:00','Ruang 4A'),
(1,1,3,'selasa',  '07:00:00','07:45:00','Ruang 4A'),
(1,1,1,'rabu',    '07:00:00','07:45:00','Ruang 4A'),
(1,1,4,'kamis',   '07:00:00','07:45:00','Ruang 4A'),
(2,2,1,'senin',   '07:00:00','07:45:00','Ruang 5B'),
(2,2,2,'selasa',  '07:00:00','07:45:00','Ruang 5B'),
(2,2,4,'rabu',    '07:00:00','07:45:00','Ruang 5B'),
(3,1,1,'senin',   '09:00:00','09:45:00','Ruang 6A'),
(3,1,2,'selasa',  '09:00:00','09:45:00','Ruang 6A'),
(3,1,3,'rabu',    '09:00:00','09:45:00','Ruang 6A');

-- ── Tahun Ajaran ──────────────────────────────
INSERT IGNORE INTO academic_years (id, tahun_ajaran, semester, tanggal_mulai, tanggal_selesai, is_active) VALUES
(1,'2024/2025','1','2024-07-15','2024-12-20',1),
(2,'2024/2025','2','2025-01-06','2025-06-30',0),
(3,'2025/2026','1','2025-07-14','2025-12-19',0);

-- ── Pengumuman ────────────────────────────────
INSERT IGNORE INTO announcements (id, user_id, judul, konten, target_role, is_pinned, published_at) VALUES
(1,1,'Selamat Datang di SiAkad SD','Sistem Informasi Akademik Sekolah Dasar telah resmi diluncurkan. Semua guru dan siswa dapat menggunakan sistem ini untuk mengelola kegiatan akademik dengan lebih mudah dan efisien. Silakan eksplorasi fitur-fitur yang tersedia.','all',1,NOW()),
(2,1,'Jadwal UTS Semester 1','Ujian Tengah Semester 1 akan dilaksanakan pada 14-19 Oktober 2024. Harap semua siswa mempersiapkan diri dengan baik. Materi yang diujikan sesuai dengan silabus yang telah dibagikan.','murid',0,NOW()),
(3,2,'Pengumpulan Jurnal Mengajar','Semua guru diharapkan mengisi jurnal mengajar secara rutin di sistem. Jurnal harus diisi paling lambat 1 hari setelah pertemuan berlangsung.','guru',0,NOW());

-- ── Notifikasi Sample ─────────────────────────
INSERT IGNORE INTO notifications (user_id, tipe, judul, pesan, url, is_read) VALUES
(4,'pengumuman','📢 Selamat Datang di SiAkad SD','Sistem informasi akademik sudah bisa digunakan.','/announcements/1',0),
(4,'tugas','📚 Tugas baru: Latihan Soal Pecahan','Guru Matematika membuat tugas baru.','/assignments/1',0),
(4,'absensi','⚠️ Pemberitahuan absensi','Hadir di semua pelajaran minggu ini.','/attendance',1),
(5,'pengumuman','📢 Selamat Datang di SiAkad SD','Sistem informasi akademik sudah bisa digunakan.','/announcements/1',0),
(5,'nilai','📊 Nilai baru tersedia','Nilai mata pelajaran kamu sudah diinput.','/my-grades',0);

-- ── SPP Settings ──────────────────────────────
INSERT IGNORE INTO spp_settings (tahun_ajaran, kelas_tingkat, jumlah_per_bulan) VALUES
('2024/2025','1',50000),
('2024/2025','2',50000),
('2024/2025','3',60000),
('2024/2025','4',60000),
('2024/2025','5',70000),
('2024/2025','6',70000);

-- ── SPP Payments Sample ───────────────────────
INSERT IGNORE INTO spp_payments (student_id, bulan, tahun, jumlah, status, tanggal_bayar, created_by) VALUES
(1,7,2024,60000,'lunas','2024-07-10',1),
(1,8,2024,60000,'lunas','2024-08-05',1),
(1,9,2024,60000,'lunas','2024-09-03',1),
(1,10,2024,60000,'lunas','2024-10-07',1),
(2,7,2024,60000,'lunas','2024-07-11',1),
(2,8,2024,60000,'lunas','2024-08-06',1),
(2,9,2024,60000,'belum', NULL,1);

-- ── Diskusi Sample ────────────────────────────
INSERT IGNORE INTO discussions (id, class_id, user_id, judul, konten, is_pinned) VALUES
(1,1,2,'Materi Pecahan — Ada yang Kesulitan?','Halo anak-anak, apakah ada yang masih kesulitan dengan materi pecahan biasa yang kita pelajari kemarin? Silakan tanyakan di sini!',1),
(2,2,3,'Latihan Soal Cerita Matematika','Untuk persiapan UTS, saya sarankan berlatih soal cerita. Ada yang mau berbagi tips belajar?',0);

INSERT IGNORE INTO discussion_replies (discussion_id, user_id, konten) VALUES
(1,4,'Bu, saya masih bingung cara menjumlahkan pecahan yang penyebutnya berbeda. Bisa dijelaskan lagi?'),
(1,2,'Baik Ahmad! Caranya: samakan dulu penyebutnya dengan mencari KPK, lalu jumlahkan pembilangnya. Kita bahas di pertemuan berikutnya ya!'),
(2,6,'Saya biasanya baca soal dua kali dulu sebelum mulai mengerjakan, baru tentukan apa yang diketahui dan ditanya.');

-- Update reply counts
UPDATE discussions d SET reply_count = (
    SELECT COUNT(*) FROM discussion_replies r WHERE r.discussion_id = d.id
);

-- ── Jurnal Mengajar Sample ────────────────────
INSERT IGNORE INTO teaching_journals (id, attendance_session_id, teacher_id, class_id, subject_id, tanggal, materi_pokok, materi_detail, metode, media) VALUES
(1,1,1,1,1,DATE_SUB(CURDATE(),INTERVAL 2 DAY),'Penjumlahan Pecahan Berpenyebut Berbeda','Siswa belajar cara menyamakan penyebut menggunakan KPK, kemudian menjumlahkan pembilang.','Ceramah, Tanya Jawab, Latihan Soal','Papan Tulis, Buku Paket MTK Kelas 4'),
(2,2,1,1,2,DATE_SUB(CURDATE(),INTERVAL 1 DAY),'Teks Deskripsi — Ciri dan Struktur','Membahas pengertian, ciri-ciri, dan struktur teks deskripsi. Siswa mengidentifikasi contoh teks.','Diskusi Kelompok, Presentasi','Teks Bacaan, Lembar Kerja Siswa'),
(3,3,2,2,1,DATE_SUB(CURDATE(),INTERVAL 2 DAY),'Operasi Campuran Bilangan Bulat','Latihan soal operasi campuran (+, -, ×, ÷) dengan prioritas operasi yang benar.','Drill dan Latihan Mandiri','Buku Latihan, Kartu Soal');

SET FOREIGN_KEY_CHECKS = 1;

-- ── VERIFIKASI ────────────────────────────────
SELECT 'users'               AS tabel, COUNT(*) AS total FROM users
UNION ALL SELECT 'students',           COUNT(*) FROM students
UNION ALL SELECT 'teachers',           COUNT(*) FROM teachers
UNION ALL SELECT 'classes',            COUNT(*) FROM classes
UNION ALL SELECT 'subjects',           COUNT(*) FROM subjects
UNION ALL SELECT 'grades',             COUNT(*) FROM grades
UNION ALL SELECT 'assignments',        COUNT(*) FROM assignments
UNION ALL SELECT 'attendance_sessions',COUNT(*) FROM attendance_sessions
UNION ALL SELECT 'attendances',        COUNT(*) FROM attendances
UNION ALL SELECT 'report_notes',       COUNT(*) FROM report_notes
UNION ALL SELECT 'academic_calendar',  COUNT(*) FROM academic_calendar
UNION ALL SELECT 'schedules',          COUNT(*) FROM schedules
UNION ALL SELECT 'announcements',      COUNT(*) FROM announcements
UNION ALL SELECT 'notifications',      COUNT(*) FROM notifications
UNION ALL SELECT 'spp_settings',       COUNT(*) FROM spp_settings
UNION ALL SELECT 'spp_payments',       COUNT(*) FROM spp_payments
UNION ALL SELECT 'discussions',        COUNT(*) FROM discussions
UNION ALL SELECT 'discussion_replies', COUNT(*) FROM discussion_replies
UNION ALL SELECT 'teaching_journals',  COUNT(*) FROM teaching_journals
UNION ALL SELECT 'academic_years',     COUNT(*) FROM academic_years;
