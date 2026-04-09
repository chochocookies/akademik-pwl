<?php
$title = 'Preview Rapor — '.$data['student']['name'];
require_once __DIR__ . '/../layouts/header.php';
$student = $data['student'];
$grades  = $data['grades'];
$note    = $data['note'];
$abs     = $data['absStats'];
?>
<div class="max-w-4xl mx-auto space-y-5">
  <div class="flex flex-wrap items-center gap-3">
    <a href="javascript:history.back()" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div class="flex-1"><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Preview Rapor</h2></div>
    <a href="<?= url('/reports/pdf/'.$student['id'].'?semester='.$semester) ?>" target="_blank" class="btn btn-primary btn-sm">
      <i data-lucide="printer" class="w-4 h-4"></i> Cetak / Download PDF
    </a>
  </div>

  <!-- Rapor card — mirrors template -->
  <div class="card border-2 border-slate-200 dark:border-dark-border">
    <!-- Header sekolah -->
    <div class="text-center pb-5 mb-5 border-b-2 border-slate-800 dark:border-slate-600">
      <div class="flex items-center justify-center gap-4 mb-3">
        <div class="w-16 h-16 rounded-full bg-brand-100 dark:bg-brand-900/40 flex items-center justify-center">
          <i data-lucide="graduation-cap" class="w-8 h-8 text-brand-600 dark:text-brand-400"></i>
        </div>
        <div class="text-left">
          <h1 class="font-display font-bold text-xl text-slate-900 dark:text-white">LAPORAN HASIL BELAJAR SISWA</h1>
          <p class="text-sm text-slate-600 dark:text-slate-400">Sekolah Dasar — <?= APP_NAME ?></p>
          <p class="text-xs text-slate-400 dark:text-dark-text">Tahun Pelajaran <?= TAHUN_AJARAN ?> · Semester <?= $semester ?></p>
        </div>
      </div>
    </div>

    <!-- Identitas siswa -->
    <div class="grid grid-cols-2 gap-6 mb-6 p-4 bg-slate-50 dark:bg-dark-card rounded-2xl">
      <div class="space-y-2 text-sm">
        <?php $fields = [['Nama','name'],['NIS','nis'],['Kelas','nama_kelas'],['Tingkat','tingkat']]; ?>
        <?php foreach ($fields as [$lbl,$key]): ?>
        <div class="flex gap-3">
          <span class="text-slate-400 dark:text-dark-text w-24 shrink-0"><?= $lbl ?></span>
          <span class="font-semibold text-slate-800 dark:text-slate-100">: <?= e($student[$key]??'—') ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="space-y-2 text-sm">
        <?php $fields2 = [['Jenis Kelamin', $student['gender']==='L'?'Laki-laki':'Perempuan'],['Tgl Lahir', $student['birth_date']?formatDate($student['birth_date']):'—'],['Semester', 'Semester '.$semester],['Tahun Ajaran', TAHUN_AJARAN]]; ?>
        <?php foreach ($fields2 as [$lbl,$val]): ?>
        <div class="flex gap-3">
          <span class="text-slate-400 dark:text-dark-text w-28 shrink-0"><?= $lbl ?></span>
          <span class="font-semibold text-slate-800 dark:text-slate-100">: <?= e($val) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Tabel Nilai -->
    <h3 class="font-display font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
      <i data-lucide="bar-chart-3" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i> Nilai Akademik
    </h3>
    <div class="table-wrap mb-6">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th><th>Mata Pelajaran</th>
            <th class="text-center">Harian (30%)</th><th class="text-center">UTS (30%)</th>
            <th class="text-center">UAS (40%)</th><th class="text-center">Nilai Akhir</th>
            <th class="text-center">Predikat</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($grades as $i => $g):
          $na = (float)$g['nilai_akhir'];
          $gl = $na>=90?'A':($na>=80?'B':($na>=70?'C':'D'));
        ?>
        <tr>
          <td class="text-slate-400 text-xs"><?= $i+1 ?></td>
          <td class="font-medium text-slate-800 dark:text-slate-100"><?= e($g['nama_mapel']) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_harian'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uts'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uas'],1) ?></td>
          <td class="text-center font-display font-bold text-lg <?= $na>=90?'text-emerald-600 dark:text-emerald-400':($na>=80?'text-brand-600 dark:text-brand-400':($na>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= number_format($na,1) ?></td>
          <td class="text-center"><span class="badge badge-<?= $gl ?>"><?= $gl ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($grades)): ?>
        <tr><td colspan="7" class="text-center text-slate-400 py-6">Belum ada nilai</td></tr>
        <?php else: ?>
        <tr class="bg-slate-50 dark:bg-dark-surface font-bold">
          <td colspan="5" class="px-4 py-3 text-right text-slate-700 dark:text-slate-300">Rata-rata Akhir</td>
          <td class="text-center px-4 py-3 font-display text-xl <?= $avgNilai>=90?'text-emerald-600':($avgNilai>=80?'text-brand-600':($avgNilai>=70?'text-amber-600':'text-red-600')) ?>"><?= number_format($avgNilai,2) ?></td>
          <td class="text-center px-4 py-3"><span class="badge badge-<?= $predikat ?>"><?= $predikat ?></span></td>
        </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Kehadiran + Sikap -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
      <div>
        <h3 class="font-display font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
          <i data-lucide="calendar-check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i> Rekap Kehadiran
        </h3>
        <div class="grid grid-cols-4 gap-2">
          <?php foreach ([['Hadir',$abs['hadir']??0,'emerald'],['Sakit',$abs['sakit']??0,'blue'],['Izin',$abs['izin']??0,'amber'],['Alpha',$abs['alpha']??0,'red']] as [$lbl,$val,$col]): ?>
          <div class="text-center p-3 bg-<?= $col ?>-50 dark:bg-<?= $col ?>-900/20 rounded-xl">
            <div class="font-display font-bold text-xl text-<?= $col ?>-700 dark:text-<?= $col ?>-400"><?= $val ?></div>
            <div class="text-xs text-<?= $col ?>-600 dark:text-<?= $col ?>-500 mt-0.5"><?= $lbl ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <h3 class="font-display font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
          <i data-lucide="star" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i> Penilaian
        </h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between items-center p-2.5 bg-slate-50 dark:bg-dark-card rounded-xl">
            <span class="text-slate-500 dark:text-dark-text">Sikap / Karakter</span>
            <span class="badge badge-<?= $note['predikat_sikap']??'B' ?>"><?= $note['predikat_sikap']??'B' ?></span>
          </div>
          <div class="flex justify-between items-center p-2.5 bg-slate-50 dark:bg-dark-card rounded-xl">
            <span class="text-slate-500 dark:text-dark-text">Keterampilan</span>
            <span class="badge badge-<?= $note['predikat_keterampilan']??'B' ?>"><?= $note['predikat_keterampilan']??'B' ?></span>
          </div>
          <?php if ($note['ranking']): ?>
          <div class="flex justify-between items-center p-2.5 bg-slate-50 dark:bg-dark-card rounded-xl">
            <span class="text-slate-500 dark:text-dark-text">Peringkat Kelas</span>
            <span class="font-display font-bold text-slate-900 dark:text-white">#<?= $note['ranking'] ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Catatan Wali Kelas -->
    <div class="mb-5">
      <h3 class="font-display font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
        <i data-lucide="message-square" class="w-4 h-4 text-violet-600 dark:text-violet-400"></i> Catatan Wali Kelas
      </h3>
      <div class="p-4 bg-slate-50 dark:bg-dark-card rounded-2xl min-h-16 text-sm text-slate-700 dark:text-slate-300 italic">
        <?= $note['catatan_wali'] ? nl2br(e($note['catatan_wali'])) : '<span class="text-slate-400 dark:text-dark-text not-italic">Belum ada catatan dari wali kelas.</span>' ?>
      </div>
    </div>

    <!-- Form input catatan (guru/admin only) -->
    <?php if (Auth::is('admin','guru')): ?>
    <div class="pt-5 border-t border-slate-100 dark:border-dark-border">
      <h3 class="font-display font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
        <i data-lucide="pencil" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i> Input Catatan & Penilaian
      </h3>
      <form method="POST" action="<?= url('/reports/'.$student['id'].'/note') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="semester" value="<?= $semester ?>">
        <input type="hidden" name="class_id" value="<?= $student['class_id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div class="form-group">
            <label class="form-label">Predikat Sikap</label>
            <select name="predikat_sikap" class="form-input">
              <?php foreach (['A','B','C','D'] as $p): ?><option value="<?= $p ?>" <?= ($note['predikat_sikap']??'B')===$p?'selected':'' ?>><?= $p ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Predikat Keterampilan</label>
            <select name="predikat_keterampilan" class="form-input">
              <?php foreach (['A','B','C','D'] as $p): ?><option value="<?= $p ?>" <?= ($note['predikat_keterampilan']??'B')===$p?'selected':'' ?>><?= $p ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Peringkat Kelas</label>
            <input type="number" name="ranking" value="<?= e($note['ranking']??'') ?>" class="form-input" placeholder="cth: 3" min="1">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Catatan Wali Kelas</label>
          <textarea name="catatan_wali" class="form-input" rows="3" placeholder="Tulis catatan dan motivasi untuk siswa..."><?= e($note['catatan_wali']??'') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Catatan Kepala Sekolah <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
          <textarea name="catatan_kepala" class="form-input" rows="2" placeholder="Catatan dari kepala sekolah..."><?= e($note['catatan_kepala']??'') ?></textarea>
        </div>
        <div class="flex gap-3">
          <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Catatan</button>
          <a href="<?= url('/reports/pdf/'.$student['id'].'?semester='.$semester) ?>" target="_blank" class="btn btn-secondary">
            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Rapor
          </a>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
