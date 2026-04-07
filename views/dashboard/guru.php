<?php $title = 'Dashboard Guru'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-6 max-w-7xl mx-auto">

  <!-- Welcome hero -->
  <div class="hero-guru rounded-3xl p-6 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23ffffff fill-opacity=1%3E%3Cpath d=M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z/%3E%3C/g%3E%3C/g%3E%3C/svg%3E') repeat"></div>
    <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden md:block">
      <div class="w-24 h-24 bg-white/10 rounded-3xl rotate-12"></div>
      <div class="w-14 h-14 bg-white/10 rounded-2xl -rotate-6 mt-3 ml-6"></div>
    </div>
    <div class="relative">
      <p class="text-cyan-200 text-sm font-medium mb-1">Selamat Mengajar 👨‍🏫</p>
      <h2 class="font-display font-bold text-3xl text-white tracking-tight"><?= e($teacher['name']) ?></h2>
      <p class="text-cyan-200 text-sm mt-2 flex items-center gap-4">
        <span class="flex items-center gap-1.5"><i data-lucide="credit-card" class="w-3.5 h-3.5"></i>NIP: <?= e($teacher['nip'] ?? '—') ?></span>
        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5"></i>Smt <?= SEMESTER ?> · <?= TAHUN_AJARAN ?></span>
      </p>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-3 gap-4">
    <?php $statCards = [
      ['Kelas Diampu',  $stats['total_kelas'],  'building-2',    'text-brand-600 dark:text-brand-400',   'bg-brand-50 dark:bg-brand-900/30'],
      ['Total Siswa',   $stats['total_siswa'],  'users',          'text-emerald-600 dark:text-emerald-400','bg-emerald-50 dark:bg-emerald-900/30'],
      ['Total Tugas',   $stats['total_tugas'],  'clipboard-list', 'text-amber-600 dark:text-amber-400',   'bg-amber-50 dark:bg-amber-900/30'],
    ];
    foreach ($statCards as $i => [$lbl,$val,$icon,$col,$bg]): ?>
    <div class="card-stat">
      <div class="flex items-center justify-between mb-4">
        <div class="stat-icon <?= $bg ?>"><i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $col ?>"></i></div>
      </div>
      <div class="font-display font-bold text-3xl text-slate-900 dark:text-white"><?= $val ?></div>
      <div class="text-xs font-medium text-slate-500 dark:text-dark-text mt-1.5"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Quick Actions -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    <?php $qa = [
      ['/grades',           'bar-chart-3',    'Input Nilai',    'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-brand-900/30'],
      ['/attendance/create','calendar-plus',  'Absensi Baru',   'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-700 dark:text-cyan-400 hover:bg-cyan-100 dark:hover:bg-cyan-900/30'],
      ['/assignments/create','file-plus',     'Buat Tugas',     'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/30'],
      ['/profile',          'user',           'Profil Saya',    'bg-slate-50 dark:bg-dark-card text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-dark-hover'],
    ]; ?>
    <?php foreach ($qa as [$href,$icon,$label,$cls]): ?>
    <a href="<?= url($href) ?>" class="flex flex-col items-center gap-2 p-4 <?= $cls ?> rounded-2xl transition-all duration-200 text-center group">
      <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
      <span class="text-xs font-semibold"><?= $label ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Kelas Saya -->
    <div class="card">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Kelas Saya</h3>
          <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= count($classes) ?> kelas diampu</p>
        </div>
        <a href="<?= url('/grades') ?>" class="btn btn-primary btn-sm">
          <i data-lucide="star" class="w-3.5 h-3.5"></i> Input Nilai
        </a>
      </div>
      <div class="space-y-3">
        <?php foreach ($classes as $c): ?>
        <div class="group flex items-center gap-4 p-4 bg-slate-50 dark:bg-dark-card rounded-2xl hover:bg-brand-50 dark:hover:bg-brand-900/15 transition-all duration-200 border border-transparent hover:border-brand-100 dark:hover:border-brand-900/50">
          <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/40 flex items-center justify-center shrink-0">
            <span class="font-display font-bold text-brand-700 dark:text-brand-400 text-lg"><?= $c['tingkat'] ?></span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($c['nama_kelas']) ?></p>
            <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= $c['jumlah_siswa'] ?> siswa</p>
          </div>
          <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="<?= url('/grades/'.$c['id'].'/input') ?>" class="btn btn-primary btn-sm">Nilai</a>
            <a href="<?= url('/classes/'.$c['id']) ?>" class="btn btn-secondary btn-sm">Detail</a>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($classes)): ?>
        <div class="empty-state py-8">
          <i data-lucide="inbox" class="empty-icon"></i>
          <p class="empty-title">Belum ada kelas</p>
          <p class="empty-desc">Hubungi admin untuk assignment kelas</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Tugas Terbaru -->
    <div class="card">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Tugas Terbaru</h3>
          <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5">5 tugas terkini</p>
        </div>
        <a href="<?= url('/assignments/create') ?>" class="btn btn-primary btn-sm">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i> Buat
        </a>
      </div>
      <div class="space-y-3">
        <?php foreach ($assignments as $a):
          $isPast = isDeadlinePassed($a['deadline']);
          $daysLeft = ceil((strtotime($a['deadline']) - time()) / 86400);
        ?>
        <div class="p-4 border border-slate-100 dark:border-dark-border rounded-2xl hover:border-slate-200 dark:hover:border-dark-muted transition-colors">
          <div class="flex items-start justify-between gap-2 mb-2">
            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm leading-tight"><?= e($a['judul']) ?></p>
            <span class="badge <?= $isPast ? 'badge-red' : 'badge-green' ?> shrink-0"><?= $isPast ? 'Lewat' : 'Aktif' ?></span>
          </div>
          <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 dark:text-dark-text">
            <span class="flex items-center gap-1"><i data-lucide="building-2" class="w-3 h-3"></i><?= e($a['nama_kelas']) ?></span>
            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i><?= formatDate($a['deadline'],'d M Y') ?></span>
            <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3 h-3"></i><?= $a['total_submissions'] ?> submit</span>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($assignments)): ?>
        <div class="empty-state py-8">
          <i data-lucide="clipboard-list" class="empty-icon"></i>
          <p class="empty-title">Belum ada tugas</p>
          <p class="empty-desc">Buat tugas pertama untuk siswa</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <!-- Announcements & Calendar -->
  <?php if (!empty($announcements) || !empty($upcomingEvents)): ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php if (!empty($announcements)): ?>
    <div class="card">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-base">Pengumuman</h3>
        <a href="<?= url('/announcements') ?>" class="btn btn-secondary btn-sm">Semua</a>
      </div>
      <div class="space-y-2">
        <?php foreach ($announcements as $ann): ?>
        <a href="<?= url('/announcements/'.$ann['id']) ?>" class="block p-3 bg-slate-50 dark:bg-dark-card rounded-xl hover:bg-brand-50 dark:hover:bg-brand-900/15 transition-all group">
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm truncate"><?= e($ann['judul']) ?></p>
          <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= timeAgo($ann['published_at']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($upcomingEvents)): ?>
    <div class="card">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-base">Event Mendatang</h3>
        <a href="<?= url('/calendar') ?>" class="btn btn-secondary btn-sm">Kalender</a>
      </div>
      <div class="space-y-2">
        <?php $evC=['libur'=>'text-red-600','ujian'=>'text-amber-600','event'=>'text-brand-600','lainnya'=>'text-slate-500'];
        foreach ($upcomingEvents as $ev): $d=(int)ceil((strtotime($ev['tanggal_mulai'])-time())/86400); ?>
        <div class="flex items-center gap-3 p-2.5 bg-slate-50 dark:bg-dark-card rounded-xl">
          <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center shrink-0 text-center leading-none">
            <span class="text-xs font-bold <?= $evC[$ev['tipe']]??'text-slate-500' ?>"><?= date('d',strtotime($ev['tanggal_mulai'])) ?></span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate"><?= e($ev['judul']) ?></p>
            <p class="text-xs text-slate-400 dark:text-dark-text"><?= $d<=0?'Berlangsung':"$d hari lagi" ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
