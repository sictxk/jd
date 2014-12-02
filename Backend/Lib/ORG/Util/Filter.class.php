<?php
import('@.ORG.Util.Exceptions');
/*
一般过滤示例：[] 代表可选项，| 内容代表只能用一个，有多种选择

1. 过滤变量
$title="fsdfds";

$title = Filter::filterVar($title,'s'，1);
$title = Filter::filterVar($title,'s',[1|2|3]);

2. GET过滤变量
$title="fsdfds";

$title = Filter::filterGet($title,'s'，1);
$title = Filter::filterGet($title,'s',[1|2|3]);

3. POST过滤变量
$title="fsdfds";

$title = Filter::filterPost($title,'s'，1);
$title = Filter::filterPost($title,'s',[1|2|3]);

特殊过滤示例： 

1. 回调函数
function callBack($string){
	echo "okokaa";
	return $string;
}

$title="fsdfds";

$title = Filter::filterVar($title,'fc',2,'callBack');
$title = Filter::filterGET($title,'fc',2,'callBack');
$title = Filter::filterPOST($title,'fc',2,'callBack');

2. 正则表达式

$title="fsdfds";

$title = Filter::filterVar($title,'ri',2,'/[a-z0-9]+/');
$title = Filter::filterGET($title,'ri',2,'/[a-z0-9]+/');
$title = Filter::filterPOST($title,'ri',2,'/[a-z0-9]+/');

3. 整数范围检测 用[ - ]符号分割范围 [小-大]

$title=123;

$title = Filter::filterVar($title,'i',2,'12-340');
$title = Filter::filterGET($title,'i',2,'12-340');
$title = Filter::filterPOST($title,'i',2,'12-340');

*/

/**
 * 过滤类
 * '<' => '&#60;',
 * '>' => '&#62;',
 * "'" => '&#39;',
 * '"' => '&#34;',
 * '?' => '&#63;',
 * '&' => '&#38;',
 */
class Filter
{
	private static $_wordFilterObj = null;
	private static $_bannedWords = null;
	private static $_filterType = null;
	private static $_options = null;
	private static $_words = null;
		
	private function __construct(){}
	private function __destruct(){}
	
	/**
	 * 输出过滤
	 *
	 * @param string $val	过滤内容
	 * @param string $mode	过滤模式	flash = 为flash赋值
	 * @return string
	 */
	public static function filterOut($val,$mode = 'flash'){
		switch ($mode){
			case 'flash':
				$trans = array(
					'&#60;' => '<',
					'&#62;' => '>',
					"&#39;" => "'",
					'&#34;' => '"',
					'&#44;' => ',',
					'&#40;' => '(',
					'&#41;' => ')',
		 			'&#63;' => '?',
				);
				$val = urlencode(strtr($val, $trans));
				break;
			case 'flash1':
				$trans = array(
					'&#60;' => '<',
					'&#62;' => '>',
					"&#39;" => "\'",
					'&#34;' => '\"',
					'&#44;' => '\,',
					'&#40;' => '(',
					'&#41;' => ')',
		 			'&#63;' => '?',
				);
				$val = urlencode(strtr($val, $trans));
				break;
			case 'text':
				$trans = array(
					'&#60;' => '\<',
					'&#62;' => '\>',
					"&#39;" => "\'",
					'&#34;' => '\"',
					'&#44;' => '\,',
					'&#40;' => '\(',
					'&#41;' => '\)',
		 			'&#63;' => '?',
				);
				$val = strtr($val, $trans);
				break;
			case 'input':
				$trans = array(
					'&#60;' => '\<',
					'&#62;' => '\>',
					"&#39;" => "\'",
					'&#34;' => '\"',
					'&#44;' => '\,',
					'&#40;' => '\(',
					'&#41;' => '\)',
		 			'&#63;' => '?',
          '&amp;' => '&',
				);
				$val = strtr($val, $trans);
				break;
		}
		return $val;
	}
	
