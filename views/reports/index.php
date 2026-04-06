<?php $title = 'Rapor Digital'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Rapor Digital</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Generate & kelola rapor siswa per semester</p></div>
    <select onchange="window.location='?semester='+this.value" class="form-input w-40">
      <option value="1" <?= $semester=='1'?'selected':'' ?>>Semester 1</option>
      <option value="2" <?= $semester=='2'?'selected':'' ?>>Semester 2</option>
    </select>
  </div>
  <div class="card p-4 flex items-start gap-3 bg-brand-50 dark:bg-brand-900/20 border-brand-100 dark:border-brand-900/50">
    <i data-lucide="info" class="w-4 h-4 text-brand-600 dark:text-brand-400 mt-0.5 shrink-0"></i>
    <p class="text-sm text-brand-800 dark:text-brand-300">Tahun Ajaran <strong><?= TAHUN_AJARAN ?></strong> · Semester <strong><?= $semester ?></strong> — Rapor di-generate otomatis dari data nilai yang sudah diinput guru.</p>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $i => $c): ?>
    <a href="<?= url('/reports/'.$c['id'].'?semester='.$semester) ?>" class="card card-hover group relative overflow-hidden" style="animation-delay:<?= $i*60 ?>ms">
      <div class="absolute top-0 right-0 w-28 h-28 bg-brand-50 dark:bg-brand-900/10 rounded-bl-[3.5rem] -mr-6 -mt-6 group-hover:scale-110 transition-all"></div>
      <div class="relative">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/40 flex items-center justify-center">
            <span class="font-display font-bold text-brand-700 dark:text-brand-400 text-xl"><?= $c['tingkat'] ?></span>
          </div>
          <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300 group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
        </div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg"><?= e($c['nama_kelas']) ?></h3>
        <p class="text-slate-400 dark:text-dark-text text-sm mt-1 flex items-center gap-1.5"><i data-lucide="users" class="w-3.5 h-3.5"></i><?= $c['jumlah_siswa'] ?> siswa</p>
        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-dark-border"><span class="text-xs text-brand-600 dark:text-brand-400 font-semibold">Lihat Rapor Kelas →</span></div>
      </div>
    </a>
    <?php endforeach; ?>
    <?php if (empty($classes)): ?><div class="col-span-3"><div class="empty-state py-16"><i data-lucide="file-text" class="empty-icon"></i><p class="empty-title">Belum ada kelas</p></div></div><?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
