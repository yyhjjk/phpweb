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
session_start();
if ($_GET['action']=="logout") {
	session_unset();
	session_destroy();
	cpheader();
	redirect("<b>�����˳���½</b>","./index.php");
	echo "</body>\n</html>";
}

// ��½��֤
if ($_POST['action']=="login") {
	$username=addslashes(trim($_POST['username']));
	$password=addslashes(trim($_POST['password']));
	if (checkuser($username, md5($password)))
	{
		$_SESSION['username'] = $username;
        $_SESSION['password'] = md5($password);
		loginsucceed($_POST['username'],$_POST['password']);
		cpheader();
                echo "<b>��½�ɹ�,���Ժ�......</b>";
	        echo "<meta http-equiv=\"refresh\" content=\"0;URL=./index.php\">\n";
		echo "</body>\n</html>";
	} else {
		loginfaile($_POST['username'],$_POST['password']);
		loginpage();
	}
}


// ��֤�û��Ƿ��ڵ�½״̬
if (isset($_SESSION["username"]) and isset($_SESSION["password"])) {
	islogin($_SESSION["username"],$_SESSION["password"]);
} else {
	loginpage();
}


// ��̨��½���ҳ��
function loginpage(){
	cpheader();
	echo "<body onload=\"document.getElementById('login-form').username.focus()\">\n";
	echo "<form method=\"post\" action=\"index.php\" id=\"login-form\">\n";
	echo "<table width=\"250\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"1\" class=\"tblborder\">\n";
	echo " <tr id=\"cat\">";
	echo "  <td class=\"tbhead\" colspan=\"2\" height=\"25\" align=\"center\">";
	echo "   <input type=\"hidden\" name=\"action\" value=\"login\">";
	echo "    <font color=\"#F5D300\"><b>��½���</font></b>";
	echo "  </td>";
	echo " </tr>";
	echo " <tr class=\"firstalt\"><td width=\"118\">�û���:</td>";
	echo "  <td width=\"232\"><input type=\"text\" name=\"username\" value=\"\"></td></tr>";
	echo " <tr class=\"secondalt\"><td width=\"118\">����:</td>";
	echo "  <td width=\"232\"><input type=\"password\" name=\"password\" value=\"\"></td></tr>";
	echo "<tr class=\"tbhead\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"��½\"> <input type=\"reset\" value=\"����\">";
	echo "</td></tr></table></form>";
	echo "</body></html>";
	exit();
}

// ��¼�����һ�в���
getlog();
?>
