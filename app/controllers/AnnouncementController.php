<?php
class AnnouncementController extends Controller {

    public function index(): void {
        Middleware::auth();
        $role          = Auth::role();
        $announcements = (new AnnouncementModel())->getVisible($role);
        $canCreate     = Auth::is('admin','guru');
        $this->view('announcements.index', compact('announcements','canCreate'));
    }

    public function show(string $id): void {
        Middleware::auth();
        $announcement = (new AnnouncementModel())->findWithAuthor((int)$id);
        if (!$announcement) { Flash::set('error','Pengumuman tidak ditemukan.'); redirect('/announcements'); }
        $this->view('announcements.show', compact('announcement'));
    }

    public function create(): void {
        Middleware::role('admin','guru');
        $this->view('announcements.create');
    }

    public function store(): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $v = Validator::make($_POST, ['judul'=>'required|min:5','konten'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/announcements/create'); }

        $id = (new AnnouncementModel())->create([
            'user_id'      => Auth::id(),
            'judul'        => $this->post('judul'),
            'konten'       => $this->post('konten'),
            'target_role'  => $this->post('target_role', 'all'),
            'is_pinned'    => $this->post('is_pinned') ? 1 : 0,
            'published_at' => $this->post('published_at') ?: date('Y-m-d H:i:s'),
            'expired_at'   => $this->post('expired_at') ?: null,
        ]);

        // Send notifications to target users
        $target = $this->post('target_role','all');
        $judul  = $this->post('judul');
        if ($target === 'all') {
            NotificationModel::sendToRole('guru',  'pengumuman', "📢 $judul", '', url('/announcements/'.$id));
            NotificationModel::sendToRole('murid', 'pengumuman', "📢 $judul", '', url('/announcements/'.$id));
        } else {
            NotificationModel::sendToRole($target, 'pengumuman', "📢 $judul", '', url('/announcements/'.$id));
        }

        Flash::set('success', 'Pengumuman berhasil diterbitkan.');
        redirect('/announcements');
    }

    public function edit(string $id): void {
        Middleware::role('admin','guru');
        $announcement = (new AnnouncementModel())->find((int)$id);
        if (!$announcement) { Flash::set('error','Tidak ditemukan.'); redirect('/announcements'); }
        // Guru hanya bisa edit miliknya
        if (Auth::is('guru') && $announcement['user_id'] != Auth::id()) {
            Flash::set('error','Akses ditolak.'); redirect('/announcements');
        }
        $this->view('announcements.edit', compact('announcement'));
    }

    public function update(string $id): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $announcement = (new AnnouncementModel())->find((int)$id);
        if (Auth::is('guru') && $announcement['user_id'] != Auth::id()) {
            Flash::set('error','Akses ditolak.'); redirect('/announcements');
        }
        (new AnnouncementModel())->update((int)$id, [
            'judul'       => $this->post('judul'),
            'konten'      => $this->post('konten'),
            'target_role' => $this->post('target_role','all'),
            'is_pinned'   => $this->post('is_pinned') ? 1 : 0,
            'expired_at'  => $this->post('expired_at') ?: null,
        ]);
        Flash::set('success','Pengumuman diperbarui.');
        redirect('/announcements');
    }

    public function destroy(string $id): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $announcement = (new AnnouncementModel())->find((int)$id);
        if (Auth::is('guru') && $announcement['user_id'] != Auth::id()) {
            Flash::set('error','Akses ditolak.'); redirect('/announcements');
        }
        (new AnnouncementModel())->delete((int)$id);
        Flash::set('success','Pengumuman dihapus.');
        redirect('/announcements');
    }
}
