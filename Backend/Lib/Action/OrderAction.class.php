<?php

class OrderAction extends Action {
    public function index(){
    	$map = array();
		$map['student_name'] = !empty($_POST['student_name']) ? $_POST['student_name'] : (!empty($_GET['student_name']) ? $_GET['student_name'] : '');
		$map['order_status'] = !empty($_POST['order_status']) ? $_POST['order_status'] : (!empty($_GET['status']) ? $_GET['order_status'] : '');
		
		
		$map_sql = "SELECT oi.*,c.title as course_title FROM order_info oi LEFT JOIN course c ON oi.course_id=c.course_id WHERE oi.order_status>0 ";
		$map_sql_count = "SELECT count(oi.order_id) FROM order_info oi LEFT JOIN course c ON oi.course_id=c.course_id WHERE oi.order_status>0 ";
		if(!empty($map['student_name'])){
			$map_sql .= " AND oi.student_name like '%".$map['student_name']."%'";
			$map_sql_count .= " AND oi.student_name like '%".$map['student_name']."%'";
		}
		if(!empty($map['order_status'])){
		 	$map_sql .= " AND oi.order_status='".$map['order_status']."'";
		 	$map_sql_count .= " AND oi.order_status='".$map['order_status']."'";
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['order_status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  order by oi.order_id desc limit ".($cur_page-1)*$page_size.",".$page_size;
		//echo $map_sql;
		
		$order = new Model("OrderInfo"); 
		$list = $order->query($map_sql);
		
		
		$this->assign('order_list',$list);
		import("ORG.Util.Page");

		$data_count  = $order->query($map_sql_count);
		
		$Page = new Page($count,$page_size);
		
		
		$show       = $Page->show();
		$this->assign('page',$show);
    	
    	$this->assign('order_status', array('1'=>'未确认预约','2'=>'已确认预约','3'=>'已撤销','4'=>'授课完成'));
    	
		$this->display();
    }
    
    public function add(){
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
    	
		$this->display();
    }
    
    public function edit(){
    	$order_id = $this->_param('order_id');
    	$order = new Model("OrderInfo");
    	$sql = "SELECT oi.*,ca.title as category_tile,co.title as course_title FROM order_info oi LEFT JOIN course co ON oi.course_id=co.course_id".
    			" LEFT JOIN category ca ON co.category_id=ca.pkid LEFT JOIN teacher_area ta ON oi.area_id=ta.area_id WHERE oi.order_id=".$order_id;
    	$arr_form = $order->query($sql);
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$_SESSION[$order_id]['teacher_id'] = $arr_form[0]['teacher_id'];
    	$this->assign('order_status', array('1'=>'预约中','2'=>'确认预约','3'=>'交易成功','4'=>'交易失败'));
    	$this->assign('teacher_id', $arr_form[0]['teacher_id']);
    	$this->assign('os_value', $arr_form[0]['order_status']);
    	
    	//获取匹配教师TOP5
        $teacher = new Model("Teacher");
        $sql = "SELECT t.* FROM teacher t LEFT JOIN teacher_course tc ON t.pkid=tc.teacher_id LEFT JOIN teacher_area ta ON t.pkid=ta.teacher_id ".
            "WHERE tc.course_id=".$arr_form[0]['course_id']." and ta.area_id=".$arr_form[0]['area_id']." ORDER BY score DESC LIMIT 5";
        $match_list = $teacher->query($sql);
        foreach($match_list as $key=>$val){
            if($val['pkid']==$arr_form[0]['teacher_id']){
                $match_list[$key]['check_flg'] = "checked";
            }
        }
        //print_R($match_list);
        $this->assign('match_list',$match_list);

        $this->display();
    }
    

    
    
    public function renew(){
    	
		$order = new Model("OrderInfo");
		$data['order_id'] = $this->_param('order_id');
		$status = $this->_param('order_status');
		$data['order_status'] = $status[0];
		
		$teacher_id = $this->_param('teacher_id');
		$post_tid = $teacher_id[0];
		
		if($post_tid!=$_SESSION[$order_id]['teacher_id']){
			$data['teacher_id'] = $post_tid;
			$teacher = new Model("Teacher");
			$res = $teacher->where("pkid=".$post_tid)->select();
			$data['teacher_name'] = $res[0]['truename'];
			$data['teacher_mobile'] = $res[0]['mobile'];
		}
		
		$order->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",2,$msg);
    }
    
    
    public function remove(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("OrderInfo");
		
		$order->query('DELETE FROM order_info WHERE order_id='.$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function hide(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("OrderInfo");
		
		$order->query("UPDATE order_info SET status='N' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function show(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("OrderInfo");
		
		$order->query("UPDATE order_info SET status='Y' WHERE order_id=".$order_id);
    	
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

        $order_info = new Model("OrderInfo");
        $order_info->query("UPDATE order_info SET rating_status=2 WHERE order_id=".$order_id);

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
		
    }
    
}
?>