	/**
	 * 过滤GET参数
	 *
	 * @param string 	$key		参数名
	 * @param string 	$filterType	过滤类型
	 * @param boolean 	$errMode	错误处理模式 1 = 忽略错误并继续执行程序 2 = 报错并停止程序 3 = 导航到错误页 4 = js导航到错误页 5 = AJAX报错
	 * @param mixed 	$options	其他过滤方法
	 * @param mixed 	$words		是否过滤敏感词
	 * @return mixed
	 */
	private static function _filterGet($key, $filterType, $errMode = 3, $options = false, $words = false) {
		if (empty($key)) {
			throw new Exceptions('0000','filterGet 函数参数 key 丢失');
		}
		if (empty($filterType)) {
			throw new Exceptions('0000','filterGet 函数参数 filterType 丢失');
		}
		if (getType($filterType) != 'string') {
			throw new Exceptions('0000','filterGet 函数参数 filterType 类型错误');
		}
		if(self::_filterHasVar(INPUT_GET, $key, $errMode) === false){
			return false;
		}
		if (is_array($_GET[$key])) {
			return self::_filterExec(true,INPUT_GET, $filterType, $key, $errMode, $options, $words);
		} else {
			return self::_filterExec(false,INPUT_GET, $filterType, $key, $errMode, $options, $words);	
		}
	}
	
	public static function filterGet($key, $filterType, $errMode = 3, $options = false){
		return self::_filterGet($key, $filterType, $errMode, $options, false);
	}

	public static function filterGetWords($key, $filterType, $errMode = 3, $options = false){
		return self::_filterGet($key, $filterType, $errMode, $options, true);
	}
	
	/**
	 * 过滤POST参数
	 *
	 * @param string 	$key		参数名
	 * @param string 	$filterType	过滤类型
	 * @param boolean 	$errMode	错误处理模式 1 = 忽略错误并继续执行程序 2 = 报错并停止程序 3 = 导航到错误页 4 = ajax忽略错误并继续执行程序 5 = ajax报错并停止程序
	 * @param mixed 	$options	其他过滤方法
	 * @param mixed 	$words		是否过滤敏感词
	 * @return mixed
	 */
	private static function _filterPost($key, $filterType, $errMode = 3, $options = false, $words = false){
		if (empty($key)) {
			throw new Exceptions('0000','filterPost 函数参数 key 丢失');
		}
		if (empty($filterType)) {
			throw new Exceptions('0000','filterPost 函数参数 filterType 丢失');
		}
		if (getType($filterType) != 'string') {
			throw new Exceptions('0000','filterGet 函数参数 filterType 类型错误');
		}
		if(self::_filterHasVar(INPUT_POST, $key, $errMode) === false){
			return false;
		}
		if (is_array($_POST[$key])) {
			return self::_filterExec(true,INPUT_POST, $filterType, $key, $errMode, $options, $words);
		} else {
			return self::_filterExec(false,INPUT_POST, $filterType, $key, $errMode, $options, $words);	
		}
	}

	public static function filterPost($key, $filterType, $errMode = 3, $options = false){
		return self::_filterPost($key, $filterType, $errMode, $options, false);
	}

	public static function filterPostWords($key, $filterType, $errMode = 3, $options = false){
		return self::_filterPost($key, $filterType, $errMode, $options, true);
	}

	
	/**
	 * 过滤变量
	 *
	 * @param string 	$key		参数名
	 * @param string 	$filterType	过滤类型
	 * @param boolean 	$errMode	错误处理模式 1 = 忽略错误并继续执行程序 2 = 报错并停止程序 3 = 导航到错误页
	 * @param mixed 	$options	其他过滤方法
	 * @param mixed 	$words		是否过滤敏感词
	 * @return mixed
	 */
	private static function _filterVar($var, $filterType, $errMode = 3, $options = false, $words = false){
		if (empty($var) && $var!=0 && $var!='0' && $var!='') {
			if ($errMode  == 1 || $errMode == 4) {
				return $var;
			} else {
				throw new Exceptions('filterVar 函数参数 var 丢失');
			}
		}
		if (empty($filterType)) {
			throw new Exceptions('filterVar 函数参数 filterType 丢失');
		}
		if (getType($filterType) != 'string') {
			throw new Exceptions('filterGet 函数参数 filterType 类型错误');
		}
		if(is_array($var)){
			return self::_filterExec(true,false, $filterType, $var, $errMode, $options, $words);
		} else {
			return self::_filterExec(false,false, $filterType, $var, $errMode, $options, $words);
		}
	}

