      </div>
    </main>

    <!-- Footer -->
    <footer class="px-6 py-3 border-t border-slate-100 dark:border-dark-border text-xs text-slate-400 dark:text-dark-text flex items-center justify-between bg-white/60 dark:bg-dark-surface/60 shrink-0">
      <span>SiAkad SD &copy; <?= date('Y') ?></span>
      <span>Semester <?= SEMESTER ?> &bull; <?= TAHUN_AJARAN ?></span>
    </footer>
  </div>
</div>

<script>
lucide.createIcons();

// ── Notification badge polling ──────────────────────────
async function updateNotifBadge() {
  try {
    const r = await fetch('<?= url('/notifications/count') ?>');
    const data = await r.json();
    const badge = document.getElementById('notif-badge');
    if (badge) {
      if (data.count > 0) {
        badge.textContent = data.count > 9 ? '9+' : data.count;
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    }
  } catch(e) {}
}
updateNotifBadge();
setInterval(updateNotifBadge, 60000); // poll every 60s

// ── Announcement banner (if pinned & not dismissed) ─────
// Could add localStorage dismissal logic here

// Dark mode
const htmlEl = document.getElementById('html-root');
if (localStorage.getItem('theme')==='dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  htmlEl.classList.add('dark');
}
function toggleDark() {
  const isDark = htmlEl.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Sidebar mobile
function openSidebar() {
  document.getElementById('sidebar').classList.remove('-translate-x-full');
  const overlay = document.getElementById('overlay');
  overlay.classList.remove('opacity-0','pointer-events-none');
  overlay.classList.add('opacity-100');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.add('-translate-x-full');
  const overlay = document.getElementById('overlay');
  overlay.classList.add('opacity-0','pointer-events-none');
  overlay.classList.remove('opacity-100');
}

// Flash auto-dismiss
setTimeout(() => {
  document.querySelectorAll('[data-flash]').forEach(el => {
    el.style.transition = 'all 0.5s ease';
    el.style.opacity = '0';
    el.style.transform = 'translateY(-8px)';
    setTimeout(() => el.remove(), 500);
  });
}, 4500);

// Confirm dialogs
document.querySelectorAll('[data-confirm]').forEach(btn => {
  btn.addEventListener('click', e => {
    if (!confirm(btn.dataset.confirm || 'Yakin ingin melanjutkan?')) e.preventDefault();
  });
});

// Table search helper (global)
function tableSearch(inputId, tableId) {
  const input = document.getElementById(inputId);
  if (!input) return;
  input.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#'+tableId+' tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
}
</script>
</body>
</html>
