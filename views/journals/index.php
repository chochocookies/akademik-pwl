<?php $title = 'Jurnal Mengajar'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Jurnal Mengajar</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= $stats['total'] ?> total · <?= $stats['bulan_ini'] ?> bulan ini</p></div>
    <?php if (Auth::is('guru','admin')): ?><a href="<?= url('/journals/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Jurnal</a><?php endif; ?>
  </div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Tanggal</th><th>Kelas</th><th>Mapel</th><?php if (Auth::is('admin')): ?><th>Guru</th><?php endif; ?><th>Materi Pokok</th><th>Metode</th><th class="text-right pr-5">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($journals as $j): ?>
        <tr>
          <td><div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= formatDate($j['tanggal'],'d M Y') ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDate($j['tanggal'],'l') ?></p></div></td>
          <td><span class="badge badge-blue"><?= e($j['nama_kelas']) ?></span></td>
          <td><div class="flex items-center gap-2"><span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400"><?= e($j['kode_mapel']) ?></span><span class="text-sm text-slate-700 dark:text-slate-300"><?= e($j['nama_mapel']) ?></span></div></td>
          <?php if (Auth::is('admin')): ?><td class="text-sm text-slate-500 dark:text-dark-text"><?= e($j['guru_name']) ?></td><?php endif; ?>
          <td class="font-medium text-slate-800 dark:text-slate-100 max-w-xs truncate"><?= e($j['materi_pokok']) ?></td>
          <td class="text-xs text-slate-500 dark:text-dark-text"><?= e($j['metode']??'—') ?></td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <a href="<?= url('/journals/'.$j['id']) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
              <?php if (Auth::is('guru','admin')): ?>
              <a href="<?= url('/journals/'.$j['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
              <form method="POST" action="<?= url('/journals/'.$j['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus jurnal ini?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($journals)): ?><tr><td colspan="7"><div class="empty-state py-10"><i data-lucide="book-open" class="empty-icon"></i><p class="empty-title">Belum ada jurnal mengajar</p></div></td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
