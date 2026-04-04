<?php $title = 'Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-6 max-w-7xl mx-auto">

  <!-- Header -->
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Manajemen Absensi</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= $stats['total_sesi'] ?> total sesi · <?= $stats['bulan_ini'] ?> bulan ini</p>
    </div>
    <?php if (Auth::is('guru','admin')): ?>
    <a href="<?= url('/attendance/create') ?>" class="btn btn-primary">
      <i data-lucide="plus" class="w-4 h-4"></i> Buat Sesi Absensi
    </a>
    <?php endif; ?>
  </div>

  <!-- Filter by class -->
  <?php if (!empty($classes)): ?>
  <div class="flex flex-wrap gap-2">
    <button onclick="filterClass('all')" class="class-filter-btn btn btn-secondary btn-sm active-filter" data-class="all">Semua Kelas</button>
    <?php foreach ($classes as $c): ?>
    <button onclick="filterClass('<?= $c['id'] ?>')" class="class-filter-btn btn btn-secondary btn-sm" data-class="<?= $c['id'] ?>">
      <?= e($c['nama_kelas']) ?>
    </button>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Sessions table -->
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table" id="sessionTable">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <?php if (Auth::is('admin')): ?><th>Guru</th><?php endif; ?>
            <th class="text-center">Hadir</th>
            <th class="text-center">Sakit</th>
            <th class="text-center">Izin</th>
            <th class="text-center">Alpha</th>
            <th class="text-right pr-5">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($sessions as $s): ?>
        <tr data-class-id="<?= $s['class_id'] ?>">
          <td>
            <div>
              <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= formatDate($s['tanggal'], 'd M Y') ?></p>
              <p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDate($s['tanggal'], 'l') ?></p>
            </div>
          </td>
          <td><span class="badge badge-blue"><?= e($s['nama_kelas']) ?></span></td>
          <td>
            <div class="flex items-center gap-2">
              <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-dark-muted flex items-center justify-center text-xs font-bold font-mono text-slate-600 dark:text-slate-400"><?= e($s['kode_mapel']) ?></span>
              <span class="text-sm text-slate-700 dark:text-slate-300"><?= e($s['nama_mapel']) ?></span>
            </div>
          </td>
          <?php if (Auth::is('admin')): ?><td class="text-sm text-slate-500 dark:text-dark-text"><?= e($s['guru_name']) ?></td><?php endif; ?>
          <td class="text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm"><?= $s['hadir'] ?: 0 ?></span>
          </td>
          <td class="text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-sm"><?= $s['sakit'] ?: 0 ?></span>
          </td>
          <td class="text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold text-sm"><?= $s['izin'] ?: 0 ?></span>
          </td>
          <td class="text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold text-sm"><?= $s['alpha'] ?: 0 ?></span>
          </td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <a href="<?= url('/attendance/'.$s['id']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
              </a>
              <?php if (Auth::is('guru','admin')): ?>
              <a href="<?= url('/attendance/'.$s['id'].'/fill') ?>" class="btn btn-primary btn-sm btn-icon" title="Edit Absensi">
                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
              </a>
              <form method="POST" action="<?= url('/attendance/'.$s['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus sesi absensi ini?">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($sessions)): ?>
        <tr><td colspan="9">
          <div class="empty-state py-16">
            <i data-lucide="calendar-check" class="empty-icon"></i>
            <p class="empty-title">Belum ada sesi absensi</p>
            <p class="empty-desc">Buat sesi absensi baru untuk mulai mencatat kehadiran</p>
            <?php if (Auth::is('guru')): ?>
            <a href="<?= url('/attendance/create') ?>" class="btn btn-primary mt-4">Buat Sesi Pertama</a>
            <?php endif; ?>
          </div>
        </td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
.active-filter { @apply bg-brand-600 text-white hover:bg-brand-700; }
</style>

<script>
function filterClass(classId) {
  document.querySelectorAll('.class-filter-btn').forEach(b => {
    b.classList.toggle('bg-brand-600', b.dataset.class === classId);
    b.classList.toggle('text-white',   b.dataset.class === classId);
    b.classList.toggle('hover:bg-brand-700', b.dataset.class === classId);
  });
  document.querySelectorAll('#sessionTable tbody tr').forEach(row => {
    row.style.display = (classId === 'all' || row.dataset.classId === classId) ? '' : 'none';
  });
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
