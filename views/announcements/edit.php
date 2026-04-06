<?php $title = 'Edit Pengumuman'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/announcements') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Pengumuman</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/announcements/'.$announcement['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Judul *</label><input type="text" name="judul" value="<?= e($announcement['judul']) ?>" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Isi Pengumuman *</label><textarea name="konten" class="form-input" rows="6" required><?= e($announcement['konten']) ?></textarea></div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Target</label>
          <select name="target_role" class="form-input">
            <option value="all" <?= $announcement['target_role']==='all'?'selected':'' ?>>Semua</option>
            <option value="guru" <?= $announcement['target_role']==='guru'?'selected':'' ?>>Hanya Guru</option>
            <option value="murid" <?= $announcement['target_role']==='murid'?'selected':'' ?>>Hanya Murid</option>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Kedaluwarsa</label><input type="datetime-local" name="expired_at" value="<?= $announcement['expired_at'] ? date('Y-m-d\TH:i', strtotime($announcement['expired_at'])) : '' ?>" class="form-input"></div>
      </div>
      <div class="form-group">
        <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-card rounded-xl cursor-pointer">
          <input type="checkbox" name="is_pinned" value="1" <?= $announcement['is_pinned']?'checked':'' ?> class="rounded accent-brand-600">
          <span class="text-sm font-medium text-slate-700 dark:text-slate-300">📌 Sematkan pengumuman</span>
        </label>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/announcements') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
