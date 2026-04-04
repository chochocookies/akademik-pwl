<?php $title = 'Detail Sesi Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto space-y-5">
  <div class="flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3">
      <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
      <div>
        <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Absensi</h2>
        <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($session['nama_kelas']) ?> · <?= formatDateID($session['tanggal'], 'l, d M Y') ?></p>
      </div>
    </div>
    <?php if (Auth::is('guru','admin')): ?>
    <a href="<?= url('/attendance/'.$session['id'].'/fill') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit Absensi
    </a>
    <?php endif; ?>
  </div>

  <!-- Session Info -->
  <div class="card">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider">Kelas</p><p class="font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($session['nama_kelas']) ?></p></div>
      <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider">Mata Pelajaran</p><p class="font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($session['nama_mapel']) ?></p></div>
      <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider">Guru</p><p class="font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($session['guru_name'] ?? '—') ?></p></div>
      <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider">Keterangan</p><p class="font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($session['keterangan'] ?? '—') ?></p></div>
    </div>

    <!-- Summary counts -->
    <?php
      $counts = ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];
      foreach ($records as $r) $counts[$r['status']] = ($counts[$r['status']] ?? 0) + 1;
      $total = array_sum($counts);
    ?>
    <div class="grid grid-cols-4 gap-3 mt-5 pt-5 border-t border-slate-100 dark:border-dark-border">
      <?php foreach ([['hadir','Hadir','bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'],['sakit','Sakit','bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'],['izin','Izin','bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400'],['alpha','Alpha','bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400']] as [$k,$l,$cls]): ?>
      <div class="<?= $cls ?> rounded-2xl p-3 text-center">
        <div class="font-display font-bold text-2xl"><?= $counts[$k] ?></div>
        <div class="text-xs font-medium mt-0.5"><?= $l ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Records table -->
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Siswa</th><th class="text-center">Status</th><th>Catatan</th></tr></thead>
        <tbody>
        <?php foreach ($records as $i => $r):
          $statusStyle = [
            'hadir' => ['badge-green',   'check-circle',   'Hadir'],
            'sakit' => ['badge-blue',    'thermometer',    'Sakit'],
            'izin'  => ['badge-amber',   'file-text',      'Izin'],
            'alpha' => ['badge-red',     'x-circle',       'Alpha'],
          ][$r['status']] ?? ['badge-slate','circle','—'];
        ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($r['student_name'],0,1)) ?></div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($r['student_name']) ?></p>
                <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($r['nis']) ?></p>
              </div>
            </div>
          </td>
          <td class="text-center">
            <span class="badge <?= $statusStyle[0] ?>">
              <i data-lucide="<?= $statusStyle[1] ?>" class="w-3 h-3"></i>
              <?= $statusStyle[2] ?>
            </span>
          </td>
          <td class="text-sm text-slate-500 dark:text-dark-text"><?= e($r['catatan'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($records)): ?>
        <tr><td colspan="4"><div class="empty-state py-8"><i data-lucide="users" class="empty-icon"></i><p class="empty-title">Belum ada data</p></div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
