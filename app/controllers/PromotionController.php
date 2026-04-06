<?php
class PromotionController extends Controller {

    private function getNextClass(int $currentTingkat, string $currentName): ?array {
        if ($currentTingkat >= 6) return null; // Kelas 6 → alumni
        $nextTingkat = $currentTingkat + 1;
        // Try to find a class with the same letter suffix (e.g., 4A → 5A)
        preg_match('/[A-Za-z]$/', $currentName, $matches);
        $suffix = $matches[0] ?? 'A';
        $nextClass = $this->db->fetch(
            "SELECT * FROM classes WHERE tingkat=? AND nama_kelas LIKE ? LIMIT 1",
            [$nextTingkat, '%'.$suffix]
        );
        // Fallback: any class with next tingkat
        if (!$nextClass) {
            $nextClass = $this->db->fetch("SELECT * FROM classes WHERE tingkat=? LIMIT 1", [$nextTingkat]);
        }
        return $nextClass;
    }

    public function index(): void {
        Middleware::admin();
        $classes = (new ClassModel())->allWithDetails();
        $this->view('promotions.index', compact('classes'));
    }

    public function byClass(string $classId): void {
        Middleware::admin();
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/promotions'); }

        $students     = (new StudentModel())->byClass((int)$classId);
        $nextClass    = $this->getNextClass((int)$class['tingkat'], $class['nama_kelas']);
        $isGraduating = (int)$class['tingkat'] === 6;

        // Calculate average grade per student
        $studentData = [];
        foreach ($students as $s) {
            $grades  = (new GradeModel())->getStudentGrades($s['id'], SEMESTER, TAHUN_AJARAN);
            $avg     = count($grades) ? round(array_sum(array_column($grades,'nilai_akhir'))/count($grades),1) : 0;
            $autoNaik= $avg >= 70;
            $studentData[] = array_merge($s, [
                'avg_nilai'  => $avg,
                'auto_naik'  => $autoNaik,
                'jumlah_mapel'=> count($grades),
            ]);
        }
        $this->view('promotions.by_class', compact('class','studentData','nextClass','isGraduating'));
    }

    public function process(string $classId): void {
        Middleware::admin();
        verify_csrf();

        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/promotions'); }

        $decisions  = $this->post('decision', []); // [student_id => 'naik'|'tidak'|'alumni']
        $nextClassId= $this->post('next_class_id');
        $isGraduating = (int)$class['tingkat'] === 6;

        $naik = 0; $tidak = 0; $alumni = 0;

        $this->db->beginTransaction();
        try {
            foreach ($decisions as $studentId => $decision) {
                $studentId = (int)$studentId;
                if ($decision === 'naik' && !$isGraduating) {
                    (new StudentModel())->update($studentId, ['class_id' => (int)$nextClassId]);
                    $naik++;
                } elseif ($decision === 'tidak') {
                    // Tetap di kelas yang sama (tinggal kelas)
                    $tidak++;
                } elseif ($decision === 'alumni' || ($isGraduating && $decision === 'naik')) {
                    (new StudentModel())->update($studentId, [
                        'class_id' => null,
                        'status'   => 'alumni',
                        'angkatan' => TAHUN_AJARAN,
                    ]);
                    $alumni++;
                    // Deactivate user account
                    $student = (new StudentModel())->find($studentId);
                    if ($student) (new UserModel())->update($student['user_id'], ['is_active' => 0]);
                }
            }
            $this->db->commit();
            Flash::set('success', "Promosi selesai: {$naik} naik kelas, {$tidak} tinggal kelas, {$alumni} lulus/alumni.");
        } catch (\Exception $e) {
            $this->db->rollback();
            Flash::set('error','Gagal memproses: '.$e->getMessage());
        }
        redirect('/promotions');
    }
}
