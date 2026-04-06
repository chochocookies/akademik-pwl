# SiAkad SD — Sistem Informasi Akademik Sekolah Dasar

PHP Native · MVC · RBAC · Tailwind CSS v3 · Dark Mode

---

## Fitur yang Sudah Ada

### Admin
- Dashboard: statistik siswa, guru, kelas, nilai, absensi
- CRUD Siswa, Guru, Kelas, Mata Pelajaran
- Manajemen User (aktif/nonaktif/hapus)
- Monitor Nilai, Tugas, Absensi semua kelas
- Profil + ganti password

### Guru
- Dashboard + quick actions
- Input nilai batch (Harian/UTS/UAS, preview real-time)
- Buat/edit/hapus tugas, nilai submission siswa
- Buat sesi absensi + isi kehadiran (Hadir/Sakit/Izin/Alpha)
- Profil + ganti password

### Murid
- Dashboard + quick links
- Rekap nilai per mapel + grade A/B/C/D
- Submit tugas + upload file
- Rekap absensi per mapel + riwayat
- Profil + ganti password

### Sistem
- Clean URL routing
- CSRF protection
- Flash messages auto-dismiss
- Form validation
- Dark mode (persisten localStorage)
- Tailwind CSS v3 lokal (tidak ada CDN)
- Responsive + mobile sidebar

---

## Struktur Folder

```
akademik/
├── app/
│   ├── config.php
│   ├── core/
│   │   ├── Auth.php
│   │   ├── BaseClasses.php
│   │   ├── Database.php
│   │   ├── Router.php
│   │   └── helpers.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ResourceControllers.php         # Student, Teacher, Class
│   │   ├── GradeAssignmentControllers.php  # Grade, Assignment
│   │   ├── AttendanceController.php
│   │   └── AdminControllers.php            # Subject, User, Profile
│   └── models/
│       └── Models.php                      # Semua model
│
├── database/
│   └── schema.sql
│
├── public/                                 # ← Web root Apache
│   ├── index.php                           # Entry point
│   ├── .htaccess
│   ├── css/app.css                         # Compiled Tailwind (66KB)
│   └── uploads/                            # [buat manual] file submission
│
├── resources/css/app.css                   # Tailwind source
├── routes/web.php
│
├── views/
│   ├── landing.php                         # [Placeholder] Landing page
│   ├── layouts/
│   │   ├── header.php                      # Sidebar + topbar + dark mode
│   │   └── footer.php
│   ├── auth/login.php
│   ├── dashboard/{admin,guru,murid}.php
│   ├── students/{index,create,edit,show}.php
│   ├── teachers/{index,create,edit,show}.php
│   ├── classes/{index,create,edit,show}.php
│   ├── subjects/{index,create,edit}.php
│   ├── grades/{index,by_class,create,my_grades}.php
│   ├── assignments/{guru_index,admin_index,murid_index,create,edit,show}.php
│   ├── attendance/{index,create,fill,show,rekap,murid_index}.php
│   ├── users/index.php
│   ├── profile/show.php
│   └── errors/404.php
│
├── tailwind.config.js
├── package.json
└── README.md
```

---

## Instalasi

### 1. Import Database
```bash
mysql -u root -p < database/schema.sql
```

### 2. Edit `app/config.php`
```php
define('APP_URL', 'http://localhost/akademik/public');
define('DB_PASS', '');
```

### 3. Buat Folder Uploads
```bash
mkdir -p public/uploads && chmod 755 public/uploads
```

### 4. Aktifkan mod_rewrite
```apache
<Directory "/path/to/akademik/public">
    AllowOverride All
</Directory>
```

### 5. Buka Browser
```
http://localhost/akademik/public/
```

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sekolah.sch.id | password |
| Guru | budi@sekolah.sch.id | password |
| Murid | ahmad@sekolah.sch.id | password |

> Jika login gagal, generate ulang hash:
> `echo password_hash('password', PASSWORD_DEFAULT);`
> lalu update kolom `password` di tabel `users`.

---

## NPM Scripts

```bash
npm install       # Install Tailwind
npm run build     # Compile + minify (production)
npm run watch     # Watch mode (development)
```

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| 404 semua halaman | Aktifkan `mod_rewrite` + `AllowOverride All` |
| Login gagal | Generate ulang password hash (lihat di atas) |
| CSS tidak muncul | Pastikan `APP_URL` sesuai URL di browser |
| Upload gagal | Buat `public/uploads/` dengan chmod 755 |
| Tabel attendance tidak ada | Pastikan seluruh `schema.sql` ter-import |

---

## Fitur Tambahan (Update Core Academic)

### Rapor Digital
- Admin & Guru: pilih kelas → daftar siswa + status rapor → preview per siswa
- Input catatan wali kelas, predikat sikap/keterampilan, peringkat
- Template cetak (`/reports/pdf/{id}`) — buka di tab baru, browser print ke PDF
- Murid: akses rapor sendiri via `/my-rapor` atau menu sidebar

### Kalender Akademik
- Tampilan grid bulanan dengan event berwarna per tipe (Libur/Ujian/Event)
- Admin: tambah/hapus event langsung dari kalender
- Jadwal pelajaran per kelas (`/calendar/schedule/{classId}`)
- Admin: tambah/hapus slot jadwal per hari
- Widget "Event Mendatang" di semua dashboard

### Jurnal Mengajar
- Guru: catat materi, metode, media per pertemuan
- Bisa dikaitkan ke sesi absensi yang sudah ada
- Admin bisa monitor semua jurnal semua guru

### Notifikasi In-App
- Bell counter di topbar — polling otomatis setiap 60 detik
- Triggered otomatis saat: tugas baru dibuat, nilai diinput, alpha di absensi, pengumuman diterbitkan
- Halaman `/notifications` — semua dibaca setelah dibuka

### Pengumuman Sekolah
- Admin/Guru: buat pengumuman dengan target role (Semua/Guru/Murid)
- Pin pengumuman penting — tampil paling atas dengan ikon 📌
- Waktu tayang & kedaluwarsa bisa diatur
- Notifikasi otomatis ke user yang dituju
- Widget ringkasan di semua dashboard

### Database Baru
```sql
report_notes          -- Catatan & predikat rapor per siswa per semester
academic_calendar     -- Event kalender (libur, ujian, event)
schedules             -- Jadwal pelajaran tetap per kelas
teaching_journals     -- Jurnal mengajar guru
notifications         -- Notifikasi in-app per user
announcements         -- Pengumuman sekolah
```
