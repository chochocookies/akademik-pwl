<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SiAkad SD — Sistem Informasi Akademik Sekolah Dasar</title>
  <link rel="stylesheet" href="<?= url('/css/app.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    *{-webkit-font-smoothing:antialiased;}
    body{font-family:'DM Sans',system-ui,sans-serif;}
    h1,h2,h3,.font-display{font-family:'Bricolage Grotesque',system-ui,sans-serif;}
    .text-2xs{font-size:.65rem;line-height:1rem;}
    .blob{position:absolute;border-radius:50%;filter:blur(80px);animation:float 8s ease-in-out infinite;}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-20px)}}
    .hero-section{min-height:100vh;display:flex;align-items:center;}
    .feature-card{transition:all .25s ease;}
    .feature-card:hover{transform:translateY(-4px);}
    .nav-link{transition:color .2s;}
    .btn-hero{transition:all .25s cubic-bezier(.16,1,.3,1);}
    .btn-hero:hover{transform:translateY(-2px);}
    .btn-hero:active{transform:translateY(0);}
    @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .animate-1{animation:fadeUp .6s .1s both;}
    .animate-2{animation:fadeUp .6s .25s both;}
    .animate-3{animation:fadeUp .6s .4s both;}
    .animate-4{animation:fadeUp .6s .55s both;}
    .stat-item{animation:fadeUp .6s both;}
    .topbar{backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);}
  </style>
</head>
<body class="bg-slate-50 dark:bg-[#0D1117] transition-colors duration-300" id="page-body">

<!-- ── NAVBAR ── -->
<nav class="topbar fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-[#161B27]/80 border-b border-slate-100 dark:border-slate-800">
  <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
    <!-- Logo -->
    <a href="#" class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center">
        <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
      </div>
      <div>
        <span class="font-display font-bold text-slate-900 dark:text-white text-[15px] leading-none">SiAkad SD</span>
        <p class="text-2xs text-slate-400 dark:text-slate-600 leading-none mt-0.5">Sistem Akademik</p>
      </div>
    </a>

    <!-- Links -->
    <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600 dark:text-slate-400">
      <a href="#fitur" class="nav-link hover:text-brand-600 dark:hover:text-brand-400">Fitur</a>
      <a href="#role" class="nav-link hover:text-brand-600 dark:hover:text-brand-400">Pengguna</a>
      <a href="#mulai" class="nav-link hover:text-brand-600 dark:hover:text-brand-400">Mulai</a>
    </div>

    <div class="flex items-center gap-3">
      <button onclick="toggleDark()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle tema">
        <i data-lucide="sun"  class="w-4 h-4 dark:hidden text-amber-500"></i>
        <i data-lucide="moon" class="w-4 h-4 hidden dark:block text-brand-400"></i>
      </button>
      <a href="<?= url('/login') ?>" class="btn-hero inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-sm">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        Masuk
      </a>
    </div>
  </div>
</nav>

