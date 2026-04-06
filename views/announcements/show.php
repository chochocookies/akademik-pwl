<?php $title = e($announcement['judul']); require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/announcements') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Pengumuman</h2>
  </div>
  <div class="card">
    <div class="flex flex-wrap gap-2 mb-4">
      <?php if ($announcement['is_pinned']): ?><span class="badge badge-amber">📌 Disematkan</span><?php endif; ?>
      <span class="badge <?= ['all'=>'badge-blue','guru'=>'badge-violet','murid'=>'badge-green'][$announcement['target_role']]??'badge-slate' ?>"><?= ['all'=>'Semua','guru'=>'Guru','murid'=>'Murid'][$announcement['target_role']] ?></span>
    </div>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-2xl mb-2"><?= e($announcement['judul']) ?></h2>
    <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-dark-text mb-6 pb-4 border-b border-slate-100 dark:border-dark-border">
      <span class="flex items-center gap-1"><i data-lucide="user" class="w-3.5 h-3.5"></i><?= e($announcement['author_name']) ?></span>
      <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i><?= formatDate($announcement['published_at'],'d M Y H:i') ?></span>
    </div>
    <div class="prose prose-slate dark:prose-invert max-w-none text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
      <?= nl2br(e($announcement['konten'])) ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
