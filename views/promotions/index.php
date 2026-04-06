<?php $title = 'Kenaikan Kelas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-5xl mx-auto">
  <div>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Kenaikan Kelas</h2>
    <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Proses kenaikan kelas siswa di akhir tahun ajaran <?= TAHUN_AJARAN ?></p>
  </div>
  <div class="card p-4 flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-900/50">
    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0"></i>
    <div class="text-sm text-amber-800 dark:text-amber-300">
      <strong>Perhatian:</strong> Proses kenaikan kelas tidak dapat dibatalkan. Siswa kelas 6 yang naik akan otomatis menjadi <strong>Alumni</strong> dan akunnya dinonaktifkan. Pastikan semua nilai sudah final sebelum memproses.
    </div>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $c): ?>
    <a href="<?= url('/promotions/'.$c['id']) ?>" class="card card-hover group relative overflow-hidden">
      <div class="absolute top-0 right-0 w-24 h-24 <?= (int)$c['tingkat']===6 ? 'bg-amber-50 dark:bg-amber-900/10' : 'bg-brand-50 dark:bg-brand-900/10' ?> rounded-bl-[3.5rem] -mr-5 -mt-5 group-hover:scale-110 transition-all"></div>
      <div class="relative">
        <div class="flex items-center justify-between mb-3">
          <div class="w-12 h-12 rounded-2xl <?= (int)$c['tingkat']===6 ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' : 'bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-400' ?> flex items-center justify-center font-display font-bold text-xl">
            <?= $c['tingkat'] ?>
          </div>
          <?php if ((int)$c['tingkat']===6): ?>
          <span class="badge badge-amber text-2xs">🎓 Lulus</span>
          <?php else: ?>
          <span class="badge badge-blue text-2xs">→ Kls <?= (int)$c['tingkat']+1 ?></span>
          <?php endif; ?>
        </div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white"><?= e($c['nama_kelas']) ?></h3>
        <p class="text-sm text-slate-400 dark:text-dark-text mt-1"><?= $c['jumlah_siswa'] ?> siswa</p>
        <div class="mt-3 pt-3 border-t border-slate-100 dark:border-dark-border text-xs text-brand-600 dark:text-brand-400 font-semibold">Proses Kenaikan Kelas →</div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
