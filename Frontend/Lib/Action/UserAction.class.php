<?php
header("Content-type: text/html;charset=GB2312");
class UserAction extends Action {
	public function _initialize(){
		//$this->assign('category_tree', D('AgencyCategory')->getCategoryTree());
	} 
	public function index(){
		redirect("/Frontend/User/home");
	}
	
    public function regist(){
		$student_id = $_SESSION['user_info']['pkid'];
    	if($student_id!=''){
    		redirect("/Frontend/");exit;
    	}
		$data_user = array();
		$data_user['email'] = '';
		$data_user['login_pass'] = '';
		$this->assign('data_user',$data_user);
		$this->display();
    }
    public function registerConfirm(){
		

		$user = new Model("User");
		
		$email  = $this->_param('email');
		$data = $user->where("email='".$email."'")->find();
		if(!empty($data)){
			$msg = mb_convert_encoding("该邮箱已注册账号，请换个邮箱注册或者找回原账号密码。","GB2312","UTF-8");
			print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/regiser';</script>";
			exit;
		}
		
		$mobile  = $this->_param('mobile');
		$data2 = $user->where("mobile='".$mobile."'")->find();
		if(!empty($data2)){
			$msg = mb_convert_encoding("该手机已注册账号，请换个手机或者找回原账号密码。","GB2312","UTF-8");
			print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/regiser';</script>";
			exit;
		}
		
		$user->create();
		$user->password = $this->_param('password');
        $user->truename = $this->_param('truename');
        $user->email = $this->_param('email');
        $user->mobile = $this->_param('mobile');
        $user->school = $this->_param('school');
		$user->status = 2;
		$user->ctime = date('Y-m-d H:i:s');
		$user->add();
		
		//$content = $user->nickname."，您好：\r\t"." 感谢注册复爵教育网！";
		//SendMail($user->email,"复爵教育网注册成功",$content);
		
		$msg = mb_convert_encoding("您已注册成功，我们会尽快联络您","GB2312","UTF-8");
		$ref_url = base64_decode("/Frontend");
		print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/Index';</script>";
		exit;
    }
    
    public function login(){
		
    	$student_id = $_SESSION['user_info']['pkid'];
    	if($student_id!=''){
    		redirect("/Frontend/User/profile");exit;
    	}
		
		$_SESSION["HTTP_REFERER"] = $this->_param('ref_url') ? base64_decode($this->_param('ref_url')) : $_SERVER["HTTP_REFERER"];
		//print_r($_COOKIE['user_info']);
		$this->assign('data_user',$_COOKIE['user_info']);
		$this->display();
		
    }
    
    public function login_verify(){
	
	
    	$username = $_POST['username'] ? trim($_POST['username']) : '';
    	$password = $_POST['password'] ? trim($_POST['password']) : '';
    	
	    $user = new Model("User");
           $sql = "SELECT * FROM user WHERE (account='".$username."' OR mobile='".$username."' OR email='".$username."') AND password='".$password."'";
	    $user_info =  $user->query($sql);
    	if($user_info[0]['pkid']==''){
    		$msg = mb_convert_encoding("您输入账号或密码错误,请重新输入！","GB2312","UTF-8");
    		$rand_num = rand(1,50);
    		print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/login/r/$rand_num';</script>";
    		exit;
    	}else{
		/*$userId = $user_info[0]['pkid'];
    		$loginLog = D("LoginLog");
    		$macList = $loginLog->getMacLog($userId);
    		
    		import("@.ORG.Util.MacAddress");
    		$mac = new MacAddress(PHP_OS);   
		    $mac_address = $mac->mac_addr;
    		if(sizeof($macList)==3 && !in_array($mac_address,$macList)){
    			$msg = mb_convert_encoding("为了保障您的账号安全，每号仅限三台电脑使用！","GB2312","UTF-8");
	    		print "<script language=\"javascript\">alert('$msg');location.href='/';</script>";
	    		exit;
    		}
    		$logData['user_id'] = $userId;
    		$logData['mac_address'] = $mac_address;
    		$logData['login_time'] = date("Y-m-d H:i:s");
    		$loginLog->addLog($logData);
    		*/
            $grade = new Model("Grade");
            $grade_info = $grade->where('pkid='.$user_info[0]['grade_id'])->find();
            $user_info[0]['grade_title'] = $grade_info['title'];
            $user_info[0]['grade_stage'] = mb_substr($grade_info['title'],0,2,'utf-8');
            $user_info[0]['grade_year'] = mb_substr($grade_info['title'],2,3,'utf-8');
    		$_SESSION['user_info'] = $user_info[0];
    		/*$http_referer = strpos($_SESSION["HTTP_REFERER"],'forget') ? '/' : $_SESSION["HTTP_REFERER"];
            echo $http_referer;echo 'a';*/
			redirect('/Frontend/Learning');
			exit;
    	}
    }
    
