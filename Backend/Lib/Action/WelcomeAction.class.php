<?php

class WelcomeAction extends Action {
    public function index(){
		
		if(!$_SESSION['account']){
			redirect("/Backend/Index/index");
		}
		
    	$this->assign('account',$_SESSION['account']);
    	$this->assign('inner_url','/Backend/Grade/index');
    	
		$this->display();
    }
    
}
?>