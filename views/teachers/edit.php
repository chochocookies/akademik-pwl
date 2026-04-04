<?php $title = 'Edit Guru'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/teachers') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Data Guru</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($teacher['name']) ?></p></div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/teachers/'.$teacher['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group md:col-span-2"><label class="form-label">Nama Lengkap *</label><input type="text" name="name" value="<?= e($teacher['name']) ?>" class="form-input" required></div>
        <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" value="<?= e($teacher['email']) ?>" class="form-input" required></div>
        <div class="form-group"><label class="form-label">NIP</label><input type="text" name="nip" value="<?= e($teacher['nip']??'') ?>" class="form-input"></div>
        <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" value="<?= e($teacher['phone']??'') ?>" class="form-input"></div>
        <div class="form-group md:col-span-2"><label class="form-label">Alamat</label><textarea name="address" class="form-input" rows="2"><?= e($teacher['address']??'') ?></textarea></div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/teachers') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