    public function logout(){
    	$_SESSION['user_info'] = '';
    	$_COOKIE['user_info'] = '';
		unset($_SESSION['user_info']);
		unset($_COOKIE['user_info']);
		
		//setcookie("user_info[pkid]", $user_info['pkid'], time()-3600*24);
		//setcookie("user_info[mobile]",$user_info['mobile'], time()-3600*24);
		//setcookie("user_info[login_pass]",$_POST['login_pass'], time()-3600*24);
		//setcookie("user_info[is_teacher]",$user_info['is_teacher'], time()-3600*24);
		//setcookie("user_info['email']",$user_info['email'], time()-3600*24);
		//setcookie("user_info['login_pass']",$_POST['login_pass'], time()-3600*24);
		
		$msg = mb_convert_encoding("已退出本次登录","GB2312","UTF-8");
		print "<script language=\"javascript\">alert('$msg');location.href='/Frontend';</script>";
    	exit;
    }
    
    
    public function forget(){
   		//SendMail($condition['email'],"复爵教育网找回密码",$content);
    	/*$student_id = $_SESSION['user_info']['pkid'];
    	if($student_id!=''){
    		redirect("/Frontend/User/profile");exit;
    	}*/
   		
		$this->display();
    }
    
    public function forget_get(){
    	
    	/*$vcode = $this->_param('vcode');
    	if($vcode!=$_SESSION["ses_vcode"]){
			$msg = mb_convert_encoding( "验证码输入有误，请重新输入","GB2312","UTF-8");
			print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/forget';</script>";
    		exit;
    	}*/
    	$method = $this->_param('method');
    	$condition['email'] = $this->_param('email');
    	$condition['mobile'] = $this->_param('mobile');
    	
   		$user = new Model('User');
   		if($method=='email'){
	   		$data = $user->where("email='".$condition['email']."'")->find();
	   		if(empty($data)){
				$msg = mb_convert_encoding("系统查不到此邮件注册的账号，请重新输入","GB2312","UTF-8");
				print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/forget';</script>";
	    		exit;
	   		}else{
	   			
	   			$new_pass = rand(100000,999999);
	   			$new_pass_md = md5($new_pass);
	   			$user->query("UPDATE user SET login_pass='$new_pass_md' WHERE email='".$condition['email']."'");
	   			
	   			setcookie("user_info[login_pass]","", time()-3600*24);
	   					
				$content = $data['nickname']."，您好：\r\t"." 您的临时密码为".$new_pass.",为了您的账户安全，请登录后尽快修改密码。";
				
				SendMail($condition['email'],"复爵教育网找回密码",$content);
	   			
	   			$msg = mb_convert_encoding("已将新的密码发送至您的邮箱，请查收登录！","GB2312","UTF-8");
				print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/login';</script>";
	    		exit;
	   		}
   		}elseif($method=='mobile'){
	   		$data = $user->where("mobile='".$condition['mobile']."'")->find();
	   		if(empty($data)){
				$msg = mb_convert_encoding("系统查不到此手机号的账号，请重新输入","GB2312","UTF-8");
				print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/forget';</script>";
	    		exit;
	   		}else{
	   			
	   			$new_pass = rand(100000,999999);
	   			$new_pass_md = md5($new_pass);
	   			$user->query("UPDATE user SET login_pass='$new_pass_md' WHERE mobile='".$condition['mobile']."'");
	   			
	   			setcookie("user_info[login_pass]","", time()-3600*24);
	   					
				$content = $data['nickname']."，您好：\r\t"." 您的临时密码为".$new_pass.",为了您的账户安全，请登录后尽快修改密码。";
				
				$this->SendSms($condition['mobile'],"复爵教育网找回密码",$content);
	   			
	   			$msg = mb_convert_encoding("已将新的密码发送至您的手机，请查收！","GB2312","UTF-8");
				print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/login';</script>";
	    		exit;
	   		}
   			
   		}
		//$this->display();
    }
    
	public function SendSms($mobile,$content){
		
		import('@.ORG.Util.SmsApi');
        $sms_api = new SmsApi();
        $postData['mobile'] = $mobile;
        $postData['content'] = $content;
        $responseBody = $sms_api->getApiResponse($postData);
        
		$sms_log = new Model("SmsLog");
		$sms_log->create();
		$sms_log->mobile = $mobile;
		$sms_log->content = $content;
		$sms_log->result = $responseBody;
		$sms_log->ctime = date("Y-m-d H:i:s");
		$sms_log->add();
		
        return $responseBody;
        
	}
    
