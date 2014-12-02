<?php

class KechengOrderAction extends Action {
    public function index(){
    	$map = array();
    	$map['order_sn'] = !empty($_POST['order_sn']) ? $_POST['order_sn'] : (!empty($_GET['order_sn']) ? $_GET['order_sn'] : '');
		$map['visitor_name'] = !empty($_POST['visitor_name']) ? $_POST['visitor_name'] : (!empty($_GET['visitor_name']) ? $_GET['visitor_name'] : '');
		$map['visitor_mobile'] = !empty($_POST['visitor_mobile']) ? $_POST['visitor_mobile'] : (!empty($_GET['visitor_mobile']) ? $_GET['visitor_mobile'] : '');
		$map['order_status'] = !empty($_POST['order_status']) ? $_POST['order_status'] : (!empty($_GET['status']) ? $_GET['order_status'] : '');
		
		$map_sql = "SELECT oi.*,c.title as title,a.code as agency_code,a.address as address,a.title as agency_title,".
					"a.telephone as agency_telephone,c.image as image FROM kecheng_order oi ".
					"LEFT JOIN kechengbao c ON oi.kecheng_id=c.pkid ".
					"LEFT JOIN agency a ON oi.agency_id=a.pkid WHERE oi.order_status>0 ";
		$map_sql_count = "SELECT count(oi.order_id) FROM kecheng_order oi ".
						"LEFT JOIN kechengbao c ON oi.kecheng_id=c.pkid ".
						"LEFT JOIN agency a ON oi.agency_id=a.pkid WHERE oi.order_status>0 ";
		if(!empty($map['visitor_name'])){
			$map_sql .= " AND oi.visitor_name like '%".$map['visitor_name']."%'";
			$map_sql_count .= " AND oi.visitor_name like '%".$map['visitor_name']."%'";
		}
		if(!empty($map['visitor_mobile'])){
			$map_sql .= " AND oi.visitor_mobile like '%".$map['visitor_mobile']."%'";
			$map_sql_count .= " AND oi.visitor_mobile like '%".$map['visitor_mobile']."%'";
		}
		if(!empty($map['order_status'])){
		 	$map_sql .= " AND oi.order_status='".$map['order_status']."'";
		 	$map_sql_count .= " AND oi.order_status='".$map['order_status']."'";
		}
		if(!empty($map['order_sn'])){
		 	$map_sql .= " AND oi.order_sn='".$map['order_sn']."'";
		 	$map_sql_count .= " AND oi.order_sn='".$map['order_sn']."'";
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['order_status']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  order by oi.order_id desc limit ".($cur_page-1)*$page_size.",".$page_size;
		//echo $map_sql;
		
		$order = new Model("KechengOrder"); 
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
    	$order = new Model("KechengOrder");
    	$sql = "SELECT  oi.*,c.title as title,a.code as agency_code,a.address as address,a.title as agency_title,".
					" a.telephone as agency_telephone,c.image as image FROM kecheng_order oi LEFT JOIN kechengbao c ON oi.kecheng_id=c.pkid ".
					" LEFT JOIN agency a ON oi.agency_id=a.pkid  ".
    		    	" WHERE oi.order_id=".$order_id;
    	$arr_form = $order->query($sql);
    	$this->assign('arr_form',$arr_form[0]);

    	$this->assign('order_status', array('1'=>'预约中','2'=>'确认预约','3'=>'交易成功','4'=>'交易失败'));
    	$this->assign('os_value', $arr_form[0]['order_status']);
    	
    	$this->assign('pay_status', array('0'=>'未支付','1'=>'已支付'));
    	$this->assign('ps_value', $arr_form[0]['pay_status']);
    	
    	$this->assign('verify_status', array('0'=>'未核销','1'=>'已核销'));
    	$this->assign('vs_value', $arr_form[0]['verify_status']);
    	
        $this->display();
    }
    

    
    
    public function renew(){
    	
		$order = new Model("KechengOrder");
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
    	
		$order = new Model("KechengOrder");
		
		$order->query('DELETE FROM kecheng_order WHERE order_id='.$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function hide(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("KechengOrder");
		
		$order->query("UPDATE kecheng_order SET status='N' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
    	//redirect("/Backend/Order/index");
    }
    
    public function show(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("KechengOrder");
		
		$order->query("UPDATE kecheng_order SET status='Y' WHERE order_id=".$order_id);
    	
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

        $kecheng_order = new Model("KechengOrder");
        $kecheng_order->query("UPDATE kecheng_order SET rating_status=2 WHERE order_id=".$order_id);

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/Order/index",1,$msg);
		
    }
    
}
?>