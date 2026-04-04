<?php $title = 'Tugas Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-3xl mx-auto">
  <div class="flex items-center justify-between flex-wrap gap-3">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Daftar Tugas</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Kelas <?= e($student['nama_kelas']??'—') ?></p></div>
    <?php if (count($assignments)>0): $pct=round(count($submissions)/count($assignments)*100); ?>
    <div class="flex items-center gap-2.5 px-4 py-2 bg-slate-100 dark:bg-dark-card rounded-xl">
      <div class="w-16 h-1.5 bg-slate-200 dark:bg-dark-muted rounded-full overflow-hidden"><div class="h-full bg-emerald-500 rounded-full" style="width:<?= $pct ?>%"></div></div>
      <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"><?= count($submissions) ?>/<?= count($assignments) ?></span>
    </div>
    <?php endif; ?>
  </div>
  <div class="space-y-3">
    <?php foreach ($assignments as $a):
      $isPast=isDeadlinePassed($a['deadline']);
      $submitted=in_array($a['id'],$submittedIds);
      $daysLeft=ceil((strtotime($a['deadline'])-time())/86400);
    ?>
    <div class="card card-hover group">
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 <?= $submitted?'bg-emerald-100 dark:bg-emerald-900/30':($isPast?'bg-red-100 dark:bg-red-900/30':'bg-amber-100 dark:bg-amber-900/30') ?>">
          <i data-lucide="<?= $submitted?'check-circle':($isPast?'alert-circle':'clock') ?>" class="w-5 h-5 <?= $submitted?'text-emerald-600 dark:text-emerald-400':($isPast?'text-red-500':'text-amber-600 dark:text-amber-400') ?>"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-3 flex-wrap">
            <div>
              <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg leading-tight"><?= e($a['judul']) ?></h3>
              <div class="flex gap-2 mt-1.5"><span class="badge badge-blue"><?= e($a['nama_mapel']) ?></span></div>
            </div>
            <div class="shrink-0">
              <?php if ($submitted): ?><span class="badge badge-green">✓ Selesai</span>
              <?php elseif ($isPast): ?><span class="badge badge-red">Terlambat</span>
              <?php else: ?><span class="badge badge-amber"><?= $daysLeft ?> hari lagi</span><?php endif; ?>
            </div>
          </div>
          <?php if ($a['deskripsi']): ?><p class="text-sm text-slate-500 dark:text-dark-text mt-2 line-clamp-2"><?= e($a['deskripsi']) ?></p><?php endif; ?>
          <div class="flex items-center gap-4 mt-3 text-xs text-slate-400 dark:text-dark-text">
            <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i>Deadline: <?= formatDate($a['deadline'],'d M Y H:i') ?></span>
            <span class="flex items-center gap-1"><i data-lucide="star" class="w-3.5 h-3.5"></i>Maks <?= $a['max_nilai'] ?> poin</span>
          </div>
          <div class="flex gap-2 mt-4">
            <a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-secondary btn-sm"><i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail</a>
            <?php if (!$submitted): ?><a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-primary btn-sm"><i data-lucide="upload" class="w-3.5 h-3.5"></i><?= $isPast?'Kumpul (Terlambat)':'Kumpulkan' ?></a><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($assignments)): ?><div class="card"><div class="empty-state py-16"><i data-lucide="inbox" class="empty-icon"></i><p class="empty-title">Belum ada tugas</p></div></div><?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
