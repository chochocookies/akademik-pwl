<?php $title = 'Dashboard Murid'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-6 max-w-7xl mx-auto">

  <!-- Hero -->
  <div class="hero-murid rounded-3xl p-6 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=0 0 60 60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23ffffff fill-opacity=1%3E%3Cpath d=M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z/%3E%3C/g%3E%3C/g%3E%3C/svg%3E') repeat"></div>
    <div class="relative flex items-center justify-between">
      <div>
        <p class="text-teal-200 text-sm font-medium mb-1">Halo, selamat belajar 👦</p>
        <h2 class="font-display font-bold text-3xl text-white tracking-tight"><?= e($student['name']) ?></h2>
        <p class="text-teal-200 text-sm mt-2 flex items-center gap-4">
          <span>NIS: <?= e($student['nis']) ?></span>
          <span><?= e($student['nama_kelas'] ?? '—') ?></span>
        </p>
      </div>
      <div class="text-right hidden sm:block">
        <div class="font-display font-bold text-5xl text-white/90"><?= $stats['rata_nilai'] ?></div>
        <div class="text-teal-200 text-xs mt-1 font-medium">Rata-rata Nilai</div>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php $sc = [
      ['Mata Pelajaran', $stats['total_nilai'], 'book-open','text-brand-600 dark:text-brand-400','bg-brand-50 dark:bg-brand-900/30'],
      ['Rata-rata Nilai',$stats['rata_nilai'],'trending-up','text-emerald-600 dark:text-emerald-400','bg-emerald-50 dark:bg-emerald-900/30'],
      ['Total Tugas',$stats['total_tugas'],'clipboard-list','text-amber-600 dark:text-amber-400','bg-amber-50 dark:bg-amber-900/30'],
      ['Tugas Selesai',$stats['tugas_selesai'],'check-circle','text-violet-600 dark:text-violet-400','bg-violet-50 dark:bg-violet-900/30'],
    ];
    foreach ($sc as [$lbl,$val,$icon,$col,$bg]): ?>
    <div class="card-stat">
      <div class="stat-icon <?= $bg ?> mb-4"><i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $col ?>"></i></div>
      <div class="font-display font-bold text-2xl text-slate-900 dark:text-white"><?= $val ?></div>
      <div class="text-xs font-medium text-slate-500 dark:text-dark-text mt-1.5"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Quick links -->
  <div class="grid grid-cols-3 gap-3">
    <?php foreach ([
      ['/my-grades','star','Lihat Nilai','bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 hover:bg-brand-100'],
      ['/attendance','calendar-check','Absensi Saya','bg-cyan-50 dark:bg-cyan-900/20 text-cyan-700 dark:text-cyan-400 hover:bg-cyan-100'],
      ['/profile','user','Profil Saya','bg-slate-50 dark:bg-dark-card text-slate-700 dark:text-slate-300 hover:bg-slate-100'],
    ] as [$href,$icon,$label,$cls]): ?>
    <a href="<?= url($href) ?>" class="flex flex-col items-center gap-2 p-4 <?= $cls ?> rounded-2xl transition-all text-center">
      <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
      <span class="text-xs font-semibold"><?= $label ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Nilai -->
    <div class="card">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Nilai Semester Ini</h3>
        <a href="<?= url('/my-grades') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
      </div>

      <?php if (empty($grades)): ?>
      <div class="empty-state py-8"><p class="empty-title">Belum ada nilai</p></div>
      <?php else: ?>
      <div class="space-y-2">
        <?php foreach (array_slice($grades,0,6) as $g):
          $na=(float)$g['nilai_akhir'];
          $barColor=$na>=90?'bg-emerald-500':($na>=80?'bg-brand-500':($na>=70?'bg-amber-500':'bg-red-500'));
        ?>
        <div class="p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-card">
          <p class="text-sm font-medium"><?= e($g['nama_mapel']) ?></p>
          <div class="progress-bar mt-1.5 h-1.5">
            <div class="progress-fill <?= $barColor ?>" style="width:<?= $na ?>%"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Tugas -->
    <div class="card">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Daftar Tugas</h3>
        <a href="<?= url('/assignments') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
      </div>

      <div class="space-y-2">
        <?php foreach (array_slice($assignments,0,5) as $a):
          $isPast=isDeadlinePassed($a['deadline']);
          $submitted=in_array($a['id'],$submittedIds);
        ?>
        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-card">
          <p class="text-sm"><?= e($a['judul']) ?></p>
          <?php if($submitted): ?>
            <span class="badge badge-green text-2xs">Selesai</span>
          <?php elseif($isPast): ?>
            <span class="badge badge-red text-2xs">Terlambat</span>
          <?php else: ?>
            <a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-primary btn-sm">Kumpul</a>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Pengumuman + Calendar (FIXED) -->
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
            <?php if (!empty($ann['is_pinned'])): ?>
              <i data-lucide="pin" class="w-3.5 h-3.5 text-amber-500 mt-0.5"></i>
            <?php else: ?>
              <i data-lucide="megaphone" class="w-3.5 h-3.5 text-brand-500 mt-0.5"></i>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-sm truncate"><?= e($ann['judul']) ?></p>
              <p class="text-xs text-slate-400 mt-0.5"><?= timeAgo($ann['published_at']) ?></p>
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
        $evColors=[
          'libur'=>'bg-red-100 dark:bg-red-900/30 text-red-700',
          'ujian'=>'bg-amber-100 dark:bg-amber-900/30 text-amber-700',
          'event'=>'bg-brand-100 dark:bg-brand-900/30 text-brand-700',
          'lainnya'=>'bg-slate-100 dark:bg-dark-muted text-slate-600'
        ];
        foreach ($upcomingEvents as $ev):
          $t=strtotime($ev['tanggal_mulai']);
          $d=(int)ceil(($t-time())/86400);
          $type=$ev['tipe']??'lainnya';
        ?>
        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-card rounded-xl">
          <div class="w-12 h-12 rounded-xl <?= $evColors[$type] ?> flex flex-col items-center justify-center">
            <span class="text-sm font-bold"><?= date('d',$t) ?></span>
            <span class="text-2xs"><?= date('M',$t) ?></span>
          </div>
          <div class="flex-1">
            <p class="font-semibold text-sm truncate"><?= e($ev['judul']) ?></p>
            <p class="text-xs text-slate-400"><?= $d<=0?'Sedang berlangsung':"dalam $d hari" ?></p>
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