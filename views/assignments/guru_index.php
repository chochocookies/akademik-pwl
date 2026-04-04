<?php $title = 'Tugas Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tugas Saya</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($assignments) ?> tugas dibuat</p></div>
    <a href="<?= url('/assignments/create') ?>" class="btn btn-primary"><i data-lucide="plus" class="w-4 h-4"></i> Buat Tugas</a>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($assignments as $i => $a): $isPast=isDeadlinePassed($a['deadline']); $daysLeft=ceil((strtotime($a['deadline'])-time())/86400); ?>
    <div class="card card-hover group" style="animation-delay:<?= $i*60 ?>ms">
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex-1 min-w-0">
          <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg leading-tight"><?= e($a['judul']) ?></h3>
          <div class="flex flex-wrap gap-2 mt-2">
            <span class="badge badge-blue"><?= e($a['nama_kelas']) ?></span>
            <span class="badge badge-slate"><?= e($a['nama_mapel']) ?></span>
          </div>
        </div>
        <span class="badge <?= $isPast?'badge-red':'badge-green' ?> shrink-0"><?= $isPast?'Lewat':'Aktif' ?></span>
      </div>
      <?php if ($a['deskripsi']): ?><p class="text-sm text-slate-500 dark:text-dark-text line-clamp-2 mb-3"><?= e($a['deskripsi']) ?></p><?php endif; ?>
      <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 dark:text-dark-text mb-4">
        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i><?= formatDate($a['deadline'],'d M Y H:i') ?></span>
        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i><?= $a['total_submissions'] ?> submit</span>
        <?php if (!$isPast): ?><span class="flex items-center gap-1 text-amber-500"><i data-lucide="timer" class="w-3.5 h-3.5"></i><?= $daysLeft ?> hari lagi</span><?php endif; ?>
      </div>
      <div class="flex gap-2 pt-3 border-t border-slate-100 dark:border-dark-border">
        <a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-secondary btn-sm flex-1 justify-center"><i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail</a>
        <a href="<?= url('/assignments/'.$a['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
        <form method="POST" action="<?= url('/assignments/'.$a['id'].'/delete') ?>" class="inline">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus tugas ini?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($assignments)): ?>
    <div class="col-span-2"><div class="empty-state py-16"><i data-lucide="clipboard-list" class="empty-icon"></i><p class="empty-title">Belum ada tugas</p><p class="empty-desc">Buat tugas pertama untuk siswa</p><a href="<?= url('/assignments/create') ?>" class="btn btn-primary mt-4">Buat Tugas Pertama</a></div></div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
