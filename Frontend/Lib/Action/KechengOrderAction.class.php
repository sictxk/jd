<?php
header("Content-type: text/html;charset=GB2312");
class KechengOrderAction extends Action {
    public function index(){
		
		$user_id = $_SESSION['user_info']['pkid'];
		if(!$user_id){
			$msg = mb_convert_encoding("请先登录。","GB2312","UTF-8");
			print "<script language=\"javascript\">alert('$msg');location.href='/Member/User/login';</script>";
			exit;
		}
 		$kecheng_order = new Model("KechengOrder");
 		
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
 		
 		$order_sql = "SELECT oi.*,t.code as agency_code,t.title,t.vouchsafe,t.telephone,t.address,pc.name as area_name FROM kecheng_order oi ".
                       " LEFT JOIN agency t ON oi.agency_id=t.pkid  LEFT JOIN province_city pc ON t.area_id=pc.item_id  ".
 						" WHERE oi.user_id=".$user_id." order by oi.order_id desc limit ".($cur_page-1)*$page_size.",".$page_size;
 		$data_order = $kecheng_order->query($order_sql);//print_r($data_order);
 		$this->assign('data_order',$data_order);
		
		import("ORG.Util.Page");
		
		$count_sql = "SELECT count(order_id) as total FROM kecheng_order WHERE user_id=".$user_id;
		$count_num = $kecheng_order->query($count_sql);
		$count = $count_num[0]['total'];
		
		$Page = new Page($count,$page_size);
		
		$show       = $Page->show();//echo $show;
		$this->assign('page',$show);
		
    	$user = new Model("User");
    	$basic_info = $user->query("SELECT * FROM user WHERE pkid='".$_SESSION['user_info']['pkid']."'");
		$this->assign('basic_info',$basic_info[0]);
		
        $user_score = new Model("UserScore");
        $sql="SELECT sum(score) as total FROM user_score WHERE user_id=".$_SESSION['user_info']['pkid'];
        $data = $user_score->query($sql);
        $total_score = ($data[0]['total']>0) ? $data[0]['total'] : 0;
        $this->assign('total_score',$total_score);
		
		$this->display();
    }


    public function confirm(){

        $arr_form = array();
        $arr_form['kecheng_id'] = $this->_param('kecheng_id') ? $this->_param('kecheng_id') : '';
		$arr_form['agency_id'] = $this->_param('agency_id') ? $this->_param('agency_id') : '';
		$arr_form['user_id'] = $this->_param('user_id') ? $this->_param('user_id') : '';
		
        $user = new Model("User");
        $data_user = $user->where("pkid=".$arr_form['user_id'])->find();
        
        $kecheng = new Model("Kechengbao");
        $data_kecheng = $kecheng->where("pkid=".$arr_form['kecheng_id'])->find();
        
		$kecheng_order = new Model("KechengOrder");
		$arr_form['order_status'] = 0;
		$data_order = $kecheng_order->where($arr_form)->find();
		if(empty($data_order)){
			$kecheng_order->create();
	        $order_sn = 'kc'.date("YmdHis");
			$kecheng_order->order_sn = $order_sn;
			$kecheng_order->kecheng_id = $arr_form['kecheng_id'];
			$kecheng_order->agency_id = $arr_form['agency_id'];
			$kecheng_order->user_id = $arr_form['user_id'];
			$kecheng_order->visitor_mobile = $data_user['mobile'];
			$kecheng_order->market_price = $data_kecheng['market_price'];
			$kecheng_order->order_amount = $data_kecheng['yitu_price'];
			$kecheng_order->order_status = 0;
			$kecheng_order->pay_status = 0;
			$kecheng_order->ctime = date("Y-m-d H:i:s");
			$order_id = $kecheng_order->add();
		}else{
			$order_id = $data_order['order_id'];
			$order_sn = $data_order['order_sn'];
		}
		
		$this->assign('data_user',$data_user);
		$this->assign('data_kecheng',$data_kecheng);
		
        $agency = new Model("Agency");
        $sql = "SELECT ag.*,ab.title as brand_title,pc.name as area_name FROM agency ag LEFT JOIN agency_brand ab ON ag.brand_id=ab.pkid ".
            " LEFT JOIN province_city pc ON ag.area_id=pc.item_id WHERE ag.pkid=".$arr_form['agency_id'];
        $data_agency = $agency->query($sql);
        $this->assign('data_agency',$data_agency[0]);
		

		
        $this->assign('arr_form',$arr_form);
		$this->assign('order_id',$order_id);
		$this->assign('order_sn',$order_sn);
        $this->display();
    }

    public function done(){

		$order_id = $this->_param('order_id');
		$visitor_mobile = $this->_param('mobile');
        $visitor_name = $this->_param('visitor_name');
		
		$kecheng_order = new Model("KechengOrder");
		$data['visitor_mobile'] = $visitor_mobile;
		$data['visitor_name'] = $visitor_name;
		
		$kecheng_order->data($data)->where('order_id='.$order_id)->save();
		
		redirect("/Member/Payment/alipayto/oid/".$order_id);
		//$this->display();
	} 

    public function payment(){
        $data_order = $_SESSION['data_order'];

        //print_r($data_order);die;
        $course = new Model("Course");
        $sql = "SELECT cat.title as category_title,c.title as course_title FROM course c ".
                "LEFT JOIN category cat ON c.category_id=cat.pkid WHERE c.course_id=".$data_order['course_id'];
        $this->assign('data_course',$course->query($sql));

        $province_city = new Model("ProvinceCity");
        $this->assign('data_area',$province_city->where("item_id=".$data_order['area_id'])->select());

        $user = new Model("User");
        $this->assign('data_user',$user->where("pkid=".$data_order['user_id'])->select());

        $this->assign('data_order',$data_order);

        $this->display();
    }
    
	public function cancel(){
		
		$oid = $this->_param('oid');
		$user_id = $_SESSION['user_info']['pkid'];
		
		
		if(!$oid){
			redirect("/Member/KechengOrder");
		}
		
		$kecheng_order = new Model("KechengOrder");
		$data = $kecheng_order->where("order_id=".$oid." AND user_id=".$user_id." AND order_status in(0,1)")->select();
		if(!$data){
			redirect("/Member/KechengOrder");
		}else{
		//print_r($data);
			$this->assign('data',$data[0]);
			$this->display();
		}
	}
	
	public function cancel_confirm(){
		
		$oid = $this->_param('order_id');
		$user_id = $_SESSION['user_info']['pkid'];
		
		$kecheng_order = new Model("KechengOrder");
		$data = $kecheng_order->where("order_id=".$oid." AND user_id=".$user_id." AND order_status in(0,1)")->select();
		if(!$data){
			redirect("/Member/KechengOrder");
		}else{
			//$sql_u = "UPDATE "
			$condition['order_id'] = $oid;
			$condition['order_status'] = 4;//已撤销
			$condition['cancel_time'] = date("Y-m-d H:i:s");
			$condition['cancel_reason'] = $this->_param('cancel_reason');
			
			$kecheng_order->save($condition);
			
			$msg = mb_convert_encoding("订单已撤销。","GB2312","UTF-8");
			print "<script language=\"javascript\">alert('$msg');location.href='/Member/KechengOrder';</script>";
			exit;
		}
	}
}