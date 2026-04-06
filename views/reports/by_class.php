<?php $title = 'Rapor '.$class['nama_kelas']; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-6xl mx-auto">
  <div class="flex flex-wrap items-center gap-3">
    <a href="<?= url('/reports?semester='.$semester) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div class="flex-1"><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Rapor <?= e($class['nama_kelas']) ?></h2><p class="text-sm text-slate-400 dark:text-dark-text">Semester <?= $semester ?> · <?= TAHUN_AJARAN ?></p></div>
  </div>

  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th><th>Siswa</th><th class="text-center">Rata Nilai</th>
            <th class="text-center">Mapel</th><th class="text-center">Hadir</th>
            <th class="text-center">Alpha</th><th class="text-center">Predikat Sikap</th>
            <th class="text-center">Catatan</th><th class="text-right pr-5">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $i => $s):
          $avg = (float)($s['rata_nilai']??0);
          $gl  = $avg>=90?'A':($avg>=80?'B':($avg>=70?'C':'D'));
          $hasNote = !empty($s['catatan_wali']);
        ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
              <div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['nis']) ?></p></div>
            </div>
          </td>
          <td class="text-center">
            <div class="flex flex-col items-center gap-1">
              <span class="font-display font-bold text-xl <?= $avg>=90?'text-emerald-600 dark:text-emerald-400':($avg>=80?'text-brand-600 dark:text-brand-400':($avg>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= $avg ? number_format($avg,1) : '—' ?></span>
              <?php if ($avg): ?><span class="badge badge-<?= $gl ?> text-2xs"><?= $gl ?></span><?php endif; ?>
            </div>
          </td>
          <td class="text-center text-slate-600 dark:text-slate-400 font-semibold"><?= $s['jumlah_mapel']??0 ?></td>
          <td class="text-center font-bold text-emerald-600 dark:text-emerald-400"><?= $s['total_hadir']??0 ?></td>
          <td class="text-center font-bold text-red-600 dark:text-red-400"><?= $s['total_alpha']??0 ?></td>
          <td class="text-center">
            <?php if ($s['predikat_sikap']): ?>
            <span class="badge badge-<?= $s['predikat_sikap'] ?>"><?= $s['predikat_sikap'] ?></span>
            <?php else: ?><span class="text-slate-300 dark:text-dark-muted text-xs">—</span><?php endif; ?>
          </td>
          <td class="text-center">
            <?php if ($hasNote): ?>
            <span class="badge badge-green text-2xs">✓ Ada</span>
            <?php else: ?><span class="badge badge-slate text-2xs">Belum</span><?php endif; ?>
          </td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <a href="<?= url('/reports/preview/'.$s['student_id'].'?semester='.$semester) ?>"
                 class="btn btn-secondary btn-sm" title="Preview Rapor">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Preview
              </a>
              <a href="<?= url('/reports/pdf/'.$s['student_id'].'?semester='.$semester) ?>"
                 target="_blank" class="btn btn-primary btn-sm" title="Cetak/PDF">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?>
        <tr><td colspan="9"><div class="empty-state py-10"><i data-lucide="users" class="empty-icon"></i><p class="empty-title">Belum ada siswa di kelas ini</p></div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
