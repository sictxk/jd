<?php

class GuideAction extends Action {
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
    public function fee(){
		$this->display();
    }
	
	public function process(){
		$this->display();
	}
}