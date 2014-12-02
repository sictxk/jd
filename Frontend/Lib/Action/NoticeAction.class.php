<?php

class NoticeAction extends Action {
    public function index(){
		$this->display('list');
    }

	public function detail() {
		$Notice = D('Notice');
		$data = $Notice->where('pkid='.$_REQUEST['pkid'])->find();
		$this->assign('data', $data);
		
		$this->display('read');
    }
	

}