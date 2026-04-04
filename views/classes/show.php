<?php $title = 'Detail Kelas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/classes') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Kelas</h2>
  </div>
  <div class="hero-murid rounded-3xl p-6 text-white relative overflow-hidden">
    <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden md:block">
      <div class="w-24 h-24 bg-white/10 rounded-3xl rotate-12"></div>
    </div>
    <div class="relative flex items-center justify-between">
      <div>
        <p class="text-teal-200 text-sm font-medium mb-1">Tahun Ajaran <?= TAHUN_AJARAN ?></p>
        <h3 class="font-display font-bold text-3xl"><?= e($class['nama_kelas']) ?></h3>
        <p class="text-teal-200 text-sm mt-2 flex items-center gap-2">
          <i data-lucide="user-check" class="w-3.5 h-3.5"></i> <?= e($class['wali_kelas_name']??'Belum ada wali kelas') ?>
        </p>
      </div>
      <div class="text-right hidden sm:block">
        <div class="font-display font-bold text-5xl"><?= count($students) ?></div>
        <div class="text-teal-200 text-sm mt-1">Siswa</div>
      </div>
    </div>
  </div>
  <?php if (Auth::is('guru','admin')): ?>
  <div class="flex flex-wrap gap-3">
    <a href="<?= url('/grades/'.$class['id'].'/input') ?>" class="btn btn-primary"><i data-lucide="star" class="w-4 h-4"></i> Input Nilai</a>
    <a href="<?= url('/grades/'.$class['id']) ?>" class="btn btn-secondary"><i data-lucide="bar-chart-3" class="w-4 h-4"></i> Lihat Nilai</a>
    <?php if (Auth::is('guru')): ?><a href="<?= url('/assignments/create?class_id='.$class['id']) ?>" class="btn btn-secondary"><i data-lucide="file-plus" class="w-4 h-4"></i> Buat Tugas</a><?php endif; ?>
  </div>
  <?php endif; ?>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Daftar Siswa (<?= count($students) ?>)</h3>
    <?php if (empty($students)): ?>
    <div class="empty-state py-8"><i data-lucide="users" class="empty-icon"></i><p class="empty-title">Belum ada siswa di kelas ini</p></div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Siswa</th><th>NIS</th><th>Gender</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($students as $i => $s): ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td><div class="flex items-center gap-3"><div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div><div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['email']) ?></p></div></div></td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted px-2 py-1 rounded-lg"><?= e($s['nis']) ?></span></td>
          <td><?= $s['gender']==='L'?'<span class="badge badge-blue">L</span>':'<span class="badge badge-purple">P</span>' ?></td>
          <td><a href="<?= url('/students/'.$s['id']) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
  <?php if (!empty($assignments)): ?>
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Tugas Kelas Ini</h3>
    <div class="space-y-2">
      <?php foreach ($assignments as $a): $p=isDeadlinePassed($a['deadline']); ?>
      <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-dark-card rounded-xl">
        <div>
          <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($a['judul']) ?></p>
          <p class="text-xs text-slate-400 dark:text-dark-text mt-0.5"><?= e($a['nama_mapel']) ?> · <?= formatDate($a['deadline'],'d M Y H:i') ?></p>
        </div>
        <div class="flex items-center gap-2">
          <span class="badge <?= $p?'badge-red':'badge-green' ?>"><?= $p?'Lewat':'Aktif' ?></span>
          <a href="<?= url('/assignments/'.$a['id']) ?>" class="btn btn-secondary btn-sm">Detail</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
