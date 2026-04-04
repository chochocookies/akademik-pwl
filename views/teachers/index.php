<?php $title = 'Data Guru'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Daftar Guru</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= count($teachers) ?> guru terdaftar</p>
    </div>
    <a href="<?= url('/teachers/create') ?>" class="btn btn-primary"><i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Guru</a>
  </div>
  <div class="card p-4">
    <div class="relative">
      <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
      <input type="text" placeholder="Cari nama atau NIP..." class="form-input pl-10" oninput="document.querySelectorAll('#teacherTable tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(this.value.toLowerCase())?'':'none'})">
    </div>
  </div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table" id="teacherTable">
        <thead><tr><th>#</th><th>Guru</th><th>NIP</th><th>Kelas Diampu</th><th>Status</th><th class="text-right pr-5">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($teachers as $i => $t): ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-md avatar-violet"><?= strtoupper(substr($t['name'],0,1)) ?></div>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($t['name']) ?></p>
                <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($t['email']) ?></p>
              </div>
            </div>
          </td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted text-slate-600 dark:text-slate-400 px-2 py-1 rounded-lg"><?= e($t['nip']??'—') ?></span></td>
          <td><span class="text-xs text-slate-500 dark:text-dark-text"><?= e($t['kelas_diampu']??'—') ?></span></td>
          <td><?= $t['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?></td>
          <td>
            <div class="flex items-center justify-end gap-1.5 pr-3">
              <a href="<?= url('/teachers/'.$t['id']) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
              <a href="<?= url('/teachers/'.$t['id'].'/edit') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
              <form method="POST" action="<?= url('/teachers/'.$t['id'].'/delete') ?>" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" data-confirm="Hapus guru <?= e($t['name']) ?>?"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($teachers)): ?>
        <tr><td colspan="6"><div class="empty-state"><i data-lucide="user-check" class="empty-icon"></i><p class="empty-title">Belum ada guru</p></div></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
