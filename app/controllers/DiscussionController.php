<?php
class DiscussionController extends Controller {

    public function index(): void {
        Middleware::auth();
        $user  = Auth::user();
        $classId = null;
        if (Auth::is('murid')) {
            $student = (new StudentModel())->findByUserId($user['id']);
            $classId = $student['class_id'] ?? null;
        } elseif (Auth::is('guru')) {
            $teacher = (new TeacherModel())->findByUserId($user['id']);
            $classIds = array_column((new TeacherModel())->getClasses($teacher['id']), 'id');
        }
        $sql = "SELECT d.*, c.nama_kelas, u.name as author_name,
                       COALESCE(r.reply_count,0) as reply_count
                FROM discussions d
                JOIN classes c ON d.class_id=c.id
                JOIN users u ON d.user_id=u.id
                LEFT JOIN (SELECT discussion_id, COUNT(*) as reply_count FROM discussion_replies GROUP BY discussion_id) r ON d.id=r.discussion_id
                WHERE 1=1";
        $params = [];
        if ($classId) { $sql .= " AND d.class_id=?"; $params[] = $classId; }
        elseif (!empty($classIds) && Auth::is('guru')) {
            $ph = implode(',',array_fill(0,count($classIds),'?'));
            $sql .= " AND d.class_id IN ($ph)"; $params = array_merge($params,$classIds);
        }
        $sql .= " ORDER BY d.is_pinned DESC, d.updated_at DESC";
        $discussions = $this->db->fetchAll($sql, $params);
        $classes = Auth::is('admin') ? (new ClassModel())->allWithDetails() :
                   (Auth::is('guru') ? (new TeacherModel())->getClasses($teacher['id']??0) :
                   ($classId ? [(new ClassModel())->findWithDetails($classId)] : []));
        $this->view('discussions.index', compact('discussions','classes'));
    }

    public function show(string $id): void {
        Middleware::auth();
        $discussion = $this->db->fetch("
            SELECT d.*, c.nama_kelas, u.name as author_name
            FROM discussions d JOIN classes c ON d.class_id=c.id JOIN users u ON d.user_id=u.id
            WHERE d.id=?",[(int)$id]);
        if (!$discussion) { Flash::set('error','Diskusi tidak ditemukan.'); redirect('/discussions'); }
        $replies = $this->db->fetchAll("
            SELECT r.*, u.name as author_name, u.role as author_role
            FROM discussion_replies r JOIN users u ON r.user_id=u.id
            WHERE r.discussion_id=? ORDER BY r.created_at ASC",[(int)$id]);
        $this->view('discussions.show', compact('discussion','replies'));
    }

    public function create(): void {
        Middleware::role('admin','guru');
        $user  = Auth::user();
        $teacher = Auth::is('guru') ? (new TeacherModel())->findByUserId($user['id']) : null;
        $classes = $teacher ? (new TeacherModel())->getClasses($teacher['id']) : (new ClassModel())->allWithDetails();
        $this->view('discussions.create', compact('classes'));
    }

    public function store(): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $v = Validator::make($_POST, ['class_id'=>'required','judul'=>'required|min:5','konten'=>'required']);
        if ($v->fails()) { Flash::set('error',$v->firstError()); redirect('/discussions/create'); }
        $id = $this->db->insert('discussions', [
            'class_id'     => (int)$this->post('class_id'),
            'assignment_id'=> $this->post('assignment_id') ?: null,
            'user_id'      => Auth::id(),
            'judul'        => $this->post('judul'),
            'konten'       => $this->post('konten'),
            'is_pinned'    => $this->post('is_pinned') ? 1 : 0,
        ]);
        NotificationModel::sendToClass((int)$this->post('class_id'),'lainnya','💬 Diskusi baru: '.$this->post('judul'),'',url('/discussions/'.$id));
        Flash::set('success','Topik diskusi dibuat.');
        redirect('/discussions/'.$id);
    }

    public function reply(string $id): void {
        Middleware::auth();
        verify_csrf();
        $v = Validator::make($_POST, ['konten'=>'required']);
        if ($v->fails()) { Flash::set('error','Balasan tidak boleh kosong.'); redirect('/discussions/'.$id); }
        $this->db->insert('discussion_replies', ['discussion_id'=>(int)$id,'user_id'=>Auth::id(),'konten'=>$this->post('konten')]);
        $this->db->query("UPDATE discussions SET reply_count=reply_count+1, updated_at=NOW() WHERE id=?", [(int)$id]);
        Flash::set('success','Balasan terkirim.');
        redirect('/discussions/'.$id);
    }

    public function destroy(string $id): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $this->db->delete('discussions','id=?',[(int)$id]);
        Flash::set('success','Diskusi dihapus.');
        redirect('/discussions');
    }

    public function deleteReply(string $id): void {
        Middleware::role('admin','guru');
        verify_csrf();
        $reply = $this->db->fetch("SELECT * FROM discussion_replies WHERE id=?",[(int)$id]);
        if ($reply) {
            $this->db->delete('discussion_replies','id=?',[(int)$id]);
            $this->db->query("UPDATE discussions SET reply_count=GREATEST(0,reply_count-1) WHERE id=?",[$reply['discussion_id']]);
        }
        Flash::set('success','Balasan dihapus.');
        redirect('back');
    }
}
