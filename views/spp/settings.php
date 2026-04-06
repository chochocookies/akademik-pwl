<?php $title = 'Setting Tarif SPP'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-lg mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/spp') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Setting Tarif SPP</h2>
  </div>
  <div class="card">
    <form method="POST" action="<?= url('/spp/settings') ?>">
      <?= csrf_field() ?>
      <p class="text-sm text-slate-500 dark:text-dark-text mb-5">Tahun Ajaran: <strong class="text-slate-700 dark:text-slate-300"><?= TAHUN_AJARAN ?></strong></p>
      <div class="space-y-3">
        <?php
        $existing = [];
        foreach ($settings as $s) $existing[$s['kelas_tingkat']] = $s['jumlah_per_bulan'];
        for ($t=1; $t<=6; $t++):
        ?>
        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-dark-card rounded-xl">
          <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center font-display font-bold text-emerald-700 dark:text-emerald-400"><?= $t ?></div>
          <div class="flex-1">
            <p class="font-semibold text-slate-700 dark:text-slate-300 text-sm">Kelas <?= $t ?></p>
            <input type="hidden" name="kelas_tingkat[]" value="<?= $t ?>">
          </div>
          <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 dark:text-dark-text">Rp</span>
            <input type="number" name="jumlah_per_bulan[]" value="<?= $existing[$t] ?? 50000 ?>" min="0" step="1000" class="form-input w-32 text-right font-mono">
            <span class="text-xs text-slate-400 dark:text-dark-text">/bln</span>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <div class="flex gap-3 pt-4 mt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Tarif</button>
        <a href="<?= url('/spp') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
