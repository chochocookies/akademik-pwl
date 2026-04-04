<?php $title = 'Nilai Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-5">
  <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Nilai Saya</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Semester <?= SEMESTER ?> · <?= TAHUN_AJARAN ?> · <?= e($student['nama_kelas']??'—') ?></p></div>
  <?php if (empty($grades)): ?>
  <div class="card"><div class="empty-state py-16"><i data-lucide="bar-chart-3" class="empty-icon"></i><p class="empty-title">Belum ada nilai</p></div></div>
  <?php else:
    $avg=round(array_sum(array_column($grades,'nilai_akhir'))/count($grades),1);
    $high=max(array_column($grades,'nilai_akhir'));
    $low=min(array_column($grades,'nilai_akhir'));
    $avgGl=$avg>=90?'A':($avg>=80?'B':($avg>=70?'C':'D'));
  ?>
  <div class="grid grid-cols-3 gap-4">
    <div class="card-stat text-center"><div class="font-display font-bold text-3xl <?= $avg>=90?'text-emerald-600 dark:text-emerald-400':($avg>=80?'text-brand-600 dark:text-brand-400':($avg>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= $avg ?></div><div class="text-xs text-slate-400 dark:text-dark-text mt-1.5 font-medium">Rata-rata</div></div>
    <div class="card-stat text-center"><div class="font-display font-bold text-3xl text-emerald-600 dark:text-emerald-400"><?= number_format((float)$high,1) ?></div><div class="text-xs text-slate-400 dark:text-dark-text mt-1.5 font-medium">Tertinggi</div></div>
    <div class="card-stat text-center"><div class="font-display font-bold text-3xl text-rose-600 dark:text-rose-400"><?= number_format((float)$low,1) ?></div><div class="text-xs text-slate-400 dark:text-dark-text mt-1.5 font-medium">Terendah</div></div>
  </div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Mata Pelajaran</th><th class="text-center">Harian</th><th class="text-center">UTS</th><th class="text-center">UAS</th><th class="text-center">Nilai Akhir</th><th class="text-center">Grade</th></tr></thead>
        <tbody>
        <?php foreach ($grades as $g):
          $na=(float)$g['nilai_akhir'];
          $gl=$na>=90?'A':($na>=80?'B':($na>=70?'C':'D'));
          $barC=$na>=90?'bg-emerald-500':($na>=80?'bg-brand-500':($na>=70?'bg-amber-500':'bg-red-500'));
        ?>
        <tr>
          <td><div class="flex items-center gap-2.5"><span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400"><?= e($g['kode_mapel']) ?></span><span class="font-medium text-slate-800 dark:text-slate-100"><?= e($g['nama_mapel']) ?></span></div></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_harian'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uts'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uas'],1) ?></td>
          <td class="text-center"><div class="flex flex-col items-center gap-1.5"><span class="font-display font-bold text-xl <?= $na>=90?'text-emerald-600 dark:text-emerald-400':($na>=80?'text-brand-600 dark:text-brand-400':($na>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= number_format($na,1) ?></span><div class="progress-bar w-20 h-1.5"><div class="progress-fill <?= $barC ?>" style="width:<?= $na ?>%"></div></div></div></td>
          <td class="text-center"><span class="badge badge-<?= $gl ?>"><?= $gl ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr>
          <td colspan="4" class="text-right font-semibold text-slate-600 dark:text-slate-400">Rata-rata:</td>
          <td class="text-center font-display font-bold text-2xl <?= $avg>=90?'text-emerald-600':($avg>=80?'text-brand-600':($avg>=70?'text-amber-600':'text-red-600')) ?>"><?= $avg ?></td>
          <td class="text-center"><span class="badge badge-<?= $avgGl ?>"><?= $avgGl ?></span></td>
        </tr></tfoot>
      </table>
    </div>
  </div>
  <div class="card">
    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3">Keterangan Grade</h4>
    <div class="flex flex-wrap gap-3 text-sm">
      <?php foreach ([['A','90–100','Sangat Baik'],['B','80–89','Baik'],['C','70–79','Cukup'],['D','<70','Perlu Bimbingan']] as [$g,$r,$l]): ?>
      <div class="flex items-center gap-2"><span class="badge badge-<?= $g ?>"><?= $g ?></span><span class="text-slate-500 dark:text-dark-text"><?= $r ?> <?= $l ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
