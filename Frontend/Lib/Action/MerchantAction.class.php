<?php

class MerchantAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
    public function join(){
		$this->display();
    }
	
    public function joinConfirm(){
		
		$contact = new Model("Contact");
		$contact->create();
		$contact->pkid = '';
		$contact->agency_name = $this->_param('agency_name');
		$contact->agency_address = $this->_param('agency_address');
		$contact->contact = $this->_param('contact');
		$contact->mobile = $this->_param('mobile');
		$contact->deal_status = 0;
		$contact->ctime = date("Y-m-d H:i:s");
		$contact->add();
		print "<script language=\"javascript\">alert('提交成功，我们会及时与您联系');location.href='/Member/Merchant/join';</script>";
		exit;
    }
}