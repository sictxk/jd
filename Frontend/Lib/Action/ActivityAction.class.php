<?php
class ActivityAction extends Action {
	
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
		$this->assign("user_id",$_SESSION['user_info']['pkid']);
	} 
	
	public function hero(){
		
		$this->assign("agency_code",$this->_param('code'));
		
		$this->display();
	}
	
	public function heroConfirm(){
		
		$agency_error = new Model("AgencyError");
		if($this->_param('error_type')!=''){
			$agency_error->create();
			$agency_error->pkid = '';
			$agency_error->error_type = implode(",",$this->_param('error_type'));
			$agency_error->deal_status = 0;
			$agency_error->ctime = date("Y-m-d H:i:s");
			$agency_error->add();
		}
		print "<script language=\"javascript\">alert('提交成功，谢谢参与');location.href='/Member/Activity/hero';</script>";
		exit;
	}
	
	public function leifeng(){
		
		$this->display();
	}
	
    public function leifengConfirm(){
		$leifeng = new Model("Leifeng");
		if($this->_param('agency_title')){
			$leifeng->create();
			$leifeng->pkid = '';
			$leifeng->deal_status = 0;
			$leifeng->ctime = date("Y-m-d H:i:s");
			$leifeng->add();
		}
		print "<script language=\"javascript\">alert('提交成功，谢谢参与');location.href='/Member/Activity/leifeng';</script>";
		exit;
	}
	
	public function may(){
		
		$activity_order = new Model("ActivityOrder");
		
		$activity_order->create();
		$activity_order->bespeak_status=1;
		$activity_order->ctime=date('Y-m-d H:i:s');
		$activity_order->add();
		
		echo 'ok';
	}
	
	public function regist(){
		
		$user_id = $_SESSION['user_info']['pkid'];
		if($user_id!=''){
			redirect('/Member/Activity/share');exit;
		}
		$this->display('regist');
	}
	
	public function share(){
		
		$user_id = $_SESSION['user_info']['pkid'];
		if($user_id==''){
			redirect('/Member/Activity/regist');exit;
		}
		
		$this->display('share');
	}
	
}
?>