	public static function filterVar($var, $filterType, $errMode = 3, $options = false){
		return self::_filterVar($var, $filterType, $errMode, $options, false);
	}

	public static function filterVarWords($var, $filterType, $errMode = 3, $options = false){
		return self::_filterVar($var, $filterType, $errMode, $options, true);
	}

	/**
	 * 错误时返回的错误原因
	 *
	 * @return string
	 */
	public static function filterErrReason(){
		if (is_null(self::$_bannedWords)) {
			$back = self::$_bannedWords;
		} else if (!is_null(self::$$filterType)) {
			switch (self::$$filterType){
				case 'ri':
					$back = "必须符合 ".self::$_options." 规则";
					break;
				case 'i':
					if (is_null(self::$_options)) {
						$back = "必须是 int 整数";
					} else {
						$back = "必须是 int 整数,范围必须在 ".self::$_options." 两个数之间";
					}
					break;
				case 'b':
					$back = "必须是 boolean 布尔型";
					break;
				case 'f':
					$back = "必须是 float 浮点数";
					break;
				case 'u':
					$back = "url地址不符合规范";
					break;
				case 'fu':
					$back = "url地址不符合规范";
					break;
				case 'e':
					$back = "email 格式不符合规范";
					break;
				case 'ip':
					$back = "IP 无效";
					break;
				case 'ipv4':
					$back = "IP 无效 或 不符合 ipv4";
					break;
				case 'ipv6':
					$back = "IP 无效 或 不符合 ipv6";
					break;
				case 'ippr':
					$back = "IP 无效 或 属于私有 IP 范围内";
					break;
				case 'iprr':
					$back = "IP 无效 或 属于保留 IP 范围内";
					break;
				case 's':
				case 'sb':
				case 'sr':
					$back = "含有不合法的危险字符";
					break;
				default:
					$back = '';
					break;
			}
			self::$$filterType = null;
		}
		
		return $back;
	}

//===================================== 内部函数 =====================================
	
	/**
	 * 判断是否是Ajax
	 * true = 是ajax操作 false = 不是ajax操作
	 * @return boolean
	 */
	private static function _checkAjax(){
		$GP=($_POST['ajax'])? $_POST:$_GET;
		$flag = ($GP['ajax']!=1)? false:true;
		return $flag;
	}
	
