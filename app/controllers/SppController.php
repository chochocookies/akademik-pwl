<?php
class SppController extends Controller {

    public function index(): void {
        Middleware::admin();
        $classes  = (new ClassModel())->allWithDetails();
        $settings = $this->db->fetchAll("SELECT * FROM spp_settings WHERE tahun_ajaran=? ORDER BY kelas_tingkat", [TAHUN_AJARAN]);
        $year     = (int)date('Y');
        // Summary per class
        $summary  = [];
        foreach ($classes as $c) {
            $total   = (new StudentModel())->byClass($c['id']);
            $lunas   = 0;
            foreach ($total as $s) {
                $paid = $this->db->count("SELECT COUNT(*) FROM spp_payments WHERE student_id=? AND tahun=? AND status='lunas'",[$s['id'],$year]);
                if ($paid >= date('n')) $lunas++;
            }
            $summary[$c['id']] = ['total'=>count($total),'lunas'=>$lunas,'belum'=>count($total)-$lunas];
        }
        $this->view('spp.index', compact('classes','settings','summary','year'));
    }

    public function student(string $studentId): void {
        Middleware::admin();
        $student  = (new StudentModel())->findWithDetails((int)$studentId);
        if (!$student) { Flash::set('error','Siswa tidak ditemukan.'); redirect('/spp'); }
        $year     = (int)($this->get('year', date('Y')));
        $payments = $this->db->fetchAll("SELECT * FROM spp_payments WHERE student_id=? AND tahun=? ORDER BY bulan",[(int)$studentId,$year]);
        $payMap   = [];
        foreach ($payments as $p) $payMap[$p['bulan']] = $p;
        // Get SPP amount
        $sppSetting = $this->db->fetch("SELECT * FROM spp_settings WHERE tahun_ajaran=? AND kelas_tingkat=?",[TAHUN_AJARAN,$student['tingkat']??'1']);
        $jumlah = $sppSetting['jumlah_per_bulan'] ?? 0;
        $this->view('spp.student', compact('student','payMap','year','jumlah'));
    }

    public function pay(): void {
        Middleware::admin();
        verify_csrf();
        $studentId = (int)$this->post('student_id');
        $bulan     = (int)$this->post('bulan');
        $tahun     = (int)$this->post('tahun');
        $jumlah    = (float)$this->post('jumlah');
        $status    = $this->post('status','lunas');
        $existing  = $this->db->fetch("SELECT * FROM spp_payments WHERE student_id=? AND bulan=? AND tahun=?",[$studentId,$bulan,$tahun]);
        $data = ['student_id'=>$studentId,'bulan'=>$bulan,'tahun'=>$tahun,'jumlah'=>$jumlah,'status'=>$status,
                 'tanggal_bayar'=>$status==='lunas'?date('Y-m-d'):null,'keterangan'=>$this->post('keterangan'),'created_by'=>Auth::id()];
        if ($existing) $this->db->update('spp_payments',array_diff_key($data,['student_id'=>0,'bulan'=>0,'tahun'=>0,'created_by'=>0]),"id=?",[$existing['id']]);
        else $this->db->insert('spp_payments',$data);
        Flash::set('success','Pembayaran SPP berhasil disimpan.');
        redirect('/spp/student/'.$studentId.'?year='.$tahun);
    }

    public function settings(): void {
        Middleware::admin();
        $settings = $this->db->fetchAll("SELECT * FROM spp_settings ORDER BY tahun_ajaran DESC, kelas_tingkat");
        $this->view('spp.settings', compact('settings'));
    }

    public function saveSettings(): void {
        Middleware::admin();
        verify_csrf();
        $tingkats = $this->post('kelas_tingkat',[]);
        $jumlah   = $this->post('jumlah_per_bulan',[]);
        foreach ($tingkats as $i => $tingkat) {
            $existing = $this->db->fetch("SELECT id FROM spp_settings WHERE tahun_ajaran=? AND kelas_tingkat=?",[TAHUN_AJARAN,$tingkat]);
            if ($existing) $this->db->update('spp_settings',['jumlah_per_bulan'=>(float)($jumlah[$i]??0)],"id=?",[$existing['id']]);
            else $this->db->insert('spp_settings',['tahun_ajaran'=>TAHUN_AJARAN,'kelas_tingkat'=>$tingkat,'jumlah_per_bulan'=>(float)($jumlah[$i]??0)]);
        }
        Flash::set('success','Setting SPP diperbarui.');
        redirect('/spp/settings');
    }
}
