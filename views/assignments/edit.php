<?php $title = 'Edit Tugas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/assignments') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Tugas</h2></div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/assignments/'.$assignment['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Judul Tugas *</label><input type="text" name="judul" value="<?= e($assignment['judul']) ?>" class="form-input" required></div>
      <div class="p-3 bg-slate-50 dark:bg-dark-card rounded-xl mb-4 text-sm text-slate-600 dark:text-slate-400">
        <strong class="text-slate-700 dark:text-slate-300"><?= e($assignment['nama_kelas']) ?></strong> · <?= e($assignment['nama_mapel']) ?>
        <span class="text-xs text-slate-400 dark:text-dark-text ml-1">(tidak dapat diubah)</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Deadline *</label><input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i',strtotime($assignment['deadline'])) ?>" class="form-input" required></div>
        <div class="form-group"><label class="form-label">Nilai Maksimal</label><input type="number" name="max_nilai" value="<?= e($assignment['max_nilai']) ?>" min="1" max="100" class="form-input"></div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-input" rows="4"><?= e($assignment['deskripsi']??'') ?></textarea></div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/assignments') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
