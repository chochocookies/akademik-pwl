<?php $title = 'Tambah Mata Pelajaran'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-lg mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/subjects') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tambah Mata Pelajaran</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/subjects') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label class="form-label">Nama Mata Pelajaran *</label>
        <input type="text" name="nama_mapel" value="<?= old('nama_mapel') ?>" class="form-input" required placeholder="cth: Matematika">
      </div>
      <div class="form-group">
        <label class="form-label">Kode Mapel *</label>
        <input type="text" name="kode_mapel" value="<?= old('kode_mapel') ?>" class="form-input" required placeholder="cth: MTK" maxlength="10" style="text-transform:uppercase">
        <p class="form-hint">Singkatan 2-10 huruf, akan dijadikan huruf kapital otomatis</p>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
        <textarea name="deskripsi" class="form-input" rows="2" placeholder="Deskripsi singkat mata pelajaran"><?= old('deskripsi') ?></textarea>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/subjects') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
