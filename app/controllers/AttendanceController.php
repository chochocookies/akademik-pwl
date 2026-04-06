<?php
class AttendanceController extends Controller {

    // ─── LIST sessions ───────────────────────────────────────────────
    public function index(): void {
        Middleware::auth();
        $user = Auth::user();

        if (Auth::is('murid')) {
            $student  = (new StudentModel())->findByUserId($user['id']);
            $summary  = (new AttendanceSessionModel())->getStudentSummary($student['id']);
            $overall  = (new AttendanceSessionModel())->getOverallStats($student['id']);
            $history  = (new AttendanceModel())->getStudentHistory($student['id'], 20);
            $this->view('attendance.murid_index', compact('student','summary','overall','history'));
            return;
        }

        $teacher  = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $sessions = (new AttendanceSessionModel())->allWithDetails($teacher['id'] ?? null);
        $classes  = $teacher ? (new TeacherModel())->getClasses($teacher['id']) : (new ClassModel())->allWithDetails();

        // Stats
        $stats = [
            'total_sesi'  => count($sessions),
            'bulan_ini'   => count(array_filter($sessions, fn($s)=>date('Y-m',strtotime($s['tanggal']))==date('Y-m'))),
        ];

        $this->view('attendance.index', compact('sessions','classes','stats','teacher'));
    }

    // ─── Create session form ─────────────────────────────────────────
    public function create(): void {
        Middleware::guru();
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $classes = (new TeacherModel())->getClasses($teacher['id']);
        $this->view('attendance.create', compact('teacher','classes'));
    }

    // ─── Store new session + pre-fill attendances ─────────────────────
    public function store(): void {
        Middleware::guru();
        verify_csrf();

        $v = Validator::make($_POST, [
            'class_id'   => 'required',
            'subject_id' => 'required',
            'tanggal'    => 'required',
        ]);
        if ($v->fails()) {
            Flash::set('error', $v->firstError());
            redirect('/attendance/create');
        }

        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);

        try {
            $this->db->beginTransaction();
            $sessionId = (new AttendanceSessionModel())->create([
                'class_id'    => (int)$this->post('class_id'),
                'teacher_id'  => $teacher['id'],
                'subject_id'  => (int)$this->post('subject_id'),
                'tanggal'     => $this->post('tanggal'),
                'keterangan'  => $this->post('keterangan'),
            ]);
            // Pre-fill all students as hadir
            $students = (new StudentModel())->byClass((int)$this->post('class_id'));
            foreach ($students as $s) {
                (new AttendanceModel())->create([
                    'session_id' => $sessionId,
                    'student_id' => $s['id'],
                    'status'     => 'hadir',
                ]);
            }
            $this->db->commit();
            Flash::set('success', 'Sesi absensi dibuat. Silakan isi kehadiran siswa.');
            redirect('/attendance/'.$sessionId.'/fill');
        } catch (\Exception $e) {
            $this->db->rollback();
            Flash::set('error', 'Gagal membuat sesi: '.$e->getMessage());
            redirect('/attendance/create');
        }
    }

    // ─── Fill attendance form ─────────────────────────────────────────
    public function fill(string $id): void {
        Middleware::guru();
        $session  = (new AttendanceSessionModel())->findWithDetails((int)$id);
        if (!$session) { Flash::set('error','Sesi tidak ditemukan.'); redirect('/attendance'); }
        $records  = (new AttendanceModel())->getBySession((int)$id);
        $students = (new StudentModel())->byClass($session['class_id']);
        $this->view('attendance.fill', compact('session','records','students'));
    }

    // ─── Save attendance records ──────────────────────────────────────
    public function save(string $id): void {
        Middleware::guru();
        verify_csrf();

        $session = (new AttendanceSessionModel())->find((int)$id);
        if (!$session) { Flash::set('error','Sesi tidak ditemukan.'); redirect('/attendance'); }

        $statuses  = $this->post('status', []);
        $attModel  = new AttendanceModel();

        foreach ($statuses as $studentId => $status) {
            $existing = $attModel->getByStudentAndSession((int)$studentId, (int)$id);
            if ($existing) {
                $attModel->update($existing['id'], ['status' => $status]);
            } else {
                $attModel->create(['session_id'=>(int)$id,'student_id'=>(int)$studentId,'status'=>$status]);
            }
        }

        // Notify alpha students
        foreach ($statuses as $studentId => $status) {
            if ($status === 'alpha') {
                $st = (new StudentModel())->find((int)$studentId);
                if ($st) NotificationModel::send($st['user_id'], 'absensi', '⚠️ Ketidakhadiran tercatat', 'Kamu tercatat ALPHA pada salah satu mata pelajaran hari ini.', url('/attendance'));
            }
        }
        Flash::set('success', 'Absensi berhasil disimpan.');
        redirect('/attendance/'.$id);
    }

    // ─── View session detail ──────────────────────────────────────────
    public function show(string $id): void {
        Middleware::auth();
        $session  = (new AttendanceSessionModel())->findWithDetails((int)$id);
        if (!$session) { Flash::set('error','Sesi tidak ditemukan.'); redirect('/attendance'); }
        $records  = (new AttendanceModel())->getBySession((int)$id);
        $this->view('attendance.show', compact('session','records'));
    }

    // ─── Delete session ───────────────────────────────────────────────
    public function destroy(string $id): void {
        Middleware::guru();
        verify_csrf();
        (new AttendanceSessionModel())->delete((int)$id);
        Flash::set('success', 'Sesi absensi dihapus.');
        redirect('/attendance');
    }

    // ─── Rekap per kelas ─────────────────────────────────────────────
    public function rekap(string $classId): void {
        Middleware::role('admin','guru');
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/attendance'); }
        $students = (new StudentModel())->byClass((int)$classId);
        $summaries = [];
        foreach ($students as $s) {
            $summaries[$s['id']] = (new AttendanceSessionModel())->getOverallStats($s['id']);
        }
        $this->view('attendance.rekap', compact('class','students','summaries'));
    }
}
