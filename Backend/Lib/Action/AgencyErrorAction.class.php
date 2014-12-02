<?php

class AgencyErrorAction extends Action {
    public function index(){
    	$map = array();
		$map['agency_code'] = !empty($_POST['agency_code']) ? $_POST['agency_code'] : (!empty($_GET['agency_code']) ? $_GET['agency_code'] : '');
		$map['deal_status'] = !empty($_POST['deal_status']) ? $_POST['deal_status'] : (!empty($_GET['deal_status']) ? $_GET['deal_status'] : '');
		
		$map_sql = "SELECT ae.*,u.nickname,c.title as agency_title ".
                    " FROM agency_error ae LEFT JOIN agency c ON ae.agency_id=c.pkid ".
                    " LEFT JOIN user u ON ae.user_id=u.pkid WHERE ae.pkid>0 ";
		$map_sql_count = "SELECT count(ae.pkid) as num FROM agency_error ae LEFT JOIN agency c ON ae.agency_id=c.pkid WHERE ae.pkid>0 ";
		if(!empty($map['agency_code'])){
			$map_sql .= " AND c.code = '".$map['agency_code']."'";
			$map_sql_count .= " AND c.code = '".$map['agency_code']."'";
		}
		if(!empty($map['deal_status'])){
		 	$map_sql .= " AND ae.deal_status='".$map['deal_status']."'";
		 	$map_sql_count .= " AND ae.deal_status='".$map['deal_status']."'";
		}
		
    	$this->assign('map',$map);
    	$this->assign('value',$map['error_type']);
    	
		$cur_page = $_GET['p'] ? $_GET['p'] : 1;
		$page_size = 10;
		
		$map_sql .= "  order by ae.pkid desc limit ".($cur_page-1)*$page_size.",".$page_size;
		
		$error = new Model("AgencyError"); 
		$list = $error->query($map_sql);
		
		$error_type = array('1'=>'名称有误','2'=>'电话有误','3'=>'地址有误');
		foreach($list as $k=>$v){
			$err = explode(',',$v['error_type']);
			$str_err = $error_type[$err[0]]." ".$error_type[$err[1]]." ".$error_type[$err[2]];
			$list[$k]['error'] = $str_err;
		}
		
		$this->assign('error_list',$list);
		import("ORG.Util.Page");
		
		$data_count  = $error->query($map_sql_count);
		$Page = new Page($data_count[0]['num'],$page_size);
		
		
		$show       = $Page->show();
		$this->assign('page',$show);
		
    	$this->assign('deal_status', array('0'=>'未处理','1'=>'已确认','2'=>'已解决'));
    	
		$this->display();
    }

    
    public function edit(){
    	$pkid = $this->_param('pkid');
    	$AgencyError = new Model("AgencyError");
    	$arr_form = $AgencyError->query('SELECT * FROM AgencyError WHERE pkid='.$pkid);
    	
    	$this->assign('arr_form',$arr_form[0]);
    	
    	$this->assign('status', array('Y'=>'显示','N'=>'隐藏'));
        $this->assign('AgencyError_type', array(1=>'前台',2=>'商家'));
    	$this->assign('value', $arr_form[0]['status']);
        $this->assign('type_value', $arr_form[0]['type']);

		
		$this->display();
    }

    
    
    public function renew(){
    	
		$AgencyError = new Model("AgencyError");
		$data['pkid'] = $this->_param('pkid');
		$data['title'] = $this->_param('title');
		$data['author'] = $this->_param('author');
		$data['source'] = $this->_param('source');
		$data['intro'] = $this->_param('intro');
		
		$data['context'] = $this->_param('context');
		$status = $this->_param('status');
		$data['status'] = $status[0];

        $type = $this->_param('type');
        $data['type'] = $type[0];
		
		
		//print_r($data);die;
		$AgencyError->save($data);
		
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",2,$msg);
		
    }
    
    
    public function remove(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$AgencyError = new Model("AgencyError");
		
		$sql = 'DELETE FROM AgencyError WHERE pkid='.$pkid;
		//echo $sql;die;
		$AgencyError->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",1,$msg);
    }
    
    public function confirm(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$AgencyError = new Model("AgencyError");
		
		$d_time = date("Y-m-d H:i:s");
		$sql = "UPDATE agency_error SET deal_status=1 ,confirm_time='".$d_time."' WHERE pkid=".$pkid;
		//echo $sql;die;
		$AgencyError->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",1,$msg);
    }
    
    public function deal(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$AgencyError = new Model("AgencyError");
		
		$d_time = date("Y-m-d H:i:s");
		$sql = "UPDATE agency_error SET deal_status=2 ,deal_time='".$d_time."' WHERE pkid=".$pkid;
		//echo $sql;die;
		$AgencyError->query($sql);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",1,$msg);
    }
    
    public function hide(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$AgencyError = new Model("AgencyError");

		$AgencyError->query("UPDATE AgencyError SET status='N' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",1,$msg);
    	//redirect("/Backend/AgencyError/index");
    }
    
    public function show(){
    	
    	$pkid = $this->_param('pkid'); 
    	
		$AgencyError = new Model("AgencyError");

		$AgencyError->query("UPDATE AgencyError SET status='Y' WHERE pkid=".$pkid);
    	
		$msg = mb_convert_encoding("OK","UTF-8","GB2312");
    	redirect("/Backend/AgencyError/index",1,$msg);
    	//redirect("/Backend/AgencyError/index");
    }
    

}
?>