<?php $title = 'Tambah Siswa'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/students') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tambah Siswa Baru</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Isi form berikut untuk mendaftarkan siswa</p>
    </div>
  </div>

  <div class="card">
    <form method="POST" action="<?= url('/students') ?>">
      <?= csrf_field() ?>

      <!-- Section: Akun -->
      <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-dark-border">
        <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center">
          <i data-lucide="lock" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
        </div>
        <div>
          <p class="font-display font-bold text-slate-800 dark:text-slate-100 text-sm">Informasi Akun</p>
          <p class="text-xs text-slate-400 dark:text-dark-text">Untuk login ke sistem</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="form-group md:col-span-2">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="name" value="<?= old('name') ?>" class="form-input" required placeholder="Nama lengkap sesuai akta lahir">
        </div>
        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" value="<?= old('email') ?>" class="form-input" required placeholder="email@sekolah.sch.id">
        </div>
        <div class="form-group">
          <label class="form-label">Password *</label>
          <input type="password" name="password" class="form-input" required placeholder="Min. 6 karakter">
        </div>
      </div>

      <!-- Section: Data Siswa -->
      <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-dark-border">
        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
          <i data-lucide="user" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div>
          <p class="font-display font-bold text-slate-800 dark:text-slate-100 text-sm">Data Siswa</p>
          <p class="text-xs text-slate-400 dark:text-dark-text">Informasi akademik siswa</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="form-group">
          <label class="form-label">NIS *</label>
          <input type="text" name="nis" value="<?= old('nis') ?>" class="form-input" required placeholder="Nomor Induk Siswa">
        </div>
        <div class="form-group">
          <label class="form-label">Jenis Kelamin *</label>
          <select name="gender" class="form-input" required>
            <option value="">— Pilih —</option>
            <option value="L" <?= old('gender')==='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= old('gender')==='P'?'selected':'' ?>>Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kelas</label>
          <select name="class_id" class="form-input">
            <option value="">— Tanpa Kelas —</option>
            <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= old('class_id')==$c['id']?'selected':'' ?>><?= e($c['nama_kelas']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="birth_date" value="<?= old('birth_date') ?>" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label">Nama Orang Tua / Wali</label>
          <input type="text" name="parent_name" value="<?= old('parent_name') ?>" class="form-input" placeholder="Nama ayah/ibu/wali">
        </div>
        <div class="form-group">
          <label class="form-label">No. Telepon</label>
          <input type="text" name="phone" value="<?= old('phone') ?>" class="form-input" placeholder="08xxxxxxxxxx">
        </div>
        <div class="form-group md:col-span-2">
          <label class="form-label">Alamat</label>
          <textarea name="address" class="form-input" rows="2" placeholder="Alamat lengkap siswa"><?= old('address') ?></textarea>
        </div>
      </div>

      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="save" class="w-4 h-4"></i> Simpan Siswa
        </button>
        <a href="<?= url('/students') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