    public function profile(){
    	$user = new Model("User");
    	
    	$basic_info = $user->query("SELECT * FROM user WHERE pkid='".$_SESSION['user_info']['pkid']."'");
		if($basic_info[0]['avatar']!=''){
			$icon_avatar = str_replace("/Public/Upload/Avatar/","/Public/Upload/Avatar/128_",$basic_info[0]['avatar']);
			$root_path = dirname(dirname(dirname(dirname(__FILE__))));
			if(!file_exists($root_path.$icon_avatar)){
				//图片裁切为指定尺寸
				import('ORG.Util.Image'); 
				$img = new Image; 
				$img->thumb($root_path.$basic_info[0]['avatar'],$root_path.$icon_avatar,'',128, 128,true);
			}
			$basic_info[0]['avatar'] = $icon_avatar;
		}
		$this->assign('basic_info',$basic_info[0]);
    	
    	$this->assign('gendar',array('M'=>'男','F'=>'女'));
    	$this->assign('gendar_value',$basic_info[0]['gendar']);


        $user_score = new Model("UserScore");
        $sql="SELECT sum(score) as total FROM user_score WHERE user_id=".$_SESSION['user_info']['pkid'];
        $data = $user_score->query($sql);
        $total_score = ($data[0]['total']>0) ? $data[0]['total'] : 0;
        $this->assign('total_score',$total_score);

		$this->display();
    }
    
    public function profile_confirm(){
    	
		$user = new Model("User");
		$data['pkid'] = $_SESSION['user_info']['pkid'];
		if($this->_param('nickname')!=''){
			$data['nickname'] = $this->_param('nickname');
		}
		/*if($this->_param('truename')!=''){
			$data['truename'] = $this->_param('truename');
		}
		if($this->_param('birth_date')!=''){
			$data['birth_date'] = $this->_param('birth_date');
		}*/
		if($this->_param('email')!=''){
			$data['email'] = $this->_param('email');
		}
		
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 1048576 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$up_path = dirname(dirname(dirname(dirname(__FILE__)))).'/Public/Upload/Avatar/';// 设置附件上传目录;
		$upload->savePath =  $up_path;
		$upload->saveRule =  time();
		
		if($_FILES['avatar']['name']!=''){
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg()); 
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$data['avatar'] = '/Public/Upload/Avatar/'.$info[0]['savename'];
				
				//图片裁切为指定尺寸
				import('ORG.Util.Image'); 
				$img = new Image; 
				$img->thumb($up_path.$info[0]['savename'],$up_path."40_".$info[0]['savename'],'',40, 40,true);
				$img->thumb($up_path.$info[0]['savename'],$up_path."68_".$info[0]['savename'],'',68, 68,true);
				$img->thumb($up_path.$info[0]['savename'],$up_path."100_".$info[0]['savename'],'',100, 100,true);
				$img->thumb($up_path.$info[0]['savename'],$up_path."128_".$info[0]['savename'],'',128, 128,true);				
			}
		}
		$data['modify_time'] = date('Y-m-d H:i:s');
		$user->save($data);
		
		
		$user_info =  $user->where("pkid=".$_SESSION['user_info']['pkid'])->select();
		$_SESSION['user_info'] = $user_info[0];
		
		$msg = "OK";
		print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/profile';</script>";
		exit;
		
    }
    
    public function changepass(){
   		if(!$_SESSION['user_info']['pkid']){
   			redirect('/Frontend/User/login');exit;
   		}
   		
    	$user = new Model("User");
    	
    	$basic_info = $user->query("SELECT * FROM user WHERE pkid='".$_SESSION['user_info']['pkid']."'");
    	
		$this->assign('basic_info',$basic_info[0]);
   		

        $user_score = new Model("UserScore");
        $sql="SELECT sum(score) as total FROM user_score WHERE user_id=".$_SESSION['user_info']['pkid'];
        $data = $user_score->query($sql);
        $total_score = ($data[0]['total']>0) ? $data[0]['total'] : 0;
        $this->assign('total_score',$total_score);
   		
		$this->display();
    }
    
    public function changepass_done(){
   		
    	$condition['login_pass'] = $_POST['old_pass'] ? md5(trim($_POST['old_pass'])) : '';
    	$condition['pkid'] = $_SESSION['user_info']['pkid'] ;
    	
		$user = M('User');
		$user_info =  $user->where($condition)->select();
    	
    	if(empty($user_info)){
    		$msg = mb_convert_encoding("您输入的旧密码不正确，请重新输入","GB2312","UTF-8");
   			print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/changepass';</script>";
			exit;
    	}else{
    		$data['pkid'] = $_SESSION['user_info']['pkid'] ;
    		$data['login_pass'] = $_POST['new_pass'] ? md5(trim($_POST['new_pass'])) : '';
			
			$user->save($data);
			
    		$msg = "OK";
   			print "<script language=\"javascript\">alert('$msg');location.href='/Frontend/User/changepass';</script>";
			exit;
    	}
    }
    
    function test(){
    	echo md5('123456');
    }
    
}

?>