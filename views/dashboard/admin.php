<?php $title = 'Dashboard Admin'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-6 max-w-7xl mx-auto">

  <!-- Welcome hero -->
  <div class="hero-admin rounded-3xl p-6 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23ffffff fill-opacity=1%3E%3Cpath d=M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z/%3E%3C/g%3E%3C/g%3E%3C/svg%3E') repeat"></div>
    <div class="absolute right-6 top-1/2 -translate-y-1/2 w-32 h-32 bg-white/10 rounded-3xl rotate-12 hidden md:block"></div>
    <div class="relative">
      <p class="text-violet-200 text-sm font-medium mb-1">Selamat datang kembali 👋</p>
      <h2 class="font-display font-bold text-3xl text-white tracking-tight"><?= e(Auth::user()['name']) ?></h2>
      <p class="text-violet-200 text-sm mt-2 flex items-center gap-2">
        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
        Semester <?= SEMESTER ?> &bull; <?= TAHUN_AJARAN ?>
      </p>
    </div>
  </div>

  <!-- Stats grid -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <?php
    $cards = [
      ['Siswa',        $stats['total_siswa'],  'users',          'text-brand-600 dark:text-brand-400',   'bg-brand-50 dark:bg-brand-900/30'],
      ['Guru',         $stats['total_guru'],   'user-check',     'text-violet-600 dark:text-violet-400', 'bg-violet-50 dark:bg-violet-900/30'],
      ['Kelas',        $stats['total_kelas'],  'building-2',     'text-emerald-600 dark:text-emerald-400','bg-emerald-50 dark:bg-emerald-900/30'],
      ['Mata Pelajaran',$stats['total_mapel'], 'book-open',      'text-amber-600 dark:text-amber-400',   'bg-amber-50 dark:bg-amber-900/30'],
      ['Rata Nilai',   $stats['rata_nilai'],   'bar-chart-3',    'text-rose-600 dark:text-rose-400',     'bg-rose-50 dark:bg-rose-900/30'],
      ['Sesi Absensi', $stats['total_sesi'],   'calendar-check', 'text-cyan-600 dark:text-cyan-400',     'bg-cyan-50 dark:bg-cyan-900/30'],
    ];
    foreach ($cards as $i => [$lbl,$val,$icon,$col,$bg]):
    ?>
    <div class="card-stat group" style="animation-delay:<?= $i*60 ?>ms">
      <div class="flex items-center justify-between mb-4">
        <div class="stat-icon <?= $bg ?>">
          <i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $col ?>"></i>
        </div>
        <i data-lucide="trending-up" class="w-4 h-4 text-slate-300 dark:text-dark-muted group-hover:text-emerald-400 transition-colors"></i>
      </div>
      <div class="font-display font-bold text-2xl text-slate-900 dark:text-white leading-none"><?= $val ?></div>
      <div class="text-xs font-medium text-slate-500 dark:text-dark-text mt-1.5"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Distribusi + Quick Actions -->
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    <div class="card lg:col-span-3">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Distribusi Siswa</h3>
          <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5">Per kelas aktif</p>
        </div>
        <a href="<?= url('/classes') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
      </div>
      <div class="space-y-4">
        <?php foreach ($classSummary as $idx => $c):
          $pct = $stats['total_siswa']>0 ? round($c['total']/$stats['total_siswa']*100) : 0;
          $colors = ['bg-brand-500','bg-violet-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-cyan-500'];
          $color  = $colors[$idx % count($colors)];
        ?>
        <div>
          <div class="flex justify-between items-center mb-2">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full <?= $color ?>"></span>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?= e($c['nama_kelas']) ?></span>
            </div>
            <span class="text-sm font-bold text-slate-900 dark:text-white"><?= $c['total'] ?> <span class="text-xs font-normal text-slate-400">(<?= $pct ?>%)</span></span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill <?= $color ?>" style="width:<?= $pct ?>%;transition-delay:<?= $idx*100 ?>ms"></div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($classSummary)): ?><p class="text-slate-400 text-sm text-center py-4">Belum ada kelas</p><?php endif; ?>
      </div>
    </div>
    <div class="card lg:col-span-2">
      <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-5">Aksi Cepat</h3>
      <div class="space-y-2">
        <?php
        $actions = [
          ['/students/create','user-plus',   'Tambah Siswa Baru',   'bg-brand-50 dark:bg-brand-900/20 hover:bg-brand-100 dark:hover:bg-brand-900/30 text-brand-700 dark:text-brand-400'],
          ['/teachers/create','user-check',  'Tambah Guru Baru',    'bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/30 text-violet-700 dark:text-violet-400'],
          ['/classes/create', 'building-2',  'Buat Kelas Baru',     'bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'],
          ['/announcements/create','megaphone','Buat Pengumuman',   'bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 text-amber-700 dark:text-amber-400'],
          ['/reports',        'file-text',   'Rapor Digital',       'bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/30 text-rose-700 dark:text-rose-400'],
        ];
        foreach ($actions as [$href,$icon,$label,$cls]):
        ?>
        <a href="<?= url($href) ?>" class="flex items-center gap-3 p-3 <?= $cls ?> rounded-2xl transition-all duration-200 group">
          <i data-lucide="<?= $icon ?>" class="w-4 h-4 shrink-0"></i>
          <span class="text-sm font-medium flex-1"><?= $label ?></span>
          <i data-lucide="arrow-right" class="w-3.5 h-3.5 opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition-all"></i>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Pengumuman + Calendar -->
  <?php if (!empty($announcements) || !empty($upcomingEvents)): ?>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php if (!empty($announcements)): ?>
    <div class="card">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Pengumuman Terbaru</h3>
        <a href="<?= url('/announcements') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
      </div>
      <div class="space-y-3">
        <?php foreach ($announcements as $ann): ?>
        <a href="<?= url('/announcements/'.$ann['id']) ?>" class="block p-3 bg-slate-50 dark:bg-dark-card rounded-xl hover:bg-brand-50 dark:hover:bg-brand-900/15 border border-transparent hover:border-brand-100 dark:hover:border-brand-900/40 transition-all group">
          <div class="flex items-start gap-2.5">
            <?php if ($ann['is_pinned']): ?><i data-lucide="pin" class="w-3.5 h-3.5 text-amber-500 shrink-0 mt-0.5"></i><?php else: ?><i data-lucide="megaphone" class="w-3.5 h-3.5 text-brand-500 shrink-0 mt-0.5"></i><?php endif; ?>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm truncate group-hover:text-brand-700 dark:group-hover:text-brand-400 transition-colors"><?= e($ann['judul']) ?></p>
              <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= timeAgo($ann['published_at']) ?></p>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($upcomingEvents)): ?>
    <div class="card">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Event Mendatang</h3>
        <a href="<?= url('/calendar') ?>" class="btn btn-secondary btn-sm">Kalender</a>
      </div>
      <div class="space-y-3">
        <?php
        $evColors = ['libur'=>'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400','ujian'=>'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400','event'=>'bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-400','lainnya'=>'bg-slate-100 dark:bg-dark-muted text-slate-600 dark:text-slate-400'];
        foreach ($upcomingEvents as $ev):
          $daysAway = (int)ceil((strtotime($ev['tanggal_mulai'])-time())/86400);
        ?>
        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-card rounded-xl">
          <div class="w-12 h-12 rounded-xl <?= $evColors[$ev['tipe']]??$evColors['lainnya'] ?> flex flex-col items-center justify-center shrink-0 leading-none">
            <span class="text-sm font-bold"><?= date('d',strtotime($ev['tanggal_mulai'])) ?></span>
            <span class="text-2xs"><?= date('M',strtotime($ev['tanggal_mulai'])) ?></span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm truncate"><?= e($ev['judul']) ?></p>
            <p class="text-xs text-slate-400 dark:text-dark-text"><?= $daysAway <= 0 ? 'Sedang berlangsung' : "dalam $daysAway hari" ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Absensi Terkini -->
  <?php if (!empty($recentSessions)): ?>
  <div class="card">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Absensi Terkini</h3>
        <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5">5 sesi terakhir</p>
      </div>
      <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="space-y-2">
      <?php foreach ($recentSessions as $s): ?>
      <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-dark-card rounded-xl hover:bg-slate-100 dark:hover:bg-dark-hover transition-colors">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center shrink-0">
            <i data-lucide="calendar-check" class="w-4 h-4 text-cyan-600 dark:text-cyan-400"></i>
          </div>
          <div>
            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['nama_kelas']) ?> — <?= e($s['nama_mapel']) ?></p>
            <p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDate($s['tanggal'], 'd M Y') ?> · <?= e($s['guru_name']) ?></p>
          </div>
        </div>
        <div class="flex items-center gap-3 text-xs shrink-0">
          <span class="font-bold text-emerald-600 dark:text-emerald-400"><?= $s['hadir']??0 ?> H</span>
          <span class="font-bold text-red-500"><?= $s['alpha']??0 ?> A</span>
          <a href="<?= url('/attendance/'.$s['id']) ?>" class="btn btn-secondary btn-sm">Detail</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Siswa Terbaru -->
  <div class="card">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Data Siswa Terbaru</h3>
        <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5">5 siswa terakhir</p>
      </div>
      <a href="<?= url('/students') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Siswa</th><th>NIS</th><th>Kelas</th><th>Gender</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recentStudents as $s): ?>
        <tr>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p>
                <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['email']) ?></p>
              </div>
            </div>
          </td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted px-2 py-1 rounded-lg"><?= e($s['nis']) ?></span></td>
          <td><?= $s['nama_kelas'] ? '<span class="badge badge-blue">'.e($s['nama_kelas']).'</span>' : '<span class="text-slate-300 dark:text-dark-muted text-xs">—</span>' ?></td>
          <td><?= $s['gender']==='L' ? '<span class="badge badge-blue">L</span>' : '<span class="badge badge-purple">P</span>' ?></td>
          <td><?= $s['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?></td>
          <td><a href="<?= url('/students/'.$s['id']) ?>" class="btn btn-secondary btn-sm">Detail</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recentStudents)): ?>
        <tr><td colspan="6"><div class="empty-state py-8"><i data-lucide="users" class="empty-icon"></i><p class="empty-title">Belum ada data siswa</p></div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
