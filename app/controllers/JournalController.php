<?php
class JournalController extends Controller {

    public function index(): void {
        Middleware::role('admin','guru');
        $user    = Auth::user();
        $teacher = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $journals = (new TeachingJournalModel())->allWithDetails($teacher['id'] ?? null);
        $classes  = $teacher ? (new TeacherModel())->getClasses($teacher['id']) : (new ClassModel())->allWithDetails();
        $stats    = [
            'total'    => count($journals),
            'bulan_ini'=> count(array_filter($journals, fn($j)=>date('Y-m',strtotime($j['tanggal']))==date('Y-m'))),
        ];
        $this->view('journals.index', compact('journals','classes','stats','teacher'));
    }

    public function create(): void {
        Middleware::guru();
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $classes = (new TeacherModel())->getClasses($teacher['id']);
        // Get recent attendance sessions (for linking)
        $sessions = $this->db->fetchAll("
            SELECT ases.*, c.nama_kelas, s.nama_mapel
            FROM attendance_sessions ases
            JOIN classes c ON ases.class_id=c.id
            JOIN subjects s ON ases.subject_id=s.id
            WHERE ases.teacher_id=?
            ORDER BY ases.tanggal DESC LIMIT 20
        ", [$teacher['id']]);
        $this->view('journals.create', compact('teacher','classes','sessions'));
    }

    public function store(): void {
        Middleware::guru();
        verify_csrf();
        $v = Validator::make($_POST, ['class_id'=>'required','subject_id'=>'required','tanggal'=>'required','materi_pokok'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/journals/create'); }

        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);

        (new TeachingJournalModel())->create([
            'attendance_session_id' => $this->post('attendance_session_id') ?: null,
            'teacher_id'            => $teacher['id'],
            'class_id'              => (int)$this->post('class_id'),
            'subject_id'            => (int)$this->post('subject_id'),
            'tanggal'               => $this->post('tanggal'),
            'materi_pokok'          => $this->post('materi_pokok'),
            'materi_detail'         => $this->post('materi_detail'),
            'metode'                => $this->post('metode'),
            'media'                 => $this->post('media'),
            'catatan'               => $this->post('catatan'),
        ]);
        Flash::set('success','Jurnal mengajar berhasil disimpan.');
        redirect('/journals');
    }

    public function show(string $id): void {
        Middleware::role('admin','guru');
        $journal = (new TeachingJournalModel())->findWithDetails((int)$id);
        if (!$journal) { Flash::set('error','Jurnal tidak ditemukan.'); redirect('/journals'); }
        $this->view('journals.show', compact('journal'));
    }

    public function edit(string $id): void {
        Middleware::guru();
        $journal = (new TeachingJournalModel())->find((int)$id);
        if (!$journal) { Flash::set('error','Jurnal tidak ditemukan.'); redirect('/journals'); }
        $user    = Auth::user();
        $teacher = (new TeacherModel())->findByUserId($user['id']);
        $classes = (new TeacherModel())->getClasses($teacher['id']);
        $subjects = (new TeacherModel())->getSubjectsByClass($teacher['id'], $journal['class_id']);
        $this->view('journals.edit', compact('journal','teacher','classes','subjects'));
    }

    public function update(string $id): void {
        Middleware::guru();
        verify_csrf();
        (new TeachingJournalModel())->update((int)$id, [
            'tanggal'      => $this->post('tanggal'),
            'materi_pokok' => $this->post('materi_pokok'),
            'materi_detail'=> $this->post('materi_detail'),
            'metode'       => $this->post('metode'),
            'media'        => $this->post('media'),
            'catatan'      => $this->post('catatan'),
        ]);
        Flash::set('success','Jurnal diperbarui.');
        redirect('/journals');
    }

    public function destroy(string $id): void {
        Middleware::role('admin','guru');
        verify_csrf();
        (new TeachingJournalModel())->delete((int)$id);
        Flash::set('success','Jurnal dihapus.');
        redirect('/journals');
    }
}
