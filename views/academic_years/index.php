<?php $title = 'Tahun Ajaran'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-4xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Manajemen Tahun Ajaran</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Aktifkan periode untuk memperbarui sistem</p></div>
    <a href="<?= url('/academic-years/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Periode</a>
  </div>
  <?php if ($active): ?>
  <div class="card bg-gradient-to-br from-emerald-600 to-teal-700 text-white border-0">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center"><i data-lucide="check-circle" class="w-6 h-6"></i></div>
      <div>
        <p class="text-emerald-200 text-sm font-medium">Tahun Ajaran Aktif Saat Ini</p>
        <h3 class="font-display font-bold text-2xl"><?= e($active['tahun_ajaran']) ?> · Semester <?= $active['semester'] ?></h3>
        <p class="text-emerald-200 text-sm"><?= formatDate($active['tanggal_mulai']) ?> — <?= formatDate($active['tanggal_selesai']) ?></p>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Tahun Ajaran</th><th class="text-center">Semester</th><th>Mulai</th><th>Selesai</th><th class="text-center">Status</th><th class="text-right pr-5">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($years as $y): ?>
        <tr>
          <td class="font-semibold text-slate-800 dark:text-slate-100"><?= e($y['tahun_ajaran']) ?></td>
          <td class="text-center"><span class="badge badge-blue">Smt <?= $y['semester'] ?></span></td>
          <td class="text-sm text-slate-600 dark:text-slate-400"><?= formatDate($y['tanggal_mulai']) ?></td>
          <td class="text-sm text-slate-600 dark:text-slate-400"><?= formatDate($y['tanggal_selesai']) ?></td>
          <td class="text-center"><?= $y['is_active'] ? '<span class="badge badge-green">✓ Aktif</span>' : '<span class="badge badge-slate">—</span>' ?></td>
          <td>
            <div class="flex justify-end gap-1.5 pr-3">
              <?php if (!$y['is_active']): ?>
              <form method="POST" action="<?= url('/academic-years/'.$y['id'].'/activate') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success btn-sm" data-confirm="Aktifkan <?= e($y['tahun_ajaran']) ?> Semester <?= $y['semester'] ?>?\nConfig sistem akan otomatis diperbarui.">
                  <i data-lucide="check" class="w-3.5 h-3.5"></i> Aktifkan
                </button>
              </form>
              <form method="POST" action="<?= url('/academic-years/'.$y['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus periode ini?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
              </form>
              <?php else: ?><span class="text-xs text-slate-400 dark:text-dark-text">Sedang berjalan</span><?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($years)): ?><tr><td colspan="6"><div class="empty-state py-8"><i data-lucide="calendar" class="empty-icon"></i><p class="empty-title">Belum ada periode</p></div></td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-900/50">
    <div class="flex items-start gap-3">
      <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0"></i>
      <div class="text-sm text-amber-800 dark:text-amber-300">
        <strong>Catatan:</strong> Mengaktifkan tahun ajaran baru akan memperbarui file <code class="bg-amber-100 dark:bg-amber-900/40 px-1 rounded font-mono">app/config.php</code> secara otomatis. Semua halaman akan menggunakan periode baru setelah halaman di-refresh.
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
