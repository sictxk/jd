<?php
/**
 * 单点登录类
 *
 */
class Sso {
	/**
	 * 用户UID
	 *
	 * @var string
	 */
	var $userid="";

	/**
     * 用户帐号
     *
     * @var string
     */
	var $username = "";

	/**
     * 用户访问时间
     *
     * @var int
     */
	var $logintime = 0;

	/**
	 * 防沉迷状态
	 *
	 * @var int
	 */
	var $addictedstatus = 0;

	/**
     * 用户令牌
     *
     * @var string
     */
	var $ssotoken = "";

	function __construct(){}

	/**
   	 * 生成用户令牌
   	 *
     * @param string $userid 用户ID
     * @param string $username 用户名
     * @param string $addictedstatus 防沉迷状态
   	 * @param int $time TOKEN保存时间
   	 * @param int $iscookie 是否写COOKIE
   	 * @return string 返回TOKEN
   	 */
	public function createToken($userid,$username,$addictedstatus=0,$time=0,$iscookie=true)
	{
		$this->userid=$userid;
		$this->username = $username;
		$this->addictedstatus = $addictedstatus;
		$this->logintime=time();

		$tokeninfo = array(
			'userid' => $this->userid,
			'username' => $this->username,
			'addictedstatus' => $this->addictedstatus,
			'logintime' => $this->logintime,
		);

		$tokeninfo = json_encode($tokeninfo);
		$this->ssotoken = $this->encrypt($tokeninfo,SSO_KEY);

		if($iscookie)
			$this->setTokenCookie("",$this->ssotoken,"",$time);
		$this->getToken($this->ssotoken);

		return $this->ssotoken;
	}

	/**
     * 注销TOKEN
     *
     */
	public function cancelToken()
	{
		$this->deleteTokenCookie(SSO_NAME);
	}

	/**
     * 分解用户SSOTOKEN
     *
     * @param string $ssotoken 客户令牌
     */
	public function getToken($ssotoken="") {
		if(empty($ssotoken))
		$this->ssotoken = @$_COOKIE[SSO_NAME];
		else
		$this->ssotoken = $ssotoken;
		if(empty($this->ssotoken))
		{
			return;
		}

		$tokeninfo = $this->decrypt($this->ssotoken,SSO_KEY);
		if(empty($tokeninfo))
		return;

		$tokenvalue = json_decode(trim($tokeninfo),true);
		if(!is_array($tokenvalue)){
			$this->userid="";
			$this->username = "";
			$this->addictedstatus = "";
			$this->logintime="";
			return;
		}

		$this->userid = $tokenvalue['userid'];
		$this->username = $tokenvalue['username'];
		$this->addictedstatus = $tokenvalue['addictedstatus'];
		$this->logintime = $tokenvalue['logintime'];
	}

	/**
	 * 获取TOKEN串
	 *
	 * @return string
	 */
	public function getSsoToken()
	{
		if(empty($this->ssotoken))
		$this->ssotoken = @$_COOKIE[SSO_NAME]==NULL?"":$_COOKIE[SSO_NAME];

		return $this->ssotoken;
	}

	/**
	 * 获取用户ID
	 *
	 * @return string
	 */
	public function getUserid()
	{
		if(empty($this->userid))
		$this->getToken();
		return $this->userid;
	}

	/**
	 * 获取登录用户名
	 *
	 * @return string
	 */
	public function getUsername()
	{
		if(empty($this->username))
		$this->getToken();

		return $this->username;
	}

	/**
	 * 获取沉迷状态
	 *
	 * @return string
	 */
	public function getAddictedstatus()
	{
		if(empty($this->addictedstatus))
		$this->getToken();

		return $this->addictedstatus;
	}

	/**
	 * 获取登录时间
	 *
	 * @return int
	 */
	public function getLogintime()
	{
		if(empty($this->logintime))
		$this->getToken();

		return $this->logintime;
	}

	/**
 	 * 设置COOKIE
 	 *
 	 * @param string $cookie_name COOKIE名
 	 * @param string $cookie_value COOKIE值
 	 * @param string $cookie_domain COOKIE域
 	 * @param int $time COOKIE有效时间 单位：秒
 	 */
	public function setTokenCookie($cookie_name="",$cookie_value="", $cookie_domain="",$time = 0) {
		if(empty($cookie_name)) $cookie_name = SSO_NAME;
		if(empty($cookie_domain)) $cookie_domain = SSO_DOMAIN;
		if($time!=0)$time = time()+$time;
		setcookie($cookie_name,$cookie_value,$time,"/",$cookie_domain,0);
	}

	/**
     * 删除COOKIE
     *
 	 * @param string $cookie_name COOKIE名
 	 * @param string $cookie_domain COOKIE域
     */
	public function deleteTokenCookie($cookie_name="",$cookie_domain="")
	{
		if(empty($cookie_name)) $cookie_name = SSO_NAME;
		if(empty($cookie_domain)) $cookie_domain = SSO_DOMAIN;
		setcookie($cookie_name,"",time()-3600,"/",$cookie_domain,0);
	}

	/**
	 * 加密字符串
	 *
	 * @param decStr 需要加密的串
	 * @param strKey KEY
	 *
	 * @return
	 */
	private static function encrypt($decStr, $strKey)
	{
		return base64_encode(mcrypt_encrypt(MCRYPT_DES, $strKey, $decStr, MCRYPT_MODE_CBC,$strKey));
	}

	/**
	 * 解密字符串
	 *
	 * @param encStr 需要解密的串
	 * @param strKey KEY
	 *
	 * @return
	 */
	private static function decrypt($encStr, $strKey)
	{   
		$encStr = base64_decode(str_replace(' ','+',$encStr));
        return mcrypt_decrypt(MCRYPT_DES, $strKey, $encStr, MCRYPT_MODE_CBC,$strKey);
	}
}
?>