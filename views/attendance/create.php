<?php $title = 'Buat Sesi Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Buat Sesi Absensi</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Isi detail sesi, semua siswa akan otomatis ditandai hadir</p>
    </div>
  </div>

  <div class="card">
    <form method="POST" action="<?= url('/attendance') ?>">
      <?= csrf_field() ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group">
          <label class="form-label">Kelas *</label>
          <select name="class_id" id="classSelect" class="form-input" required onchange="loadSubjects(this.value)">
            <option value="">— Pilih Kelas —</option>
            <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= e($c['nama_kelas']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Mata Pelajaran *</label>
          <select name="subject_id" id="subjectSelect" class="form-input" required>
            <option value="">— Pilih kelas dulu —</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal *</label>
          <input type="date" name="tanggal" class="form-input" required value="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Keterangan <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
          <input type="text" name="keterangan" class="form-input" placeholder="cth: Pertemuan ke-5">
        </div>
      </div>

      <!-- Info box -->
      <div class="flex items-start gap-3 p-4 bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-900/50 rounded-2xl mb-4">
        <i data-lucide="info" class="w-4 h-4 text-brand-600 dark:text-brand-400 mt-0.5 shrink-0"></i>
        <div>
          <p class="text-sm font-semibold text-brand-800 dark:text-brand-300">Semua siswa otomatis ditandai Hadir</p>
          <p class="text-xs text-brand-600 dark:text-brand-500 mt-0.5">Setelah sesi dibuat, kamu bisa mengubah status siswa yang tidak hadir di halaman pengisian absensi.</p>
        </div>
      </div>

      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="calendar-plus" class="w-4 h-4"></i> Buat Sesi & Isi Absensi
        </button>
        <a href="<?= url('/attendance') ?>" class="btn btn-secondary">Batal</a>
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
    sel.innerHTML = '<option value="">— Pilih Mata Pelajaran —</option>' +
      data.map(s => `<option value="${s.id}">${s.nama_mapel}</option>`).join('');
  } catch { sel.innerHTML = '<option value="">Gagal memuat</option>'; }
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
