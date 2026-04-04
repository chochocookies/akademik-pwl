<?php $title = 'Absensi Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto space-y-5">
  <div>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Absensi Saya</h2>
    <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($student['nama_kelas']??'—') ?></p>
  </div>

  <!-- Overall stats -->
  <div class="card bg-gradient-to-br from-slate-800 to-slate-900 text-white border-0">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-display font-bold text-lg">Ringkasan Kehadiran</h3>
      <div class="text-right">
        <div class="font-display font-bold text-4xl text-emerald-400"><?= $overall['pct_hadir'] ?>%</div>
        <div class="text-xs text-slate-400 mt-0.5">Tingkat Hadir</div>
      </div>
    </div>
    <div class="grid grid-cols-4 gap-3 mb-4">
      <?php foreach ([
        ['Hadir', $overall['hadir']??0, 'text-emerald-400'],
        ['Sakit', $overall['sakit']??0, 'text-blue-400'],
        ['Izin',  $overall['izin']??0,  'text-amber-400'],
        ['Alpha', $overall['alpha']??0, 'text-red-400'],
      ] as [$lbl,$val,$col]): ?>
      <div class="text-center p-3 bg-white/5 rounded-xl">
        <div class="font-display font-bold text-2xl <?= $col ?>"><?= $val ?></div>
        <div class="text-xs text-slate-500 mt-0.5"><?= $lbl ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php $pct = $overall['pct_hadir']??0; ?>
    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
      <div class="h-full bg-emerald-500 rounded-full" style="width:<?= $pct ?>%"></div>
    </div>
  </div>

  <!-- Per subject summary -->
  <?php if (!empty($summary)): ?>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Kehadiran per Mata Pelajaran</h3>
    <div class="space-y-4">
      <?php foreach ($summary as $s):
        $pct2 = (float)($s['pct_hadir']??0);
        $barC = $pct2>=80?'bg-emerald-500':($pct2>=60?'bg-amber-500':'bg-red-500');
      ?>
      <div>
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400"><?= e($s['kode_mapel']) ?></span>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?= e($s['nama_mapel']) ?></span>
          </div>
          <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-dark-text">
            <span><span class="font-bold text-emerald-600 dark:text-emerald-400"><?= $s['hadir'] ?></span> H</span>
            <span><span class="font-bold text-blue-600 dark:text-blue-400"><?= $s['sakit'] ?></span> S</span>
            <span><span class="font-bold text-amber-600 dark:text-amber-400"><?= $s['izin'] ?></span> I</span>
            <span><span class="font-bold text-red-600 dark:text-red-400"><?= $s['alpha'] ?></span> A</span>
            <span class="font-bold text-slate-700 dark:text-slate-300 w-10 text-right"><?= $pct2 ?>%</span>
          </div>
        </div>
        <div class="progress-bar h-2">
          <div class="progress-fill <?= $barC ?>" style="width:<?= $pct2 ?>%"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- History -->
  <?php if (!empty($history)): ?>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Riwayat Kehadiran</h3>
    <div class="space-y-2">
      <?php foreach ($history as $h):
        $badge = match($h['status']) {
          'hadir'=>'badge-green','sakit'=>'badge-blue','izin'=>'badge-amber','alpha'=>'badge-red',default=>'badge-slate'
        };
        $label = ['hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha'][$h['status']] ?? ucfirst($h['status']);
      ?>
      <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-dark-card rounded-xl hover:bg-slate-100 dark:hover:bg-dark-hover transition-colors">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-dark-muted flex items-center justify-center shrink-0">
            <span class="text-xs font-bold text-slate-600 dark:text-slate-400"><?= date('d',strtotime($h['tanggal'])) ?></span>
          </div>
          <div>
            <p class="font-medium text-slate-800 dark:text-slate-200 text-sm"><?= e($h['nama_mapel']) ?></p>
            <p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDate($h['tanggal'],'l, d M Y') ?></p>
          </div>
        </div>
        <span class="badge <?= $badge ?>"><?= $label ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (empty($summary) && empty($history)): ?>
  <div class="card">
    <div class="empty-state py-16">
      <i data-lucide="calendar-check" class="empty-icon"></i>
      <p class="empty-title">Belum ada data absensi</p>
      <p class="empty-desc">Data kehadiran akan muncul setelah guru mengisi absensi</p>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
