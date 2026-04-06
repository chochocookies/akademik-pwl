<?php $title = 'Forum Diskusi'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-4xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Forum Diskusi</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($discussions) ?> topik diskusi</p></div>
    <?php if (Auth::is('admin','guru')): ?>
    <a href="<?= url('/discussions/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Buat Topik</a>
    <?php endif; ?>
  </div>
  <div class="space-y-3">
    <?php foreach ($discussions as $d): ?>
    <div class="card card-hover">
      <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl <?= $d['is_pinned']?'bg-amber-100 dark:bg-amber-900/30':'bg-brand-100 dark:bg-brand-900/30' ?> flex items-center justify-center shrink-0">
          <i data-lucide="<?= $d['is_pinned']?'pin':'message-circle' ?>" class="w-4 h-4 <?= $d['is_pinned']?'text-amber-600 dark:text-amber-400':'text-brand-600 dark:text-brand-400' ?>"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap gap-2 mb-1">
            <?php if ($d['is_pinned']): ?><span class="badge badge-amber text-2xs">📌 Pin</span><?php endif; ?>
            <span class="badge badge-blue text-2xs"><?= e($d['nama_kelas']) ?></span>
          </div>
          <a href="<?= url('/discussions/'.$d['id']) ?>" class="font-display font-bold text-slate-900 dark:text-white text-lg hover:text-brand-600 dark:hover:text-brand-400 transition-colors leading-tight block"><?= e($d['judul']) ?></a>
          <p class="text-sm text-slate-500 dark:text-dark-text mt-1 line-clamp-2"><?= e($d['konten']) ?></p>
          <div class="flex items-center gap-4 mt-2 text-xs text-slate-400 dark:text-dark-text">
            <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i><?= e($d['author_name']) ?></span>
            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i><?= timeAgo($d['created_at']) ?></span>
            <span class="flex items-center gap-1"><i data-lucide="message-circle" class="w-3 h-3"></i><?= $d['reply_count'] ?> balasan</span>
          </div>
        </div>
        <?php if (Auth::is('admin','guru')): ?>
        <form method="POST" action="<?= url('/discussions/'.$d['id'].'/delete') ?>" class="inline">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-danger btn-sm btn-icon shrink-0" data-confirm="Hapus diskusi ini?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
        </form>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($discussions)): ?>
    <div class="card"><div class="empty-state py-16"><i data-lucide="message-circle" class="empty-icon"></i><p class="empty-title">Belum ada diskusi</p><p class="empty-desc">Mulai topik diskusi baru untuk kelas Anda</p></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
