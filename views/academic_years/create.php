<?php $title = 'Tambah Tahun Ajaran'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-lg mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/academic-years') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tambah Periode Baru</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/academic-years') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Tahun Ajaran *</label>
        <input type="text" name="tahun_ajaran" class="form-input" required placeholder="cth: 2025/2026">
        <p class="form-hint">Format: YYYY/YYYY</p>
      </div>
      <div class="form-group"><label class="form-label">Semester *</label>
        <div class="flex gap-3">
          <label class="flex items-center gap-2.5 p-3 flex-1 bg-slate-50 dark:bg-dark-card rounded-xl cursor-pointer hover:bg-brand-50 dark:hover:bg-brand-900/15 border border-transparent hover:border-brand-100 transition-all">
            <input type="radio" name="semester" value="1" class="accent-brand-600" checked>
            <div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm">Semester 1</p><p class="text-xs text-slate-400 dark:text-dark-text">Jul — Des</p></div>
          </label>
          <label class="flex items-center gap-2.5 p-3 flex-1 bg-slate-50 dark:bg-dark-card rounded-xl cursor-pointer hover:bg-brand-50 dark:hover:bg-brand-900/15 border border-transparent hover:border-brand-100 transition-all">
            <input type="radio" name="semester" value="2" class="accent-brand-600">
            <div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm">Semester 2</p><p class="text-xs text-slate-400 dark:text-dark-text">Jan — Jun</p></div>
          </label>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Tanggal Mulai *</label><input type="date" name="tanggal_mulai" class="form-input" required></div>
        <div class="form-group"><label class="form-label">Tanggal Selesai *</label><input type="date" name="tanggal_selesai" class="form-input" required></div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/academic-years') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
