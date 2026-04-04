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
