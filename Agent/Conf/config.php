<?php
if (!defined('THINK_PATH'))	exit();
$config = require_once('../config.php');
$array  = array(
	//'SHOW_PAGE_TRACE'=>1
);
return array_merge($config, $array);
?>