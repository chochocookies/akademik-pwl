<?php
class AcademicYearController extends Controller {

    public function index(): void {
        Middleware::admin();
        $years  = $this->db->fetchAll("SELECT * FROM academic_years ORDER BY tahun_ajaran DESC, semester ASC");
        $active = $this->db->fetch("SELECT * FROM academic_years WHERE is_active=1 LIMIT 1");
        $this->view('academic_years.index', compact('years','active'));
    }

    public function create(): void {
        Middleware::admin();
        $this->view('academic_years.create');
    }

    public function store(): void {
        Middleware::admin();
        verify_csrf();
        $v = Validator::make($_POST, ['tahun_ajaran'=>'required','semester'=>'required','tanggal_mulai'=>'required','tanggal_selesai'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/academic-years/create'); }
        try {
            $this->db->insert('academic_years', [
                'tahun_ajaran'   => $this->post('tahun_ajaran'),
                'semester'       => $this->post('semester'),
                'tanggal_mulai'  => $this->post('tanggal_mulai'),
                'tanggal_selesai'=> $this->post('tanggal_selesai'),
                'is_active'      => 0,
            ]);
            Flash::set('success','Tahun ajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Flash::set('error','Periode tersebut sudah ada.');
        }
        redirect('/academic-years');
    }

    public function activate(string $id): void {
        Middleware::admin();
        verify_csrf();
        $this->db->beginTransaction();
        try {
            // Deactivate all
            $this->db->query("UPDATE academic_years SET is_active=0");
            // Activate selected
            $year = $this->db->fetch("SELECT * FROM academic_years WHERE id=?", [(int)$id]);
            $this->db->query("UPDATE academic_years SET is_active=1 WHERE id=?", [(int)$id]);
            // Update config constants in session (takes effect on next request via config)
            // Update the config file dynamically
            $configPath = BASE_PATH . '/app/config.php';
            $config = file_get_contents($configPath);
            $config = preg_replace("/define\('TAHUN_AJARAN',\s*'[^']+'\)/", "define('TAHUN_AJARAN', '".$year['tahun_ajaran']."')", $config);
            $config = preg_replace("/define\('SEMESTER',\s*'[^']+'\)/", "define('SEMESTER', '".$year['semester']."')", $config);
            file_put_contents($configPath, $config);
            $this->db->commit();
            Flash::set('success','Tahun ajaran '.e($year['tahun_ajaran']).' Semester '.$year['semester'].' kini aktif. Config otomatis diperbarui.');
        } catch (\Exception $e) {
            $this->db->rollback();
            Flash::set('error','Gagal mengaktifkan: '.$e->getMessage());
        }
        redirect('/academic-years');
    }

    public function destroy(string $id): void {
        Middleware::admin();
        verify_csrf();
        $year = $this->db->fetch("SELECT * FROM academic_years WHERE id=?",[(int)$id]);
        if ($year && $year['is_active']) { Flash::set('error','Tidak bisa menghapus tahun ajaran yang sedang aktif.'); redirect('/academic-years'); }
        $this->db->delete('academic_years','id=?',[(int)$id]);
        Flash::set('success','Tahun ajaran dihapus.');
        redirect('/academic-years');
    }
}
