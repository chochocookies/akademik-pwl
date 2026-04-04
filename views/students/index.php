<?php $title = 'Data Siswa'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-5 max-w-7xl mx-auto">
  <!-- Header -->
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Daftar Siswa</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($students) ?> siswa terdaftar</p>
    </div>
    <?php if (Auth::is('admin')): ?>
    <a href="<?= url('/students/create') ?>" class="btn btn-primary">
      <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Siswa
    </a>
    <?php endif; ?>
  </div>

  <!-- Search & filter bar -->
  <div class="card p-4">
    <div class="relative">
      <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
      <input id="studentSearch" type="text" placeholder="Cari nama, NIS, kelas, email..."
             class="form-input pl-10" oninput="tableSearch2(this.value)">
    </div>
  </div>

  <!-- Table card -->
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table" id="studentTable">
        <thead>
          <tr>
            <th class="w-8">#</th>
            <th>Siswa</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Gender</th>
            <th>Orang Tua</th>
            <th>Status</th>
            <th class="text-right pr-5">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $i => $s): ?>
        <tr>
          <td class="text-slate-400 dark:text-dark-text text-xs pl-4"><?= $i+1 ?></td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-md avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p>
                <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['email']) ?></p>
              </div>
            </div>
          </td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted text-slate-600 dark:text-slate-400 px-2 py-1 rounded-lg"><?= e($s['nis']) ?></span></td>
          <td><?= $s['nama_kelas'] ? '<span class="badge badge-blue">'.e($s['nama_kelas']).'</span>' : '<span class="text-slate-300 dark:text-dark-muted text-xs">—</span>' ?></td>
          <td><?= $s['gender']==='L' ? '<span class="badge badge-blue">Laki-laki</span>' : '<span class="badge badge-purple">Perempuan</span>' ?></td>
          <td class="text-slate-500 dark:text-dark-text text-sm"><?= e($s['parent_name'] ?? '—') ?></td>
          <td><?= $s['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?></td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <a href="<?= url('/students/'.$s['id']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
              </a>
              <?php if (Auth::is('admin')): ?>
              <a href="<?= url('/students/'.$s['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
              </a>
              <form method="POST" action="<?= url('/students/'.$s['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus"
                        data-confirm="Hapus siswa <?= e($s['name']) ?>? Data tidak bisa dikembalikan.">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?>
        <tr><td colspan="8">
          <div class="empty-state">
            <i data-lucide="users" class="empty-icon"></i>
            <p class="empty-title">Belum ada data siswa</p>
            <p class="empty-desc">Tambahkan siswa baru untuk memulai</p>
            <?php if (Auth::is('admin')): ?>
            <a href="<?= url('/students/create') ?>" class="btn btn-primary mt-4">Tambah Siswa Pertama</a>
            <?php endif; ?>
          </div>
        </td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
function tableSearch2(q) {
  q = q.toLowerCase();
  document.querySelectorAll('#studentTable tbody tr').forEach(r => {
    r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
