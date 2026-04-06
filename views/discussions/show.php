<?php $title = e($discussion['judul']); require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/discussions') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl truncate"><?= e($discussion['judul']) ?></h2>
  </div>
  <!-- Thread -->
  <div class="card">
    <div class="flex flex-wrap gap-2 mb-3">
      <?php if ($discussion['is_pinned']): ?><span class="badge badge-amber">📌 Disematkan</span><?php endif; ?>
      <span class="badge badge-blue"><?= e($discussion['nama_kelas']) ?></span>
    </div>
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-xl mb-3"><?= e($discussion['judul']) ?></h3>
    <div class="p-4 bg-slate-50 dark:bg-dark-card rounded-2xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-4"><?= nl2br(e($discussion['konten'])) ?></div>
    <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-dark-text">
      <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i><?= e($discussion['author_name']) ?></span>
      <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i><?= timeAgo($discussion['created_at']) ?></span>
      <span class="flex items-center gap-1"><i data-lucide="message-circle" class="w-3 h-3"></i><?= count($replies) ?> balasan</span>
    </div>
  </div>
  <!-- Replies -->
  <?php if (!empty($replies)): ?>
  <div class="space-y-3">
    <h4 class="font-display font-bold text-slate-700 dark:text-slate-300 text-sm px-1">Balasan (<?= count($replies) ?>)</h4>
    <?php foreach ($replies as $r):
      $roleColors = ['admin'=>'avatar-violet','guru'=>'avatar-blue','murid'=>'avatar-green'];
      $isMe = $r['user_id'] == Auth::id();
    ?>
    <div class="flex items-start gap-3 <?= $isMe ? 'flex-row-reverse' : '' ?>">
      <div class="avatar avatar-sm <?= $roleColors[$r['author_role']]??'avatar-blue' ?> shrink-0"><?= strtoupper(substr($r['author_name'],0,1)) ?></div>
      <div class="flex-1 <?= $isMe ? 'items-end' : '' ?> flex flex-col">
        <div class="flex items-center gap-2 mb-1 <?= $isMe ? 'flex-row-reverse' : '' ?>">
          <span class="text-xs font-semibold text-slate-700 dark:text-slate-300"><?= e($r['author_name']) ?></span>
          <span class="text-2xs text-slate-400 dark:text-dark-text"><?= timeAgo($r['created_at']) ?></span>
          <?php if (Auth::is('admin','guru')): ?>
          <form method="POST" action="<?= url('/discussions/reply/'.$r['id'].'/delete') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="text-2xs text-red-500 hover:underline" data-confirm="Hapus balasan ini?">Hapus</button>
          </form>
          <?php endif; ?>
        </div>
        <div class="p-3 <?= $isMe ? 'bg-brand-50 dark:bg-brand-900/25 border border-brand-100 dark:border-brand-900/50' : 'bg-slate-50 dark:bg-dark-card' ?> rounded-2xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed max-w-sm">
          <?= nl2br(e($r['konten'])) ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <!-- Reply form -->
  <div class="card">
    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3">Tulis Balasan</h4>
    <form method="POST" action="<?= url('/discussions/'.$discussion['id'].'/reply') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><textarea name="konten" class="form-input" rows="3" required placeholder="Tulis balasan Anda..."></textarea></div>
      <button type="submit" class="btn btn-primary"><i data-lucide="send" class="w-4 h-4"></i> Kirim Balasan</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
