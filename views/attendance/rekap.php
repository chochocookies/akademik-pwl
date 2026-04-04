<?php $title = 'Rekap Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Rekap Absensi</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($class['nama_kelas']) ?></p>
    </div>
  </div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Siswa</th><th class="text-center">Hadir</th><th class="text-center">Sakit</th><th class="text-center">Izin</th><th class="text-center">Alpha</th><th class="text-center">% Hadir</th></tr></thead>
        <tbody>
        <?php foreach ($students as $i => $s):
          $stat = $summaries[$s['id']] ?? ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0,'pct_hadir'=>0];
          $pct = (float)($stat['pct_hadir']??0);
          $col = $pct>=80?'text-emerald-600 dark:text-emerald-400':($pct>=60?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400');
        ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td><div class="flex items-center gap-3"><div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div><div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['nis']) ?></p></div></div></td>
          <td class="text-center font-bold text-emerald-600 dark:text-emerald-400"><?= $stat['hadir']??0 ?></td>
          <td class="text-center font-bold text-blue-600 dark:text-blue-400"><?= $stat['sakit']??0 ?></td>
          <td class="text-center font-bold text-amber-600 dark:text-amber-400"><?= $stat['izin']??0 ?></td>
          <td class="text-center font-bold text-red-600 dark:text-red-400"><?= $stat['alpha']??0 ?></td>
          <td class="text-center">
            <div class="flex items-center justify-center gap-2">
              <div class="progress-bar w-16 h-1.5"><div class="progress-fill <?= $pct>=80?'bg-emerald-500':($pct>=60?'bg-amber-500':'bg-red-500') ?>" style="width:<?= $pct ?>%"></div></div>
              <span class="font-bold <?= $col ?> text-sm w-12"><?= $pct ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
