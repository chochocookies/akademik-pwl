<?php
/** @var Router $router */

// ── Auth ──────────────────────────────────────────────────────────────────────
$router->get('/login',  [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// ── Dashboard ─────────────────────────────────────────────────────────────────
$router->get('/dashboard',       [AuthController::class, 'dashboard']);
$router->get('/dashboard/admin', [DashboardController::class, 'admin']);
$router->get('/dashboard/guru',  [DashboardController::class, 'guru']);
$router->get('/dashboard/murid', [DashboardController::class, 'murid']);

// ── Students ──────────────────────────────────────────────────────────────────
$router->get('/students',              [StudentController::class, 'index']);
$router->get('/students/create',       [StudentController::class, 'create']);
$router->post('/students',             [StudentController::class, 'store']);
$router->get('/students/{id}',         [StudentController::class, 'show']);
$router->get('/students/{id}/edit',    [StudentController::class, 'edit']);
$router->post('/students/{id}/update', [StudentController::class, 'update']);
$router->post('/students/{id}/delete', [StudentController::class, 'destroy']);

// ── Teachers ──────────────────────────────────────────────────────────────────
$router->get('/teachers',              [TeacherController::class, 'index']);
$router->get('/teachers/create',       [TeacherController::class, 'create']);
$router->post('/teachers',             [TeacherController::class, 'store']);
$router->get('/teachers/{id}',         [TeacherController::class, 'show']);
$router->get('/teachers/{id}/edit',    [TeacherController::class, 'edit']);
$router->post('/teachers/{id}/update', [TeacherController::class, 'update']);
$router->post('/teachers/{id}/delete', [TeacherController::class, 'destroy']);

// ── Classes ───────────────────────────────────────────────────────────────────
$router->get('/classes',              [ClassController::class, 'index']);
$router->get('/classes/create',       [ClassController::class, 'create']);
$router->post('/classes',             [ClassController::class, 'store']);
$router->get('/classes/{id}',         [ClassController::class, 'show']);
$router->get('/classes/{id}/edit',    [ClassController::class, 'edit']);
$router->post('/classes/{id}/update', [ClassController::class, 'update']);
$router->post('/classes/{id}/delete', [ClassController::class, 'destroy']);

// ── Subjects ──────────────────────────────────────────────────────────────────
$router->get('/subjects',              [SubjectController::class, 'index']);
$router->get('/subjects/create',       [SubjectController::class, 'create']);
$router->post('/subjects',             [SubjectController::class, 'store']);
$router->get('/subjects/{id}/edit',    [SubjectController::class, 'edit']);
$router->post('/subjects/{id}/update', [SubjectController::class, 'update']);
$router->post('/subjects/{id}/delete', [SubjectController::class, 'destroy']);

// ── Grades ────────────────────────────────────────────────────────────────────
$router->get('/grades',                  [GradeController::class, 'index']);
$router->get('/grades/{classId}',        [GradeController::class, 'byClass']);
$router->get('/grades/{classId}/input',  [GradeController::class, 'create']);
$router->post('/grades',                 [GradeController::class, 'store']);
$router->get('/my-grades',               [GradeController::class, 'myGrades']);

// ── Assignments ───────────────────────────────────────────────────────────────
$router->get('/assignments',                  [AssignmentController::class, 'index']);
$router->get('/assignments/create',           [AssignmentController::class, 'create']);
$router->get('/assignments/subjects',         [AssignmentController::class, 'getSubjectsByClass']);
$router->post('/assignments',                 [AssignmentController::class, 'store']);
$router->get('/assignments/{id}',             [AssignmentController::class, 'show']);
$router->get('/assignments/{id}/edit',        [AssignmentController::class, 'edit']);
$router->post('/assignments/{id}/update',     [AssignmentController::class, 'update']);
$router->post('/assignments/{id}/delete',     [AssignmentController::class, 'destroy']);
$router->post('/assignments/{id}/submit',     [AssignmentController::class, 'submit']);
$router->post('/submissions/{id}/grade',      [AssignmentController::class, 'gradeSubmission']);

// ── Attendance ────────────────────────────────────────────────────────────────
$router->get('/attendance',                      [AttendanceController::class, 'index']);
$router->get('/attendance/create',               [AttendanceController::class, 'create']);
$router->get('/attendance/rekap/{classId}',      [AttendanceController::class, 'rekap']);  // before {id}!
$router->post('/attendance',                     [AttendanceController::class, 'store']);
$router->get('/attendance/{id}',                 [AttendanceController::class, 'show']);
$router->get('/attendance/{id}/fill',            [AttendanceController::class, 'fill']);
$router->post('/attendance/{id}/save',           [AttendanceController::class, 'save']);
$router->post('/attendance/{id}/delete',         [AttendanceController::class, 'destroy']);

// ── Profile ───────────────────────────────────────────────────────────────────
$router->get('/profile',         [ProfileController::class, 'show']);
$router->post('/profile/update', [ProfileController::class, 'update']);
$router->post('/profile/password',[ProfileController::class, 'changePassword']);

// ── Users (admin) ─────────────────────────────────────────────────────────────
$router->get('/users',              [UserController::class, 'index']);
$router->post('/users/{id}/toggle', [UserController::class, 'toggleActive']);
$router->post('/users/{id}/delete', [UserController::class, 'destroy']);

// ── Murid self-rapor shortcut ─────────────────────────────────────────────────
$router->get('/my-rapor', function() {
    Middleware::murid();
    $student = (new StudentModel())->findByUserId(Auth::id());
    if (!$student) { Flash::set('error','Data siswa tidak ditemukan.'); redirect('/dashboard'); }
    redirect('/reports/preview/' . $student['id'] . '?semester=' . SEMESTER);
});

// ── Reports ───────────────────────────────────────────────────────────────────
$router->get('/reports',                       [ReportController::class, 'index']);
$router->get('/reports/preview/{studentId}',   [ReportController::class, 'preview']);
$router->get('/reports/pdf/{studentId}',       [ReportController::class, 'pdf']);
$router->post('/reports/{studentId}/note',     [ReportController::class, 'saveNote']);
$router->get('/reports/{classId}',             [ReportController::class, 'byClass']);

// ── Calendar ──────────────────────────────────────────────────────────────────
$router->get('/calendar',                      [CalendarController::class, 'index']);
$router->get('/calendar/schedule/{classId}',   [CalendarController::class, 'schedule']);
$router->post('/calendar/event',               [CalendarController::class, 'storeEvent']);
$router->post('/calendar/event/{id}/delete',   [CalendarController::class, 'destroyEvent']);
$router->post('/calendar/schedule',            [CalendarController::class, 'storeSchedule']);
$router->post('/calendar/schedule/{id}/delete',[CalendarController::class, 'destroySchedule']);

// ── Journals ──────────────────────────────────────────────────────────────────
$router->get('/journals',              [JournalController::class, 'index']);
$router->get('/journals/create',       [JournalController::class, 'create']);
$router->post('/journals',             [JournalController::class, 'store']);
$router->get('/journals/{id}',         [JournalController::class, 'show']);
$router->get('/journals/{id}/edit',    [JournalController::class, 'edit']);
$router->post('/journals/{id}/update', [JournalController::class, 'update']);
$router->post('/journals/{id}/delete', [JournalController::class, 'destroy']);

// ── Notifications ─────────────────────────────────────────────────────────────
$router->get('/notifications',             [NotificationController::class, 'index']);
$router->get('/notifications/count',       [NotificationController::class, 'count']);
$router->post('/notifications/read-all',   [NotificationController::class, 'markAllRead']);
$router->post('/notifications/{id}/read',  [NotificationController::class, 'markRead']);

// ── Announcements ─────────────────────────────────────────────────────────────
$router->get('/announcements',                    [AnnouncementController::class, 'index']);
$router->get('/announcements/create',             [AnnouncementController::class, 'create']);
$router->post('/announcements',                   [AnnouncementController::class, 'store']);
$router->get('/announcements/{id}',               [AnnouncementController::class, 'show']);
$router->get('/announcements/{id}/edit',          [AnnouncementController::class, 'edit']);
$router->post('/announcements/{id}/update',       [AnnouncementController::class, 'update']);
$router->post('/announcements/{id}/delete',       [AnnouncementController::class, 'destroy']);
