<?php
/** @var Router $router */

// Auth Routes
$router->get('/login',  [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard (redirect to role-specific)
$router->get('/',          [AuthController::class, 'dashboard']);
$router->get('/dashboard', [AuthController::class, 'dashboard']);

// Dashboard by role
$router->get('/dashboard/admin', [DashboardController::class, 'admin']);
$router->get('/dashboard/guru',  [DashboardController::class, 'guru']);
$router->get('/dashboard/murid', [DashboardController::class, 'murid']);

// Students
$router->get('/students',               [StudentController::class, 'index']);
$router->get('/students/create',        [StudentController::class, 'create']);
$router->post('/students',              [StudentController::class, 'store']);
$router->get('/students/{id}',          [StudentController::class, 'show']);
$router->get('/students/{id}/edit',     [StudentController::class, 'edit']);
$router->post('/students/{id}/update',  [StudentController::class, 'update']);
$router->post('/students/{id}/delete',  [StudentController::class, 'destroy']);

// Teachers
$router->get('/teachers',               [TeacherController::class, 'index']);
$router->get('/teachers/create',        [TeacherController::class, 'create']);
$router->post('/teachers',              [TeacherController::class, 'store']);
$router->get('/teachers/{id}',          [TeacherController::class, 'show']);
$router->get('/teachers/{id}/edit',     [TeacherController::class, 'edit']);
$router->post('/teachers/{id}/update',  [TeacherController::class, 'update']);
$router->post('/teachers/{id}/delete',  [TeacherController::class, 'destroy']);

// Classes
$router->get('/classes',               [ClassController::class, 'index']);
$router->get('/classes/create',        [ClassController::class, 'create']);
$router->post('/classes',              [ClassController::class, 'store']);
$router->get('/classes/{id}',          [ClassController::class, 'show']);
$router->get('/classes/{id}/edit',     [ClassController::class, 'edit']);
$router->post('/classes/{id}/update',  [ClassController::class, 'update']);
$router->post('/classes/{id}/delete',  [ClassController::class, 'destroy']);

// Grades
$router->get('/grades',             [GradeController::class, 'index']);
$router->get('/grades/{classId}',   [GradeController::class, 'byClass']);
$router->get('/grades/{classId}/input', [GradeController::class, 'create']);
$router->post('/grades',            [GradeController::class, 'store']);
$router->get('/my-grades',          [GradeController::class, 'myGrades']);

// Assignments
$router->get('/assignments',                        [AssignmentController::class, 'index']);
$router->get('/assignments/create',                 [AssignmentController::class, 'create']);
$router->get('/assignments/subjects',               [AssignmentController::class, 'getSubjectsByClass']);
$router->post('/assignments',                       [AssignmentController::class, 'store']);
$router->get('/assignments/{id}',                   [AssignmentController::class, 'show']);
$router->get('/assignments/{id}/edit',              [AssignmentController::class, 'edit']);
$router->post('/assignments/{id}/update',           [AssignmentController::class, 'update']);
$router->post('/assignments/{id}/delete',           [AssignmentController::class, 'destroy']);
$router->post('/assignments/{id}/submit',           [AssignmentController::class, 'submit']);
$router->post('/submissions/{id}/grade',            [AssignmentController::class, 'gradeSubmission']);

// Attendance
$router->get('/attendance',                     [AttendanceController::class, 'index']);
$router->get('/attendance/create',              [AttendanceController::class, 'create']);
$router->post('/attendance',                    [AttendanceController::class, 'store']);
$router->get('/attendance/{id}',                [AttendanceController::class, 'show']);
$router->get('/attendance/{id}/fill',           [AttendanceController::class, 'fill']);
$router->post('/attendance/{id}/save',          [AttendanceController::class, 'save']);
$router->post('/attendance/{id}/delete',        [AttendanceController::class, 'destroy']);
$router->get('/attendance/rekap/{classId}',     [AttendanceController::class, 'rekap']);
