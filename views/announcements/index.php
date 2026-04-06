<?php $title = 'Pengumuman'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-4xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Pengumuman Sekolah</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($announcements) ?> pengumuman aktif</p></div>
    <?php if ($canCreate): ?><a href="<?= url('/announcements/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Buat Pengumuman</a><?php endif; ?>
  </div>
  <div class="space-y-4">
    <?php foreach ($announcements as $a):
      $isExpired = $a['expired_at'] && strtotime($a['expired_at']) < time();
      $targetBadge = ['all'=>'badge-blue','guru'=>'badge-violet','murid'=>'badge-green'][$a['target_role']]??'badge-slate';
      $targetLabel = ['all'=>'Semua','guru'=>'Guru','murid'=>'Murid'][$a['target_role']]??$a['target_role'];
    ?>
    <div class="card <?= $a['is_pinned'] ? 'border-amber-200 dark:border-amber-900/50' : '' ?> hover:shadow-card-md transition-all">
      <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-3 flex-1 min-w-0">
          <?php if ($a['is_pinned']): ?>
          <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="pin" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
          </div>
          <?php else: ?>
          <div class="w-8 h-8 rounded-xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="megaphone" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
          </div>
          <?php endif; ?>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap gap-2 mb-1.5">
              <?php if ($a['is_pinned']): ?><span class="badge badge-amber text-2xs">📌 Disematkan</span><?php endif; ?>
              <span class="badge <?= $targetBadge ?> text-2xs"><?= $targetLabel ?></span>
              <?php if ($isExpired): ?><span class="badge badge-red text-2xs">Kedaluwarsa</span><?php endif; ?>
            </div>
            <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg leading-tight">
              <a href="<?= url('/announcements/'.$a['id']) ?>" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors"><?= e($a['judul']) ?></a>
            </h3>
            <p class="text-sm text-slate-500 dark:text-dark-text mt-1.5 line-clamp-2 leading-relaxed"><?= e(strip_tags($a['konten'])) ?></p>
            <div class="flex items-center gap-3 mt-2 text-xs text-slate-400 dark:text-dark-text">
              <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i><?= e($a['author_name']) ?></span>
              <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i><?= timeAgo($a['published_at']) ?></span>
            </div>
          </div>
        </div>
        <?php if ($canCreate && (Auth::is('admin') || $a['user_id']==Auth::id())): ?>
        <div class="flex gap-1 shrink-0">
          <a href="<?= url('/announcements/'.$a['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
          <form method="POST" action="<?= url('/announcements/'.$a['id'].'/delete') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus pengumuman ini?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($announcements)): ?>
    <div class="card"><div class="empty-state py-16"><i data-lucide="megaphone" class="empty-icon"></i><p class="empty-title">Belum ada pengumuman</p><p class="empty-desc">Pengumuman dari sekolah akan muncul di sini</p></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
