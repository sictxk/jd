<?php

class IndexAction extends Action {
    public function index(){
		$this->display('login');
    }
    
    public function login(){
    	$account = $_POST['account'] ? trim($_POST['account']) : '';
    	$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';
    	
    	$Agent = new Model("Agent");
    	$arr_form = $Agent->query("SELECT * FROM agent WHERE UserName='".$account."' and PassWD='".$password."'");
    	
    	if(!$arr_form){
    		$msg = mb_convert_encoding("您输入的账号或密码不正确，请重新输入!","GB2312","UTF-8");
    		print "<script language=\"javascript\">alert('$msg');location.href='/Agent/index.php';</script>";
    		exit;
    	}else{
    		$_SESSION['agent_account'] = $arr_form[0];
    		redirect("/Agent/Welcome/index");
    	}
    }
    
    public function logout(){
    	$_SESSION['agent_account'] = '';
    	redirect("/Agent/Index/index");
    }
    
    public function mybase(){
        $this->assign('arr_form',$_SESSION['agent_account']);
    	$this->display('mybase');
    }
}
?>