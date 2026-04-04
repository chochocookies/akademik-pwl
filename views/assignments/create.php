<?php $title = 'Buat Tugas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/assignments') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Buat Tugas Baru</h2></div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/assignments') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">
      <div class="form-group">
        <label class="form-label">Judul Tugas *</label>
        <input type="text" name="judul" value="<?= old('judul') ?>" class="form-input" required placeholder="Contoh: Latihan Soal Pecahan Bab 3">
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group">
          <label class="form-label">Kelas *</label>
          <select name="class_id" id="classSelect" class="form-input" required onchange="loadSubjects(this.value)">
            <option value="">— Pilih Kelas —</option>
            <?php foreach ($classes as $c): ?><option value="<?= $c['id'] ?>" <?= old('class_id')==$c['id']?'selected':'' ?>><?= e($c['nama_kelas']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Mata Pelajaran *</label>
          <select name="subject_id" id="subjectSelect" class="form-input" required>
            <option value="">— Pilih kelas dulu —</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Deadline *</label>
          <input type="datetime-local" name="deadline" value="<?= old('deadline') ?>" class="form-input" required min="<?= date('Y-m-d\TH:i') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Nilai Maksimal</label>
          <input type="number" name="max_nilai" value="<?= old('max_nilai',100) ?>" min="1" max="100" class="form-input">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi / Instruksi</label>
        <textarea name="deskripsi" class="form-input" rows="4" placeholder="Jelaskan instruksi tugas secara lengkap..."><?= old('deskripsi') ?></textarea>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Buat Tugas</button>
        <a href="<?= url('/assignments') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<script>
async function loadSubjects(classId) {
  const sel = document.getElementById('subjectSelect');
  sel.innerHTML = '<option value="">Memuat...</option>';
  if (!classId) { sel.innerHTML = '<option value="">— Pilih kelas dulu —</option>'; return; }
  try {
    const r = await fetch(`<?= url('/assignments/subjects') ?>?class_id=${classId}`);
    const data = await r.json();
    sel.innerHTML = '<option value="">— Pilih Mata Pelajaran —</option>' + data.map(s=>`<option value="${s.id}">${s.nama_mapel}</option>`).join('');
  } catch { sel.innerHTML = '<option value="">Gagal memuat</option>'; }
}
const urlClass = new URLSearchParams(location.search).get('class_id');
if (urlClass) { document.getElementById('classSelect').value=urlClass; loadSubjects(urlClass); }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
