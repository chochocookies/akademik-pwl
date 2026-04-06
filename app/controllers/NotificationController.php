<?php
class NotificationController extends Controller {

    public function index(): void {
        Middleware::auth();
        $notifications = (new NotificationModel())->getForUser(Auth::id(), 50);
        // Mark all as read after viewing
        (new NotificationModel())->markAllRead(Auth::id());
        $this->view('notifications.index', compact('notifications'));
    }

    public function count(): never {
        Middleware::auth();
        $count = (new NotificationModel())->countUnread(Auth::id());
        $this->json(['count' => $count]);
    }

    public function markRead(string $id): void {
        Middleware::auth();
        (new NotificationModel())->markRead((int)$id, Auth::id());
        // If AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->json(['ok' => true]);
        }
        redirect('/notifications');
    }

    public function markAllRead(): void {
        Middleware::auth();
        verify_csrf();
        (new NotificationModel())->markAllRead(Auth::id());
        Flash::set('success', 'Semua notifikasi ditandai sudah dibaca.');
        redirect('/notifications');
    }
}
