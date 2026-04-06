<?php
// =============================================
// USER MODEL
// =============================================
class UserModel extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findWhere('email', $email);
    }

    public function allWithRole(string $role): array {
        return $this->db->fetchAll("SELECT * FROM users WHERE role = ? ORDER BY name", [$role]);
    }

    public function stats(): array {
        return $this->db->fetch("
            SELECT 
                COUNT(*) as total,
                SUM(role='admin') as admin,
                SUM(role='guru') as guru,
                SUM(role='murid') as murid
            FROM users
        ");
    }
}

// =============================================
// STUDENT MODEL
// =============================================
class StudentModel extends Model {
    protected string $table = 'students';

    public function allWithDetails(): array {
        return $this->db->fetchAll("
            SELECT s.*, u.name, u.email, u.is_active,
                   c.nama_kelas, c.tingkat
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN classes c ON s.class_id = c.id
            ORDER BY u.name
        ");
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT s.*, u.name, u.email, u.is_active,
                   c.nama_kelas, c.tingkat
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.id = ?
        ", [$id]);
    }

    public function findByUserId(int $userId): ?array {
        return $this->db->fetch("
            SELECT s.*, u.name, u.email,
                   c.nama_kelas, c.tingkat, c.id as class_id
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN classes c ON s.class_id = c.id
            WHERE s.user_id = ?
        ", [$userId]);
    }

    public function byClass(int $classId): array {
        return $this->db->fetchAll("
            SELECT s.*, u.name, u.email
            FROM students s
            JOIN users u ON s.user_id = u.id
            WHERE s.class_id = ?
            ORDER BY u.name
        ", [$classId]);
    }

    public function countByClass(): array {
        return $this->db->fetchAll("
            SELECT c.id, c.nama_kelas, COUNT(s.id) as total
            FROM classes c
            LEFT JOIN students s ON c.id = s.class_id
            GROUP BY c.id, c.nama_kelas
        ");
    }
}

// =============================================
// TEACHER MODEL
// =============================================
class TeacherModel extends Model {
    protected string $table = 'teachers';

    public function allWithDetails(): array {
        return $this->db->fetchAll("
            SELECT t.*, u.name, u.email, u.is_active,
                   GROUP_CONCAT(DISTINCT c.nama_kelas SEPARATOR ', ') as kelas_diampu
            FROM teachers t
            JOIN users u ON t.user_id = u.id
            LEFT JOIN teacher_subjects ts ON t.id = ts.teacher_id
            LEFT JOIN classes c ON ts.class_id = c.id
            GROUP BY t.id, u.name, u.email, u.is_active
            ORDER BY u.name
        ");
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT t.*, u.name, u.email
            FROM teachers t
            JOIN users u ON t.user_id = u.id
            WHERE t.id = ?
        ", [$id]);
    }

    public function findByUserId(int $userId): ?array {
        return $this->db->fetch("
            SELECT t.*, u.name, u.email
            FROM teachers t
            JOIN users u ON t.user_id = u.id
            WHERE t.user_id = ?
        ", [$userId]);
    }

    public function getClasses(int $teacherId): array {
        return $this->db->fetchAll("
            SELECT DISTINCT c.*, COUNT(s.id) as jumlah_siswa
            FROM teacher_subjects ts
            JOIN classes c ON ts.class_id = c.id
            LEFT JOIN students s ON c.id = s.class_id
            WHERE ts.teacher_id = ?
            GROUP BY c.id
        ", [$teacherId]);
    }

    public function getSubjectsByClass(int $teacherId, int $classId): array {
        return $this->db->fetchAll("
            SELECT s.* FROM teacher_subjects ts
            JOIN subjects s ON ts.subject_id = s.id
            WHERE ts.teacher_id = ? AND ts.class_id = ?
        ", [$teacherId, $classId]);
    }
}

// =============================================
// CLASS MODEL
// =============================================
class ClassModel extends Model {
    protected string $table = 'classes';

    public function allWithDetails(): array {
        return $this->db->fetchAll("
            SELECT c.*, u.name as wali_kelas_name,
                   COUNT(s.id) as jumlah_siswa
            FROM classes c
            LEFT JOIN users u ON c.wali_kelas_id = u.id
            LEFT JOIN students s ON c.id = s.class_id
            GROUP BY c.id
            ORDER BY c.tingkat, c.nama_kelas
        ");
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT c.*, u.name as wali_kelas_name
            FROM classes c
            LEFT JOIN users u ON c.wali_kelas_id = u.id
            WHERE c.id = ?
        ", [$id]);
    }
}

// =============================================
// SUBJECT MODEL
// =============================================
class SubjectModel extends Model {
    protected string $table = 'subjects';
}

// =============================================
// GRADE MODEL
// =============================================
class GradeModel extends Model {
    protected string $table = 'grades';

    public function getStudentGrades(int $studentId, string $semester = null, string $tahunAjaran = null): array {
        $sql = "
            SELECT g.*, s.nama_mapel, s.kode_mapel, u.name as guru_name
            FROM grades g
            JOIN subjects s ON g.subject_id = s.id
            JOIN teachers t ON g.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE g.student_id = ?
        ";
        $params = [$studentId];
        if ($semester) { $sql .= " AND g.semester = ?"; $params[] = $semester; }
        if ($tahunAjaran) { $sql .= " AND g.tahun_ajaran = ?"; $params[] = $tahunAjaran; }
        $sql .= " ORDER BY s.nama_mapel";
        return $this->db->fetchAll($sql, $params);
    }

    public function getClassGrades(int $classId, int $subjectId, string $semester, string $tahunAjaran): array {
        return $this->db->fetchAll("
            SELECT g.*, u.name as student_name, st.nis
            FROM students st
            JOIN users u ON st.user_id = u.id
            LEFT JOIN grades g ON (g.student_id = st.id AND g.subject_id = ? AND g.semester = ? AND g.tahun_ajaran = ?)
            WHERE st.class_id = ?
            ORDER BY u.name
        ", [$subjectId, $semester, $tahunAjaran, $classId]);
    }

    public function averageByClass(int $classId): ?array {
        return $this->db->fetch("
            SELECT 
                AVG(g.nilai_akhir) as rata_rata,
                MAX(g.nilai_akhir) as tertinggi,
                MIN(g.nilai_akhir) as terendah
            FROM grades g
            JOIN students s ON g.student_id = s.id
            WHERE s.class_id = ?
        ", [$classId]);
    }

    public function globalAverage(): float {
        return (float)$this->db->query("SELECT AVG(nilai_akhir) FROM grades")->fetchColumn();
    }

    public function upsert(array $data): int {
        $existing = $this->db->fetch(
            "SELECT id FROM grades WHERE student_id = ? AND subject_id = ? AND semester = ? AND tahun_ajaran = ?",
            [$data['student_id'], $data['subject_id'], $data['semester'], $data['tahun_ajaran']]
        );
        if ($existing) {
            $this->update($existing['id'], $data);
            return $existing['id'];
        }
        return $this->create($data);
    }
}

// =============================================
// ASSIGNMENT MODEL
// =============================================
class AssignmentModel extends Model {
    protected string $table = 'assignments';

    public function allWithDetails(?int $teacherId = null): array {
        $sql = "
            SELECT a.*, u.name as guru_name, c.nama_kelas, s.nama_mapel,
                   COUNT(sub.id) as total_submissions
            FROM assignments a
            JOIN teachers t ON a.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            JOIN classes c ON a.class_id = c.id
            JOIN subjects s ON a.subject_id = s.id
            LEFT JOIN submissions sub ON a.id = sub.assignment_id
        ";
        $params = [];
        if ($teacherId) { $sql .= " WHERE a.teacher_id = ?"; $params[] = $teacherId; }
        $sql .= " GROUP BY a.id ORDER BY a.deadline ASC";
        return $this->db->fetchAll($sql, $params);
    }

    public function byClass(int $classId): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as guru_name, s.nama_mapel,
                   COUNT(sub.id) as total_submissions
            FROM assignments a
            JOIN teachers t ON a.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            JOIN subjects s ON a.subject_id = s.id
            LEFT JOIN submissions sub ON a.id = sub.assignment_id
            WHERE a.class_id = ?
            GROUP BY a.id
            ORDER BY a.deadline ASC
        ", [$classId]);
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT a.*, u.name as guru_name, c.nama_kelas, s.nama_mapel
            FROM assignments a
            JOIN teachers t ON a.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            JOIN classes c ON a.class_id = c.id
            JOIN subjects s ON a.subject_id = s.id
            WHERE a.id = ?
        ", [$id]);
    }
}

// =============================================
// SUBMISSION MODEL
// =============================================
class SubmissionModel extends Model {
    protected string $table = 'submissions';

    public function findByAssignmentAndStudent(int $assignmentId, int $studentId): ?array {
        return $this->db->fetch(
            "SELECT * FROM submissions WHERE assignment_id = ? AND student_id = ?",
            [$assignmentId, $studentId]
        );
    }

    public function getByAssignment(int $assignmentId): array {
        return $this->db->fetchAll("
            SELECT sub.*, u.name as student_name, st.nis
            FROM submissions sub
            JOIN students st ON sub.student_id = st.id
            JOIN users u ON st.user_id = u.id
            WHERE sub.assignment_id = ?
            ORDER BY u.name
        ", [$assignmentId]);
    }

    public function getByStudent(int $studentId): array {
        return $this->db->fetchAll("
            SELECT sub.*, a.judul, a.deadline, s.nama_mapel
            FROM submissions sub
            JOIN assignments a ON sub.assignment_id = a.id
            JOIN subjects s ON a.subject_id = s.id
            WHERE sub.student_id = ?
            ORDER BY sub.submitted_at DESC
        ", [$studentId]);
    }
}

// =============================================
// ATTENDANCE SESSION MODEL
// =============================================
class AttendanceSessionModel extends Model {
    protected string $table = 'attendance_sessions';

    public function allWithDetails(?int $teacherId = null, ?int $classId = null): array {
        $sql = "
            SELECT s.*, c.nama_kelas, c.tingkat, sub.nama_mapel, sub.kode_mapel,
                   u.name as guru_name,
                   COUNT(a.id) as total_records,
                   SUM(a.status='hadir') as hadir,
                   SUM(a.status='sakit') as sakit,
                   SUM(a.status='izin') as izin,
                   SUM(a.status='alpha') as alpha
            FROM attendance_sessions s
            JOIN classes c ON s.class_id = c.id
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN teachers t ON s.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            LEFT JOIN attendances a ON a.session_id = s.id
            WHERE 1=1
        ";
        $params = [];
        if ($teacherId) { $sql .= " AND s.teacher_id = ?"; $params[] = $teacherId; }
        if ($classId)   { $sql .= " AND s.class_id = ?";   $params[] = $classId; }
        $sql .= " GROUP BY s.id ORDER BY s.tanggal DESC";
        return $this->db->fetchAll($sql, $params);
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT s.*, c.nama_kelas, c.tingkat, sub.nama_mapel, u.name as guru_name
            FROM attendance_sessions s
            JOIN classes c ON s.class_id = c.id
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN teachers t ON s.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE s.id = ?
        ", [$id]);
    }

    public function getStudentSummary(int $studentId, ?int $classId = null): array {
        $sql = "
            SELECT sub.nama_mapel, sub.kode_mapel,
                   COUNT(a.id) as total,
                   SUM(a.status='hadir') as hadir,
                   SUM(a.status='sakit') as sakit,
                   SUM(a.status='izin') as izin,
                   SUM(a.status='alpha') as alpha,
                   ROUND(SUM(a.status='hadir')/COUNT(a.id)*100,1) as pct_hadir
            FROM attendances a
            JOIN attendance_sessions s ON a.session_id = s.id
            JOIN subjects sub ON s.subject_id = sub.id
            WHERE a.student_id = ?
        ";
        $params = [$studentId];
        if ($classId) { $sql .= " AND s.class_id = ?"; $params[] = $classId; }
        $sql .= " GROUP BY sub.id ORDER BY sub.nama_mapel";
        return $this->db->fetchAll($sql, $params);
    }

    public function getOverallStats(int $studentId): array {
        $row = $this->db->fetch("
            SELECT COUNT(a.id) as total,
                   SUM(a.status='hadir') as hadir,
                   SUM(a.status='sakit') as sakit,
                   SUM(a.status='izin') as izin,
                   SUM(a.status='alpha') as alpha
            FROM attendances a WHERE a.student_id = ?
        ", [$studentId]);
        $row = $row ?: ['total'=>0,'hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];
        $row['pct_hadir'] = $row['total'] > 0 ? round($row['hadir']/$row['total']*100, 1) : 0;
        return $row;
    }
}

// =============================================
// ATTENDANCE MODEL
// =============================================
class AttendanceModel extends Model {
    protected string $table = 'attendances';

    public function getBySession(int $sessionId): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as student_name, st.nis
            FROM students st
            JOIN users u ON st.user_id = u.id
            LEFT JOIN attendances a ON (a.student_id = st.id AND a.session_id = ?)
            JOIN attendance_sessions sess ON sess.class_id = st.class_id
            WHERE sess.id = ?
            ORDER BY u.name
        ", [$sessionId, $sessionId]);
    }

    public function getByStudentAndSession(int $studentId, int $sessionId): ?array {
        return $this->db->fetch(
            "SELECT * FROM attendances WHERE student_id=? AND session_id=?",
            [$studentId, $sessionId]
        );
    }

    public function upsertBulk(int $sessionId, array $records): void {
        foreach ($records as $studentId => $status) {
            $existing = $this->getByStudentAndSession($studentId, $sessionId);
            $data = ['session_id'=>$sessionId,'student_id'=>(int)$studentId,'status'=>$status,'catatan'=>null];
            if ($existing) $this->update($existing['id'], ['status'=>$status]);
            else $this->create($data);
        }
    }

    public function getStudentHistory(int $studentId, int $limit = 30): array {
        return $this->db->fetchAll("
            SELECT a.*, s.tanggal, sub.nama_mapel, c.nama_kelas
            FROM attendances a
            JOIN attendance_sessions s ON a.session_id = s.id
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN classes c ON s.class_id = c.id
            WHERE a.student_id = ?
            ORDER BY s.tanggal DESC
            LIMIT $limit
        ", [$studentId]);
    }
}

// =============================================
// REPORT NOTE MODEL
// =============================================
class ReportNoteModel extends Model {
    protected string $table = 'report_notes';

    public function findByStudent(int $studentId, string $semester, string $tahunAjaran): ?array {
        return $this->db->fetch(
            "SELECT * FROM report_notes WHERE student_id=? AND semester=? AND tahun_ajaran=?",
            [$studentId, $semester, $tahunAjaran]
        );
    }

    public function upsert(array $data): void {
        $existing = $this->findByStudent($data['student_id'], $data['semester'], $data['tahun_ajaran']);
        if ($existing) $this->update($existing['id'], $data);
        else $this->create($data);
    }

    public function getClassReport(int $classId, string $semester, string $tahunAjaran): array {
        return $this->db->fetchAll("
            SELECT s.id as student_id, u.name, st.nis, st.gender, st.birth_date,
                   rn.catatan_wali, rn.catatan_kepala, rn.predikat_sikap,
                   rn.predikat_keterampilan, rn.ranking,
                   ROUND(AVG(g.nilai_akhir),2) as rata_nilai,
                   COUNT(g.id) as jumlah_mapel,
                   SUM(CASE WHEN att.status='hadir' THEN 1 ELSE 0 END) as total_hadir,
                   SUM(CASE WHEN att.status='sakit' THEN 1 ELSE 0 END) as total_sakit,
                   SUM(CASE WHEN att.status='izin'  THEN 1 ELSE 0 END) as total_izin,
                   SUM(CASE WHEN att.status='alpha' THEN 1 ELSE 0 END) as total_alpha
            FROM students s
            JOIN users u ON s.user_id = u.id
            JOIN students st ON s.id = st.id
            LEFT JOIN grades g ON (g.student_id=s.id AND g.semester=? AND g.tahun_ajaran=?)
            LEFT JOIN attendances att ON att.student_id=s.id
            LEFT JOIN attendance_sessions ases ON (att.session_id=ases.id AND ases.class_id=?)
            LEFT JOIN report_notes rn ON (rn.student_id=s.id AND rn.semester=? AND rn.tahun_ajaran=?)
            WHERE s.class_id=?
            GROUP BY s.id, u.name, st.nis, st.gender, st.birth_date,
                     rn.catatan_wali, rn.catatan_kepala, rn.predikat_sikap,
                     rn.predikat_keterampilan, rn.ranking
            ORDER BY u.name
        ", [$semester, $tahunAjaran, $classId, $semester, $tahunAjaran, $classId]);
    }

    public function getDetailForRapor(int $studentId, string $semester, string $tahunAjaran): array {
        $grades = (new GradeModel())->getStudentGrades($studentId, $semester, $tahunAjaran);
        $note   = $this->findByStudent($studentId, $semester, $tahunAjaran);
        $student = (new StudentModel())->findWithDetails($studentId);
        $absStats = (new AttendanceSessionModel())->getOverallStats($studentId);
        return compact('grades','note','student','absStats');
    }
}

// =============================================
// CALENDAR MODEL
// =============================================
class CalendarModel extends Model {
    protected string $table = 'academic_calendar';

    public function getByMonth(int $year, int $month): array {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end   = date('Y-m-t', strtotime($start));
        return $this->db->fetchAll("
            SELECT * FROM academic_calendar
            WHERE tanggal_mulai <= ? AND tanggal_selesai >= ?
            ORDER BY tanggal_mulai
        ", [$end, $start]);
    }

    public function getUpcoming(int $limit = 5): array {
        return $this->db->fetchAll("
            SELECT * FROM academic_calendar
            WHERE tanggal_selesai >= CURDATE()
            ORDER BY tanggal_mulai LIMIT $limit
        ");
    }

    public function allWithCreator(): array {
        return $this->db->fetchAll("
            SELECT c.*, u.name as created_by_name
            FROM academic_calendar c
            JOIN users u ON c.created_by = u.id
            ORDER BY c.tanggal_mulai DESC
        ");
    }
}

// =============================================
// SCHEDULE MODEL
// =============================================
class ScheduleModel extends Model {
    protected string $table = 'schedules';

    public function getByClass(int $classId): array {
        return $this->db->fetchAll("
            SELECT s.*, sub.nama_mapel, sub.kode_mapel, u.name as guru_name
            FROM schedules s
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN teachers t ON s.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE s.class_id = ?
            ORDER BY FIELD(s.hari,'senin','selasa','rabu','kamis','jumat','sabtu'), s.jam_mulai
        ", [$classId]);
    }

    public function getByTeacher(int $teacherId): array {
        return $this->db->fetchAll("
            SELECT s.*, sub.nama_mapel, c.nama_kelas
            FROM schedules s
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN classes c ON s.class_id = c.id
            WHERE s.teacher_id = ?
            ORDER BY FIELD(s.hari,'senin','selasa','rabu','kamis','jumat','sabtu'), s.jam_mulai
        ", [$teacherId]);
    }

    public function getTodaySchedule(int $classId): array {
        $hariIni = ['Sunday'=>'minggu','Monday'=>'senin','Tuesday'=>'selasa',
                    'Wednesday'=>'rabu','Thursday'=>'kamis','Friday'=>'jumat','Saturday'=>'sabtu'][date('l')];
        return $this->db->fetchAll("
            SELECT s.*, sub.nama_mapel, u.name as guru_name
            FROM schedules s
            JOIN subjects sub ON s.subject_id = sub.id
            JOIN teachers t ON s.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE s.class_id=? AND s.hari=?
            ORDER BY s.jam_mulai
        ", [$classId, $hariIni]);
    }
}

// =============================================
// TEACHING JOURNAL MODEL
// =============================================
class TeachingJournalModel extends Model {
    protected string $table = 'teaching_journals';

    public function allWithDetails(?int $teacherId = null): array {
        $sql = "
            SELECT j.*, c.nama_kelas, s.nama_mapel, s.kode_mapel,
                   u.name as guru_name,
                   ases.tanggal as sesi_tanggal
            FROM teaching_journals j
            JOIN classes c ON j.class_id = c.id
            JOIN subjects s ON j.subject_id = s.id
            JOIN teachers t ON j.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            LEFT JOIN attendance_sessions ases ON j.attendance_session_id = ases.id
            WHERE 1=1
        ";
        $params = [];
        if ($teacherId) { $sql .= " AND j.teacher_id=?"; $params[] = $teacherId; }
        $sql .= " ORDER BY j.tanggal DESC, j.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    public function findWithDetails(int $id): ?array {
        return $this->db->fetch("
            SELECT j.*, c.nama_kelas, s.nama_mapel, u.name as guru_name
            FROM teaching_journals j
            JOIN classes c ON j.class_id = c.id
            JOIN subjects s ON j.subject_id = s.id
            JOIN teachers t ON j.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE j.id=?
        ", [$id]);
    }

    public function getByClass(int $classId, ?int $subjectId = null): array {
        $sql = "
            SELECT j.*, s.nama_mapel, u.name as guru_name
            FROM teaching_journals j
            JOIN subjects s ON j.subject_id = s.id
            JOIN teachers t ON j.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            WHERE j.class_id=?
        ";
        $params = [$classId];
        if ($subjectId) { $sql .= " AND j.subject_id=?"; $params[] = $subjectId; }
        $sql .= " ORDER BY j.tanggal DESC";
        return $this->db->fetchAll($sql, $params);
    }
}

// =============================================
// NOTIFICATION MODEL
// =============================================
class NotificationModel extends Model {
    protected string $table = 'notifications';

    public function getForUser(int $userId, int $limit = 20): array {
        return $this->db->fetchAll("
            SELECT * FROM notifications
            WHERE user_id=?
            ORDER BY created_at DESC
            LIMIT $limit
        ", [$userId]);
    }

    public function countUnread(int $userId): int {
        return $this->db->count(
            "SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0",
            [$userId]
        );
    }

    public function markRead(int $id, int $userId): void {
        $this->db->query("UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?", [$id, $userId]);
    }

    public function markAllRead(int $userId): void {
        $this->db->query("UPDATE notifications SET is_read=1 WHERE user_id=?", [$userId]);
    }

    public static function send(int $userId, string $tipe, string $judul, string $pesan = '', string $url = ''): void {
        $db = Database::getInstance();
        $db->insert('notifications', [
            'user_id' => $userId,
            'tipe'    => $tipe,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'url'     => $url,
            'is_read' => 0,
        ]);
    }

    public static function sendToRole(string $role, string $tipe, string $judul, string $pesan = '', string $url = ''): void {
        $db = Database::getInstance();
        $users = $db->fetchAll("SELECT id FROM users WHERE role=? AND is_active=1", [$role]);
        foreach ($users as $u) {
            self::send($u['id'], $tipe, $judul, $pesan, $url);
        }
    }

    public static function sendToClass(int $classId, string $tipe, string $judul, string $pesan = '', string $url = ''): void {
        $db = Database::getInstance();
        $students = $db->fetchAll("
            SELECT u.id FROM students s JOIN users u ON s.user_id=u.id
            WHERE s.class_id=? AND u.is_active=1
        ", [$classId]);
        foreach ($students as $u) {
            self::send($u['id'], $tipe, $judul, $pesan, $url);
        }
    }
}

// =============================================
// ANNOUNCEMENT MODEL
// =============================================
class AnnouncementModel extends Model {
    protected string $table = 'announcements';

    public function getVisible(string $role): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name
            FROM announcements a
            JOIN users u ON a.user_id = u.id
            WHERE (a.target_role='all' OR a.target_role=?)
              AND a.published_at <= NOW()
              AND (a.expired_at IS NULL OR a.expired_at >= NOW())
            ORDER BY a.is_pinned DESC, a.published_at DESC
        ", [$role]);
    }

    public function allWithAuthor(): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name
            FROM announcements a
            JOIN users u ON a.user_id = u.id
            ORDER BY a.is_pinned DESC, a.created_at DESC
        ");
    }

    public function findWithAuthor(int $id): ?array {
        return $this->db->fetch("
            SELECT a.*, u.name as author_name
            FROM announcements a
            JOIN users u ON a.user_id = u.id
            WHERE a.id=?
        ", [$id]);
    }

    public function getRecent(string $role, int $limit = 3): array {
        return $this->db->fetchAll("
            SELECT * FROM announcements
            WHERE (target_role='all' OR target_role=?)
              AND published_at <= NOW()
              AND (expired_at IS NULL OR expired_at >= NOW())
            ORDER BY is_pinned DESC, published_at DESC
            LIMIT $limit
        ", [$role]);
    }
}
