<!DOCTYPE html>
<html lang="id" class="h-full" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'SiAkad SD') ?> — SiAkad SD</title>
  <link rel="stylesheet" href="<?= url('/css/app.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    h1,h2,h3,h4,.font-display{font-family:'Bricolage Grotesque',system-ui,sans-serif;}
    body{font-family:'DM Sans',system-ui,sans-serif;}
    code,.font-mono{font-family:'JetBrains Mono',monospace;}
    .text-2xs{font-size:.65rem;line-height:1rem;}
    #sidebar{transition:transform .3s cubic-bezier(.16,1,.3,1);}
    .topbar{backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);}
    .nav-link.active::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:55%;background:#2563EB;border-radius:0 4px 4px 0;}
    .page-content{animation:fadeUp .4s cubic-bezier(.16,1,.3,1) both;}
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
    .btn{position:relative;overflow:hidden;}
    select.form-input,input.form-input,textarea.form-input{appearance:auto;}
  </style>
</head>
<body class="bg-slate-50 dark:bg-dark-bg h-full text-slate-900 dark:text-slate-100 transition-colors duration-300">
<?php
Auth::start();
$_user = Auth::user();
$_role = Auth::role();
$_uri = '/' . ltrim(substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen(parse_url(APP_URL, PHP_URL_PATH))), '/');
$_roleData = [
  'admin'=>['label'=>'Administrator','dot'=>'bg-violet-400','av'=>'avatar-violet'],
  'guru' =>['label'=>'Guru',         'dot'=>'bg-brand-400', 'av'=>'avatar-blue'],
  'murid'=>['label'=>'Murid',        'dot'=>'bg-emerald-400','av'=>'avatar-green'],
];
$_rd = $_roleData[$_role] ?? $_roleData['admin'];
$_nav = [
  'admin'=>[
    ['icon'=>'layout-dashboard','label'=>'Dashboard',     'href'=>'/dashboard/admin','s'=>null],
    ['s'=>'Master Data'],
    ['icon'=>'users',           'label'=>'Data Siswa',    'href'=>'/students'],
    ['icon'=>'user-check',      'label'=>'Data Guru',     'href'=>'/teachers'],
    ['icon'=>'building-2',      'label'=>'Data Kelas',    'href'=>'/classes'],
    ['icon'=>'book-open',       'label'=>'Mata Pelajaran','href'=>'/subjects'],
    ['icon'=>'shield',          'label'=>'Manajemen User','href'=>'/users'],
    ['s'=>'Akademik'],
    ['icon'=>'bar-chart-3',     'label'=>'Nilai',         'href'=>'/grades'],
    ['icon'=>'clipboard-list',  'label'=>'Tugas',         'href'=>'/assignments'],
    ['icon'=>'calendar-check',  'label'=>'Absensi',       'href'=>'/attendance'],
    ['icon'=>'file-text',       'label'=>'Rapor Digital', 'href'=>'/reports'],
    ['s'=>'Sekolah'],
    ['icon'=>'calendar',        'label'=>'Kalender',      'href'=>'/calendar'],
    ['icon'=>'megaphone',       'label'=>'Pengumuman',    'href'=>'/announcements'],
    ['icon'=>'book-open-check', 'label'=>'Jurnal Mengajar','href'=>'/journals'],
    ['s'=>'Akun'],
    ['icon'=>'bell',            'label'=>'Notifikasi',    'href'=>'/notifications'],
    ['icon'=>'user',            'label'=>'Profil Saya',   'href'=>'/profile'],
  ],
  'guru'=>[
    ['icon'=>'layout-dashboard','label'=>'Dashboard',     'href'=>'/dashboard/guru','s'=>null],
    ['s'=>'Kelas'],
    ['icon'=>'building-2',      'label'=>'Kelas Saya',   'href'=>'/classes'],
    ['s'=>'Akademik'],
    ['icon'=>'bar-chart-3',     'label'=>'Input Nilai',  'href'=>'/grades'],
    ['icon'=>'clipboard-list',  'label'=>'Tugas',        'href'=>'/assignments'],
    ['icon'=>'calendar-check',  'label'=>'Absensi',      'href'=>'/attendance'],
    ['icon'=>'file-text',       'label'=>'Rapor',        'href'=>'/reports'],
    ['icon'=>'book-open-check', 'label'=>'Jurnal Mengajar','href'=>'/journals'],
    ['s'=>'Sekolah'],
    ['icon'=>'calendar',        'label'=>'Kalender',     'href'=>'/calendar'],
    ['icon'=>'megaphone',       'label'=>'Pengumuman',   'href'=>'/announcements'],
    ['s'=>'Akun'],
    ['icon'=>'bell',            'label'=>'Notifikasi',   'href'=>'/notifications'],
    ['icon'=>'user',            'label'=>'Profil Saya',  'href'=>'/profile'],
  ],
  'murid'=>[
    ['icon'=>'layout-dashboard','label'=>'Dashboard',   'href'=>'/dashboard/murid','s'=>null],
    ['s'=>'Akademik'],
    ['icon'=>'star',            'label'=>'Nilai Saya',  'href'=>'/my-grades'],
    ['icon'=>'clipboard-list',  'label'=>'Tugas',       'href'=>'/assignments'],
    ['icon'=>'calendar-check',  'label'=>'Absensi',     'href'=>'/attendance'],
    ['icon'=>'file-text',       'label'=>'Rapor',       'href'=>'/my-rapor'],
    ['s'=>'Sekolah'],
    ['icon'=>'calendar',        'label'=>'Kalender',    'href'=>'/calendar'],
    ['icon'=>'megaphone',       'label'=>'Pengumuman',  'href'=>'/announcements'],
    ['s'=>'Akun'],
    ['icon'=>'bell',            'label'=>'Notifikasi',  'href'=>'/notifications'],
    ['icon'=>'user',            'label'=>'Profil Saya', 'href'=>'/profile'],
  ],
];
$_items = $_nav[$_role] ?? [];
?>
<div class="flex h-screen overflow-hidden">
  <div id="overlay" class="fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none lg:hidden transition-opacity duration-300" onclick="closeSidebar()"></div>

  <!-- SIDEBAR -->
  <aside id="sidebar" class="w-[260px] shrink-0 flex flex-col fixed lg:relative z-40 h-full bg-white dark:bg-dark-surface border-r border-slate-100 dark:border-dark-border -translate-x-full lg:translate-x-0 shadow-sidebar">
    <!-- Logo -->
    <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-100 dark:border-dark-border shrink-0">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shrink-0">
        <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
      </div>
      <div>
        <div class="font-display font-bold text-slate-900 dark:text-white text-[15px] leading-tight">SiAkad SD</div>
        <div class="text-2xs text-slate-400 dark:text-slate-600 font-medium tracking-wide">Sistem Akademik</div>
      </div>
    </div>

    <!-- User -->
    <div class="px-4 py-3 border-b border-slate-100 dark:border-dark-border shrink-0">
      <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-dark-card">
        <div class="avatar avatar-md <?= $_rd['av'] ?>"><?= strtoupper(substr($_user['name'],0,1)) ?></div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate leading-tight"><?= e($_user['name']) ?></p>
          <div class="flex items-center gap-1.5 mt-0.5">
            <span class="w-1.5 h-1.5 rounded-full <?= $_rd['dot'] ?> shrink-0 animate-pulse-slow"></span>
            <span class="text-2xs font-medium text-slate-500 dark:text-dark-text"><?= $_rd['label'] ?></span>
          </div>
        </div>
        <button onclick="toggleDark()" class="btn btn-ghost btn-icon btn-sm" title="Toggle tema">
          <i data-lucide="sun"  class="w-4 h-4 dark:hidden text-amber-500"></i>
          <i data-lucide="moon" class="w-4 h-4 hidden dark:block text-brand-400"></i>
        </button>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
      <?php foreach ($_items as $item):
        if (isset($item['s'])) {
          echo $item['s'] ? "<div class='nav-section-label mt-4 mb-1'>{$item['s']}</div>" : "<div class='mt-1'></div>";
          continue;
        }
        $isActive = $_uri === $item['href'] || (strlen($item['href'])>1 && str_starts_with($_uri, $item['href'].'/'));
        $disabled = isset($item['badge']);
      ?>
        <a href="<?= $disabled ? '#' : url($item['href']) ?>"
           class="nav-link <?= $isActive ? 'active' : '' ?> <?= $disabled ? 'opacity-40 pointer-events-none' : '' ?>">
          <span class="nav-icon"><i data-lucide="<?= $item['icon'] ?>" class="w-4 h-4"></i></span>
          <span class="flex-1 text-[13.5px]"><?= $item['label'] ?></span>
          <?php if ($disabled): ?><span class="badge badge-amber text-2xs"><?= $item['badge'] ?></span><?php endif; ?>
          <span class="nav-indicator"></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Logout -->
    <div class="px-3 py-3 border-t border-slate-100 dark:border-dark-border shrink-0">
      <a href="<?= url('/logout') ?>" class="nav-link text-red-500 dark:text-red-400 hover:!bg-red-50 dark:hover:!bg-red-900/20 hover:!text-red-600 dark:hover:!text-red-400">
        <span class="nav-icon"><i data-lucide="log-out" class="w-4 h-4"></i></span>
        <span class="text-[13.5px]">Keluar</span>
      </a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <!-- Topbar -->
    <header class="topbar h-16 bg-white/80 dark:bg-dark-surface/80 border-b border-slate-100 dark:border-dark-border flex items-center px-4 gap-3 shrink-0 sticky top-0 z-20">
      <button onclick="openSidebar()" class="btn btn-ghost btn-icon lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
      </button>
      <div class="flex-1 min-w-0">
        <h1 class="font-display font-bold text-slate-900 dark:text-white text-[17px] leading-tight truncate"><?= e($title ?? 'Dashboard') ?></h1>
        <?php if (!empty($breadcrumb)): ?>
        <p class="text-xs text-slate-400 dark:text-slate-600 mt-0.5"><?= e($breadcrumb) ?></p>
        <?php endif; ?>
      </div>
      <div class="flex items-center gap-2 shrink-0">
        <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-dark-card rounded-xl text-xs font-medium text-slate-500 dark:text-slate-500">
          <i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?= date('d M Y') ?>
        </div>
        <button onclick="toggleDark()" class="btn btn-ghost btn-icon hidden md:flex" title="Toggle tema">
          <i data-lucide="sun"  class="w-4 h-4 dark:hidden text-amber-500"></i>
          <i data-lucide="moon" class="w-4 h-4 hidden dark:block text-brand-400"></i>
        </button>
        <!-- Notification bell -->
        <a href="<?= url('/notifications') ?>" class="btn btn-ghost btn-icon relative" title="Notifikasi">
          <i data-lucide="bell" class="w-4 h-4"></i>
          <span id="notif-badge" class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-red-500 text-white text-2xs font-bold flex items-center justify-center hidden">0</span>
        </a>
        <!-- Announcements quick link -->
        <a href="<?= url('/announcements') ?>" class="btn btn-ghost btn-icon hidden md:flex" title="Pengumuman">
          <i data-lucide="megaphone" class="w-4 h-4"></i>
        </a>
        <div class="avatar avatar-sm <?= $_rd['av'] ?>"><?= strtoupper(substr($_user['name'],0,1)) ?></div>
      </div>
    </header>

    <!-- Flash -->
    <?php $flashes = Flash::all(); if (!empty($flashes)): ?>
    <div class="px-6 pt-4 space-y-2">
      <?php foreach ($flashes as $type => $msg):
        $cls = ['success'=>'flash-success','error'=>'flash-error','warning'=>'flash-warning','info'=>'flash-info'][$type] ?? 'flash-info';
        $icon = ['success'=>'check-circle','error'=>'x-circle','warning'=>'alert-triangle','info'=>'info'][$type] ?? 'info';
      ?>
      <div class="flash <?= $cls ?>" data-flash>
        <i data-lucide="<?= $icon ?>" class="w-4 h-4 shrink-0"></i>
        <span class="flex-1"><?= e($msg) ?></span>
        <button onclick="this.closest('[data-flash]').remove()" class="opacity-60 hover:opacity-100 transition-opacity">
          <i data-lucide="x" class="w-3.5 h-3.5"></i>
        </button>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="p-6 page-content">
