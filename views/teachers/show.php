<?php $title = 'Detail Guru'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/teachers') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Guru</h2>
  </div>
  <div class="card">
    <div class="flex flex-col sm:flex-row gap-5">
      <div class="avatar avatar-xl avatar-violet self-start"><?= strtoupper(substr($teacher['name'],0,1)) ?></div>
      <div class="flex-1">
        <div class="flex justify-between items-start gap-4 flex-wrap">
          <div>
            <h3 class="font-display font-bold text-slate-900 dark:text-white text-2xl"><?= e($teacher['name']) ?></h3>
            <p class="text-slate-400 dark:text-dark-text text-sm mt-0.5"><?= e($teacher['email']) ?></p>
          </div>
          <a href="<?= url('/teachers/'.$teacher['id'].'/edit') ?>" class="btn btn-secondary btn-sm"><i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit</a>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-slate-100 dark:border-dark-border">
          <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">NIP</p><p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($teacher['nip']??'—') ?></p></div>
          <div><p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Telepon</p><p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($teacher['phone']??'—') ?></p></div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Kelas yang Diajar</h3>
    <?php if (empty($classes)): ?>
    <div class="empty-state py-8"><i data-lucide="building-2" class="empty-icon"></i><p class="empty-title">Belum ada kelas yang diampu</p></div>
    <?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
      <?php foreach ($classes as $c): ?>
      <a href="<?= url('/classes/'.$c['id']) ?>" class="group p-4 bg-slate-50 dark:bg-dark-card rounded-2xl hover:bg-brand-50 dark:hover:bg-brand-900/20 border border-transparent hover:border-brand-100 dark:hover:border-brand-900/50 transition-all text-center">
        <div class="font-display font-bold text-brand-600 dark:text-brand-400 text-2xl"><?= $c['tingkat'] ?></div>
        <div class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($c['nama_kelas']) ?></div>
        <div class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= $c['jumlah_siswa'] ?> siswa</div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
