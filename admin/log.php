<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵���������¼
// =============================================================// 
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");
cpheader();
//ɾ�������¼����
if ($_POST['action']=="removeadmin") {
	if($pass == $dellog_pass)
	{
		$DB->query("DELETE FROM ".$db_prefix."adminlog");
		redirect("���м�¼��ɾ��!", "log.php?action=admin");
	} else {
		sa_exit("ɾ����־���ܳ״���!", "log.php?action=admin");
	}
}//removeadminall

//ɾ����½��¼����
if ($_POST['action']=="removelogin") {
	if($pass == $dellog_pass)
	{
		$DB->query("DELETE FROM ".$db_prefix."loginlog");
		redirect("���м�¼��ɾ��", "log.php?action=login");
	} else {
		sa_exit("ɾ����־���ܳ״���!", "log.php?action=login");
	}
}//removeadminall


//������־ҳ��
if ($_GET['action']=="admin") {

    echo "<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" align=\"center\" width=\"92%\">\n<tr>\n<td>\n";
    echo "<a href=\"log.php?action=deladmin\"><b>������м�¼</b></a>\n";
    echo "</td></tr>\n";
    echo "</td>\n</tr>\n</table>\n";

	$page = intval($_GET['page']);
	if(!empty($page)) {
		$start_limit = ($page - 1) * 50;
	} else {
		$start_limit = 0;
		$page = 1;
	}
	
	$query="SELECT * FROM ".$db_prefix."adminlog ORDER BY adminlogid DESC";
    $total=$DB->num_rows($DB->query($query));
	$multipage = multi($total, "50", $page, "log.php?action=admin","php");

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"90%\" class=\"tblborder\">\n<tr>\n<td>\n";
    $cpforms->tableheader();
	echo "<tr>\n";
    echo " <td class=\"tbhead\" colspan=\"5\">\n";
    echo " <b><font color=\"#F5D300\">�鿴�����¼</font></b>\n";
    echo " </td>\n";
    echo "</tr>\n";
    echo "<tr align=\"center\" bgcolor=\"#999999\">\n";
    echo " <td nowrap>ID</td>\n";
    echo " <td nowrap>IP</td>\n";
    echo " <td nowrap>ʱ��</td>\n";
    echo " <td nowrap>�ļ�</td>\n";
    echo " <td nowrap>����</td>\n";
    echo "</tr>";
	$adminlogs = $DB->query("SELECT * FROM ".$db_prefix."adminlog ORDER BY adminlogid DESC LIMIT $start_limit, 50");

    while($adminlog=$DB->fetch_array($adminlogs)){
		echo "<tr class=".getrowbg().">\n";
		echo " <td nowrap align=\"center\">$adminlog[adminlogid]</td>\n";
		echo " <td nowrap align=\"center\">$adminlog[ipaddress]</td>\n";
		echo " <td nowrap align=\"center\">".date("Y-m-d H:i:s",$adminlog[date])."</td>\n";
		echo " <td nowrap>$adminlog[script]</td>\n";
		echo " <td nowrap>$adminlog[action]</td>\n";
		echo "</td></tr>";
    }
	echo "<tr class=".getrowbg().">";
	echo "<td colspan=\"5\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<td>���� <font color=\"#000000\" style=\"font-size:11px\"><b>".$total."</b></font> ����¼ | <font color=\"#000000\" style=\"font-size:11px\"><b>50</b></font> ��/ҳ</td>\n";
	echo "<td align=\"right\">\n";
	echo $multipage;
	echo "</td></tr></table>\n";
	echo "</td></tr>\n";
	$cpforms->tablefooter();
	echo "</td>\n</tr>\n</table>";
}//end admin

//ɾ��������־ҳ��
if ($_GET['action']=="deladmin") {
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"90%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'ɾ�����м�¼'));
	$cpforms->makeinput(array('text'=>'������ config.php �ļ��е��ܳ�:','type'=>'password','name'=>'pass'));
	echo "<tr align=\"center\" class=".getrowbg().">\n";
	echo "  <td colspan=\"2\"><font color=\"#FF0000\">ע�⣺ɾ�����й����¼��ȷ����</font></td>\n";
    echo "</tr>\n";
    $cpforms->makehidden(array('name'=>'action',
                          'value'=>'removeadmin'));
    $cpforms->formfooter(array('confirm'=>1));
	echo "</td>\n</tr>\n</table>";
}//end delall


