<?php $title = 'Buat Topik Diskusi'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/discussions') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Buat Topik Diskusi</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/discussions') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Kelas *</label>
        <select name="class_id" class="form-input" required>
          <option value="">— Pilih Kelas —</option>
          <?php foreach ($classes as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['nama_kelas']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Judul Topik *</label><input type="text" name="judul" class="form-input" required placeholder="Judul topik diskusi"></div>
      <div class="form-group"><label class="form-label">Isi / Pertanyaan *</label><textarea name="konten" class="form-input" rows="5" required placeholder="Tuliskan pertanyaan atau topik yang ingin didiskusikan..."></textarea></div>
      <div class="form-group">
        <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-card rounded-xl cursor-pointer hover:bg-amber-50 dark:hover:bg-amber-900/15 transition-colors">
          <input type="checkbox" name="is_pinned" value="1" class="rounded accent-brand-600">
          <div><p class="text-sm font-semibold text-slate-700 dark:text-slate-300">📌 Sematkan topik ini</p><p class="text-xs text-slate-400 dark:text-dark-text">Topik akan muncul paling atas</p></div>
        </label>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="send" class="w-4 h-4"></i> Buat Topik</button>
        <a href="<?= url('/discussions') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