<!-- ── HERO ── -->
<section class="hero-section relative overflow-hidden bg-gradient-to-br from-[#0a0f1e] via-[#0d1529] to-[#0a0f1e] pt-16">
  <!-- Background blobs -->
  <div class="blob w-[500px] h-[500px] bg-brand-600/15 -top-32 -left-32" style="animation-delay:0s"></div>
  <div class="blob w-[400px] h-[400px] bg-violet-600/10 bottom-0 right-0" style="animation-delay:-3s"></div>
  <div class="blob w-[300px] h-[300px] bg-cyan-600/08 top-1/2 left-1/2 -translate-x-1/2" style="animation-delay:-5s"></div>

  <!-- Grid pattern overlay -->
  <div class="absolute inset-0 opacity-[0.03]" style="background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:60px 60px"></div>

  <div class="relative max-w-6xl mx-auto px-6 py-24 md:py-36">
    <div class="max-w-3xl">
      <!-- Badge -->
      <div class="animate-1 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-500/15 border border-brand-500/30 text-brand-300 text-xs font-semibold mb-6">
        <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
        Sistem Informasi Akademik Sekolah Dasar
      </div>

      <h1 class="animate-2 font-display font-bold text-4xl md:text-6xl text-white leading-tight tracking-tight mb-6">
        Kelola Akademik<br>
        <span class="bg-gradient-to-r from-brand-400 to-cyan-400 bg-clip-text text-transparent">Lebih Cerdas</span>
      </h1>

      <p class="animate-3 text-slate-400 text-lg md:text-xl leading-relaxed mb-10 max-w-2xl">
        Platform manajemen sekolah dasar lengkap — dari nilai, absensi, rapor, hingga komunikasi guru dan murid. Semua dalam satu sistem yang mudah digunakan.
      </p>

      <div class="animate-4 flex flex-wrap gap-4">
        <a href="<?= url('/login') ?>" class="btn-hero inline-flex items-center gap-2.5 px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-2xl shadow-[0_0_30px_rgba(37,99,235,0.4)] hover:shadow-[0_0_40px_rgba(37,99,235,0.6)]">
          <i data-lucide="log-in" class="w-5 h-5"></i>
          Masuk ke Sistem
        </a>
        <a href="#fitur" class="btn-hero inline-flex items-center gap-2.5 px-6 py-3 bg-white/10 hover:bg-white/15 text-white font-semibold rounded-2xl border border-white/20">
          <i data-lucide="arrow-down" class="w-5 h-5"></i>
          Lihat Fitur
        </a>
      </div>

      <!-- Quick demo accounts -->
      <div class="animate-4 flex flex-wrap gap-2 mt-6">
        <span class="text-slate-500 text-xs self-center">Coba demo:</span>
        <?php
        $demos = [
          ['admin@sekolah.sch.id','Admin','bg-violet-500/20 border-violet-500/30 text-violet-300'],
          ['budi@sekolah.sch.id','Guru', 'bg-brand-500/20 border-brand-500/30 text-brand-300'],
          ['ahmad@sekolah.sch.id','Murid','bg-emerald-500/20 border-emerald-500/30 text-emerald-300'],
        ];
        foreach ($demos as [$email,$label,$cls]):
        ?>
        <a href="<?= url('/login') ?>" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border <?= $cls ?> text-xs font-semibold hover:opacity-80 transition-opacity">
          <?= $label ?>
        </a>
        <?php endforeach; ?>
        <span class="text-slate-600 text-xs self-center">(password: <code class="bg-white/8 px-1.5 py-0.5 rounded font-mono">password</code>)</span>
      </div>
    </div>
  </div>

  <!-- Stats bar -->
  <div class="relative border-t border-white/5 bg-white/3">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php
        $stats = [
          ['11','Controller','Modul Sistem'],
          ['76','File PHP','Kode Sumber'],
          ['5+','Fitur','Terintegrasi'],
          ['3','Role','Admin · Guru · Murid'],
        ];
        foreach ($stats as $i => [$num,$label,$sub]):
        ?>
        <div class="stat-item text-center" style="animation-delay:<?= $i*100+600 ?>ms">
          <div class="font-display font-bold text-3xl text-white"><?= $num ?></div>
          <div class="text-slate-300 text-sm font-semibold mt-0.5"><?= $label ?></div>
          <div class="text-slate-600 text-xs mt-0.5"><?= $sub ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ── FITUR ── -->
