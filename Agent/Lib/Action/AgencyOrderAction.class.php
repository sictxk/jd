<?php

class AgencyOrderAction extends Action {
    public function index(){
    	$map = array();
		$map['visitor_name'] = !empty($_POST['visitor_name']) ? $_POST['visitor_name'] : (!empty($_GET['visitor_name']) ? $_GET['visitor_name'] : '');
		$map['order_status'] = !empty($_POST['order_status']) ? $_POST['order_status'] : (!empty($_GET['status']) ? $_GET['order_status'] : '');
		$map['date'] = !empty($_POST['date']) ? $_POST['date'] : (!empty($_GET['date']) ? $_GET['date'] : '');
		
		
		$map_sql = "SELECT oi.*, ac.title as course_title, c.title as agency_title,c.telephone as agency_telephone,c.address,c.vouchsafe,pc.name as area_name".
                    " FROM agency_order oi LEFT JOIN agency c ON oi.agency_id=c.pkid ".
                    " LEFT JOIN province_city pc ON c.area_id=pc.item_id  ".
        			" LEFT JOIN agency_course ac ON oi.course_id=ac.pkid WHERE oi.order_status>0 ";
		$map_sql_count = "SELECT count(oi.order_id) as num FROM agency_order oi LEFT JOIN agency c ON oi.agency_id=c.pkid WHERE oi.order_status>0 ";
		if(!empty($map['visitor_name'])){
			$map_sql .= " AND oi.visitor_name like '%".$map['visitor_name']."%'";
			$map_sql_count .= " AND oi.visitor_name like '%".$map['visitor_name']."%'";
		}
		if(!empty($map['order_status'])){
		 	$map_sql .= " AND oi.order_status='".$map['order_status']."'";
		 	$map_sql_count .= " AND oi.order_status='".$map['order_status']."'";
		}
		if(!empty($map['date'])){
			if($map['date']=='today'){
				$today = date("Y-m-d");
				$map_sql .= " AND oi.ctime>='".$today."'";
		 		$map_sql_count .= " AND oi.ctime>='".$today."'";
			}else{
				$week = date("W");
				$map_sql .= " AND DATE_FORMAT(oi.ctime,'%u')=".$week;
		 		$map_sql_count .= " AND DATE_FORMAT(oi.ctime,'%u')=".$week;
			}
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['order_status']);
    	$this->assign('date',$map['date']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  order by oi.order_id desc limit ".($cur_page-1)*$page_size.",".$page_size;
		
		$order = new Model("AgencyOrder"); 
		$list = $order->query($map_sql);
		
		
		$this->assign('order_list',$list);
		import("ORG.Util.Page");
		
		$data_count  = $order->query($map_sql_count);
		
		$Page = new Page($data_count[0]['num'],$page_size);
		
		
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
    	$order = new Model("AgencyOrder");

        $sql = "SELECT oi.*, ac.title as course_title, c.code as agency_code, c.title as agency_title,c.telephone as agency_telephone,c.address,c.vouchsafe,pc.name as area_name".
            " FROM agency_order oi LEFT JOIN agency c ON oi.agency_id=c.pkid ".
            " LEFT JOIN province_city pc ON c.area_id=pc.item_id LEFT JOIN agency_course ac ON oi.course_id=ac.pkid WHERE oi.order_id=".$order_id;
    	$arr_form = $order->query($sql);
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$_SESSION[$order_id]['agency_id'] = $arr_form[0]['agency_id'];
    	$this->assign('order_status', array('1'=>'预约中','2'=>'确认预约','3'=>'交易成功','4'=>'交易失败'));
    	$this->assign('agency_id', $arr_form[0]['agency_id']);
    	$this->assign('os_value', $arr_form[0]['order_status']);
    	
        $this->display();
    }
    

    
    
    public function renew(){
    	
		$order = new Model("AgencyOrder");
		$data['order_id'] = $this->_param('order_id');
		$status = $this->_param('order_status');
		$data['order_status'] = $status[0];
		$data['bespeak_date'] = $this->_param('bespeak_date');
		
		
		
		/*$post_tid = $agency_id[0];
		
		if($post_tid!=$_SESSION[$order_id]['agency_id']){
			$data['agency_id'] = $post_tid;
			$teacher = new Model("Teacher");
			$res = $teacher->where("pkid=".$post_tid)->select();
			$data['teacher_name'] = $res[0]['truename'];
			$data['teacher_mobile'] = $res[0]['mobile'];
		}*/
		
		$order->save($data);
		
		if($data['order_status']==2){
			$agency = new Model("Agency");
			$agency_id = $this->_param('agency_id');
			$data_agency = $agency->where("pkid=".$agency_id)->find();
			
			$data_order = $order->where("order_id=".$data['order_id'])->find();
			
			$postData = array();
			$postData['mobile'] = $data_order['visitor_mobile'];
			$postData['content'] = "【伊兔网】试课预约码：".$data_order['bespeak_code']."，请凭此前往".$data_agency['title']."免费试课，地址在".$data_agency['address']."，电话：".$data_agency['telephone']."。";
			
			import('@.ORG.Util.SmsApi');
			$sms_api = new SmsApi();
			$res = $sms_api->getApiResponse($postData);
			
			$sms_log = new Model("SmsLog");
			$sms_log->create();
			$sms_log->mobile = $postData['mobile'];
			$sms_log->content = $postData['content'];
			$sms_log->result = $res;
			$sms_log->ctime = date("Y-m-d H:i:s");
			$sms_log->add();
		}
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyOrder/index",2,$msg);
    }
    
    
    public function remove(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("AgencyOrder");
		
		$order->query('DELETE FROM agency_order WHERE order_id='.$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyOrder/index",1,$msg);
    	//redirect("/Backend/AgencyOrder/index");
    }
    
    public function hide(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("AgencyOrder");
		
		$order->query("UPDATE agency_order SET status='N' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyOrder/index",1,$msg);
    	//redirect("/Backend/AgencyOrder/index");
    }
    
    public function show(){
    	
    	$order_id = $this->_param('order_id'); 
    	
		$order = new Model("AgencyOrder");
		
		$order->query("UPDATE agency_order SET status='Y' WHERE order_id=".$order_id);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyOrder/index",1,$msg);
    	//redirect("/Backend/AgencyOrder/index");
    }
    
    public function rating(){
    	
    	$pkid = $this->_param('order_id'); 
    	$tid = $this->_param('tid'); 
    	
		$this->assign('order_id',$pkid);
		$this->assign('agency_id',$tid);
		$rating_type = array('+'=>'信用值升级','-'=>'信用值降级');
		$this->assign('rating_type',$rating_type);
		
		$this->display();
    }
    
    public function rating_done(){
    	
    	$order_id = $this->_param('order_id'); 
    	$agency_id = $this->_param('agency_id'); 
    	$rating_type = $this->_param('rating_type'); 
    	$score = $rating_type.'1';
    	
		$teacher_view = new Model("TeacherReview");
		$teacher_view->create();
		$teacher_view->order_id = $order_id;
		$teacher_view->agency_id = $agency_id;
		$teacher_view->rating_type = $rating_type;
		$teacher_view->score = 1;
		
		$teacher_view->ctime = date("Y-m-d H:i:s");
		$teacher_view->add();
		
		$teacher = new Model("Teacher");
		$teacher->query("UPDATE teacher SET score=score".$score." WHERE pkid=".$agency_id);

        $agency_order = new Model("AgencyOrder");
        $agency_order->query("UPDATE agency_order SET rating_status=2 WHERE order_id=".$order_id);

		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyOrder/index",1,$msg);
		
    }
    
}
?>