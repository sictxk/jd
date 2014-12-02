<?php
if (!defined('THINK_PATH'))	exit();
$config = require_once('../config.php');
$array  = array(
    'LOAD_EXT_CONFIG' => 'database,RBAC',//引入其他配置文件
    'APP_AUTOLOAD_PATH'	  => '@.TagLib,',
    'LOAD_EXT_FILE'=>'Util,RestRequest',//自动导入公告函数文件
    'SESSION_AUTO_START'  => true,
    'SHOW_PAGE_TRACE'	  => 0,			//显示调试信息
	
	//'SHOW_PAGE_TRACE'=>1
	/*'MAIL_ADDRESS'=>'easytotur@sina.cn', // 邮箱地址
	'MAIL_SMTP'=>'smtp.sina.cn', // 邮箱SMTP服务器
	'MAIL_LOGINNAME'=>'easytotur', // 邮箱登录帐号
	'MAIL_PASSWORD'=>'19871008j' // 邮箱密码*/
	'MAIL_ADDRESS'=>'easytotur@163.com', // 邮箱地址
	'MAIL_SMTP'=>'smtp.163.com', // 邮箱SMTP服务器
	'MAIL_LOGINNAME'=>'easytotur', // 邮箱登录帐号
	'MAIL_PASSWORD'=>'19871008j', // 邮箱密码
	
	
	'USER_PKID' => $_SESSION['user_info']['pkid'], 
	'USER_NICKNAME' => $_SESSION['user_info']['nickname'], 
	'USER_ISTEACHER' => $_SESSION['user_info']['is_teacher']
		
		
	
);
return array_merge($config, $array);
?>