<section id="fitur" class="py-20 bg-white dark:bg-[#161B27]">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 text-xs font-semibold mb-4">
        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
        Fitur Lengkap
      </div>
      <h2 class="font-display font-bold text-3xl md:text-4xl text-slate-900 dark:text-white">Semua yang dibutuhkan sekolah</h2>
      <p class="text-slate-500 dark:text-slate-400 mt-4 max-w-xl mx-auto">Dari manajemen data hingga pelaporan — semua terintegrasi dalam satu platform yang mudah digunakan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php
      $features = [
        ['bar-chart-3',    'Manajemen Nilai',      'Input nilai harian, UTS, UAS. Formula otomatis 30%+30%+40%. Grade A/B/C/D real-time.',                     'text-brand-600 dark:text-brand-400',   'bg-brand-50 dark:bg-brand-900/30'],
        ['calendar-check', 'Sistem Absensi',        'Catat kehadiran dengan cepat. Rekap per siswa per mapel. Status Hadir/Sakit/Izin/Alpha.',                   'text-emerald-600 dark:text-emerald-400','bg-emerald-50 dark:bg-emerald-900/30'],
        ['file-text',      'Rapor Digital',         'Generate rapor otomatis dari data nilai. Preview & cetak PDF langsung dari browser. Catatan wali kelas.',    'text-violet-600 dark:text-violet-400', 'bg-violet-50 dark:bg-violet-900/30'],
        ['clipboard-list', 'Tugas & Submission',    'Guru buat tugas dengan deadline. Murid submit file. Guru beri nilai submission langsung.',                    'text-amber-600 dark:text-amber-400',   'bg-amber-50 dark:bg-amber-900/30'],
        ['calendar',       'Kalender Akademik',     'Event sekolah, jadwal ujian, hari libur dalam satu kalender. Jadwal pelajaran tetap per kelas.',             'text-cyan-600 dark:text-cyan-400',     'bg-cyan-50 dark:bg-cyan-900/30'],
        ['megaphone',      'Pengumuman Sekolah',    'Admin & guru buat pengumuman bertarget. Pin pengumuman penting. Notifikasi otomatis ke pengguna.',            'text-rose-600 dark:text-rose-400',     'bg-rose-50 dark:bg-rose-900/30'],
        ['book-open-check','Jurnal Mengajar',       'Guru catat materi, metode, dan media tiap pertemuan. Terhubung langsung ke sesi absensi.',                   'text-teal-600 dark:text-teal-400',     'bg-teal-50 dark:bg-teal-900/30'],
        ['bell',           'Notifikasi In-App',     'Pemberitahuan otomatis untuk tugas baru, nilai, absensi alpha. Badge counter real-time di topbar.',          'text-orange-600 dark:text-orange-400', 'bg-orange-50 dark:bg-orange-900/30'],
        ['shield',         'Multi-Role RBAC',       'Tiga role terpisah: Admin, Guru, Murid. Middleware akses di setiap halaman. CSRF protection & session.',     'text-slate-600 dark:text-slate-400',   'bg-slate-100 dark:bg-slate-800'],
      ];
      foreach ($features as $i => [$icon,$title,$desc,$col,$bg]):
      ?>
      <div class="feature-card p-6 bg-slate-50 dark:bg-[#1E2433] rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand-200 dark:hover:border-brand-900/50 hover:shadow-lg" style="animation:fadeUp .5s <?= $i*80 ?>ms both">
        <div class="w-11 h-11 rounded-2xl <?= $bg ?> flex items-center justify-center mb-4">
          <i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $col ?>"></i>
        </div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-base mb-2"><?= $title ?></h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── ROLE ── -->