//��½��־ҳ��
if ($_GET['action']=="login") {

    echo "<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" align=\"center\" width=\"92%\">\n<tr>\n<td>\n";
    echo "<a href=\"log.php?action=dellogin\"><b>������м�¼</b></a>\n";
    echo "</td></tr>\n";
    echo "</td>\n</tr>\n</table>\n";

	$page = intval($_GET['page']);
	if(!empty($page)) {
		$start_limit = ($page - 1) * 50;
	} else {
		$start_limit = 0;
		$page = 1;
	}
	
	$query="SELECT * FROM ".$db_prefix."loginlog ORDER BY loginlogid DESC";
    $total=$DB->num_rows($DB->query($query));
	$multipage = multi($total, "50", $page, "log.php?action=login","php");

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"90%\" class=\"tblborder\">\n<tr>\n<td>\n";
    $cpforms->tableheader();
	echo "<tr>\n";
    echo " <td class=\"tbhead\" colspan=\"6\">\n";
    echo " <b><font color=\"#F5D300\">�鿴��½��¼</font></b>\n";
    echo " </td>\n";
    echo "</tr>\n";
    echo "<tr align=\"center\" bgcolor=\"#999999\">\n";
    echo " <td nowrap>ID</td>\n";
    echo " <td nowrap>�û���</td>\n";
    echo " <td nowrap>����</td>\n";
    echo " <td nowrap>ʱ��</td>\n";
    echo " <td nowrap>IP</td>\n";
	echo " <td nowrap>���</td>\n";
    echo "</tr>";
	$loginlogs = $DB->query("SELECT * FROM ".$db_prefix."loginlog ORDER BY loginlogid DESC LIMIT $start_limit, 50");

    while($loginlog=$DB->fetch_array($loginlogs)){
		echo "<tr class=".getrowbg().">\n";
		echo " <td nowrap align=\"center\">$loginlog[loginlogid]</td>\n";
		echo " <td nowrap>$loginlog[username]</td>\n";
		echo " <td nowrap>$loginlog[password]</td>\n";
		echo " <td nowrap align=\"center\">".date("Y-m-d H:i:s",$loginlog[date])."</td>\n";
		echo " <td nowrap align=\"center\">$loginlog[ipaddress]</td>\n";
		echo " <td nowrap align=\"center\">";
		if($loginlog[result] == "1")
		{
			echo "�ɹ�</td>\n";
		} else {
			echo "<font color=\"#FF0000\">ʧ��</td>\n";
		}
		echo "</tr>\n";
    }
	echo "<tr class=".getrowbg().">";
	echo "<td colspan=\"6\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<td>���� <font color=\"#000000\" style=\"font-size:11px\"><b>".$total."</b></font> ����¼ | <font color=\"#000000\" style=\"font-size:11px\"><b>50</b></font> ��/ҳ</td>\n";
	echo "<td align=\"right\">\n";
	echo $multipage;
	echo "</td></tr></table>\n";
	echo "</td></tr>\n";
	$cpforms->tablefooter();
	echo "</td>\n</tr>\n</table>";
}//end login

//ɾ����½��־ҳ��
if ($_GET['action']=="dellogin") {
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"90%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'ɾ�����м�¼'));
	$cpforms->makeinput(array('text'=>'������ config.php �ļ��е��ܳ�:','type'=>'password','name'=>'pass'));
	echo "<tr align=\"center\" class=".getrowbg().">\n";
	echo "  <td colspan=\"2\"><font color=\"#FF0000\">ע�⣺ɾ�����е�½ʧ�ܵļ�¼��ȷ����</font></td>\n";
    echo "</tr>\n";
    $cpforms->makehidden(array('name'=>'action','value'=>'removelogin'));
    $cpforms->formfooter(array('confirm'=>1));
	echo "</td>\n</tr>\n</table>";
}//end delloginall

cpfooter();

?>    
