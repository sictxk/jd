<?php
/*
* 请求CRM接口
*/
header("Content-type: text/html; charset=utf-8");
class SmsApi {

	public function __construct() {

	}
	/**
	 * 查询微信token
	 *
	 * @return string
	 */
	public function getApiResponse($data=array()) {
        $url = 'http://121.199.1.69:8801/sms.aspx';
        $postData = array();
        $postData = $data;
        
        $postData['userid'] = '1982';
        $postData['account'] = 'yitu';
        $postData['password'] = '147258';
        $postData['sendTime'] = '';
        $postData['action'] = 'send';
        $postData['extno'] = '';
        
        $res = $this->action($url,$postData);
        return $res;
	}



    public function action($url,$params = array()){
        $ch = curl_init();
        $params = http_build_query($params);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close ($ch);
        unset($ch);
        
        return $result;
    }
}

?>