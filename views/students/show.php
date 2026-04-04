<?php $title = 'Detail Siswa'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-4xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/students') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Siswa</h2>
  </div>

  <!-- Profile card -->
  <div class="card">
    <div class="flex flex-col sm:flex-row gap-5">
      <div class="avatar avatar-xl avatar-blue self-start"><?= strtoupper(substr($student['name'],0,1)) ?></div>
      <div class="flex-1">
        <div class="flex items-start justify-between gap-4 flex-wrap">
          <div>
            <h3 class="font-display font-bold text-slate-900 dark:text-white text-2xl"><?= e($student['name']) ?></h3>
            <p class="text-slate-400 dark:text-dark-text text-sm mt-0.5"><?= e($student['email']) ?></p>
            <div class="flex flex-wrap gap-2 mt-3">
              <?= $student['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?>
              <?php if ($student['nama_kelas']): ?>
              <span class="badge badge-blue"><?= e($student['nama_kelas']) ?></span>
              <?php endif; ?>
              <?= $student['gender']==='L' ? '<span class="badge badge-blue">Laki-laki</span>' : '<span class="badge badge-purple">Perempuan</span>' ?>
            </div>
          </div>
          <?php if (Auth::is('admin')): ?>
          <a href="<?= url('/students/'.$student['id'].'/edit') ?>" class="btn btn-secondary btn-sm">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
          </a>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5 pt-5 border-t border-slate-100 dark:border-dark-border">
          <?php $infos = [
            ['NIS',         $student['nis']],
            ['Tingkat',     'Kelas '.($student['tingkat'] ?? '—')],
            ['Tgl Lahir',   $student['birth_date'] ? formatDate($student['birth_date']) : '—'],
            ['Orang Tua',   $student['parent_name'] ?? '—'],
            ['Telepon',     $student['phone'] ?? '—'],
            ['Alamat',      $student['address'] ?? '—'],
          ];
          foreach ($infos as [$lbl,$val]): ?>
          <div>
            <p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider"><?= $lbl ?></p>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($val) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Nilai -->
  <div class="card">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Rekap Nilai</h3>
      <?php if (!empty($grades)):
        $avg = round(array_sum(array_column($grades,'nilai_akhir'))/count($grades),1);
        $gradeLetter = $avg>=90?'A':($avg>=80?'B':($avg>=70?'C':'D'));
      ?>
      <div class="flex items-center gap-3">
        <span class="badge badge-<?= $gradeLetter ?>">Grade <?= $gradeLetter ?></span>
        <span class="font-display font-bold text-2xl text-slate-900 dark:text-white"><?= $avg ?></span>
        <span class="text-xs text-slate-400 dark:text-dark-text">rata-rata</span>
      </div>
      <?php endif; ?>
    </div>
    <?php if (empty($grades)): ?>
    <div class="empty-state py-8"><i data-lucide="bar-chart-3" class="empty-icon"></i><p class="empty-title">Belum ada nilai</p></div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Mata Pelajaran</th><th class="text-center">Harian</th><th class="text-center">UTS</th><th class="text-center">UAS</th><th class="text-center">Nilai Akhir</th><th class="text-center">Grade</th></tr></thead>
        <tbody>
        <?php foreach ($grades as $g):
          $na = (float)$g['nilai_akhir'];
          $gl = $na>=90?'A':($na>=80?'B':($na>=70?'C':'D'));
        ?>
        <tr>
          <td>
            <div class="flex items-center gap-2.5">
              <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400"><?= e($g['kode_mapel']) ?></span>
              <span class="font-medium text-slate-800 dark:text-slate-100"><?= e($g['nama_mapel']) ?></span>
            </div>
          </td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_harian'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uts'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uas'],1) ?></td>
          <td class="text-center font-display font-bold text-lg <?= $na>=90?'text-emerald-600 dark:text-emerald-400':($na>=80?'text-brand-600 dark:text-brand-400':($na>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= number_format($na,1) ?></td>
          <td class="text-center"><span class="badge badge-<?= $gl ?>"><?= $gl ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4" class="text-right font-semibold text-slate-600 dark:text-slate-400">Rata-rata:</td>
            <td class="text-center font-display font-bold text-xl <?= $avg>=90?'text-emerald-600':($avg>=80?'text-brand-600':($avg>=70?'text-amber-600':'text-red-600')) ?>"><?= $avg ?></td>
            <td class="text-center"><span class="badge badge-<?= $gradeLetter ?>"><?= $gradeLetter ?></span></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
