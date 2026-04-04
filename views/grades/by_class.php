<?php $title = 'Nilai '.$class['nama_kelas']; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center gap-3">
    <a href="<?= url('/grades') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div class="flex-1"><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Nilai <?= e($class['nama_kelas']) ?></h2></div>
    <?php if (Auth::is('guru','admin')): ?>
    <a href="<?= url('/grades/'.$class['id'].'/input') ?>" class="btn btn-primary btn-sm"><i data-lucide="pencil" class="w-3.5 h-3.5"></i> Input Nilai</a>
    <?php endif; ?>
  </div>
  <div class="card p-4">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
      <div class="flex-1 min-w-40"><label class="form-label">Mata Pelajaran</label>
        <select name="subject_id" class="form-input" onchange="this.form.submit()">
          <?php foreach ($subjects as $s): ?><option value="<?= $s['id'] ?>" <?= $selectedSubject==$s['id']?'selected':'' ?>><?= e($s['nama_mapel']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="min-w-36"><label class="form-label">Semester</label>
        <select name="semester" class="form-input" onchange="this.form.submit()">
          <option value="1" <?= $semester=='1'?'selected':'' ?>>Semester 1</option>
          <option value="2" <?= $semester=='2'?'selected':'' ?>>Semester 2</option>
        </select>
      </div>
    </form>
  </div>
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Siswa</th><th class="text-center">Harian</th><th class="text-center">UTS</th><th class="text-center">UAS</th><th class="text-center">Akhir</th><th class="text-center">Grade</th></tr></thead>
        <tbody>
        <?php $total=0;$cnt=0; foreach ($grades as $i => $g):
          $na=(float)($g['nilai_akhir']??0);
          if($g['nilai_akhir']!==null){$total+=$na;$cnt++;}
          $gl=$na>=90?'A':($na>=80?'B':($na>=70?'C':'D'));
        ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td><div class="flex items-center gap-2.5"><div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($g['student_name'],0,1)) ?></div><p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($g['student_name']) ?></p></div></td>
          <?php if($g['nilai_akhir']!==null): ?>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_harian'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uts'],1) ?></td>
          <td class="text-center text-slate-600 dark:text-slate-400"><?= number_format((float)$g['nilai_uas'],1) ?></td>
          <td class="text-center font-display font-bold text-lg <?= $na>=90?'text-emerald-600 dark:text-emerald-400':($na>=80?'text-brand-600 dark:text-brand-400':($na>=70?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400')) ?>"><?= number_format($na,1) ?></td>
          <td class="text-center"><span class="badge badge-<?= $gl ?>"><?= $gl ?></span></td>
          <?php else: ?><td colspan="5" class="text-center text-slate-300 dark:text-dark-muted text-xs italic">Belum ada nilai</td><?php endif; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <?php if($cnt>0): $avg=round($total/$cnt,1); $gl=$avg>=90?'A':($avg>=80?'B':($avg>=70?'C':'D')); ?>
        <tfoot><tr>
          <td colspan="5" class="text-right font-semibold text-slate-600 dark:text-slate-400 py-3">Rata-rata kelas:</td>
          <td class="text-center font-display font-bold text-xl py-3 <?= $avg>=90?'text-emerald-600':($avg>=80?'text-brand-600':($avg>=70?'text-amber-600':'text-red-600')) ?>"><?= $avg ?></td>
          <td class="text-center py-3"><span class="badge badge-<?= $gl ?>"><?= $gl ?></span></td>
        </tr></tfoot>
        <?php endif; ?>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
