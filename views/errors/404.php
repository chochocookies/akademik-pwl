<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — SiAkad SD</title>
  <link rel="stylesheet" href="<?= defined('APP_URL') ? url('/css/app.css') : '/public/css/app.css' ?>">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <style>h1,.font-display{font-family:'Bricolage Grotesque',system-ui,sans-serif;}body{font-family:'DM Sans',system-ui,sans-serif;}</style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
  <div class="text-center">
    <div class="font-display font-extrabold text-9xl text-slate-200 leading-none select-none">404</div>
    <h1 class="font-display font-bold text-slate-700 text-2xl mt-4 mb-2">Halaman tidak ditemukan</h1>
    <p class="text-slate-400 mb-8">Maaf, halaman yang kamu cari tidak ada atau sudah dihapus.</p>
    <a href="<?= defined('APP_URL') ? url('/dashboard') : '/' ?>" class="inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-brand-700 transition-colors text-sm">
      <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
    </a>
  </div>
  <script>lucide.createIcons();</script>
</body>
</html>
