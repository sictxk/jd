<?php

class IndexAction extends Action {
    public function index(){
		$this->display('login');
    }
    
    
    public function login(){
    	$account = $_POST['account'] ? trim($_POST['account']) : '';
    	$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';
    	
    	$admin = $admin = new Model("Admin");
    	$arr_form = $admin->query("SELECT * FROM admin WHERE account='".$account."' and password='".$password."'");
    	
    	if(!$arr_form){
    		$msg = mb_convert_encoding("您输入的账号或密码不正确，请重新输入!","GB2312","UTF-8");
    		print "<script language=\"javascript\">alert('$msg');location.href='/Backend/index.php';</script>";
    		exit;
    	}else{
    		$_SESSION['account'] = $account;
    		
    		//$msg = mb_convert_encoding("登陆成功","GB2312","UTF-8");
    		//redirect("/Backend/Welcome/index",1,$msg);
    		redirect("/Backend/Welcome/index");
    	}
		//$this->display('login');
    }
    
    public function logout(){
    	$_SESSION['account'] = '';
    	//$msg = mb_convert_encoding("退出成功","GB2312","UTF-8");
    	//redirect("/Backend/Index/index",2,$msg);
    	redirect("/Backend/Index/index");
    }
    
}
?>