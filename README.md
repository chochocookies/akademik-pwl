# 🎓 SiAkad SD — Sistem Informasi Akademik Sekolah Dasar
**PHP Native MVC + Role-Based Access Control + Tailwind CSS**

---

## 📁 Struktur Proyek

```
akademik/
├── app/
│   ├── config.php                    # Konfigurasi aplikasi & database
│   ├── core/
│   │   ├── Database.php              # Singleton PDO wrapper
│   │   ├── Auth.php                  # Session-based authentication
│   │   ├── helpers.php               # Middleware, Flash, Validator, helpers
│   │   ├── Router.php                # Custom URL router
│   │   └── BaseClasses.php           # Abstract Controller & Model
│   ├── models/
│   │   └── Models.php                # Semua model (User, Student, Teacher, dll.)
│   └── controllers/
│       ├── AuthController.php        # Login, logout, redirect
│       ├── DashboardController.php   # Dashboard per role
│       ├── ResourceControllers.php   # Student, Teacher, Class CRUD
│       └── GradeAssignmentControllers.php # Grade & Assignment logic
├── views/
│   ├── layouts/
│   │   ├── header.php                # Layout utama + sidebar + topbar
│   │   └── footer.php                # Footer + JS scripts
│   ├── auth/login.php
│   ├── dashboard/{admin,guru,murid}.php
│   ├── students/{index,create,edit,show}.php
│   ├── teachers/{index,create,edit,show}.php
│   ├── classes/{index,create,edit,show}.php
│   ├── grades/{index,by_class,create,my_grades}.php
│   ├── assignments/{admin_index,guru_index,murid_index,create,edit,show}.php
│   └── errors/404.php
├── routes/
│   └── web.php                       # Semua definisi route
├── public/
│   ├── index.php                     # Entry point aplikasi
│   └── .htaccess                     # Clean URL rewriting
└── database/
    └── schema.sql                    # Database schema + seed data
```

---

## ⚙️ Cara Instalasi

### 1. Persiapan
- PHP >= 8.0
- MySQL >= 5.7 / MariaDB >= 10.3
- Apache dengan `mod_rewrite` aktif
- XAMPP / Laragon / WAMP

### 2. Setup Database

```bash
# Import schema ke MySQL
mysql -u root -p < database/schema.sql
```

Atau buka **phpMyAdmin** → Import → pilih file `database/schema.sql`

### 3. Konfigurasi

Edit `app/config.php`:

```php
define('APP_URL', 'http://localhost/akademik/public'); // Sesuaikan path

define('DB_HOST', 'localhost');
define('DB_NAME', 'akademik_sd');
define('DB_USER', 'root');
define('DB_PASS', '');   // Password MySQL kamu
```

### 4. Pindahkan ke htdocs / www

```
htdocs/
└── akademik/          ← Letakkan seluruh folder di sini
    ├── app/
    ├── public/
    ├── views/
    └── ...
```

### 5. Buka di Browser

```
http://localhost/akademik/public/
```

---

## 🔑 Akun Demo

| Role      | Email                      | Password   |
|-----------|----------------------------|------------|
| Admin     | admin@sekolah.sch.id       | password   |
| Guru      | budi@sekolah.sch.id        | password   |
| Guru      | siti@sekolah.sch.id        | password   |
| Murid     | ahmad@sekolah.sch.id       | password   |
| Murid     | dewi@sekolah.sch.id        | password   |

> **Catatan:** Seed data menggunakan hash password dari Laravel (password: `password`).
> Jika login gagal, generate ulang hash dengan:
> ```php
> echo password_hash('password', PASSWORD_DEFAULT);
> ```
> Dan update kolom `password` di tabel `users`.

---

## 🏗️ Arsitektur

### MVC Pattern
```
Request → public/index.php → Router → Controller → Model → View
```

### Role-Based Access Control
```php
Middleware::admin();          // Hanya admin
Middleware::guru();           // Guru + Admin
Middleware::murid();          // Hanya murid
Middleware::role('guru','admin'); // Flexible
```

### Custom Router
```php
$router->get('/students', [StudentController::class, 'index']);
$router->post('/students', [StudentController::class, 'store']);
$router->get('/students/{id}', [StudentController::class, 'show']);
```

### Flash Messages
```php
Flash::set('success', 'Data berhasil disimpan!');
Flash::set('error', 'Terjadi kesalahan.');
```

### Validator
```php
$v = Validator::make($_POST, [
    'name'  => 'required|min:3',
    'email' => 'required|email',
    'nilai' => 'required|numeric|between:0,100',
]);
if ($v->fails()) { /* handle error */ }
```

---

## 🎯 Fitur Lengkap

### 🛠️ Admin
- ✅ Dashboard dengan statistik lengkap
- ✅ CRUD Siswa (dengan data user terintegrasi)
- ✅ CRUD Guru (dengan assign mata pelajaran & kelas)
- ✅ CRUD Kelas (dengan wali kelas)
- ✅ Monitoring nilai semua kelas
- ✅ Lihat semua tugas

### 👨‍🏫 Guru
- ✅ Dashboard dengan ringkasan kelas & tugas
- ✅ Lihat kelas yang diampu
- ✅ Input nilai siswa (Harian/UTS/UAS) — batch per kelas
- ✅ Otomatis hitung nilai akhir (30%+30%+40%)
- ✅ Buat, edit, hapus tugas
- ✅ Lihat & beri nilai submission siswa

### 👦 Murid
- ✅ Dashboard dengan progress lengkap
- ✅ Lihat nilai semua mata pelajaran + grade (A/B/C/D)
- ✅ Lihat daftar tugas + status pengumpulan
- ✅ Submit tugas dengan file upload + catatan
- ✅ Lihat nilai tugas dari guru

---

## 📊 Database Schema

### Relasi Utama
```
users (1) ──── (1) teachers ──── (M) teacher_subjects (M) ──── (1) classes
users (1) ──── (1) students (M) ──── (1) classes
students (M) ──── (M) grades via (subject_id, teacher_id)
assignments (1) ──── (M) submissions (M) ──── (1) students
```

### Formula Nilai Akhir
```sql
nilai_akhir = (nilai_harian × 0.3) + (nilai_uts × 0.3) + (nilai_uas × 0.4)
```
Dihitung otomatis oleh MySQL `GENERATED COLUMN`.

---

## 🛠️ Troubleshooting

**Q: Halaman blank / error 500?**
- Cek `error_log` di `htdocs/`
- Pastikan PHP PDO MySQL extension aktif
- Cek konfigurasi DB di `app/config.php`

**Q: Routing tidak bekerja (404)?**
- Pastikan `mod_rewrite` aktif di Apache
- Cek `.htaccess` ada di folder `public/`
- Di XAMPP: aktifkan `AllowOverride All` di `httpd.conf`

**Q: Upload file tidak bisa?**
- Buat folder `public/uploads/` dengan permission write

**Q: Login gagal meski password benar?**
- Regenerate password hash:
```php
// Jalankan di PHP CLI atau file sementara
echo password_hash('password', PASSWORD_DEFAULT);
// Copy hasilnya ke database
```

---

## 💡 Pengembangan Lanjutan

- [ ] Export nilai ke Excel/PDF
- [ ] Sistem absensi siswa
- [ ] Notifikasi deadline tugas
- [ ] Kalender akademik
- [ ] Rapor otomatis per semester
- [ ] Multi tahun ajaran

---

**Dibuat untuk tugas mata kuliah Pemrograman Web**
*PHP Native | MVC Architecture | Role-Based Access Control | Tailwind CSS*
