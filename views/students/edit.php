<?php $title = 'Edit Siswa'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/students') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Data Siswa</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= e($student['name']) ?></p>
    </div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/students/'.$student['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group md:col-span-2">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="name" value="<?= e($student['name']) ?>" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" value="<?= e($student['email']) ?>" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">NIS *</label>
          <input type="text" name="nis" value="<?= e($student['nis']) ?>" class="form-input" required>
        </div>
        <div class="form-group">
          <label class="form-label">Jenis Kelamin *</label>
          <select name="gender" class="form-input" required>
            <option value="L" <?= $student['gender']==='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= $student['gender']==='P'?'selected':'' ?>>Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kelas</label>
          <select name="class_id" class="form-input">
            <option value="">— Tanpa Kelas —</option>
            <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $student['class_id']==$c['id']?'selected':'' ?>><?= e($c['nama_kelas']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="birth_date" value="<?= e($student['birth_date']??'') ?>" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Nama Orang Tua</label>
          <input type="text" name="parent_name" value="<?= e($student['parent_name']??'') ?>" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Telepon</label>
          <input type="text" name="phone" value="<?= e($student['phone']??'') ?>" class="form-input">
        </div>
        <div class="form-group md:col-span-2">
          <label class="form-label">Alamat</label>
          <textarea name="address" class="form-input" rows="2"><?= e($student['address']??'') ?></textarea>
        </div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/students') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
