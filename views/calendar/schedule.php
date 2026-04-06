<?php $title = 'Jadwal Pelajaran'; require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$hariList   = ['senin','selasa','rabu','kamis','jumat','sabtu'];
$hariLabels = ['senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu','kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu'];
$byHari = [];
foreach ($schedules as $s) $byHari[$s['hari']][] = $s;
?>
<div class="space-y-5 max-w-6xl mx-auto">
  <div class="flex flex-wrap items-center gap-3">
    <a href="<?= url('/calendar') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <div class="flex-1"><h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Jadwal Pelajaran</h2><p class="text-sm text-slate-400 dark:text-dark-text"><?= e($class['nama_kelas']) ?></p></div>
    <?php if (Auth::is('admin')): ?>
    <button onclick="document.getElementById('addScheduleModal').classList.remove('hidden')" class="btn btn-primary">
      <i data-lucide="plus" class="w-4 h-4"></i> Tambah Jadwal
    </button>
    <?php endif; ?>
  </div>

  <div class="card p-0 overflow-x-auto">
    <table class="data-table">
      <thead>
        <tr>
          <th class="w-24">Jam</th>
          <?php foreach ($hariList as $h): ?><th><?= $hariLabels[$h] ?></th><?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
      <?php
      // Collect all time slots
      $slots = [];
      foreach ($schedules as $s) $slots[$s['jam_mulai']] = true;
      ksort($slots);
      if (empty($slots)): ?>
      <tr><td colspan="7">
        <div class="empty-state py-10"><i data-lucide="calendar" class="empty-icon"></i><p class="empty-title">Belum ada jadwal</p><p class="empty-desc">Tambahkan jadwal pelajaran untuk kelas ini</p></div>
      </td></tr>
      <?php else: foreach ($slots as $jam => $_): ?>
      <tr>
        <td class="font-mono text-xs text-slate-500 dark:text-dark-text text-center"><?= substr($jam,0,5) ?></td>
        <?php foreach ($hariList as $h):
          $found = null;
          foreach ($byHari[$h]??[] as $s) if ($s['jam_mulai']==$jam) { $found=$s; break; }
        ?>
        <td>
          <?php if ($found): ?>
          <div class="p-2 bg-brand-50 dark:bg-brand-900/20 rounded-xl">
            <p class="text-xs font-semibold text-brand-800 dark:text-brand-300 truncate"><?= e($found['nama_mapel']) ?></p>
            <p class="text-2xs text-brand-600 dark:text-brand-400 mt-0.5"><?= e($found['guru_name']) ?></p>
            <?php if ($found['ruangan']): ?><p class="text-2xs text-slate-400 dark:text-dark-text"><?= e($found['ruangan']) ?></p><?php endif; ?>
            <?php if (Auth::is('admin')): ?>
            <form method="POST" action="<?= url('/calendar/schedule/'.$found['id'].'/delete') ?>" class="inline">
              <?= csrf_field() ?>
              <button type="submit" class="text-2xs text-red-500 hover:underline mt-1" data-confirm="Hapus jadwal ini?">Hapus</button>
            </form>
            <?php endif; ?>
          </div>
          <?php else: ?><div class="h-12"></div><?php endif; ?>
        </td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Schedule Modal -->
<?php if (Auth::is('admin')): ?>
<div id="addScheduleModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white dark:bg-dark-card rounded-3xl shadow-card-lg p-6 w-full max-w-lg animate-fade-up">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Tambah Jadwal</h3>
      <button onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="btn btn-ghost btn-icon btn-sm"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <form method="POST" action="<?= url('/calendar/schedule') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
      <div class="grid grid-cols-2 gap-4">
        <div class="form-group">
          <label class="form-label">Guru / Pengajar *</label>
          <select name="teacher_id" class="form-input" required>
            <option value="">— Pilih Guru —</option>
            <?php foreach ($teachers as $t): ?><option value="<?= $t['id'] ?>"><?= e($t['name']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Mata Pelajaran *</label>
          <select name="subject_id" class="form-input" required>
            <option value="">— Pilih Mapel —</option>
            <?php foreach ($subjects as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['nama_mapel']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Hari *</label>
          <select name="hari" class="form-input" required>
            <?php foreach ($hariLabels as $val=>$lbl): ?><option value="<?= $val ?>"><?= $lbl ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Ruangan</label>
          <input type="text" name="ruangan" class="form-input" placeholder="cth: Ruang A1">
        </div>
        <div class="form-group">
          <label class="form-label">Jam Mulai *</label>
          <input type="time" name="jam_mulai" class="form-input" required value="07:00">
        </div>
        <div class="form-group">
          <label class="form-label">Jam Selesai *</label>
          <input type="time" name="jam_selesai" class="form-input" required value="07:45">
        </div>
      </div>
      <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-dark-border">
        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
        <button type="button" onclick="document.getElementById('addScheduleModal').classList.add('hidden')" class="btn btn-secondary">Batal</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
