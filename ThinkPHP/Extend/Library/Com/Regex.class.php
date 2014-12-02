<?php
/**--------------------------------------------------------------------------------#
* 正则检测函数
*--------------------------------------------------------------------------------**/
class Regex {
	private static $email = "/^([A-Z0-9]+(?:[-_]{1}[A-Z0-9]+)*)(?:\.[A-Z0-9]+(?:[-_]{1}[A-Z0-9]+)*)*@(?:[A-Z0-9]+(?:[-_]{1}[A-Z0-9]+)*\.)+[A-Z]{2,6}$/i";
	private static $numletter = "/^[A-Za-z0-9]+$/";
	private static $alias = "/^[\x{4e00}-\x{9fa5}\w+=*!@#$%&{}\(\)\[\]~^-]+$/u";
	private static $mobile = "/^1[3|4|5|8][0-9]\d{8}$/";
	private static $float = "/^[+-]?\d+\.\d+$/";
	private static $integer = "/^[+-]?\d+$/";
	private static $thousandth = "/^[+-]?[0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?$/";
	private static $time = "/^(2[0-3]|[01]?[0-9])(:[0-5]?[0-9]){1,2}$/";
	private static $date = "/^([0-9]{4})-(1[0-2]|0?[1-9])-(3[0-1]|[12][0-9]|0?[1-9])$/";
	private static $datetime = "/^([0-9]{4})-(1[0-2]|0?[1-9])-(3[0-1]|[12][0-9]|0?[1-9])[ ]((2[0-3]|[01]?[0-9])(:[0-5]?[0-9]){1,2})?$/";
	private static $phone = "/^([0+]\d{2,3}[ -])?(\d{2,4}[ -])?\d{7,8}([ -]\d+)?$/";
	private static $uri = "/^https?:\/\/[A-Z0-9-]+(\.[A-Z0-9-]+)+([\/?].*)?$/i";
	private static $empty = "/[(\xc2\xa0)|\s　]+/im";
	
	// 替换连续空白
	public static function trimEmpty($str) {
		return preg_replace(self::$empty, ' ', $str);	
	}
	
	// 长度检测
	public static function isLength($str, $min = 1, $max = 99999999, $encode = "UTF-8") {
		$len = iconv_strlen(trim($str), $encode);
		return ($len >= $min && $len <= $max);
	}
	
	// 手机
	public static function isMobile($str) {
		return preg_match(self::$mobile, $str);
	}
	// 电话
	public static function isPhone($str) {
		return preg_match(self::$phone, $str);
	}
	// 邮件
	public static function isEmail($str) {
		return preg_match(self::$email, $str);
	}
	// 网址
	public static function isUrl($str) {
		return preg_match(self::$uri, $str);
	}
	// 日期时间
	public static function isDateTime($str) {
		return preg_match(self::$datetime, $str);
	}
	// 日期
	public static function isDate($str) {
		return preg_match(self::$date, $str);
	}
	// 时间
	public static function isTime($str) {
		return preg_match(self::$time, $str);
	}
	// 数字型(浮点)
	public static function isNumber($str) {
		return preg_match(self::$float, $str);
	}
	// 千分位数字型(浮点)
	public static function isThNumber($str) {
		return preg_match(self::$thousandth, $str);
	}
	// 整数
	public static function isInteger($str) {
		return preg_match(self::$integer, $str);
	}
	// 数字字母混合
	public static function isNumberLetter($str) {
		return preg_match(self::$numletter, $str);
	}
	//是否用户名(字母数字加中文)
	public static function isUser($str){
		return preg_match(self::$alias, $str);
	}
}

?>