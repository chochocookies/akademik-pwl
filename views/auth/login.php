<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — SiAkad SD</title>
  <link rel="stylesheet" href="<?= url('/css/app.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>
    h1,h2,h3,.font-display{font-family:'Bricolage Grotesque',system-ui,sans-serif;}
    body{font-family:'DM Sans',system-ui,sans-serif;}
    .text-2xs{font-size:.65rem;line-height:1rem;}
    .card-login{animation:slideUp .55s cubic-bezier(.16,1,.3,1) both;}
    @keyframes slideUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    .blob{position:absolute;border-radius:50%;filter:blur(60px);animation:float 8s ease-in-out infinite;}
    @keyframes float{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-20px) scale(1.05)}}
    .demo-btn{transition:all .2s ease;}
    .demo-btn:hover{transform:translateY(-1px);}
  </style>
</head>
<body class="min-h-screen bg-[#050A14] flex items-center justify-center p-4 relative overflow-hidden">

<!-- Background blobs -->
<div class="blob w-96 h-96 bg-brand-600/20 -top-24 -left-24" style="animation-delay:0s"></div>
<div class="blob w-80 h-80 bg-violet-600/15 bottom-0 right-0" style="animation-delay:-3s"></div>
<div class="blob w-64 h-64 bg-cyan-600/10 top-1/2 left-1/2" style="animation-delay:-5s"></div>

<div class="relative w-full max-w-[420px]">

  <!-- Header -->
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center mx-auto mb-5 shadow-[0_0_40px_rgba(37,99,235,0.4)]">
      <i data-lucide="graduation-cap" class="w-8 h-8 text-white"></i>
    </div>
    <h1 class="font-display font-bold text-white text-3xl tracking-tight">SiAkad SD</h1>
    <p class="text-slate-500 mt-2 text-sm">Sistem Informasi Akademik Sekolah Dasar</p>
  </div>

  <!-- Card -->
  <div class="card-login rounded-3xl border border-white/8 bg-white/[0.04] backdrop-blur-2xl p-8 shadow-[0_20px_60px_rgba(0,0,0,0.5)]">

    <?php if ($msg = Flash::get('error')): ?>
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/15 border border-red-500/25 text-red-400 text-sm mb-6">
      <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
      <?= e($msg) ?>
    </div>
    <?php endif; ?>
    <?php if ($msg = Flash::get('success')): ?>
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-sm mb-6">
      <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
      <?= e($msg) ?>
    </div>
    <?php endif; ?>

    <h2 class="font-display font-bold text-white text-xl mb-6">Masuk ke Akun</h2>

    <form method="POST" action="<?= url('/login') ?>" class="space-y-4">
      <?= csrf_field() ?>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Email</label>
        <div class="relative">
          <i data-lucide="mail" class="w-4 h-4 text-slate-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
          <input type="email" name="email" value="<?= old('email') ?>" required
                 placeholder="email@sekolah.sch.id"
                 class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/8 border border-white/10 text-white text-sm
                        placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500/50
                        focus:bg-white/12 transition-all">
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Password</label>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 text-slate-600 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
          <input type="password" name="password" id="pwd" required
                 placeholder="••••••••"
                 class="w-full pl-10 pr-12 py-3 rounded-xl bg-white/8 border border-white/10 text-white text-sm
                        placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500/50
                        focus:bg-white/12 transition-all">
          <button type="button" id="togglePwd" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-600 hover:text-slate-400 transition-colors">
            <i data-lucide="eye" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <button type="submit"
              class="w-full mt-2 py-3 px-4 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl
                     flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]
                     shadow-[0_0_20px_rgba(37,99,235,0.35)] hover:shadow-[0_0_30px_rgba(37,99,235,0.5)]">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        Masuk
      </button>
    </form>

    <!-- Demo Accounts -->
    <div class="mt-6 pt-6 border-t border-white/8">
      <p class="text-slate-600 text-xs text-center mb-3 font-medium uppercase tracking-wider">Akun Demo</p>
      <div class="grid grid-cols-3 gap-2">
        <button onclick="fillDemo('admin@sekolah.sch.id')"
                class="demo-btn py-2.5 px-2 rounded-xl bg-violet-500/15 border border-violet-500/25 text-violet-400 text-xs font-semibold hover:bg-violet-500/25 transition-colors">
          🛠 Admin
        </button>
        <button onclick="fillDemo('budi@sekolah.sch.id')"
                class="demo-btn py-2.5 px-2 rounded-xl bg-brand-500/15 border border-brand-500/25 text-brand-400 text-xs font-semibold hover:bg-brand-500/25 transition-colors">
          👨‍🏫 Guru
        </button>
        <button onclick="fillDemo('ahmad@sekolah.sch.id')"
                class="demo-btn py-2.5 px-2 rounded-xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-semibold hover:bg-emerald-500/25 transition-colors">
          👦 Murid
        </button>
      </div>
      <p class="text-slate-700 text-xs text-center mt-3">Password: <code class="bg-white/8 px-2 py-0.5 rounded-lg font-mono text-slate-500">password</code></p>
    </div>
  </div>

  <p class="text-center text-slate-700 text-xs mt-6">SiAkad SD &copy; <?= date('Y') ?> &bull; Tahun Ajaran <?= TAHUN_AJARAN ?></p>
</div>

<script>
lucide.createIcons();
function fillDemo(email) {
  document.querySelector('[name=email]').value = email;
  document.querySelector('[name=password]').value = 'password';
}
document.getElementById('togglePwd').addEventListener('click', function() {
  const pwd = document.getElementById('pwd');
  pwd.type = pwd.type === 'password' ? 'text' : 'password';
  this.querySelector('[data-lucide]').setAttribute('data-lucide', pwd.type === 'password' ? 'eye' : 'eye-off');
  lucide.createIcons();
});
</script>
</body>
</html>
