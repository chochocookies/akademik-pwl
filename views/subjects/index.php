<?php $title = 'Mata Pelajaran'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-4xl mx-auto">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Mata Pelajaran</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($subjects) ?> mata pelajaran terdaftar</p>
    </div>
    <a href="<?= url('/subjects/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Mapel</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($subjects as $i => $s): ?>
    <div class="card card-hover group" style="animation-delay:<?= $i*50 ?>ms">
      <div class="flex items-start justify-between mb-4">
        <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center">
          <span class="font-mono font-bold text-brand-700 dark:text-brand-400 text-sm"><?= e($s['kode_mapel']) ?></span>
        </div>
        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
          <a href="<?= url('/subjects/'.$s['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
          <form method="POST" action="<?= url('/subjects/'.$s['id'].'/delete') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus mata pelajaran <?= e($s['nama_mapel']) ?>?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
          </form>
        </div>
      </div>
      <h3 class="font-display font-bold text-slate-900 dark:text-white text-base"><?= e($s['nama_mapel']) ?></h3>
      <?php if ($s['deskripsi']): ?>
      <p class="text-xs text-slate-400 dark:text-dark-text mt-1 line-clamp-2"><?= e($s['deskripsi']) ?></p>
      <?php endif; ?>
      <div class="flex gap-3 mt-4 pt-3 border-t border-slate-100 dark:border-dark-border text-xs text-slate-500 dark:text-dark-text">
        <span class="flex items-center gap-1"><i data-lucide="user-check" class="w-3.5 h-3.5"></i><?= $s['total_guru'] ?> guru</span>
        <span class="flex items-center gap-1"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i><?= $s['total_nilai'] ?> nilai</span>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($subjects)): ?>
    <div class="col-span-3"><div class="empty-state py-16"><i data-lucide="book-open" class="empty-icon"></i><p class="empty-title">Belum ada mata pelajaran</p></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
