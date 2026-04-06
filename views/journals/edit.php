<?php $title = 'Edit Jurnal'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/journals') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Jurnal Mengajar</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/journals/'.$journal['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Tanggal *</label><input type="date" name="tanggal" value="<?= e($journal['tanggal']) ?>" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Materi Pokok *</label><input type="text" name="materi_pokok" value="<?= e($journal['materi_pokok']) ?>" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Uraian Materi</label><textarea name="materi_detail" class="form-input" rows="4"><?= e($journal['materi_detail']??'') ?></textarea></div>
      <div class="grid grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Metode</label><input type="text" name="metode" value="<?= e($journal['metode']??'') ?>" class="form-input"></div>
        <div class="form-group"><label class="form-label">Media</label><input type="text" name="media" value="<?= e($journal['media']??'') ?>" class="form-input"></div>
      </div>
      <div class="form-group"><label class="form-label">Catatan</label><textarea name="catatan" class="form-input" rows="2"><?= e($journal['catatan']??'') ?></textarea></div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/journals') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
