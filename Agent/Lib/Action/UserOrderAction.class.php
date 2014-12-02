<?php

class UserOrderAction extends Action {
    public function index(){
    	$map = array();
		$map['user_id'] = !empty($_POST['user_id']) ? $_POST['user_id'] : (!empty($_GET['user_id']) ? $_GET['user_id'] : '');
		$map_sql = "SELECT oi.*,c.title as grade_title FROM user_order oi LEFT JOIN grade c ON oi.grade_id=c.pkid WHERE oi.order_status>0 ";
		$map_sql_count = "SELECT count(oi.pkid) as num FROM user_order oi LEFT JOIN grade c ON oi.grade_id=c.pkid WHERE oi.order_status>0 ";
		if(!empty($map['user_id'])){
			$map_sql .= " AND oi.user_id =".$map['user_id'];
			$map_sql_count .= " AND oi.user_id =".$map['user_id'];
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['order_status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  order by oi.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;
		$order = new Model("UserOrder"); 
		$list = $order->query($map_sql);
        $order_course = new Model("OrderCourse");
        foreach($list as $k=>$v){
            $sql = "SELECT c.title FROM order_course co LEFT JOIN course c ON co.course_id=c.pkid WHERE co.order_id=".$v['pkid'];
            $list[$k]['course_list'] = $order_course->query($sql);
        }
		$this->assign('order_list',$list);
		import("ORG.Util.Page");

		$data_count  = $order->query($map_sql_count);
		$count = $data_count[0]['num'];
        if($count==0){
            redirect('/Backend/UserOrder/add/user_id/'.$map['user_id']);
        }
		$Page = new Page($count,$page_size);
		
		
		$show       = $Page->show();
		$this->assign('page',$show);

        $this->assign('data_user',M("User")->where('pkid='.$map['user_id'])->find());

		$this->display();
    }
    
    public function add(){
        $this->assign('grade_id', $this->gradeSet());
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('user_id', $_GET['user_id']);
        $this->assign('data_user',M("User")->where('pkid='.$_GET['user_id'])->find());
		$this->display();
    }

    public function save(){

        $user_order = new Model("UserOrder");
        $user_order->create();
        $user_order->user_id = $_POST['user_id'];
        $user_order->order_sn = date('YmdHis');
        $user_order->ctime = date('Y-m-d H:i:s');
        $user_order->order_status = 1;
        $user_order->course_list = implode(',',$_POST['course_id']);
        $order_id = $user_order->add();

        $grade_id = $_POST['grade_id'];
        $course_id = $_POST['course_id'];
        $order_course = new Model("OrderCourse");
        $unit['order_id'] = $order_id;
        $unit['grade_id'] = $grade_id;
        foreach($course_id as $val){
            $unit['course_id'] = $val;
            $unit['ctime'] = date("Y-m-d H:i:s");
            $order_course->data($unit)->add();
        }

        redirect("/Backend/UserOrder/index/user_id/".$_POST['user_id']);
    }

    public function edit(){
    	$order_id = $this->_param('order_id');
    	$order = new Model("UserOrder");
    	$sql = "SELECT oi.*,ca.title as grade_tile FROM user_order oi ".
    			" LEFT JOIN grade ca ON oi.grade_id=ca.pkid  WHERE oi.pkid=".$order_id;
    	$arr_form = $order->query($sql);
    	$this->assign('arr_form',$arr_form[0]);

        $this->assign('grade_id', $this->gradeSet());
        $this->assign('user_id', $arr_form[0]['user_id']);
        $this->assign('grade_value', $arr_form[0]['grade_id']);
        $this->assign('data_user',M("User")->where('pkid='.$arr_form[0]['user_id'])->find());


        $course = new Model("Course");
        $data_list = $course->where("grade_id = ".$arr_form[0]['grade_id'])->select();
        $order_course_list = explode(',',$arr_form[0]['course_list']);
        foreach($data_list as $key=>$val){
            if(in_array($val['pkid'],$order_course_list)){
                $data_list[$key]['checked'] = 'checked';
            }
        }
        $this->assign('course_list',$data_list);

        $this->display();
    }
    

    
    
    public function renew(){
    	
		$order = new Model("UserOrder");
		$data['pkid'] = $this->_param('pkid');
        $data['grade_id'] = $this->_param('grade_id');
        $data['course_list'] = implode(',',$this->_param('course_id'));
        $data['pay_amount'] = $this->_param('pay_amount');
        $data['pay_date'] = $this->_param('pay_date');
        $data['expire_date'] = $this->_param('expire_date');
        $data['start_date'] = $this->_param('start_date');

		$order->data($data)->save();


        $grade_id = $_POST['grade_id'];
        $course_id = $_POST['course_id'];
        $order_course = new Model("OrderCourse");

        $order_course->where('order_id='.$data['pkid'])->delete();

        $unit['order_id'] = $data['pkid'];
        $unit['grade_id'] = $grade_id;
        foreach($course_id as $val){
            $unit['course_id'] = $val;
            $unit['ctime'] = date("Y-m-d H:i:s");
            $order_course->data($unit)->add();
        }

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/UserOrder/index/user_id/".$this->_param('user_id'),2,$msg);
    }
    
    
    public function remove(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("UserOrder");
		
		$order->query('DELETE FROM user_order WHERE order_id='.$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function hide(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("UserOrder");
		
		$order->query("UPDATE user_order SET status='N' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function show(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("UserOrder");
		
		$order->query("UPDATE user_order SET status='Y' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function rating(){
    	
    	$pkid = $this->_param('order_id'); 
    	$tid = $this->_param('tid'); 
    	
		$this->assign('order_id',$pkid);
		$this->assign('teacher_id',$tid);
		$rating_type = array('+'=>'信用值升级','-'=>'信用值降级');
		$this->assign('rating_type',$rating_type);
		
		$this->display();
    }
    
    public function rating_done(){
    	
    	$order_id = $this->_param('order_id'); 
    	$teacher_id = $this->_param('teacher_id'); 
    	$rating_type = $this->_param('rating_type'); 
    	$score = $rating_type.'1';
    	
		$teacher_view = new Model("TeacherReview");
		$teacher_view->create();
		$teacher_view->order_id = $order_id;
		$teacher_view->teacher_id = $teacher_id;
		$teacher_view->rating_type = $rating_type;
		$teacher_view->score = 1;
		
		$teacher_view->ctime = date("Y-m-d H:i:s");
		$teacher_view->add();
		
		$teacher = new Model("Teacher");
		$teacher->query("UPDATE teacher SET score=score".$score." WHERE pkid=".$teacher_id);

        $user_order = new Model("UserOrder");
        $user_order->query("UPDATE user_order SET rating_status=2 WHERE order_id=".$order_id);

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
		
    }
    private function gradeSet(){
        $Grade = D('Grade');
        $arr_grade =  $Grade->where('pkid > 0')->select();

        $arr_select = array();
        foreach($arr_grade as $key=>$val){
            $arr_select[$val['pkid']] = $val['title'];
        }

        return $arr_select;
    }
}
?>