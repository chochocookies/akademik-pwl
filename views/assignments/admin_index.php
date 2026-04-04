<?php $title = 'Semua Tugas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Semua Tugas</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($assignments) ?> tugas di semua kelas</p></div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Judul</th><th>Guru</th><th>Kelas</th><th>Mapel</th><th>Deadline</th><th class="text-center">Submit</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($assignments as $a): $p=isDeadlinePassed($a['deadline']); ?>
        <tr>
          <td class="font-semibold text-slate-800 dark:text-slate-100"><?= e($a['judul']) ?></td>
          <td class="text-sm text-slate-500 dark:text-dark-text"><?= e($a['guru_name']) ?></td>
          <td><span class="badge badge-blue"><?= e($a['nama_kelas']) ?></span></td>
          <td class="text-xs text-slate-500 dark:text-dark-text"><?= e($a['nama_mapel']) ?></td>
          <td class="text-xs text-slate-500 dark:text-dark-text"><?= formatDate($a['deadline'],'d M Y H:i') ?></td>
          <td class="text-center font-bold text-slate-700 dark:text-slate-300"><?= $a['total_submissions'] ?></td>
          <td><span class="badge <?= $p?'badge-red':'badge-green' ?>"><?= $p?'Lewat':'Aktif' ?></span></td>
          <td><a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($assignments)): ?><tr><td colspan="8"><div class="empty-state"><i data-lucide="clipboard-list" class="empty-icon"></i><p class="empty-title">Belum ada tugas</p></div></td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
