<?php $title = 'Buat Pengumuman'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/announcements') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Buat Pengumuman</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/announcements') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Judul Pengumuman *</label><input type="text" name="judul" class="form-input" required placeholder="Judul pengumuman yang jelas dan singkat"></div>
      <div class="form-group"><label class="form-label">Isi Pengumuman *</label><textarea name="konten" class="form-input" rows="6" required placeholder="Tulis isi pengumuman di sini..."></textarea></div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group">
          <label class="form-label">Target Penerima</label>
          <select name="target_role" class="form-input">
            <option value="all">Semua (Guru & Murid)</option>
            <option value="guru">Hanya Guru</option>
            <option value="murid">Hanya Murid</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Waktu Tayang</label>
          <input type="datetime-local" name="published_at" class="form-input" value="<?= date('Y-m-d\TH:i') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Kedaluwarsa <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
          <input type="datetime-local" name="expired_at" class="form-input">
          <p class="form-hint">Kosongkan jika tidak ada tanggal kedaluwarsa</p>
        </div>
        <div class="form-group">
          <label class="form-label">Opsi</label>
          <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-card rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-dark-hover transition-colors">
            <input type="checkbox" name="is_pinned" value="1" class="rounded accent-brand-600">
            <div>
              <p class="text-sm font-medium text-slate-700 dark:text-slate-300">📌 Sematkan pengumuman</p>
              <p class="text-xs text-slate-400 dark:text-dark-text">Tampil paling atas dan ditandai khusus</p>
            </div>
          </label>
        </div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="megaphone" class="w-4 h-4"></i> Terbitkan Pengumuman</button>
        <a href="<?= url('/announcements') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
