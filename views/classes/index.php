<?php $title = 'Data Kelas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex items-center justify-between">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Daftar Kelas</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($classes) ?> kelas aktif</p></div>
    <?php if (Auth::is('admin')): ?><a href="<?= url('/classes/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Buat Kelas</a><?php endif; ?>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $i => $c): ?>
    <div class="card card-hover group relative overflow-hidden" style="animation-delay:<?= $i*60 ?>ms">
      <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
      <div class="relative">
        <div class="flex items-start justify-between mb-4">
          <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
            <span class="font-display font-bold text-emerald-700 dark:text-emerald-400 text-2xl"><?= $c['tingkat'] ?></span>
          </div>
          <?php if (Auth::is('admin')): ?>
          <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="<?= url('/classes/'.$c['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
            <form method="POST" action="<?= url('/classes/'.$c['id'].'/delete') ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus kelas <?= e($c['nama_kelas']) ?>?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
            </form>
          </div>
          <?php endif; ?>
        </div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-xl"><?= e($c['nama_kelas']) ?></h3>
        <p class="text-slate-400 dark:text-dark-text text-sm mt-1 flex items-center gap-1.5">
          <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
          <?= e($c['wali_kelas_name']??'Belum ada wali kelas') ?>
        </p>
        <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100 dark:border-dark-border">
          <div class="flex items-center gap-1.5 text-slate-500 dark:text-dark-text text-sm">
            <i data-lucide="users" class="w-4 h-4"></i>
            <span class="font-semibold text-slate-700 dark:text-slate-300"><?= $c['jumlah_siswa'] ?></span> siswa
          </div>
          <a href="<?= url('/classes/'.$c['id']) ?>" class="btn btn-secondary btn-sm">Detail</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($classes)): ?>
    <div class="col-span-3"><div class="empty-state py-16"><i data-lucide="building-2" class="empty-icon"></i><p class="empty-title">Belum ada kelas</p></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
