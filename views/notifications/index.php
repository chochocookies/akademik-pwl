<?php $title = 'Notifikasi'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center justify-between">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Notifikasi</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($notifications) ?> notifikasi</p></div>
    <?php if (!empty($notifications)): ?>
    <form method="POST" action="<?= url('/notifications/read-all') ?>">
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-secondary btn-sm"><i data-lucide="check-check" class="w-3.5 h-3.5"></i> Tandai Semua Dibaca</button>
    </form>
    <?php endif; ?>
  </div>

  <?php if (empty($notifications)): ?>
  <div class="card"><div class="empty-state py-16"><i data-lucide="bell" class="empty-icon"></i><p class="empty-title">Tidak ada notifikasi</p><p class="empty-desc">Kamu sudah membaca semua notifikasi</p></div></div>
  <?php else: ?>
  <div class="space-y-2">
    <?php foreach ($notifications as $n):
      $icons = ['tugas'=>'clipboard-list','nilai'=>'bar-chart-3','absensi'=>'calendar-check','pengumuman'=>'megaphone','lainnya'=>'bell'];
      $colors = ['tugas'=>'avatar-blue','nilai'=>'avatar-green','absensi'=>'avatar-amber','pengumuman'=>'avatar-violet','lainnya'=>'bg-slate-100 dark:bg-dark-muted text-slate-600 dark:text-slate-400'];
    ?>
    <div class="card <?= !$n['is_read'] ? 'border-brand-200 dark:border-brand-900/50 bg-brand-50/30 dark:bg-brand-900/10' : '' ?> p-4">
      <div class="flex items-start gap-3">
        <div class="avatar avatar-sm <?= $colors[$n['tipe']]??$colors['lainnya'] ?> shrink-0 mt-0.5">
          <i data-lucide="<?= $icons[$n['tipe']]??'bell' ?>" class="w-4 h-4"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm leading-tight"><?= e($n['judul']) ?></p>
            <?php if (!$n['is_read']): ?><span class="w-2 h-2 rounded-full bg-brand-500 shrink-0 mt-1"></span><?php endif; ?>
          </div>
          <?php if ($n['pesan']): ?><p class="text-xs text-slate-500 dark:text-dark-text mt-1 leading-relaxed"><?= e($n['pesan']) ?></p><?php endif; ?>
          <p class="text-2xs text-slate-400 dark:text-dark-text mt-1.5"><?= timeAgo($n['created_at']) ?></p>
          <?php if ($n['url'] && $n['url'] !== url('')): ?>
          <a href="<?= e($n['url']) ?>" class="text-xs text-brand-600 dark:text-brand-400 font-semibold hover:underline mt-1 inline-block">Lihat selengkapnya →</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