<section id="role" class="py-20 bg-slate-50 dark:bg-[#0D1117]">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <h2 class="font-display font-bold text-3xl md:text-4xl text-slate-900 dark:text-white">Satu sistem, tiga peran</h2>
      <p class="text-slate-500 dark:text-slate-400 mt-4">Setiap pengguna mendapatkan tampilan dan akses yang sesuai dengan perannya.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php
      $roles = [
        ['🛠️','Administrator','Kelola semua data. Monitor nilai, absensi, rapor seluruh sekolah. Manajemen user dan mata pelajaran.','from-violet-600 to-indigo-700',[
          'CRUD Siswa, Guru, Kelas','Manajemen Mata Pelajaran','Monitor Semua Nilai & Absensi','Rapor Digital Semua Kelas','Kalender & Pengumuman','Manajemen User & Akses',
        ]],
        ['👨‍🏫','Guru','Input nilai, absensi, jurnal mengajar. Buat tugas dan nilai submission. Kelola kelas yang diampu.','from-brand-600 to-cyan-700',[
          'Input Nilai Batch (Harian/UTS/UAS)','Buat & Nilai Tugas Siswa','Isi Absensi + Jurnal Mengajar','Preview Rapor Kelas','Buat Pengumuman','Jadwal & Kalender',
        ]],
        ['👦','Murid','Lihat nilai, tugas, dan rapor. Submit tugas dengan upload file. Pantau kehadiran dan pengumuman.','from-emerald-600 to-teal-700',[
          'Rekap Nilai + Grade A/B/C/D','Submit Tugas + Upload File','Lihat Rapor Diri Sendiri','Rekap Absensi per Mapel','Notifikasi Tugas & Nilai','Pengumuman & Kalender',
        ]],
      ];
      foreach ($roles as [$emoji,$name,$desc,$grad,$items]):
      ?>
      <div class="bg-white dark:bg-[#1E2433] rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br <?= $grad ?> p-6 text-white">
          <div class="text-4xl mb-3"><?= $emoji ?></div>
          <h3 class="font-display font-bold text-xl"><?= $name ?></h3>
          <p class="text-white/70 text-sm mt-2 leading-relaxed"><?= $desc ?></p>
        </div>
        <div class="p-6">
          <ul class="space-y-2.5">
            <?php foreach ($items as $item): ?>
            <li class="flex items-center gap-2.5 text-sm text-slate-600 dark:text-slate-400">
              <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
              <?= $item ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── CTA ── -->
<section id="mulai" class="py-20 bg-gradient-to-br from-brand-950 via-brand-900 to-indigo-950 relative overflow-hidden">
  <div class="blob w-96 h-96 bg-brand-500/20 top-0 right-0" style="animation-delay:-2s"></div>
  <div class="blob w-64 h-64 bg-violet-500/15 bottom-0 left-0" style="animation-delay:-4s"></div>
  <div class="relative max-w-2xl mx-auto px-6 text-center">
    <h2 class="font-display font-bold text-3xl md:text-4xl text-white mb-4">Siap memulai?</h2>
    <p class="text-brand-200 text-lg mb-10">Masuk ke sistem dan kelola akademik sekolah dasar Anda dengan lebih mudah.</p>
    <div class="flex flex-wrap justify-center gap-4">
      <a href="<?= url('/login') ?>" class="btn-hero inline-flex items-center gap-2.5 px-8 py-4 bg-white text-brand-700 font-bold rounded-2xl shadow-xl hover:shadow-2xl text-base">
        <i data-lucide="log-in" class="w-5 h-5"></i>
        Masuk Sekarang
      </a>
    </div>
    <p class="text-brand-400 text-sm mt-6">Akun demo tersedia · password: <code class="bg-white/10 px-1.5 py-0.5 rounded font-mono">password</code></p>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="bg-slate-900 border-t border-slate-800 py-8">
  <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center">
        <i data-lucide="graduation-cap" class="w-4 h-4 text-white"></i>
      </div>
      <span class="font-display font-bold text-slate-300 text-sm">SiAkad SD</span>
    </div>
    <p class="text-slate-600 text-sm">© <?= date('Y') ?> SiAkad SD · PHP Native MVC · Tahun Ajaran <?= TAHUN_AJARAN ?></p>
    <div class="flex items-center gap-4 text-xs text-slate-500">
      <a href="<?= url('/login') ?>" class="hover:text-slate-300 transition-colors">Masuk</a>
      <a href="#fitur" class="hover:text-slate-300 transition-colors">Fitur</a>
    </div>
  </div>
</footer>

<script>
lucide.createIcons();

// Dark mode
const htmlEl = document.getElementById('html-root');
if (localStorage.getItem('theme')==='dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  htmlEl.classList.add('dark');
}
function toggleDark() {
  const isDark = htmlEl.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({behavior:'smooth',block:'start'}); }
  });
});

// Navbar background on scroll
const nav = document.querySelector('nav');
window.addEventListener('scroll', () => {
  nav.style.background = window.scrollY > 20 ? 'rgba(255,255,255,0.95)' : '';
});
</script>
</body>
</html>
