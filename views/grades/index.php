<?php $title = 'Manajemen Nilai'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Input & Monitor Nilai</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Pilih kelas untuk mengelola nilai</p></div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $i => $c): ?>
    <div class="card card-hover group relative overflow-hidden" style="animation-delay:<?= $i*60 ?>ms">
      <div class="absolute top-0 right-0 w-28 h-28 bg-amber-50 dark:bg-amber-900/10 rounded-bl-[3.5rem] -mr-6 -mt-6 group-hover:scale-110 transition-all"></div>
      <div class="relative">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
            <span class="font-display font-bold text-amber-700 dark:text-amber-400 text-xl"><?= $c['tingkat'] ?></span>
          </div>
          <span class="badge badge-blue"><?= $c['jumlah_siswa']??0 ?> siswa</span>
        </div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg"><?= e($c['nama_kelas']) ?></h3>
        <p class="text-slate-400 dark:text-dark-text text-xs mt-1"><?= e($c['wali_kelas_name']??'—') ?></p>
        <div class="flex gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-dark-border">
          <a href="<?= url('/grades/'.$c['id']) ?>" class="btn btn-secondary btn-sm flex-1 justify-center"><i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat</a>
          <?php if (Auth::is('guru','admin')): ?>
          <a href="<?= url('/grades/'.$c['id'].'/input') ?>" class="btn btn-primary btn-sm flex-1 justify-center"><i data-lucide="pencil" class="w-3.5 h-3.5"></i> Input</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($classes)): ?><div class="col-span-3"><div class="empty-state py-16"><i data-lucide="building-2" class="empty-icon"></i><p class="empty-title">Belum ada kelas</p></div></div><?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
