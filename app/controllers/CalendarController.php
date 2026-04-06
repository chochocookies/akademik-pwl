<?php
class CalendarController extends Controller {

    public function index(): void {
        Middleware::auth();
        $year  = (int)$this->get('year',  date('Y'));
        $month = (int)$this->get('month', date('n'));
        if ($month < 1) { $month = 12; $year--; }
        if ($month > 12){ $month = 1;  $year++; }

        $events   = (new CalendarModel())->getByMonth($year, $month);
        $upcoming = (new CalendarModel())->getUpcoming(6);

        // Map events by date for fast lookup
        $eventMap = [];
        foreach ($events as $e) {
            $cur = strtotime($e['tanggal_mulai']);
            $end = strtotime($e['tanggal_selesai']);
            while ($cur <= $end) {
                $key = date('Y-m-d', $cur);
                $eventMap[$key][] = $e;
                $cur = strtotime('+1 day', $cur);
            }
        }

        // Today's schedule (for guru/murid)
        $schedule = null;
        $user = Auth::user();
        if (Auth::is('guru')) {
            $teacher  = (new TeacherModel())->findByUserId($user['id']);
            $schedule = $teacher ? (new ScheduleModel())->getByTeacher($teacher['id']) : [];
        } elseif (Auth::is('murid')) {
            $student  = (new StudentModel())->findByUserId($user['id']);
            $schedule = $student ? (new ScheduleModel())->getTodaySchedule($student['class_id'] ?? 0) : [];
        }

        $this->view('calendar.index', compact('year','month','events','eventMap','upcoming','schedule'));
    }

    public function schedule(string $classId): void {
        Middleware::role('admin','guru');
        $class    = (new ClassModel())->findWithDetails((int)$classId);
        if (!$class) { Flash::set('error','Kelas tidak ditemukan.'); redirect('/calendar'); }
        $schedules = (new ScheduleModel())->getByClass((int)$classId);
        $teachers  = (new TeacherModel())->allWithDetails();
        $subjects  = (new SubjectModel())->all('nama_mapel');
        $this->view('calendar.schedule', compact('class','schedules','teachers','subjects'));
    }

    public function storeEvent(): void {
        Middleware::admin();
        verify_csrf();
        $v = Validator::make($_POST, ['judul'=>'required','tanggal_mulai'=>'required','tanggal_selesai'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/calendar'); }
        (new CalendarModel())->create([
            'judul'          => $this->post('judul'),
            'tanggal_mulai'  => $this->post('tanggal_mulai'),
            'tanggal_selesai'=> $this->post('tanggal_selesai'),
            'tipe'           => $this->post('tipe','event'),
            'deskripsi'      => $this->post('deskripsi'),
            'warna'          => $this->post('warna','#3B82F6'),
            'created_by'     => Auth::id(),
        ]);
        Flash::set('success','Event kalender ditambahkan.');
        redirect('/calendar');
    }

    public function destroyEvent(string $id): void {
        Middleware::admin();
        verify_csrf();
        (new CalendarModel())->delete((int)$id);
        Flash::set('success','Event dihapus.');
        redirect('/calendar');
    }

    public function storeSchedule(): void {
        Middleware::admin();
        verify_csrf();
        $v = Validator::make($_POST, ['class_id'=>'required','teacher_id'=>'required','subject_id'=>'required','hari'=>'required','jam_mulai'=>'required','jam_selesai'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/calendar/schedule/'.$this->post('class_id')); }
        try {
            (new ScheduleModel())->create([
                'class_id'   => (int)$this->post('class_id'),
                'teacher_id' => (int)$this->post('teacher_id'),
                'subject_id' => (int)$this->post('subject_id'),
                'hari'       => $this->post('hari'),
                'jam_mulai'  => $this->post('jam_mulai'),
                'jam_selesai'=> $this->post('jam_selesai'),
                'ruangan'    => $this->post('ruangan'),
            ]);
            Flash::set('success','Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            Flash::set('error','Slot waktu sudah terisi untuk kelas ini.');
        }
        redirect('/calendar/schedule/'.$this->post('class_id'));
    }

    public function destroySchedule(string $id): void {
        Middleware::admin();
        verify_csrf();
        $s = (new ScheduleModel())->find((int)$id);
        (new ScheduleModel())->delete((int)$id);
        Flash::set('success','Jadwal dihapus.');
        redirect('/calendar/schedule/'.($s['class_id'] ?? ''));
    }
}
