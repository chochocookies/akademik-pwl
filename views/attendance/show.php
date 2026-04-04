<?php $title = 'Detail Absensi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/attendance') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Absensi</h2>
  </div>

  <!-- Session info banner -->
  <div class="card bg-gradient-to-br from-slate-800 to-slate-900 text-white border-0">
    <div class="flex items-start justify-between gap-4 flex-wrap">
      <div>
        <p class="text-slate-400 text-sm"><?= e($session['nama_kelas']) ?> · <?= e($session['nama_mapel']) ?></p>
        <h3 class="font-display font-bold text-2xl mt-1"><?= formatDate($session['tanggal'], 'l, d M Y') ?></h3>
        <?php if ($session['keterangan']): ?>
        <p class="text-slate-400 text-sm mt-1"><?= e($session['keterangan']) ?></p>
        <?php endif; ?>
        <p class="text-slate-400 text-xs mt-2">Oleh: <?= e($session['guru_name']) ?></p>
      </div>
      <?php if (Auth::is('guru','admin')): ?>
      <a href="<?= url('/attendance/'.$session['id'].'/fill') ?>" class="btn btn-secondary btn-sm">
        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
      </a>
      <?php endif; ?>
    </div>

    <!-- Stats summary -->
    <?php
    $hadir = array_sum(array_column($records, 'status') === array_fill(0, count($records), 'hadir') ? array_column($records,'status') : []);
    $countH=0; $countS=0; $countI=0; $countA=0;
    foreach ($records as $r) {
      if (!$r['status']) continue;
      match($r['status']) { 'hadir'=>$countH++, 'sakit'=>$countS++, 'izin'=>$countI++, 'alpha'=>$countA++, default=>null };
    }
    $total = $countH + $countS + $countI + $countA;
    $pct = $total > 0 ? round($countH/$total*100) : 0;
    ?>
    <div class="grid grid-cols-4 gap-3 mt-5 pt-5 border-t border-white/10">
      <div class="text-center"><div class="font-display font-bold text-2xl text-emerald-400"><?= $countH ?></div><div class="text-xs text-slate-500 mt-0.5">Hadir</div></div>
      <div class="text-center"><div class="font-display font-bold text-2xl text-blue-400"><?= $countS ?></div><div class="text-xs text-slate-500 mt-0.5">Sakit</div></div>
      <div class="text-center"><div class="font-display font-bold text-2xl text-amber-400"><?= $countI ?></div><div class="text-xs text-slate-500 mt-0.5">Izin</div></div>
      <div class="text-center"><div class="font-display font-bold text-2xl text-red-400"><?= $countA ?></div><div class="text-xs text-slate-500 mt-0.5">Alpha</div></div>
    </div>
    <div class="mt-4">
      <div class="flex justify-between text-xs text-slate-400 mb-1.5">
        <span>Tingkat Kehadiran</span>
        <span class="font-bold text-white"><?= $pct ?>%</span>
      </div>
      <div class="h-2 bg-white/10 rounded-full overflow-hidden">
        <div class="h-full bg-emerald-500 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
      </div>
    </div>
  </div>

  <!-- Records table -->
  <div class="card p-0">
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>#</th><th>Siswa</th><th>NIS</th><th class="text-center">Status</th></tr></thead>
        <tbody>
        <?php foreach ($records as $i => $r):
          if (!$r['status']) $r['status'] = 'hadir';
          $badge = match($r['status']) {
            'hadir' => 'badge-green',
            'sakit' => 'badge-blue',
            'izin'  => 'badge-amber',
            'alpha' => 'badge-red',
            default => 'badge-slate'
          };
          $label = match($r['status']) {
            'hadir'=>'✓ Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha',default=>ucfirst($r['status'])
          };
        ?>
        <tr>
          <td class="text-slate-400 text-xs pl-4"><?= $i+1 ?></td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar avatar-sm <?= $r['status']==='hadir'?'avatar-green':($r['status']==='alpha'?'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400':'avatar-blue') ?>"><?= strtoupper(substr($r['student_name']??'?',0,1)) ?></div>
              <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($r['student_name']??'—') ?></p>
            </div>
          </td>
          <td><span class="font-mono text-xs bg-slate-100 dark:bg-dark-muted px-2 py-1 rounded-lg"><?= e($r['nis']??'—') ?></span></td>
          <td class="text-center"><span class="badge <?= $badge ?>"><?= $label ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
