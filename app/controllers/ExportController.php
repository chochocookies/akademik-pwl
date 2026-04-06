<?php
/**
 * Export Controller
 * Exports data ke format Excel (CSV) dan PDF (print template)
 * Tidak memerlukan library external - menggunakan CSV native PHP dan HTML print
 */
class ExportController extends Controller {

    // ── Helper: send CSV ─────────────────────────────────────────────
    private function sendCsv(string $filename, array $headers, array $rows): never {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        echo "\xEF\xBB\xBF"; // BOM for Excel UTF-8
        $out = fopen('php://output', 'w');
        fputcsv($out, $headers, ';');
        foreach ($rows as $row) fputcsv($out, $row, ';');
        fclose($out);
        exit;
    }

    // ── Daftar Siswa ─────────────────────────────────────────────────
    public function students(): void {
        Middleware::role('admin','guru');
        $students = (new StudentModel())->allWithDetails();
        $headers  = ['No','Nama','NIS','Email','Kelas','Tingkat','Gender','Tgl Lahir','Nama Orang Tua','Telepon','Status'];
        $rows = [];
        foreach ($students as $i => $s) {
            $rows[] = [
                $i+1, $s['name'], $s['nis'], $s['email'],
                $s['nama_kelas']??'—', $s['tingkat']??'—',
                $s['gender']==='L'?'Laki-laki':'Perempuan',
                $s['birth_date'] ? date('d/m/Y',strtotime($s['birth_date'])) : '—',
                $s['parent_name']??'—', $s['phone']??'—',
                $s['is_active']?'Aktif':'Nonaktif',
            ];
        }
        $this->sendCsv('daftar_siswa_'.date('Ymd').'.csv', $headers, $rows);
    }

    // ── Rekap Nilai Kelas ─────────────────────────────────────────────
    public function grades(string $classId): void {
        Middleware::role('admin','guru');
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/grades'); }

        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $students    = (new StudentModel())->byClass((int)$classId);
        $subjects    = (new SubjectModel())->all('nama_mapel');

        // Build headers: Nama, NIS, [tiap mapel], Rata-rata
        $headers = ['No','Nama Siswa','NIS'];
        foreach ($subjects as $sub) $headers[] = $sub['nama_mapel'];
        $headers[] = 'Rata-rata';
        $headers[] = 'Grade';

        $rows = [];
        foreach ($students as $i => $s) {
            $grades = (new GradeModel())->getStudentGrades($s['id'], $semester, $tahunAjaran);
            $gradeMap = [];
            foreach ($grades as $g) $gradeMap[$g['subject_id']] = number_format((float)$g['nilai_akhir'],2,',','');
            $row = [$i+1, $s['name'], $s['nis']];
            $total = 0; $count = 0;
            foreach ($subjects as $sub) {
                $val = $gradeMap[$sub['id']] ?? '—';
                $row[] = $val;
                if ($val !== '—') { $total += (float)str_replace(',','.',$val); $count++; }
            }
            $avg = $count ? round($total/$count, 2) : 0;
            $grade = $avg>=90?'A':($avg>=80?'B':($avg>=70?'C':'D'));
            $row[] = $count ? number_format($avg,2,',','') : '—';
            $row[] = $count ? $grade : '—';
            $rows[] = $row;
        }
        $filename = 'nilai_'.strtolower(str_replace(' ','_',$class['nama_kelas'])).'_smt'.$semester.'_'.date('Ymd').'.csv';
        $this->sendCsv($filename, $headers, $rows);
    }

    // ── Rekap Absensi Kelas ───────────────────────────────────────────
    public function attendance(string $classId): void {
        Middleware::role('admin','guru');
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/attendance'); }
        $students = (new StudentModel())->byClass((int)$classId);

        $headers = ['No','Nama Siswa','NIS','Hadir','Sakit','Izin','Alpha','Total Pertemuan','% Kehadiran'];
        $rows    = [];
        foreach ($students as $i => $s) {
            $stats = (new AttendanceSessionModel())->getOverallStats($s['id']);
            $total = ($stats['hadir']??0)+($stats['sakit']??0)+($stats['izin']??0)+($stats['alpha']??0);
            $pct   = $total > 0 ? round(($stats['hadir']??0)/$total*100,1) : 0;
            $rows[] = [
                $i+1, $s['name'], $s['nis'],
                $stats['hadir']??0, $stats['sakit']??0,
                $stats['izin']??0, $stats['alpha']??0,
                $total, $pct.'%',
            ];
        }
        $filename = 'absensi_'.strtolower(str_replace(' ','_',$class['nama_kelas'])).'_'.date('Ymd').'.csv';
        $this->sendCsv($filename, $headers, $rows);
    }

    // ── Rapor PDF (HTML print) ────────────────────────────────────────
    public function rapor(string $studentId): void {
        Middleware::role('admin','guru','murid');
        if (Auth::is('murid')) {
            $me = (new StudentModel())->findByUserId(Auth::id());
            if (!$me || $me['id'] != (int)$studentId) { Flash::set('error','Akses ditolak.'); redirect('/dashboard'); }
        }
        $semester    = $this->get('semester', SEMESTER);
        $tahunAjaran = TAHUN_AJARAN;
        $data = (new ReportNoteModel())->getDetailForRapor((int)$studentId, $semester, $tahunAjaran);
        if (!$data['student']) { Flash::set('error','Siswa tidak ditemukan.'); redirect('/reports'); }
        $avgNilai = count($data['grades'])
            ? round(array_sum(array_column($data['grades'],'nilai_akhir'))/count($data['grades']),2) : 0;
        $predikat = $avgNilai>=90?'A':($avgNilai>=80?'B':($avgNilai>=70?'C':'D'));
        // Reuse the rapor print template
        view('reports.template', compact('data','semester','tahunAjaran','avgNilai','predikat'));
    }
}
