<?php
/*
 * 短信接口调用
 * 
 */
class SmsAction extends Action {

    /*
    * 会员激活发送验证码
    */
    public function sendVerifyCode(){
		
		$mobile = $_POST['mobile'];
		$verifyCode = rand(100000,999999);
		$content = '【伊兔网】验证码：'.$verifyCode.'（当次有效）。';
		$res = $this->sendSms($mobile,$content);
		
		$sms_log = new Model("SmsLog");
		$sms_log->create();
		$sms_log->mobile = $mobile;
		$sms_log->content = $content;
		$sms_log->result = $res;
		$sms_log->ctime = date("Y-m-d H:i:s");
		$sms_log->add();
		
		if(strpos($res,"Success")!==false){
        	echo $verifyCode;
        }
    }
    
    /*
    * 校验用户短信验证码
    */
    public function checkVerifyCode(){
		$mobile = $_POST['mobile'];
		$verifyCode = $_POST['verifyCode'];
        if($_SESSION[$mobile]==$verifyCode){
            echo 'ok';die;
        }
    }
    
    /*
    * 通过CRM API发送短信
    */
    public function sendSms($mobile,$content){
    	
        $postData = array();
        $postData['mobile'] = $mobile;
        $postData['content'] = $content;
        
        import('@.ORG.Util.SmsApi');
        $sms_api = new SmsApi();
        $responseBody = $sms_api->getApiResponse($postData);
        return $responseBody;
    }
    
    public function test(){
    	$this->display('test');
    }
}

?>