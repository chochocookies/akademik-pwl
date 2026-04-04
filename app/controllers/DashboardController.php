<?php
class DashboardController extends Controller {

    // =============================================
    // ADMIN DASHBOARD
    // =============================================
    public function admin(): void {
        Middleware::admin();

        $userModel    = new UserModel();
        $studentModel = new StudentModel();
        $gradeModel   = new GradeModel();

        $stats = [
            'total_siswa'  => $studentModel->count(),
            'total_guru'   => (new TeacherModel())->count(),
            'total_kelas'  => (new ClassModel())->count(),
            'total_mapel'  => (new SubjectModel())->count(),
            'rata_nilai'   => round($gradeModel->globalAverage(), 1),
            'user_stats'   => $userModel->stats(),
        ];

        $recentStudents = (new StudentModel())->allWithDetails();
        $recentStudents = array_slice($recentStudents, 0, 5);

        $classSummary = (new StudentModel())->countByClass();
        $recentSessions = (new AttendanceSessionModel())->allWithDetails(null, null);
        $recentSessions = array_slice($recentSessions, 0, 5);
        $totalSessions  = count((new AttendanceSessionModel())->allWithDetails());
        $stats['total_sesi'] = $totalSessions;

        $this->view('dashboard.admin', compact('stats', 'recentStudents', 'classSummary', 'recentSessions'));
    }

    // =============================================
    // GURU DASHBOARD
    // =============================================
    public function guru(): void {
        Middleware::guru();

        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);

        if (!$teacher) {
            Flash::set('error', 'Data guru tidak ditemukan. Hubungi admin.');
            redirect('/login');
        }

        $classes     = (new TeacherModel())->getClasses($teacher['id']);
        $assignments = (new AssignmentModel())->allWithDetails($teacher['id']);
        $assignments = array_slice($assignments, 0, 5);

        $totalStudents = 0;
        foreach ($classes as $c) $totalStudents += $c['jumlah_siswa'];

        $stats = [
            'total_kelas'    => count($classes),
            'total_siswa'    => $totalStudents,
            'total_tugas'    => count((new AssignmentModel())->allWithDetails($teacher['id'])),
        ];

        $this->view('dashboard.guru', compact('teacher', 'classes', 'assignments', 'stats'));
    }

    // =============================================
    // MURID DASHBOARD
    // =============================================
    public function murid(): void {
        Middleware::murid();

        $user    = Auth::user();
        $student = (new StudentModel())->findByUserId($user['id']);

        if (!$student) {
            Flash::set('error', 'Data murid tidak ditemukan. Hubungi admin.');
            redirect('/login');
        }

        $grades      = (new GradeModel())->getStudentGrades($student['id'], SEMESTER, TAHUN_AJARAN);
        $assignments = (new AssignmentModel())->byClass($student['class_id'] ?? 0);
        $submissions = (new SubmissionModel())->getByStudent($student['id']);

        $submittedIds = array_column($submissions, 'assignment_id');
        $avgGrade = count($grades) ? round(array_sum(array_column($grades, 'nilai_akhir')) / count($grades), 1) : 0;

        $stats = [
            'total_nilai'    => count($grades),
            'rata_nilai'     => $avgGrade,
            'total_tugas'    => count($assignments),
            'tugas_selesai'  => count($submissions),
        ];

        $this->view('dashboard.murid', compact('student', 'grades', 'assignments', 'submissions', 'submittedIds', 'stats'));
    }
}
