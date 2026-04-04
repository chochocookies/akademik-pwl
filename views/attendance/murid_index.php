<?php $title = 'Absensi Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto space-y-5">
  <div>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Kehadiran Saya</h2>
    <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($student['nama_kelas'] ?? '—') ?> · Tahun Ajaran <?= TAHUN_AJARAN ?></p>
  </div>

  <!-- Overall stats -->
  <?php if (!empty($overall)): 
    $pct = (float)($overall['pct_hadir'] ?? 0);
    $totalSesi = ($overall['hadir'] ?? 0) + ($overall['sakit'] ?? 0) + ($overall['izin'] ?? 0) + ($overall['alpha'] ?? 0);
  ?>
  <div class="card">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
      <div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Ringkasan Kehadiran</h3>
        <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= $totalSesi ?> total pertemuan</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="font-display font-bold text-4xl <?= $pct>=80?'text-emerald-600 dark:text-emerald-400':($pct>=60?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400') ?>"><?= $pct ?>%</div>
        <div class="text-sm text-slate-500 dark:text-dark-text">kehadiran</div>
      </div>
    </div>
    <div class="progress-bar mb-5">
      <div class="progress-fill <?= $pct>=80?'bg-emerald-500':($pct>=60?'bg-amber-500':'bg-red-500') ?>" style="width:<?= $pct ?>%"></div>
    </div>
    <div class="grid grid-cols-4 gap-3">
      <?php $statItems = [
        [$overall['hadir']??0, 'Hadir',  'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'],
        [$overall['sakit']??0, 'Sakit',  'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'],
        [$overall['izin']??0,  'Izin',   'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400'],
        [$overall['alpha']??0, 'Alpha',  'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400'],
      ];
      foreach ($statItems as [$val, $lbl, $cls]): ?>
      <div class="<?= $cls ?> rounded-2xl p-3 text-center">
        <div class="font-display font-bold text-2xl"><?= $val ?></div>
        <div class="text-xs font-medium mt-0.5"><?= $lbl ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Per subject summary -->
  <?php if (!empty($summary)): ?>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Per Mata Pelajaran</h3>
    <div class="space-y-3">
      <?php foreach ($summary as $s):
        $p = (float)($s['pct_hadir'] ?? 0);
        $barC = $p>=80?'bg-emerald-500':($p>=60?'bg-amber-500':'bg-red-500');
        $textC = $p>=80?'text-emerald-600 dark:text-emerald-400':($p>=60?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400');
      ?>
      <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-dark-card rounded-xl hover:bg-slate-100 dark:hover:bg-dark-hover transition-colors">
        <span class="w-10 h-10 rounded-xl bg-slate-200 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400 shrink-0"><?= e($s['kode_mapel']) ?></span>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['nama_mapel']) ?></p>
          <div class="flex items-center gap-2 mt-1.5">
            <div class="progress-bar flex-1 h-1.5"><div class="progress-fill <?= $barC ?>" style="width:<?= $p ?>%"></div></div>
            <span class="text-xs text-slate-400 dark:text-dark-text"><?= $s['hadir']??0 ?>/<?= ($s['hadir']??0)+($s['sakit']??0)+($s['izin']??0)+($s['alpha']??0) ?></span>
          </div>
        </div>
        <span class="font-display font-bold text-xl <?= $textC ?> shrink-0 w-14 text-right"><?= $p ?>%</span>
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
        $statusData = [
          'hadir' => ['icon'=>'check-circle','cls'=>'text-emerald-600 dark:text-emerald-400','bg'=>'bg-emerald-50 dark:bg-emerald-900/20','label'=>'Hadir'],
          'sakit' => ['icon'=>'thermometer',  'cls'=>'text-blue-600 dark:text-blue-400',    'bg'=>'bg-blue-50 dark:bg-blue-900/20',    'label'=>'Sakit'],
          'izin'  => ['icon'=>'file-text',    'cls'=>'text-amber-600 dark:text-amber-400',  'bg'=>'bg-amber-50 dark:bg-amber-900/20',  'label'=>'Izin'],
          'alpha' => ['icon'=>'x-circle',     'cls'=>'text-red-600 dark:text-red-400',      'bg'=>'bg-red-50 dark:bg-red-900/20',      'label'=>'Alpha'],
        ];
        $sd = $statusData[$h['status']] ?? $statusData['hadir'];
      ?>
      <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-dark-card transition-colors">
        <div class="w-9 h-9 <?= $sd['bg'] ?> rounded-xl flex items-center justify-center shrink-0">
          <i data-lucide="<?= $sd['icon'] ?>" class="w-4 h-4 <?= $sd['cls'] ?>"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($h['nama_mapel']) ?></p>
          <p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDateID($h['tanggal'], 'l, d M Y') ?></p>
        </div>
        <span class="badge <?= ['hadir'=>'badge-green','sakit'=>'badge-blue','izin'=>'badge-amber','alpha'=>'badge-red'][$h['status']] ?>"><?= $sd['label'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (empty($overall) && empty($summary)): ?>
  <div class="card">
    <div class="empty-state py-16">
      <i data-lucide="calendar-check" class="empty-icon"></i>
      <p class="empty-title">Belum ada data kehadiran</p>
      <p class="empty-desc">Data kehadiran akan muncul setelah guru mengisi absensi</p>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
