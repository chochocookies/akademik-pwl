<?php $title = 'Kalender Akademik'; require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$monthNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$dayNames   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
$prevMonth  = $month == 1 ? 12 : $month - 1;
$prevYear   = $month == 1 ? $year - 1 : $year;
$nextMonth  = $month == 12 ? 1 : $month + 1;
$nextYear   = $month == 12 ? $year + 1 : $year;
$firstDay   = (int)date('w', mktime(0,0,0,$month,1,$year));
$daysInMonth= (int)date('t', mktime(0,0,0,$month,1,$year));
$today      = date('Y-m-d');

$typeColors = ['libur'=>'bg-red-500','ujian'=>'bg-amber-500','event'=>'bg-brand-500','lainnya'=>'bg-slate-500'];
$typeLabels = ['libur'=>'Libur','ujian'=>'Ujian','event'=>'Event','lainnya'=>'Lainnya'];
?>
<div class="space-y-5 max-w-7xl mx-auto">
  <div class="flex flex-wrap items-center justify-between gap-4">
    <div><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Kalender Akademik</h2><p class="text-sm text-slate-400 dark:text-dark-text mt-0.5"><?= $monthNames[$month] ?> <?= $year ?></p></div>
    <?php if (Auth::is('admin')): ?>
    <button onclick="document.getElementById('addEventModal').classList.remove('hidden')" class="btn btn-primary">
      <i data-lucide="plus" class="w-4 h-4"></i> Tambah Event
    </button>
    <?php endif; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

    <!-- Calendar Grid -->
    <div class="lg:col-span-3 card">
      <!-- Navigation -->
      <div class="flex items-center justify-between mb-5">
        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg"><?= $monthNames[$month] ?> <?= $year ?></h3>
        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
      </div>
      <!-- Day headers -->
      <div class="grid grid-cols-7 mb-2">
        <?php foreach ($dayNames as $d): ?>
        <div class="text-center text-xs font-bold text-slate-400 dark:text-dark-text py-2"><?= $d ?></div>
        <?php endforeach; ?>
      </div>
      <!-- Dates -->
      <div class="grid grid-cols-7 gap-1">
        <?php
        // Empty cells for first week
        for ($i = 0; $i < $firstDay; $i++) {
            echo '<div class="h-20 rounded-xl bg-slate-50 dark:bg-dark-card opacity-40"></div>';
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr  = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isToday  = $dateStr === $today;
            $dayEvents= $eventMap[$dateStr] ?? [];
            $isSunday = (date('w', strtotime($dateStr)) == 0);
        ?>
        <div class="h-20 rounded-xl p-1.5 border transition-all <?= $isToday ? 'border-brand-400 bg-brand-50 dark:bg-brand-900/20' : 'border-transparent bg-slate-50 dark:bg-dark-card hover:border-slate-200 dark:hover:border-dark-border' ?>">
          <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-bold <?= $isToday ? 'text-brand-600 dark:text-brand-400' : ($isSunday ? 'text-red-500' : 'text-slate-600 dark:text-slate-400') ?>"><?= $day ?></span>
            <?php if ($isToday): ?><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span><?php endif; ?>
          </div>
          <div class="space-y-0.5 overflow-hidden">
            <?php foreach (array_slice($dayEvents, 0, 2) as $ev): ?>
            <div class="text-2xs font-medium px-1.5 py-0.5 rounded text-white truncate <?= $typeColors[$ev['tipe']]??'bg-slate-500' ?>">
              <?= e($ev['judul']) ?>
            </div>
            <?php endforeach; ?>
            <?php if (count($dayEvents) > 2): ?>
            <div class="text-2xs text-slate-400 dark:text-dark-text pl-1">+<?= count($dayEvents)-2 ?> lagi</div>
            <?php endif; ?>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
      <!-- Legend -->
      <div class="card">
        <h4 class="font-display font-bold text-slate-800 dark:text-white text-sm mb-3">Keterangan</h4>
        <div class="space-y-2">
          <?php foreach ($typeColors as $tipe => $cls): ?>
          <div class="flex items-center gap-2.5">
            <span class="w-3 h-3 rounded-full <?= $cls ?> shrink-0"></span>
            <span class="text-sm text-slate-600 dark:text-slate-400"><?= $typeLabels[$tipe] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="?year=<?= date('Y') ?>&month=<?= date('n') ?>" class="btn btn-secondary btn-sm mt-4 w-full justify-center">Kembali ke Bulan Ini</a>
      </div>

      <!-- Upcoming events -->
      <div class="card">
        <h4 class="font-display font-bold text-slate-800 dark:text-white text-sm mb-3">Event Mendatang</h4>
        <div class="space-y-2">
          <?php foreach ($upcoming as $ev): ?>
          <div class="p-3 bg-slate-50 dark:bg-dark-card rounded-xl">
            <div class="flex items-start gap-2">
              <span class="w-2 h-2 rounded-full mt-1.5 shrink-0 <?= $typeColors[$ev['tipe']]??'bg-slate-500' ?>"></span>
              <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100 text-xs"><?= e($ev['judul']) ?></p>
                <p class="text-2xs text-slate-400 dark:text-dark-text mt-0.5">
                  <?= formatDate($ev['tanggal_mulai'],'d M') ?>
                  <?= $ev['tanggal_mulai']!==$ev['tanggal_selesai'] ? ' — '.formatDate($ev['tanggal_selesai'],'d M') : '' ?>
                </p>
              </div>
            </div>
            <?php if (Auth::is('admin')): ?>
            <form method="POST" action="<?= url('/calendar/event/'.$ev['id'].'/delete') ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="text-2xs text-red-500 hover:underline mt-1" data-confirm="Hapus event ini?">Hapus</button>
            </form>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
          <?php if (empty($upcoming)): ?><p class="text-xs text-slate-400 dark:text-dark-text text-center py-2">Tidak ada event mendatang</p><?php endif; ?>
        </div>
      </div>

      <!-- Today's schedule (guru/murid) -->
      <?php if (!empty($schedule)): ?>
      <div class="card">
        <h4 class="font-display font-bold text-slate-800 dark:text-white text-sm mb-3">Jadwal Hari Ini</h4>
        <div class="space-y-2">
          <?php foreach ($schedule as $s): ?>
          <div class="flex items-center gap-3 p-2.5 bg-slate-50 dark:bg-dark-card rounded-xl">
            <div class="text-xs font-mono text-slate-400 dark:text-dark-text shrink-0">
              <?= substr($s['jam_mulai'],0,5) ?>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate"><?= e($s['nama_mapel']) ?></p>
              <?php if (isset($s['nama_kelas'])): ?>
              <p class="text-2xs text-slate-400 dark:text-dark-text"><?= e($s['nama_kelas']) ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Add Event Modal (admin only) -->
<?php if (Auth::is('admin')): ?>
<div id="addEventModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white dark:bg-dark-card rounded-3xl shadow-card-lg p-6 w-full max-w-md animate-fade-up">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Tambah Event Kalender</h3>
      <button onclick="document.getElementById('addEventModal').classList.add('hidden')" class="btn btn-ghost btn-icon btn-sm"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <form method="POST" action="<?= url('/calendar/event') ?>">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Judul Event *</label><input type="text" name="judul" class="form-input" required placeholder="cth: Ujian Akhir Semester"></div>
      <div class="grid grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Tanggal Mulai *</label><input type="date" name="tanggal_mulai" class="form-input" required value="<?= date('Y-m-d') ?>"></div>
        <div class="form-group"><label class="form-label">Tanggal Selesai *</label><input type="date" name="tanggal_selesai" class="form-input" required value="<?= date('Y-m-d') ?>"></div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div class="form-group"><label class="form-label">Tipe</label>
          <select name="tipe" class="form-input">
            <?php foreach ($typeLabels as $val=>$lbl): ?><option value="<?= $val ?>"><?= $lbl ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Warna</label>
          <div class="flex gap-2 mt-1">
            <?php foreach (['#3B82F6','#EF4444','#F59E0B','#10B981','#8B5CF6','#64748B'] as $warna): ?>
            <label class="cursor-pointer"><input type="radio" name="warna" value="<?= $warna ?>" class="sr-only"><span class="block w-7 h-7 rounded-full border-2 border-transparent hover:border-slate-400 checked:border-slate-800 transition-all" style="background:<?= $warna ?>"></span></label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-input" rows="2" placeholder="Keterangan tambahan..."></textarea></div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <button type="button" onclick="document.getElementById('addEventModal').classList.add('hidden')" class="btn btn-secondary">Batal</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