	/**
	 * 检查 GET|POST 参数是否存在
	 *
	 * @param string 	$key		可以是参数名，或参数数组
	 * @param mixed 	$value		可以是参数值，或参数值数组
	 * @param boolean 	$errMode	错误处理模式 1 = 忽略错误并继续执行程序 2 = 报错并停止程序 3 = 导航到错误页
	 * @return mixed
	 */
	private static function _filterHasVar($type, $key, $errMode){
		if (filter_has_var($type,$key) === false) {
			if($errMode > 1){
				throw new Exceptions(($type == INPUT_GET)? '0001':'0002',$key, $errMode);
			} else {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * 过滤 魔法引用 内容 [\]
	 *
	 * @param string $str
	 * @return string
	 */
	private static function _magicQ($str){
		if (self::_checkAjax()===false) {
			if (get_magic_quotes_gpc()) {
				$trans = array(
					"\'" => "'",
					'\"' => '"',
				);
				
				$str = strtr($str, $trans);
			}
		}
		return $str;
	}
	
	/**
	 * 过滤HTML危险内容
	 *
	 * @param string $str
	 * @return string
	 */
	private static function _htmlFilter($str){
		$search = array (
			"'<script[^>]*?>.*?</script>'si",
			"'<html[^>]*?>.*?<body[^>]*?>'si",
			"'</body>.*?</html>'si",
			"'<style[^>]*?>.*?</style>'si",
			"'<link[^>]*?\s*[/]?>'si",
			"'<iframe[^>]*?>.*?</iframe>'si",
			"'<form[^>]*?>(.*?)</form>'si",
			"'<textarea[^>]*?>.*?</textarea>'si",
			"'\s*id\s*=\s*[\"|\'].*?[\"|\']'si",
			"'\s*clas\s*s\s*=\s*[\"|\'].*?[\"|\']'si",
			"'<!--.*?-->'si",
		);
	
		$replace = array ("","","","","","","","","","","");
	
		$str = preg_replace($search, $replace, $str);
		
		$trans = array(
 			'?' => '&#63;',
		);
		
		return strtr($str, $trans);
	}
	
 	/**
	 * 转义html
	 *
	 * @param string $str
	 * @return string
	 */
	private static function _conFilter($str,$mode = true){
		$search = array (
			"'<script[^>]*?>'si",
			"'</script>'si",
		);
	
		$replace = array ("","");
	
		$str = preg_replace ($search, $replace, $str);
		
		$trans = array(
			'<' => '&#60;',
			'>' => '&#62;',
			"'" => '&#39;',
			'"' => '&#34;',
			',' => '&#44;',
			'(' => '&#40;',
			')' => '&#41;',
 			'?' => '&#63;',
		);
		
		$str = strtr($str, $trans);
		
		return $str;
	}
	
	/**
	 * 过滤空格和换行和制表符
	 *
	 * @param string $str
	 * @return string
	 */
	private static function _nl2brFilter($str){
		$trans = array(
			'\t'=> '&nbsp;',
			' ' => '&nbsp;',
		);
	
		$str = strtr($str, $trans);
		$str = nl2br($str);
		
		return $str;
	}
	
	/**
	 * 过滤后的特殊内容过滤
	 *
	 * @param string $filterType
	 * @param string $var
	 * @return string
	 */
	private static function _beforeFilter($filterType, $var){
		switch ($filterType) {
			case 's':
				return self::_conFilter($var);
				break;
			case 'sb':
				return self::_nl2brFilter(self::_conFilter($var));
				break;
			case 'sr':
				return self::_htmlFilter($var);
				break;
			default:
				return $var;
				break;
		}
	}
	/**
	 * 过滤数据
	 *
	 * @param string $key
	 * @param string $var
	 * @param string $filterType
	 * @param int $errMode
	 * @param mixed $options
	 * @return string
	 */
	private static function _filterData($key, $var, $filterType, $errMode, $options, $words){
		$back = false;
		$optionsStr = '';
		
		if ($filterType == 'fc') {
			$back = $options($var);
		} else {
			$optionsStr = self::_getOptions($filterType, $options);
			if($optionsStr){
				$back = filter_var($var,self::_getFilterMode($filterType),$optionsStr);
			} else {
				$back = filter_var($var,self::_getFilterMode($filterType));
			}
		}
		
		if ($back !== false && $words === true) {

			$back = self::wordsFilter(self::_chkEncode($back));
		}
		
		if($back === false){
			if($errMode > 1){
				if ($errMode == 5) {
					Ajax::alert('服务器端出现错误！');
					Ajax::response();
				} else {
					throw new Exceptions($key);
				}
			} else {
				return false;
			}
		}
		switch ($filterType){
			case 'i':
				$back = intval($back);
				break;
			case 'f':
				$back = floatval($back);
				break;
		}
		return $back;
	}
	
	/**
	 * 执行数组过滤
	 * 
	 * @param string	$var			过滤内容
	 * @param string 	$filterType		过滤模式
	 * @param mixed		$key			
	 * @param boolean 	$errMode		错误模式
	 * @param mixed 	$options		其他使用
	 * @return mixed
	 */
	private static function _aryFilter($var, $key, $filterType, $errMode, $options, $words){
		if (is_array($var)) {
			foreach ($var as $k=>$v){
				$var[$k] = self::_aryFilter($v, $key, $filterType, $errMode, $options, $words);
				if($var[$k] === false)return false;
			}
			return $var;
		} else {
			$var = self::_beforeFilter($filterType, self::_magicQ($var));
			$var = self::_filterData($key, $var, $filterType, $errMode, $options, $words);
			if($var === false)return false;
			return $var;
		}
	}

	
	/**
	 * 执行过滤
	 *
	 * @param boolean	$isAry			true = 数组 	false = no数组
	 * @param int 		$type			get|post
	 * @param string 	$filterType		过滤模式
	 * @param mixed		$key			
	 * @param boolean 	$errMode		错误模式
	 * @param mixed 	$options		其他使用
	 * @param mixed 	$words			敏感词
	 * @return mixed
	 */
	private static function _filterExec($isAry ,$type, $filterType, $key, $errMode, $options, $words = false){
		self::$_filterType = $filterType;
		
		if($type===false)$var = $key;
		else {
			$var = ($type===INPUT_GET)? $_GET[$key]:$_POST[$key];
		}
		
		if($isAry === true){
			if(count($var) > 0){
				return self::_aryFilter($var, $key, $filterType, $errMode, $options, $words);
			}
		} else {
			$var = self::_beforeFilter($filterType, self::_magicQ($var));
			return self::_filterData($key, $var, $filterType, $errMode, $options, $words);
		}
	}
	
	/**
	 * 组织复杂过滤模式
	 *
	 * @param string $filterType	过滤模式
	 * @param mixed $options		过滤辅助选项
	 * @return string
	 */
	private static function _getOptions($filterType, $options){
		$back = '';
		
		self::$_options = null;
		if($options!==false){
			switch ($filterType){
				case 'ri':#正则表达式[验证]
					$back = array('options'=>array('regexp'=>$options));
					self::$_options = $options;
					break;
				case 'i': #必须是 int 整数
					self::$_options = $options;
					$options = explode('-',$options);
					$back = array('options'=>array('min_range'=>$options[0], 'max_range'=>$options[1]));
					break;
				case 'fc':
					$back = array('options'=>$options);
					break;
			}
		} else if($filterType == 'e') {
			$options = '/^(?:[\w\-\.]+)@(?:[\-\w]+)\.(?:[\-\w\.]+)$/i';
			$back = array('options'=>array('regexp'=>$options));
			self::$_options = $options;
		}
		
		return $back;
	}
	
	/**
	 * 获得过滤模式
	 *
	 * @param string $filter
	 * @return int
	 */
	private static function _getFilterMode($filter){
		switch($filter){
			case 'ri':#正则表达式[验证]
				$flag=FILTER_VALIDATE_REGEXP;
				break;
			case 'i': #必须是 int 整数
				$flag=FILTER_VALIDATE_INT;
				break;
			case 'b': #必须是 true | false
				$flag=FILTER_VALIDATE_BOOLEAN;
				break;
			case 'f': #必须是 float 浮点数
				$flag=FILTER_VALIDATE_FLOAT;
				break;
			case 'u': #验证url地址中的url参数 http://www.tudai.com/index.php?url=http://video.tudai.com/index.php?u=10046
				$flag=FILTER_VALIDATE_URL;
				break;
			case 'fu': #过滤url地址中的url参数 http://www.tudai.com/index.php?url=http://video.tudai.com/index.php?u=10046
				$flag=FILTER_SANITIZE_URL;
				break;
			case 'e': #验证 email 格式
				/**
				 * PHP提供的过滤email有BUG，enze于2009年10月8日 注释，修改为正则过滤
				 */
				//$flag=FILTER_VALIDATE_EMAIL;
				$flag = FILTER_VALIDATE_REGEXP;
				break;
			case 'ip': #验证 ip 格式
				$flag=FILTER_VALIDATE_IP;
				break;
			case 'ipv4': #验证 ipv4 格式
				$flag=FILTER_VALIDATE_IP.','.FILTER_FLAG_IPV4;
				break;
			case 'ipv6': #验证 ipv6 格式
				$flag=FILTER_VALIDATE_IP.','.FILTER_FLAG_IPV6;
				break;
			case 'ippr': #验证 ip 非私有IP 范围
				$flag=FILTER_VALIDATE_IP.','.FILTER_FLAG_NO_PRIV_RANGE;
				break;
			case 'iprr': #验证 ip 非保留的 IP 范围
				$flag=FILTER_VALIDATE_IP.','.FILTER_FLAG_NO_RES_RANGE;
				break;
			case 's': #保留html标签，去除 或 编码特殊字符。剔除ASCII 32以下字符 _conFilter
			case 'sb':#保留html标签，去除 或 编码特殊字符。剔除ASCII 32以下字符 _conFilter _nl2brFilter
			case 'sr':#保留html标签，去除 或 编码特殊字符。剔除ASCII 32以下字符 _htmlFilter
				$flag=FILTER_UNSAFE_RAW.','.FILTER_FLAG_STRIP_LOW;
				break;
		}
		return $flag;
	}
	
	/**
	 * 检查编码,并转换成UTF8
	 *
	 * @param mixed $back
	 * @return mixed
	 */
	private static function _chkEncode($back){
		$encode=mb_detect_encoding($back,"UTF-8,GB2312,GBK");
		switch($encode){
			case 'EUC-CN':
				$back=mb_convert_encoding($back,'UTF-8','GB2312');
				break;
			case 'CP936':
				$back= mb_convert_encoding($back,'UTF-8','GBK');
				break;
		}
		
		return $back;
	}
	
	/**
	 * 词语过滤
	 *
	 * @param string $str			传入要过滤得内容
	 *
	 * @return mixed 如果内容中存在banned过滤，返回false,否则返回过滤得内容
	 */
	public static function wordsFilter($str){

		self::$_bannedWords = null;
		
		// 词语过滤对象
		if(is_null(self::$_wordFilterObj)){
//			self::$_wordFilterObj = new Db_Zidian_WordsFilter();
//			self::$_words = self::$_wordFilterObj->getWords();
			self::$_wordFilterObj = D('BbsCommonWord');
			self::$_words = self::$_wordFilterObj->getWords();
		}
		
		$words = self::$_words;
		// 全局词语过滤变量[禁止内容]
		if($words['banned']){
			if (preg_match($words['banned'],$str)) {
				self::$_bannedWords = strtr($words['banned'],array('/('=>'[',')/i'=>']'));
				return false;
			}
		}
		// 全局词语过滤变量[过滤内容]
		if(empty($words['filter'])){
			return $str;
		} else {
			$tmpAry=preg_grep('/\w+/i',$words['filter']['find']);
			if(empty($tmpAry)) {
				if(!empty($words['filter']['find'])) {
					foreach($words['filter']['find'] as $key=>$val) {
						$words['filter']['find'][$key]='/'.$val.'/i';
					}
				} else {
					return $str;
				}
			} else {
				if(!empty($words['filter']['find'])) {
					foreach($words['filter']['find'] as $key=>$val) {
						$tempAry=array();
						if(eregi('^[a-z]{1,}$',$val) === false) {
							$words['filter']['find'][$key]='/'.$val.'/i';
						} else {
							$words['filter']['find'][$key]='/\b'.$val.'\b/i';
						}
					}
				} else {
					return $str;
				}
			}

			return @preg_replace($words['filter']['find'],$words['filter']['replace'],$str);
		}
		
		return $str;
	}

}
?>
