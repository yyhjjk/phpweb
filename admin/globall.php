<?php
// ========================== �ļ�˵�� ==========================//
// ���ļ�˵������̨��������
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


// ���س���ѡ����Ϣ
require_once ("settings.php");
// �������ݿ���Ϣ
require_once ("config.php");
// �������ݿ���
require_once ("class/mysql.php");
// ���غ�̨���ú���
require_once ("adminfunctions.php");
// ���ر���
require_once ("class/forms.php");

$DB = new DB_MySQL;

$DB->servername=$servername;
$DB->dbname=$dbname;
$DB->dbusername=$dbusername;
$DB->dbpassword=$dbpassword;

$DB->connect();
$DB->selectdb();

// ��������� register_globals = off �Ļ����¹���
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
}

// ��鰲װ�ļ��Ƿ����
if (file_exists("install.php")==1) {
         rename("install.php", "install.php.bak");
         exit;
}

// ȥ��ת���ַ�
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

// �ж� magic_quotes_gpc ״̬
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
}

set_magic_quotes_runtime(0);

$cpforms = new FORMS;

// �û���½��̨��֤����

// ��¼�����һ�в���
getlog();
?>
