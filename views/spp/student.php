<?php $title = 'SPP '.$student['name']; require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$monthNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$totalLunas = count(array_filter($payMap, fn($p) => $p['status'] === 'lunas'));
$totalTagihan = 12 * $jumlah;
$totalBayar   = array_sum(array_column(array_filter($payMap, fn($p) => $p['status'] === 'lunas'), 'jumlah'));
?>
<div class="space-y-5 max-w-4xl mx-auto">
  <div class="flex items-center gap-3">
    <a href="<?= url('/spp') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">SPP Siswa</h2><p class="text-sm text-slate-400 dark:text-dark-text"><?= e($student['name']) ?> · <?= e($student['nis']) ?> · <?= e($student['nama_kelas']??'—') ?></p></div>
  </div>
  <!-- Summary -->
  <div class="grid grid-cols-3 gap-4">
    <div class="card-stat text-center"><div class="font-display font-bold text-2xl text-emerald-600 dark:text-emerald-400"><?= $totalLunas ?>/12</div><div class="text-xs text-slate-400 dark:text-dark-text mt-1">Bulan Lunas</div></div>
    <div class="card-stat text-center"><div class="font-display font-bold text-xl text-slate-900 dark:text-white">Rp <?= number_format($totalBayar,0,',','.') ?></div><div class="text-xs text-slate-400 dark:text-dark-text mt-1">Total Dibayar</div></div>
    <div class="card-stat text-center"><div class="font-display font-bold text-xl text-red-600 dark:text-red-400">Rp <?= number_format($totalTagihan-$totalBayar,0,',','.') ?></div><div class="text-xs text-slate-400 dark:text-dark-text mt-1">Sisa Tagihan</div></div>
  </div>
  <!-- Monthly grid -->
  <div class="card">
    <h3 class="font-display font-bold text-slate-900 dark:text-white mb-5">Riwayat Pembayaran <?= $year ?></h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
      <?php for ($m=1; $m<=12; $m++):
        $p = $payMap[$m] ?? null;
        $status = $p['status'] ?? 'belum';
        $isPast  = $m <= (int)date('n');
      ?>
      <div class="p-4 rounded-2xl border-2 <?= $status==='lunas'?'border-emerald-200 dark:border-emerald-900/50 bg-emerald-50 dark:bg-emerald-900/20':($isPast?'border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-900/10':'border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-dark-card') ?> text-center relative">
        <div class="font-display font-bold text-lg text-slate-800 dark:text-slate-100"><?= $monthNames[$m] ?></div>
        <div class="mt-1.5">
          <?php if ($status==='lunas'): ?>
          <span class="badge badge-green text-2xs">✓ Lunas</span>
          <?php elseif ($status==='cicil'): ?>
          <span class="badge badge-amber text-2xs">Cicil</span>
          <?php elseif ($isPast): ?>
          <span class="badge badge-red text-2xs">Belum</span>
          <?php else: ?>
          <span class="badge badge-slate text-2xs">Belum Jatuh Tempo</span>
          <?php endif; ?>
        </div>
        <?php if ($p): ?>
        <div class="text-xs text-slate-400 dark:text-dark-text mt-1">Rp <?= number_format($p['jumlah'],0,',','.') ?></div>
        <?php endif; ?>
        <!-- Pay button -->
        <?php if ($status !== 'lunas' && $isPast): ?>
        <button onclick="openPayModal(<?= $m ?>, '<?= $monthNames[$m] ?>', <?= $jumlah ?>)"
                class="absolute inset-0 w-full h-full rounded-2xl opacity-0 hover:opacity-100 bg-brand-600/90 text-white text-xs font-bold transition-opacity flex items-center justify-center gap-1">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i> Bayar
        </button>
        <?php endif; ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</div>

<!-- Pay Modal -->
<div id="payModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white dark:bg-dark-card rounded-3xl shadow-card-lg p-6 w-full max-w-sm animate-fade-up">
    <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">Input Pembayaran SPP</h3>
    <form method="POST" action="<?= url('/spp/pay') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
      <input type="hidden" name="tahun" value="<?= $year ?>">
      <input type="hidden" name="bulan" id="modalBulan">
      <div class="form-group"><label class="form-label">Bulan</label><input type="text" id="modalBulanLabel" class="form-input" readonly></div>
      <div class="form-group"><label class="form-label">Jumlah (Rp) *</label><input type="number" name="jumlah" id="modalJumlah" class="form-input" required min="0"></div>
      <div class="form-group"><label class="form-label">Status</label>
        <select name="status" class="form-input">
          <option value="lunas">Lunas</option><option value="cicil">Cicil</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-input" placeholder="Opsional"></div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary flex-1 justify-center"><i data-lucide="check" class="w-4 h-4"></i> Simpan</button>
        <button type="button" onclick="closePayModal()" class="btn btn-secondary">Batal</button>
      </div>
    </form>
  </div>
</div>
<script>
const mNames = <?= json_encode($monthNames) ?>;
function openPayModal(bulan, namaBuilan, jumlah) {
  document.getElementById('modalBulan').value = bulan;
  document.getElementById('modalBulanLabel').value = namaBuilan + ' <?= $year ?>';
  document.getElementById('modalJumlah').value = jumlah;
  document.getElementById('payModal').classList.remove('hidden');
}
function closePayModal() { document.getElementById('payModal').classList.add('hidden'); }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
