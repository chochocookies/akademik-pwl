<?php $title = 'Edit Kelas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-lg mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/classes') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Edit Kelas</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/classes/'.$class['id'].'/update') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Nama Kelas *</label><input type="text" name="nama_kelas" value="<?= e($class['nama_kelas']) ?>" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Tingkat *</label>
        <select name="tingkat" class="form-input" required>
          <?php for($i=1;$i<=6;$i++): ?><option value="<?= $i ?>" <?= $class['tingkat']==$i?'selected':'' ?>>Kelas <?= $i ?></option><?php endfor; ?>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Wali Kelas</label>
        <select name="wali_kelas_id" class="form-input">
          <option value="">— Pilih —</option>
          <?php foreach ($teachers as $t): ?><option value="<?= $t['id'] ?>" <?= $class['wali_kelas_id']==$t['id']?'selected':'' ?>><?= e($t['name']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <a href="<?= url('/classes') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
