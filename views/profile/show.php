<?php $title = 'Profil Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-5">
  <h2 class="font-display font-bold text-slate-900 dark:text-white text-xl">Profil Saya</h2>

  <!-- Profile header card -->
  <?php
  $role = Auth::role();
  $heroCls = ['admin'=>'hero-admin','guru'=>'hero-guru','murid'=>'hero-murid'][$role] ?? 'hero-admin';
  $avCls   = ['admin'=>'avatar-violet','guru'=>'avatar-blue','murid'=>'avatar-green'][$role] ?? 'avatar-blue';
  $roleLabel = ['admin'=>'Administrator','guru'=>'Guru','murid'=>'Murid'][$role] ?? $role;
  ?>
  <div class="card <?= $heroCls ?> text-white border-0 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background:url('data:image/svg+xml,%3Csvg width=40 height=40 viewBox=0 0 40 40 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=none fill-rule=evenodd%3E%3Cg fill=%23ffffff fill-opacity=1%3E%3Cpath d=M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 1.4l2.83 2.83 1.41-1.41L1.41 0H0v1.41zM38.59 40l-2.83-2.83 1.41-1.41L40 38.59V40h-1.41zM40 1.41l-2.83 2.83-1.41-1.41L38.59 0H40v1.41z/%3E%3C/g%3E%3C/g%3E%3C/svg%3E') repeat"></div>
    <div class="relative flex items-center gap-5">
      <div class="w-20 h-20 rounded-3xl bg-white/20 flex items-center justify-center text-3xl font-display font-bold text-white shrink-0">
        <?= strtoupper(substr($full['name'],0,1)) ?>
      </div>
      <div>
        <p class="text-white/60 text-sm font-medium"><?= $roleLabel ?></p>
        <h3 class="font-display font-bold text-2xl text-white"><?= e($full['name']) ?></h3>
        <p class="text-white/70 text-sm mt-0.5"><?= e($full['email']) ?></p>
        <?php if ($extra):
          $identifier = $extra['nip'] ?? $extra['nis'] ?? null;
          $identLabel = Auth::is('guru') ? 'NIP' : 'NIS';
          if ($identifier): ?>
          <p class="text-white/60 text-xs mt-1 font-mono"><?= $identLabel ?>: <?= e($identifier) ?></p>
          <?php endif;
          if (!empty($extra['nama_kelas'])): ?>
          <span class="inline-block mt-2 bg-white/20 text-white text-xs px-2.5 py-1 rounded-full font-medium"><?= e($extra['nama_kelas']) ?></span>
          <?php endif;
        endif; ?>
      </div>
    </div>
  </div>

  <!-- Edit profile -->
  <div class="card">
    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-dark-border">
      <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center">
        <i data-lucide="user" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
      </div>
      <div>
        <p class="font-display font-bold text-slate-800 dark:text-slate-100 text-sm">Informasi Profil</p>
        <p class="text-xs text-slate-400 dark:text-dark-text">Perbarui nama dan email Anda</p>
      </div>
    </div>
    <form method="POST" action="<?= url('/profile/update') ?>">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-group md:col-span-2">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="name" value="<?= e($full['name']) ?>" class="form-input" required>
        </div>
        <div class="form-group md:col-span-2">
          <label class="form-label">Email *</label>
          <input type="email" name="email" value="<?= e($full['email']) ?>" class="form-input" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan</button>
    </form>
  </div>

  <!-- Change password -->
  <div class="card">
    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-dark-border">
      <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
        <i data-lucide="lock" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
      </div>
      <div>
        <p class="font-display font-bold text-slate-800 dark:text-slate-100 text-sm">Ubah Password</p>
        <p class="text-xs text-slate-400 dark:text-dark-text">Gunakan password yang kuat dan tidak mudah ditebak</p>
      </div>
    </div>
    <form method="POST" action="<?= url('/profile/password') ?>">
      <?= csrf_field() ?>
      <div class="space-y-4">
        <div class="form-group">
          <label class="form-label">Password Lama *</label>
          <input type="password" name="current_password" class="form-input" required placeholder="Password saat ini">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label class="form-label">Password Baru *</label>
            <input type="password" name="new_password" id="newPwd" class="form-input" required placeholder="Min. 6 karakter" oninput="checkStrength(this.value)">
            <!-- Strength indicator -->
            <div class="mt-2">
              <div class="progress-bar h-1.5"><div id="strengthBar" class="progress-fill bg-slate-200" style="width:0%"></div></div>
              <p id="strengthText" class="text-xs text-slate-400 dark:text-dark-text mt-1">Masukkan password baru</p>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Konfirmasi Password *</label>
            <input type="password" name="confirm_password" id="confirmPwd" class="form-input" required placeholder="Ulangi password baru" oninput="checkMatch()">
            <p id="matchText" class="text-xs mt-1 hidden"></p>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2"><i data-lucide="shield" class="w-4 h-4"></i> Ubah Password</button>
    </form>
  </div>

  <!-- Account info -->
  <div class="card bg-slate-50 dark:bg-dark-card border-slate-200 dark:border-dark-border">
    <h4 class="font-semibold text-slate-700 dark:text-slate-300 text-sm mb-3 flex items-center gap-2">
      <i data-lucide="info" class="w-4 h-4 text-slate-400"></i> Informasi Akun
    </h4>
    <div class="grid grid-cols-2 gap-3 text-sm">
      <div><p class="text-xs text-slate-400 dark:text-dark-text">Bergabung</p><p class="font-medium text-slate-700 dark:text-slate-300 mt-0.5"><?= formatDate($full['created_at']) ?></p></div>
      <div><p class="text-xs text-slate-400 dark:text-dark-text">Status Akun</p><p class="mt-0.5"><?= $full['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?></p></div>
    </div>
  </div>
</div>

<script>
function checkStrength(pwd) {
  const bar = document.getElementById('strengthBar');
  const txt = document.getElementById('strengthText');
  let score = 0;
  if (pwd.length >= 6) score++;
  if (pwd.length >= 10) score++;
  if (/[A-Z]/.test(pwd)) score++;
  if (/[0-9]/.test(pwd)) score++;
  if (/[^A-Za-z0-9]/.test(pwd)) score++;
  const levels = [
    {pct:'0%', cls:'bg-slate-200', txt:'Masukkan password baru'},
    {pct:'20%', cls:'bg-red-500',   txt:'Sangat lemah'},
    {pct:'40%', cls:'bg-orange-500',txt:'Lemah'},
    {pct:'60%', cls:'bg-amber-500', txt:'Cukup'},
    {pct:'80%', cls:'bg-brand-500', txt:'Kuat'},
    {pct:'100%',cls:'bg-emerald-500',txt:'Sangat kuat ✓'},
  ];
  const l = levels[score] || levels[0];
  bar.style.width = l.pct;
  bar.className = `progress-fill ${l.cls} transition-all duration-300`;
  txt.textContent = l.txt;
}

function checkMatch() {
  const a = document.getElementById('newPwd').value;
  const b = document.getElementById('confirmPwd').value;
  const el = document.getElementById('matchText');
  if (!b) { el.classList.add('hidden'); return; }
  el.classList.remove('hidden');
  if (a === b) {
    el.textContent = '✓ Password cocok';
    el.className = 'text-xs mt-1 text-emerald-600 dark:text-emerald-400';
  } else {
    el.textContent = '✗ Password tidak cocok';
    el.className = 'text-xs mt-1 text-red-500';
  }
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
