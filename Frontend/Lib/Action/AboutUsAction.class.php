<?php

class AboutUsAction extends Action {
	
	public function _initialize(){
		$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
    public function index(){
		$this->display();
    }
	
	public function contact(){
		$this->display();
    }
    
    
	public function register(){
		$this->display();
    }
    
	public function service(){
		$this->display();
    }
}