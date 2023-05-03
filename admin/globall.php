<?php
// ========================== 文件说明 ==========================//
// 本文件说明：后台函数集合
// ==============================================================//
error_reporting(7);
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
function debuginfo() {
	global $DB, $t, $starttime;
	$mtime = explode(' ', microtime());
	$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 2);
        return $totaltime;
}


// 加载常规选项信息
require_once ("settings.php");
// 加载数据库信息
require_once ("config.php");
// 加载数据库类
require_once ("class/mysql.php");
// 加载后台常用函数
require_once ("adminfunctions.php");
// 加载表单类
require_once ("class/forms.php");

$DB = new DB_MySQL;

$DB->servername=$servername;
$DB->dbname=$dbname;
$DB->dbusername=$dbusername;
$DB->dbpassword=$dbpassword;

$DB->connect();
$DB->selectdb();

// 允许程序在 register_globals = off 的环境下工作
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
}

// 检查安装文件是否存在
if (file_exists("install.php")==1) {
         rename("install.php", "install.php.bak");
         exit;
}

// 去除转义字符
function stripslashes_array($array) {
	while (list($k,$v) = each($array)) {
		if (is_string($v)) {
			$array[$k] = stripslashes($v);
		} else if (is_array($v))  {
			$array[$k] = stripslashes_array($v);
		}
	}
	return $array;
}

// 判断 magic_quotes_gpc 状态
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
}

set_magic_quotes_runtime(0);

$cpforms = new FORMS;

// 用户登陆后台验证部分

// 记录管理的一切操作
getlog();
?>
