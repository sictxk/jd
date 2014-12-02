<?php
header("Content-type: text/html;charset=utf-8");
class AgencyOrderAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
    public function index(){
		
		$user_id = $_SESSION['user_info']['pkid'];
		if(!$user_id){
			$msg = "请先登录。";
			print "<script language=\"javascript\">alert('$msg');location.href='/Member/User/login';</script>";
			exit;
		}
 		$agency_order = new Model("AgencyOrder");
 		
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size  = 10;
 		
 		$order_sql = "SELECT oi.*,t.code as agency_code,t.title,t.vouchsafe,t.telephone,t.address,pc.name as area_name FROM agency_order oi ".
                       " LEFT JOIN agency t ON oi.agency_id=t.pkid  LEFT JOIN province_city pc ON t.area_id=pc.item_id  ".
 						" WHERE oi.user_id=".$user_id." order by oi.order_id desc limit ".($cur_page-1)*$page_size.",".$page_size;
 		$data_order = $agency_order->query($order_sql);//print_r($data_order);
 		$this->assign('data_order',$data_order);
		
		import("ORG.Util.Page");
		
		$count_sql = "SELECT count(order_id) as total FROM agency_order WHERE user_id=".$user_id;
		$count_num = $agency_order->query($count_sql);
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
        /*if($_SESSION['bespeak_post']!=''){
        	$arr_form = $_SESSION['bespeak_post'];
        	$arr_form['user_id']= $_SESSION['user_info']['pkid'];
        }else{*/
	        $form_keys = array('agency_id','visitor_name','visitor_mobile','user_id','bespeak_date');//print_r($form_keys);
	        foreach($form_keys as $key=>$val){
	            $arr_form[$val] = $this->_param($val);
	        }
        //}
        //print_r($arr_form);die;
		if($arr_form['user_id']==''){
			$_SESSION['bespeak_post'] = $arr_form;
			$msg = "请先登录账户";
			$ref_url = base64_encode('/Member/AgencyOrder/confirm');
            print "<script language=\"javascript\">alert('$msg');location.href='/Member/User/login/ref_url/".$ref_url."';</script>";
            exit;
		}

        $user = new Model("User");
        $this->assign('data_user',$user->where("pkid=".$arr_form['user_id'])->select());

        $agency = new Model("Agency");
        $sql = "SELECT ag.*,ab.title as brand_title,pc.name as area_name FROM agency ag LEFT JOIN agency_brand ab ON ag.brand_id=ab.pkid ".
            " LEFT JOIN province_city pc ON ag.area_id=pc.item_id WHERE ag.pkid=".$arr_form['agency_id'];
        $data_agency = $agency->query($sql);
        $this->assign('data_agency',$data_agency[0]);

        $this->assign('arr_form',$arr_form);

        $this->display();
    }

    public function bespeak_done(){

		$agency_id = $this->_param('agency_id');
		$user_id = $this->_param('user_id');
		
		//$bespeak_date = $this->_param('bespeak_date');
		$visitor_mobile = $this->_param('mobile');
        $visitor_name = $this->_param('visitor_name');
        //$visitor_age = $this->_param('visitor_age');
		$bespeak_code = 'YT'.date('md').rand(100,999);

		$agency = new Model("Agency");
		$data_agency = $agency->where("pkid=".$agency_id)->find();
		
		
		$agency_order = new Model("AgencyOrder");
		$agency_order->create();
        $order_sn = date("YmdHis").rand(10,99);
		$agency_order->order_sn = $order_sn;
		$agency_order->agency_id = $agency_id;
		$agency_order->user_id = $user_id;

		//$agency_order->bespeak_date = $bespeak_date;
		$agency_order->visitor_mobile = $visitor_mobile;
        $agency_order->visitor_name = $visitor_name;
        //$agency_order->visitor_age = $visitor_age;

		$agency_order->bespeak_code = $bespeak_code;
		$agency_order->verify_status = 1;
		$agency_order->order_type = 3;//试课预约
        //$agency_order->vouchsafe = $data_agency[0]['vouchsafe'];

		$agency_order->ctime = date("Y-m-d H:i:s");
		
		$agency_order->add();

        /*$data_order = $agency_order->query("SELECT * FROM agency_order WHERE order_sn='".$order_sn."'");
        $_SESSION['data_order'] = $data_order[0];*/
        
        
        
        
        /*$postData = array();
        $postData['mobile'] = $visitor_mobile;
        $postData['content'] = "【伊兔网】试课预约码：".$bespeak_code."，请凭此前往".$data_agency['title']."免费试课，地址在".$data_agency['address']."。";
        
        import('@.ORG.Util.SmsApi');
        $sms_api = new SmsApi();
        $res = $sms_api->getApiResponse($postData);
        
		$sms_log = new Model("SmsLog");
		$sms_log->create();
		$sms_log->mobile = $postData['mobile'];
		$sms_log->content = $postData['content'];
		$sms_log->result = $res;
		$sms_log->ctime = date("Y-m-d H:i:s");
		$sms_log->add();*/
        
		if($user_id!=''){
			redirect("/Member/AgencyOrder");
		}else{
			$msg = "提交成功，请耐心等待，小伊会尽快联系您，与您核对信息。";
			print "<script language=\"javascript\">alert('$msg');location.href='/Member/Agency/show/id/".$agency_id."';</script>";
			exit;
		}
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
			redirect("/Member/AgencyOrder");
		}
		
		$agency_order = new Model("AgencyOrder");
		$data = $agency_order->where("order_id=".$oid." AND user_id=".$user_id." AND order_status in(0,1)")->select();
		if(!$data){
			redirect("/Member/AgencyOrder");
		}else{
		//print_r($data);
			$this->assign('data',$data[0]);
			$this->display();
		}
	}
	
	public function cancel_confirm(){
		
		$oid = $this->_param('order_id');
		$user_id = $_SESSION['user_info']['pkid'];
		
		$agency_order = new Model("AgencyOrder");
		$data = $agency_order->where("order_id=".$oid." AND user_id=".$user_id." AND order_status in(0,1)")->select();
		if(!$data){
			redirect("/Member/AgencyOrder");
		}else{
			//$sql_u = "UPDATE "
			$condition['order_id'] = $oid;
			$condition['order_status'] = 4;//已撤销
			$condition['cancel_time'] = date("Y-m-d H:i:s");
			$condition['cancel_reason'] = $this->_param('cancel_reason');
			
			$agency_order->save($condition);
			
			$msg = "订单已撤销。";
			print "<script language=\"javascript\">alert('$msg');location.href='/Member/AgencyOrder';</script>";
			exit;
		}
	}
}