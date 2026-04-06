<?php
class ReportController extends Controller {

    // ── List kelas untuk pilih rapor ─────────────────────────────────
    public function index(): void {
        Middleware::role('admin','guru');
        $user  = Auth::user();
        $teacher = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $classes = $teacher ? (new TeacherModel())->getClasses($teacher['id']) : (new ClassModel())->allWithDetails();
        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $this->view('reports.index', compact('classes','semester','tahunAjaran'));
    }

    // ── Daftar siswa satu kelas + status rapor ────────────────────────
    public function byClass(string $classId): void {
        Middleware::role('admin','guru');
        $class       = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/reports'); }
        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $students    = (new ReportNoteModel())->getClassReport((int)$classId, $semester, $tahunAjaran);
        $this->view('reports.by_class', compact('class','students','semester','tahunAjaran'));
    }

    // ── Preview rapor 1 siswa ─────────────────────────────────────────
    public function preview(string $studentId): void {
        Middleware::role('admin','guru','murid');

        // Murid hanya bisa lihat raportnya sendiri
        if (Auth::is('murid')) {
            $me = (new StudentModel())->findByUserId(Auth::id());
            if (!$me || $me['id'] != (int)$studentId) {
                Flash::set('error','Akses ditolak.'); redirect('/dashboard');
            }
        }

        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $data = (new ReportNoteModel())->getDetailForRapor((int)$studentId, $semester, $tahunAjaran);
        if (!$data['student']) { Flash::set('error','Siswa tidak ditemukan.'); redirect('/reports'); }

        // Grade info
        $avgNilai = count($data['grades'])
            ? round(array_sum(array_column($data['grades'],'nilai_akhir')) / count($data['grades']), 2)
            : 0;
        $predikat = $avgNilai >= 90 ? 'A' : ($avgNilai >= 80 ? 'B' : ($avgNilai >= 70 ? 'C' : 'D'));

        $this->view('reports.preview', compact('data','semester','tahunAjaran','avgNilai','predikat'));
    }

    // ── Download/Print PDF (via browser print) ────────────────────────
    public function pdf(string $studentId): void {
        Middleware::role('admin','guru','murid');

        if (Auth::is('murid')) {
            $me = (new StudentModel())->findByUserId(Auth::id());
            if (!$me || $me['id'] != (int)$studentId) {
                Flash::set('error','Akses ditolak.'); redirect('/dashboard');
            }
        }

        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $data = (new ReportNoteModel())->getDetailForRapor((int)$studentId, $semester, $tahunAjaran);
        if (!$data['student']) { Flash::set('error','Siswa tidak ditemukan.'); redirect('/reports'); }

        $avgNilai = count($data['grades'])
            ? round(array_sum(array_column($data['grades'],'nilai_akhir')) / count($data['grades']), 2)
            : 0;
        $predikat = $avgNilai >= 90 ? 'A' : ($avgNilai >= 80 ? 'B' : ($avgNilai >= 70 ? 'C' : 'D'));

        // Render print template (opens in new tab, user prints to PDF)
        $this->view('reports.template', compact('data','semester','tahunAjaran','avgNilai','predikat'));
    }

    // ── Simpan catatan wali kelas ──────────────────────────────────────
    public function saveNote(string $studentId): void {
        Middleware::role('admin','guru');
        verify_csrf();

        (new ReportNoteModel())->upsert([
            'student_id'             => (int)$studentId,
            'semester'               => $this->post('semester', SEMESTER),
            'tahun_ajaran'           => TAHUN_AJARAN,
            'catatan_wali'           => $this->post('catatan_wali'),
            'catatan_kepala'         => $this->post('catatan_kepala'),
            'predikat_sikap'         => $this->post('predikat_sikap', 'B'),
            'predikat_keterampilan'  => $this->post('predikat_keterampilan', 'B'),
            'ranking'                => $this->post('ranking') ?: null,
            'created_by'             => Auth::id(),
        ]);
        Flash::set('success', 'Catatan rapor berhasil disimpan.');
        redirect('/reports/'.$this->post('class_id').'/preview/'.((int)$studentId).'?semester='.$this->post('semester', SEMESTER));
    }
}
