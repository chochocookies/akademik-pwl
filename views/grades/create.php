<?php $title = 'Input Nilai'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-6xl mx-auto">
  <div class="flex items-center gap-3">
    <a href="<?= url('/grades/'.$class['id']) ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Input Nilai — <?= e($class['nama_kelas']) ?></h2></div>
  </div>
  <div class="card p-4 flex items-center gap-3 bg-brand-50 dark:bg-brand-900/20 border-brand-100 dark:border-brand-900/50">
    <div class="w-8 h-8 rounded-xl bg-brand-100 dark:bg-brand-900/40 flex items-center justify-center shrink-0">
      <i data-lucide="info" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
    </div>
    <p class="text-sm text-brand-800 dark:text-brand-300">Formula Nilai Akhir: <strong>30% Harian + 30% UTS + 40% UAS</strong> — dihitung otomatis saat Anda mengisi nilai</p>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/grades') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
      <?php if ($teacher): ?><input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>"><?php endif; ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="form-group">
          <label class="form-label">Mata Pelajaran *</label>
          <select name="subject_id" class="form-input" required>
            <option value="">— Pilih Mata Pelajaran —</option>
            <?php foreach ($subjects as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['nama_mapel']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Semester</label>
          <select name="semester" class="form-input">
            <option value="1" <?= SEMESTER=='1'?'selected':'' ?>>Semester 1</option>
            <option value="2" <?= SEMESTER=='2'?'selected':'' ?>>Semester 2</option>
          </select>
        </div>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Siswa</th>
              <th class="text-center w-32">Harian <span class="font-normal text-slate-400">(0–100)</span></th>
              <th class="text-center w-32">UTS <span class="font-normal text-slate-400">(0–100)</span></th>
              <th class="text-center w-32">UAS <span class="font-normal text-slate-400">(0–100)</span></th>
              <th class="text-center w-28">Nilai Akhir</th>
              <th class="w-40">Catatan</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($students as $s): ?>
          <tr>
            <td>
              <input type="hidden" name="student_ids[]" value="<?= $s['id'] ?>">
              <div class="flex items-center gap-2.5">
                <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
                <div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['nis']) ?></p></div>
              </div>
            </td>
            <td class="text-center"><input type="number" name="nilai_harian[<?= $s['id'] ?>]" min="0" max="100" step="0.5" value="0" class="form-input w-24 text-center font-mono grade-input" data-sid="<?= $s['id'] ?>" data-type="h"></td>
            <td class="text-center"><input type="number" name="nilai_uts[<?= $s['id'] ?>]" min="0" max="100" step="0.5" value="0" class="form-input w-24 text-center font-mono grade-input" data-sid="<?= $s['id'] ?>" data-type="u"></td>
            <td class="text-center"><input type="number" name="nilai_uas[<?= $s['id'] ?>]" min="0" max="100" step="0.5" value="0" class="form-input w-24 text-center font-mono grade-input" data-sid="<?= $s['id'] ?>" data-type="a"></td>
            <td class="text-center">
              <span id="final-<?= $s['id'] ?>" class="font-display font-bold text-xl text-slate-400 dark:text-dark-text">0.0</span>
            </td>
            <td><input type="text" name="catatan[<?= $s['id'] ?>]" class="form-input text-xs" placeholder="Opsional"></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="flex gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Semua Nilai</button>
        <a href="<?= url('/grades/'.$class['id']) ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<script>
const colorClass = v => v>=90?'text-emerald-600 dark:text-emerald-400':v>=80?'text-brand-600 dark:text-brand-400':v>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400';
document.querySelectorAll('.grade-input').forEach(inp => {
  inp.addEventListener('input', function() {
    const sid = this.dataset.sid;
    const h = parseFloat(document.querySelector(`[data-sid="${sid}"][data-type="h"]`).value)||0;
    const u = parseFloat(document.querySelector(`[data-sid="${sid}"][data-type="u"]`).value)||0;
    const a = parseFloat(document.querySelector(`[data-sid="${sid}"][data-type="a"]`).value)||0;
    const f = (h*0.3+u*0.3+a*0.4).toFixed(1);
    const el = document.getElementById(`final-${sid}`);
    el.textContent = f;
    el.className = `font-display font-bold text-xl ${colorClass(parseFloat(f))}`;
  });
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
