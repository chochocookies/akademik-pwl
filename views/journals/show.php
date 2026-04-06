<?php $title = 'Detail Jurnal'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/journals') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Jurnal Mengajar</h2>
  </div>
  <div class="card">
    <div class="flex items-start justify-between gap-4 mb-5 pb-5 border-b border-slate-100 dark:border-dark-border">
      <div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-xl"><?= e($journal['materi_pokok']) ?></h3>
        <div class="flex flex-wrap gap-2 mt-2">
          <span class="badge badge-blue"><?= e($journal['nama_kelas']) ?></span>
          <span class="badge badge-slate"><?= e($journal['nama_mapel']) ?></span>
        </div>
      </div>
      <?php if (Auth::is('guru','admin')): ?><a href="<?= url('/journals/'.$journal['id'].'/edit') ?>" class="btn btn-secondary btn-sm"><i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit</a><?php endif; ?>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-5">
      <?php $infos = [['Tanggal',formatDate($journal['tanggal'],'l, d M Y')],['Guru',$journal['guru_name']],['Metode',$journal['metode']??'—'],['Media',$journal['media']??'—']]; ?>
      <?php foreach ($infos as [$lbl,$val]): ?>
      <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider"><?= $lbl ?></p><p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1"><?= e($val) ?></p></div>
      <?php endforeach; ?>
    </div>
    <?php if ($journal['materi_detail']): ?>
    <div class="mb-4"><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider mb-2">Uraian Materi</p><div class="p-4 bg-slate-50 dark:bg-dark-card rounded-xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed"><?= nl2br(e($journal['materi_detail'])) ?></div></div>
    <?php endif; ?>
    <?php if ($journal['catatan']): ?>
    <div><p class="text-xs font-semibold text-slate-400 dark:text-dark-text uppercase tracking-wider mb-2">Catatan</p><div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-sm text-amber-800 dark:text-amber-300"><?= nl2br(e($journal['catatan'])) ?></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
