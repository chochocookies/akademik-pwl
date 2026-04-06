<?php $title = 'Pembayaran SPP'; require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $monthNames = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; ?>
<div class="space-y-5 max-w-6xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Pembayaran SPP</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5">Tahun <?= $year ?></p></div>
    <div class="flex gap-2">
      <a href="<?= url('/spp/settings') ?>" class="btn btn-secondary btn-sm"><i data-lucide="settings" class="w-3.5 h-3.5"></i> Setting Tarif</a>
    </div>
  </div>
  <!-- Tarif per kelas -->
  <?php if (!empty($settings)): ?>
  <div class="card">
    <h3 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3">Tarif SPP <?= TAHUN_AJARAN ?></h3>
    <div class="flex flex-wrap gap-3">
      <?php foreach ($settings as $s): ?>
      <div class="px-3 py-2 bg-slate-50 dark:bg-dark-card rounded-xl text-sm">
        <span class="font-semibold text-slate-700 dark:text-slate-300">Kelas <?= $s['kelas_tingkat'] ?>:</span>
        <span class="text-slate-500 dark:text-dark-text ml-1">Rp <?= number_format($s['jumlah_per_bulan'],0,',','.') ?>/bulan</span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <!-- Summary per kelas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($classes as $c): $s = $summary[$c['id']] ?? ['total'=>0,'lunas'=>0,'belum'=>0]; $pct = $s['total']>0?round($s['lunas']/$s['total']*100):0; ?>
    <a href="<?= url('/spp?class_id='.$c['id']) ?>" class="card card-hover group">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center font-display font-bold text-emerald-700 dark:text-emerald-400 text-xl"><?= $c['tingkat'] ?></div>
        <span class="text-xs font-semibold <?= $pct>=80?'text-emerald-600 dark:text-emerald-400':($pct>=50?'text-amber-600 dark:text-amber-400':'text-red-600 dark:text-red-400') ?>"><?= $pct ?>% lunas</span>
      </div>
      <h3 class="font-display font-bold text-slate-900 dark:text-white"><?= e($c['nama_kelas']) ?></h3>
      <div class="progress-bar mt-3 mb-2"><div class="progress-fill <?= $pct>=80?'bg-emerald-500':($pct>=50?'bg-amber-500':'bg-red-500') ?>" style="width:<?= $pct ?>%"></div></div>
      <div class="flex justify-between text-xs text-slate-500 dark:text-dark-text">
        <span><?= $s['lunas'] ?> lunas</span><span><?= $s['belum'] ?> belum</span><span><?= $s['total'] ?> total</span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
