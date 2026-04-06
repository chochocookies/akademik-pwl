<?php $title = 'Tambah Jurnal Mengajar'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/journals') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Tambah Jurnal Mengajar</h2></div>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/journals') ?>">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
        <div class="form-group">
          <label class="form-label">Kelas *</label>
          <select name="class_id" id="classSelect" class="form-input" required onchange="loadSubjects(this.value)">
            <option value="">— Pilih Kelas —</option>
            <?php foreach ($classes as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['nama_kelas']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Mata Pelajaran *</label>
          <select name="subject_id" id="subjectSelect" class="form-input" required><option value="">— Pilih kelas dulu —</option></select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal *</label>
          <input type="date" name="tanggal" class="form-input" required value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Link ke Sesi Absensi <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
          <select name="attendance_session_id" class="form-input">
            <option value="">— Tidak dikaitkan —</option>
            <?php foreach ($sessions as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['nama_kelas']) ?> · <?= e($s['nama_mapel']) ?> · <?= formatDate($s['tanggal'],'d M Y') ?></option><?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Materi Pokok *</label><input type="text" name="materi_pokok" class="form-input" required placeholder="cth: Penjumlahan Pecahan Biasa"></div>
      <div class="form-group"><label class="form-label">Uraian Materi</label><textarea name="materi_detail" class="form-input" rows="4" placeholder="Jelaskan materi yang diajarkan secara detail..."></textarea></div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Metode Pembelajaran</label><input type="text" name="metode" class="form-input" placeholder="cth: Ceramah, Diskusi, Tanya Jawab"></div>
        <div class="form-group"><label class="form-label">Media/Alat Bantu</label><input type="text" name="media" class="form-input" placeholder="cth: Papan tulis, Buku paket, PPT"></div>
      </div>
      <div class="form-group"><label class="form-label">Catatan Tambahan</label><textarea name="catatan" class="form-input" rows="2" placeholder="Kendala, refleksi, atau catatan lainnya..."></textarea></div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Jurnal</button>
        <a href="<?= url('/journals') ?>" class="btn btn-secondary">Batal</a>
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
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
