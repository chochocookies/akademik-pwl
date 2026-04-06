<?php $title = 'Kenaikan '.$class['nama_kelas']; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-5xl mx-auto">
  <div class="flex items-center gap-3">
    <a href="<?= url('/promotions') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div>
      <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Kenaikan Kelas — <?= e($class['nama_kelas']) ?></h2>
      <p class="text-sm text-slate-400 dark:text-dark-text"><?= $isGraduating ? '🎓 Kelas 6 — Siswa akan Lulus/Alumni' : 'Naik ke: '.($nextClass ? e($nextClass['nama_kelas']) : 'Kelas '.((int)$class['tingkat']+1).' (belum ada)') ?></p>
    </div>
  </div>

  <form method="POST" action="<?= url('/promotions/'.$class['id'].'/process') ?>">
    <?= csrf_field() ?>
    <?php if (!$isGraduating && $nextClass): ?>
    <input type="hidden" name="next_class_id" value="<?= $nextClass['id'] ?>">
    <?php endif; ?>

    <div class="card p-0">
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th><th>Siswa</th><th class="text-center">Rata Nilai</th><th class="text-center">Rekomendasi</th>
              <th class="text-center">
                Keputusan
                <div class="flex justify-center gap-2 mt-1">
                  <?php if (!$isGraduating): ?>
                  <button type="button" onclick="setAll('naik')" class="text-2xs px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-medium">Semua Naik</button>
                  <?php else: ?>
                  <button type="button" onclick="setAll('alumni')" class="text-2xs px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-medium">Semua Lulus</button>
                  <?php endif; ?>
                  <button type="button" onclick="setAll('tidak')" class="text-2xs px-2 py-0.5 rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-medium">Semua Tidak Naik</button>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($studentData as $i => $s):
            $gl = $s['avg_nilai']>=90?'A':($s['avg_nilai']>=80?'B':($s['avg_nilai']>=70?'C':'D'));
          ?>
          <tr>
            <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
            <td>
              <div class="flex items-center gap-3">
                <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($s['name'],0,1)) ?></div>
                <div>
                  <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($s['name']) ?></p>
                  <p class="text-xs text-slate-400 dark:text-dark-text"><?= e($s['nis']) ?> · <?= $s['jumlah_mapel'] ?> mapel</p>
                </div>
              </div>
            </td>
            <td class="text-center">
              <?php if ($s['avg_nilai'] > 0): ?>
              <span class="font-display font-bold text-lg <?= $s['avg_nilai']>=70?'text-emerald-600 dark:text-emerald-400':'text-red-600 dark:text-red-400' ?>"><?= number_format($s['avg_nilai'],1) ?></span>
              <span class="badge badge-<?= $gl ?> ml-1"><?= $gl ?></span>
              <?php else: ?><span class="text-slate-400 text-xs">Belum ada nilai</span><?php endif; ?>
            </td>
            <td class="text-center">
              <?php if ($isGraduating): ?>
              <span class="badge badge-amber">🎓 Lulus</span>
              <?php elseif ($s['auto_naik']): ?>
              <span class="badge badge-green">✓ Naik Kelas</span>
              <?php else: ?>
              <span class="badge badge-red">✗ Tidak Naik</span>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <?php if ($isGraduating): ?>
              <input type="hidden" name="decision[<?= $s['id'] ?>]" value="alumni">
              <span class="badge badge-amber text-xs">Alumni</span>
              <?php else: ?>
              <div class="flex justify-center gap-2" id="dec-<?= $s['id'] ?>">
                <label class="cursor-pointer">
                  <input type="radio" name="decision[<?= $s['id'] ?>]" value="naik" <?= $s['auto_naik']?'checked':'' ?> class="sr-only peer dec-radio" data-id="<?= $s['id'] ?>">
                  <span class="block px-3 py-1.5 rounded-lg text-xs font-semibold border-2 transition-all peer-checked:bg-emerald-500 peer-checked:border-emerald-500 peer-checked:text-white border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-400 hover:border-emerald-300">Naik</span>
                </label>
                <label class="cursor-pointer">
                  <input type="radio" name="decision[<?= $s['id'] ?>]" value="tidak" <?= !$s['auto_naik']?'checked':'' ?> class="sr-only peer dec-radio" data-id="<?= $s['id'] ?>">
                  <span class="block px-3 py-1.5 rounded-lg text-xs font-semibold border-2 transition-all peer-checked:bg-red-500 peer-checked:border-red-500 peer-checked:text-white border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-400 hover:border-red-300">Tidak</span>
                </label>
              </div>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if (!empty($studentData)): ?>
    <div class="flex items-center gap-4 mt-4">
      <button type="submit" class="btn btn-primary btn-lg"
              data-confirm="<?= $isGraduating ? 'Proses kelulusan '.$class['nama_kelas'].'? Siswa akan menjadi alumni.' : 'Proses kenaikan kelas '.$class['nama_kelas'].'? Tindakan ini tidak dapat dibatalkan.' ?>">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <?= $isGraduating ? 'Proses Kelulusan' : 'Proses Kenaikan Kelas' ?>
      </button>
      <a href="<?= url('/promotions') ?>" class="btn btn-secondary btn-lg">Batal</a>
    </div>
    <?php endif; ?>
  </form>
</div>
<script>
function setAll(val) {
  document.querySelectorAll(`.dec-radio[value="${val}"]`).forEach(r => { r.checked = true; });
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
