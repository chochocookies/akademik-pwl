<?php $title = 'Isi Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="space-y-5 max-w-5xl mx-auto">
  <div class="flex items-center gap-3">
    <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Isi Absensi</h2>
      <p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">
        <?= e($session['nama_kelas']) ?> · <?= e($session['nama_mapel']) ?> · <?= formatDate($session['tanggal'], 'd M Y') ?>
      </p>
    </div>
  </div>

  <!-- Quick select buttons -->
  <div class="card p-4">
    <div class="flex flex-wrap items-center gap-3">
      <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tandai Semua:</span>
      <button type="button" onclick="setAll('hadir')" class="btn btn-success btn-sm">
        <i data-lucide="check" class="w-3.5 h-3.5"></i> Semua Hadir
      </button>
      <button type="button" onclick="setAll('alpha')" class="btn btn-danger btn-sm">
        <i data-lucide="x" class="w-3.5 h-3.5"></i> Semua Alpha
      </button>
      <div class="ml-auto text-sm text-slate-500 dark:text-dark-text" id="counter">
        <span id="hadirCount" class="font-bold text-emerald-600 dark:text-emerald-400">0</span> hadir
      </div>
    </div>
  </div>

  <form method="POST" action="<?= url('/attendance/'.$session['id'].'/save') ?>">
    <?= csrf_field() ?>

    <div class="card p-0">
      <div class="table-wrap">
        <table class="data-table" id="attTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Siswa</th>
              <th class="text-center w-32">
                <span class="flex items-center justify-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                  <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Hadir
                </span>
              </th>
              <th class="text-center w-32">
                <span class="flex items-center justify-center gap-1.5 text-blue-600 dark:text-blue-400">
                  <i data-lucide="thermometer" class="w-3.5 h-3.5"></i> Sakit
                </span>
              </th>
              <th class="text-center w-32">
                <span class="flex items-center justify-center gap-1.5 text-amber-600 dark:text-amber-400">
                  <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Izin
                </span>
              </th>
              <th class="text-center w-32">
                <span class="flex items-center justify-center gap-1.5 text-red-600 dark:text-red-400">
                  <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Alpha
                </span>
              </th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($students as $i => $s):
            // Find existing record
            $currentStatus = 'hadir';
            foreach ($records as $r) {
              if ($r['student_id'] == $s['id']) { $currentStatus = $r['status']; break; }
            }
          ?>
          <tr class="att-row" data-status="<?= $currentStatus ?>">
            <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
            <td>
              <div class="flex items-center gap-3">
                <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
                <div>
                  <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p>
                  <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['nis']) ?></p>
                </div>
              </div>
            </td>
            <?php foreach (['hadir','sakit','izin','alpha'] as $st):
              $colors = ['hadir'=>'accent-emerald-600','sakit'=>'accent-blue-600','izin'=>'accent-amber-500','alpha'=>'accent-red-600'];
            ?>
            <td class="text-center">
              <label class="cursor-pointer">
                <input type="radio" name="status[<?= $s['id'] ?>]" value="<?= $st ?>"
                       class="w-5 h-5 <?= $colors[$st] ?> att-radio" data-student="<?= $s['id'] ?>"
                       <?= $currentStatus===$st ? 'checked' : '' ?> onchange="updateRow(this)">
              </label>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="flex gap-3 mt-5">
      <button type="submit" class="btn btn-primary btn-lg">
        <i data-lucide="save" class="w-5 h-5"></i> Simpan Absensi
      </button>
      <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-lg">Batal</a>
    </div>
  </form>
</div>

<style>
.att-row[data-status="hadir"]  { background: rgba(16,185,129,0.04); }
.att-row[data-status="sakit"]  { background: rgba(59,130,246,0.04); }
.att-row[data-status="izin"]   { background: rgba(245,158,11,0.04); }
.att-row[data-status="alpha"]  { background: rgba(239,68,68,0.06); }
.dark .att-row[data-status="hadir"] { background: rgba(16,185,129,0.06); }
.dark .att-row[data-status="alpha"] { background: rgba(239,68,68,0.08); }
</style>

<script>
function updateRow(radio) {
  const row = radio.closest('tr');
  row.dataset.status = radio.value;
  updateCounter();
}
function setAll(status) {
  document.querySelectorAll('.att-radio[value="'+status+'"]').forEach(r => {
    r.checked = true;
    r.closest('tr').dataset.status = status;
  });
  updateCounter();
}
function updateCounter() {
  const hadir = document.querySelectorAll('.att-radio[value="hadir"]:checked').length;
  document.getElementById('hadirCount').textContent = hadir;
}
updateCounter();
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
