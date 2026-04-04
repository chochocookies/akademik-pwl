<?php $title = 'Manajemen User'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Manajemen User</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Kelola semua akun pengguna sistem</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php $sc = [
      ['Total',  $stats['total']??0, 'users',      'text-slate-600 dark:text-slate-400',   'bg-slate-100 dark:bg-dark-muted'],
      ['Admin',  $stats['admin']??0, 'shield',      'text-violet-600 dark:text-violet-400', 'bg-violet-100 dark:bg-violet-900/30'],
      ['Guru',   $stats['guru']??0,  'user-check',  'text-brand-600 dark:text-brand-400',   'bg-brand-100 dark:bg-brand-900/30'],
      ['Murid',  $stats['murid']??0, 'graduation-cap','text-emerald-600 dark:text-emerald-400','bg-emerald-100 dark:bg-emerald-900/30'],
    ];
    foreach ($sc as [$lbl,$val,$icon,$col,$bg]): ?>
    <div class="card-stat">
      <div class="stat-icon <?= $bg ?> mb-4"><i data-lucide="<?= $icon ?>" class="w-5 h-5 <?= $col ?>"></i></div>
      <div class="font-display font-bold text-2xl text-slate-900 dark:text-white"><?= $val ?></div>
      <div class="text-xs text-slate-500 dark:text-dark-text mt-1.5"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Search & filter -->
  <div class="card p-4">
    <div class="flex flex-wrap gap-3">
      <div class="flex-1 min-w-48 relative">
        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
        <input type="text" placeholder="Cari nama atau email..." class="form-input pl-10"
               oninput="document.querySelectorAll('#userTable tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(this.value.toLowerCase())?'':'none'})">
      </div>
      <div class="flex gap-2">
        <?php foreach (['all'=>'Semua','admin'=>'Admin','guru'=>'Guru','murid'=>'Murid'] as $val=>$lbl): ?>
        <button onclick="filterRole('<?= $val ?>')" class="role-filter btn btn-secondary btn-sm" data-role="<?= $val ?>"><?= $lbl ?></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table" id="userTable">
        <thead>
          <tr><th>User</th><th>Role</th><th>Identitas</th><th>Kelas</th><th>Status</th><th class="text-right pr-5">Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u):
          $roleBadge = ['admin'=>'badge-purple','guru'=>'badge-blue','murid'=>'badge-green'][$u['role']] ?? 'badge-slate';
          $roleLabel = ['admin'=>'Admin','guru'=>'Guru','murid'=>'Murid'][$u['role']] ?? $u['role'];
          $isSelf    = $u['id'] == Auth::id();
        ?>
        <tr data-role="<?= $u['role'] ?>">
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-sm <?= ['admin'=>'avatar-violet','guru'=>'avatar-blue','murid'=>'avatar-green'][$u['role']]??'avatar-blue' ?>">
                <?= strtoupper(substr($u['name'],0,1)) ?>
              </div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm">
                  <?= e($u['name']) ?>
                  <?php if ($isSelf): ?><span class="badge badge-blue ml-1 text-2xs">Anda</span><?php endif; ?>
                </p>
                <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($u['email']) ?></p>
              </div>
            </div>
          </td>
          <td><span class="badge <?= $roleBadge ?>"><?= $roleLabel ?></span></td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted px-2 py-1 rounded-lg"><?= e($u['identifier']??'—') ?></span></td>
          <td><span class="text-xs text-slate-500 dark:text-dark-text"><?= e($u['nama_kelas']??'—') ?></span></td>
          <td>
            <?php if ($u['is_active']): ?>
            <span class="badge badge-green flex items-center gap-1 w-fit"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
            <?php else: ?>
            <span class="badge badge-red">Nonaktif</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <?php if (!$isSelf): ?>
              <form method="POST" action="<?= url('/users/'.$u['id'].'/toggle') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn <?= $u['is_active']?'btn-secondary':'btn-success' ?> btn-sm"
                        title="<?= $u['is_active']?'Nonaktifkan':'Aktifkan' ?>">
                  <i data-lucide="<?= $u['is_active']?'eye-off':'eye' ?>" class="w-3.5 h-3.5"></i>
                  <?= $u['is_active']?'Nonaktifkan':'Aktifkan' ?>
                </button>
              </form>
              <form method="POST" action="<?= url('/users/'.$u['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon"
                        data-confirm="Hapus user <?= e($u['name']) ?>? Semua data terkait juga akan terhapus!">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </form>
              <?php else: ?>
              <a href="<?= url('/profile') ?>" class="btn btn-secondary btn-sm">
                <i data-lucide="user" class="w-3.5 h-3.5"></i> Profil
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
function filterRole(role) {
  document.querySelectorAll('.role-filter').forEach(b => {
    const isActive = b.dataset.role === role;
    b.classList.toggle('bg-brand-600', isActive);
    b.classList.toggle('text-white', isActive);
  });
  document.querySelectorAll('#userTable tbody tr').forEach(r => {
    r.style.display = (role === 'all' || r.dataset.role === role) ? '' : 'none';
  });
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
