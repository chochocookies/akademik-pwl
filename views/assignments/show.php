<?php $title = 'Detail Tugas'; require_once __DIR__ . '/../layouts/header.php'; ?>
<?php $isPast=isDeadlinePassed($assignment['deadline']); $daysLeft=ceil((strtotime($assignment['deadline'])-time())/86400); ?>
<div class="max-w-3xl mx-auto space-y-5">
  <div class="flex items-center gap-3">
    <a href="<?= url('/assignments') ?>" class="btn btn-secondary btn-sm btn-icon"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Detail Tugas</h2>
  </div>

  <!-- Main card -->
  <div class="card">
    <div class="flex items-start justify-between gap-4 flex-wrap mb-5">
      <div>
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-2xl leading-tight"><?= e($assignment['judul']) ?></h3>
        <div class="flex flex-wrap gap-2 mt-3">
          <span class="badge badge-blue"><?= e($assignment['nama_kelas']) ?></span>
          <span class="badge badge-purple"><?= e($assignment['nama_mapel']) ?></span>
          <span class="badge <?= $isPast?'badge-red':'badge-green' ?>"><?= $isPast?'Sudah Lewat':'Aktif' ?></span>
        </div>
      </div>
      <?php if (Auth::is('guru','admin')): ?>
      <a href="<?= url('/assignments/'.$assignment['id'].'/edit') ?>" class="btn btn-secondary btn-sm"><i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit</a>
      <?php endif; ?>
    </div>
    <div class="grid grid-cols-3 gap-4 p-4 bg-slate-50 dark:bg-dark-card rounded-2xl mb-5">
      <div class="text-center"><p class="text-xs text-slate-400 dark:text-dark-text mb-1 font-medium">Guru</p><p class="font-semibold text-slate-700 dark:text-slate-300 text-sm"><?= e($assignment['guru_name']) ?></p></div>
      <div class="text-center border-x border-slate-200 dark:border-dark-border"><p class="text-xs text-slate-400 dark:text-dark-text mb-1 font-medium">Deadline</p><p class="font-semibold text-slate-700 dark:text-slate-300 text-sm"><?= formatDate($assignment['deadline'],'d M Y') ?></p><p class="text-xs text-slate-400 dark:text-dark-text"><?= formatDate($assignment['deadline'],'H:i') ?> WIB</p></div>
      <div class="text-center"><p class="text-xs text-slate-400 dark:text-dark-text mb-1 font-medium">Nilai Maks</p><p class="font-display font-bold text-2xl text-slate-900 dark:text-white"><?= $assignment['max_nilai'] ?></p></div>
    </div>
    <?php if (!$isPast && $daysLeft <= 3): ?>
    <div class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-900/50 rounded-xl text-sm text-amber-800 dark:text-amber-400 mb-4">
      <i data-lucide="timer" class="w-4 h-4 shrink-0"></i>
      Deadline <strong><?= $daysLeft > 0 ? "dalam $daysLeft hari" : "hari ini!" ?></strong>
    </div>
    <?php endif; ?>
    <?php if ($assignment['deskripsi']): ?>
    <div>
      <p class="text-xs font-bold text-slate-400 dark:text-dark-text uppercase tracking-wider mb-2">Instruksi</p>
      <div class="p-4 bg-slate-50 dark:bg-dark-card rounded-xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed"><?= nl2br(e($assignment['deskripsi'])) ?></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- MURID: Submit -->
  <?php if (Auth::is('murid')): ?>
  <div class="card">
    <h4 class="font-display font-bold text-slate-900 dark:text-white text-lg mb-4">
      <?= $mySubmission ? '✓ Tugas Dikumpulkan' : 'Kumpulkan Tugas' ?>
    </h4>
    <?php if ($mySubmission): ?>
    <div class="flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl mb-4">
      <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 shrink-0"></i>
      <div>
        <p class="font-semibold text-emerald-800 dark:text-emerald-300 text-sm">Dikumpulkan pada <?= formatDate($mySubmission['submitted_at'],'d M Y H:i') ?></p>
        <?php if ($mySubmission['nilai']!==null): ?>
        <p class="text-sm text-emerald-700 dark:text-emerald-400 mt-1">Nilai: <strong class="font-display text-lg"><?= $mySubmission['nilai'] ?></strong></p>
        <?php else: ?><p class="text-xs text-emerald-600 dark:text-emerald-500 mt-1">Menunggu penilaian dari guru...</p><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
    <form method="POST" action="<?= url('/assignments/'.$assignment['id'].'/submit') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="form-group"><label class="form-label">Catatan / Jawaban</label><textarea name="catatan" class="form-input" rows="3" placeholder="Tuliskan jawaban atau catatan pengumpulan..."><?= e($mySubmission['catatan']??'') ?></textarea></div>
      <div class="form-group"><label class="form-label">File <span class="text-slate-400 normal-case font-normal">(Opsional)</span></label><input type="file" name="file" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"><p class="form-hint">Format: PDF, DOC, DOCX, JPG, PNG. Maks 5MB.</p><?php if (!empty($mySubmission['file_path'])): ?><p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1.5 flex items-center gap-1"><i data-lucide="paperclip" class="w-3.5 h-3.5"></i>File sebelumnya: <?= e($mySubmission['file_path']) ?></p><?php endif; ?></div>
      <?php if ($isPast): ?>
      <div class="flex items-center gap-2.5 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-xl text-sm text-red-700 dark:text-red-400 mb-4"><i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>Deadline sudah lewat. Pengumpulan akan ditandai <strong>terlambat</strong>.</div>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary"><i data-lucide="upload" class="w-4 h-4"></i><?= $mySubmission?'Perbarui Pengumpulan':'Kumpulkan Tugas' ?></button>
    </form>
  </div>
  <?php endif; ?>

  <!-- GURU/ADMIN: Submissions -->
  <?php if (Auth::is('guru','admin')): ?>
  <div class="card">
    <div class="flex items-center justify-between mb-5">
      <h4 class="font-display font-bold text-slate-900 dark:text-white text-lg">Pengumpulan (<?= count($submissions) ?>)</h4>
    </div>
    <?php if (empty($submissions)): ?>
    <div class="empty-state py-8"><i data-lucide="inbox" class="empty-icon"></i><p class="empty-title">Belum ada pengumpulan</p></div>
    <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($submissions as $sub): ?>
      <div class="p-4 border border-slate-100 dark:border-dark-border rounded-2xl hover:border-slate-200 dark:hover:border-dark-muted transition-colors">
        <div class="flex items-start justify-between gap-3 flex-wrap mb-3">
          <div class="flex items-center gap-3">
            <div class="avatar avatar-sm avatar-blue"><?= strtoupper(substr($sub['student_name'],0,1)) ?></div>
            <div>
              <p class="font-semibold text-slate-800 dark:text-slate-100 text-sm"><?= e($sub['student_name']) ?></p>
              <p class="text-xs text-slate-400 dark:text-dark-text">NIS: <?= e($sub['nis']) ?> · <?= formatDate($sub['submitted_at'],'d M Y H:i') ?></p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <span class="badge <?= $sub['status']==='graded'?'badge-green':($sub['status']==='late'?'badge-red':'badge-amber') ?>"><?= ['submitted'=>'Dikumpul','graded'=>'Dinilai','late'=>'Terlambat'][$sub['status']] ?></span>
            <?php if ($sub['nilai']!==null): ?><span class="font-display font-bold text-xl text-slate-900 dark:text-white"><?= $sub['nilai'] ?></span><?php endif; ?>
          </div>
        </div>
        <?php if ($sub['catatan']): ?><div class="p-3 bg-slate-50 dark:bg-dark-card rounded-xl text-sm text-slate-600 dark:text-slate-400 mb-3"><?= nl2br(e($sub['catatan'])) ?></div><?php endif; ?>
        <?php if ($sub['file_path']): ?><a href="<?= url('/uploads/'.$sub['file_path']) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-brand-600 dark:text-brand-400 hover:underline mb-3"><i data-lucide="paperclip" class="w-3.5 h-3.5"></i>Lihat File</a><?php endif; ?>
        <form method="POST" action="<?= url('/submissions/'.$sub['id'].'/grade') ?>" class="flex items-center gap-2 mt-2 pt-3 border-t border-slate-100 dark:border-dark-border">
          <?= csrf_field() ?>
          <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
          <input type="number" name="nilai" value="<?= e($sub['nilai']??'') ?>" min="0" max="<?= $assignment['max_nilai'] ?>" placeholder="Nilai" class="form-input w-24 text-center font-mono">
          <button type="submit" class="btn btn-success btn-sm"><i data-lucide="check" class="w-3.5 h-3.5"></i> Beri Nilai</button>
        </form>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
