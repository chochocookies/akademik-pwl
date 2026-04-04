<?php $title = 'Tambah Guru'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/teachers') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tambah Guru Baru</h2></div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/teachers') ?>">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="form-group md:col-span-2">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="name" value="<?= old('name') ?>" class="form-input" required placeholder="Nama lengkap guru">
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" value="<?= old('email') ?>" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password *</label>
          <input type="password" name="password" class="form-input" required placeholder="Min. 6 karakter">
        </div>
        <div class="form-group">
          <label class="form-label">NIP</label>
          <input type="text" name="nip" value="<?= old('nip') ?>" class="form-input" placeholder="Nomor Induk Pegawai">
        </div>
        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="phone" value="<?= old('phone') ?>" class="form-input">
        </div>
        <div class="form-group md:col-span-2">
          <label class="form-label">Alamat</label>
          <textarea name="address" class="form-input" rows="2"><?= old('address') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Mata Pelajaran Diampu</label>
          <div class="space-y-2 max-h-40 overflow-y-auto p-3 bg-slate-50 dark:bg-dark-card rounded-xl border border-slate-200 dark:border-dark-border">
            <?php foreach ($subjects as $s): ?>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="subject_ids[]" value="<?= $s['id'] ?>" class="rounded accent-brand-600">
              <span class="text-sm text-slate-700 dark:text-slate-300"><?= e($s['nama_mapel']) ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Kelas Diampu</label>
          <div class="space-y-2 max-h-40 overflow-y-auto p-3 bg-slate-50 dark:bg-dark-card rounded-xl border border-slate-200 dark:border-dark-border">
            <?php foreach ($classes as $c): ?>
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="class_ids[]" value="<?= $c['id'] ?>" class="rounded accent-brand-600">
              <span class="text-sm text-slate-700 dark:text-slate-300"><?= e($c['nama_kelas']) ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Guru</button>
        <a href="<?= url('/teachers') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
