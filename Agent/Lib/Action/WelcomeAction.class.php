<?php

class WelcomeAction extends Action {
    public function index(){
		
		if(!$_SESSION['agent_account']){
			redirect("/Agent/Index/index");
		}
		
	    	$this->assign('account',$_SESSION['agent_account']['UserName']);
	    	$this->assign('inner_url','/Agent/Index/mybase');
    	
		$this->display();
    }
    
}
?>