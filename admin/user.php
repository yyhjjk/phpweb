<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵�����û�����
// =============================================================// 
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");

// ��ӹ���Ա����
if ($_POST['action']=="adduser"){
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);
	// ��֤�߼���
    if (trim($username)=="")
	{
        sa_exit("�û�������Ϊ��","javascript:history.go(-1);");
    }
	if(strlen($username)>16)
	{
		sa_exit("�û������ܳ���16���ַ�","javascript:history.go(-1);");
	}
	if(eregi("[<>{}(),%#|^&!`$]",$username))
	{
		sa_exit("�û���ֻ����a-z,0-9��'_'�����","javascript:history.go(-1);");
	}
    if (trim($password)=="")
	{
        sa_exit("���벻��Ϊ��","javascript:history.go(-1);");
    }
	if (strlen($password) < 6)
	{
		sa_exit("���볤�Ȳ�������6�ֽ�","javascript:history.go(-1);");
	}
	if(trim($password) != trim($password2))
	{
		sa_exit("�����������벻һ��","javascript:history.go(-1);");
	}

    $checkuser = $DB->fetch_one_array("SELECT username FROM ".$db_prefix."user WHERE  username='".addslashes($user_name)."'");

    if ($checkuser) {
       if ($username==$checkuser['username']) {
           sa_exit("�û��Ѵ���,��ʹ�������û���","javascript:history.go(-1);");
       }
    }
	// ��֤�߼��Խ���
    $DB->query("INSERT INTO ".$db_prefix."user (username,password) VALUES ('".addslashes($username)."','".md5($password)."')");
    redirect("����Ա�����", "./user.php?action=add");
}// ��ӽ���

// �޸��������
if($_POST['action'] == "modpassword")
{
	// ��֤�߼���
	if (trim($newpassword)=="")
	{
        sa_exit("���벻��Ϊ��","javascript:history.go(-1);");
    }
	if (strlen($newpassword) < 6)
	{
		sa_exit("���볤�Ȳ�������6�ֽ�","javascript:history.go(-1);");
	}
	if(trim($newpassword) != trim($comfirpassword))
	{
		sa_exit("�����������벻һ��","javascript:history.go(-1);");
	}
	
	$user = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE userid='".intval($userid)."'");
    if (empty($user)) {
        sa_exit("���û�������","javascript:history.go(-1);");
    }
    if (md5($_POST['oldpassword'])!=$user['password']) {
        sa_exit("������Ч","javascript:history.go(-1);");
    }
	// ��֤�߼��Խ���
	$query="UPDATE ".$db_prefix."user SET password='".addslashes(md5($_POST['newpassword']))."' WHERE  userid='".intval($user['userid'])."'";
    $DB->query($query) or die("�޸�����ʧ��");
    redirect("�޸�����ɹ�!", "./index.php?action=main");
}// �޸��������

// ɾ���û�����
if($_POST['action'] == "deluser")
{
	$query="DELETE FROM ".$db_prefix."user WHERE userid='".intval($userid)."'";
    $DB->query($query) or die("ɾ���û�ʧ��!");
    redirect("ɾ���û��ɹ�!", "./user.php?action=edit");
}// ɾ���û�����

cpheader();

// ��ӹ���Աҳ��
if ($_GET['action']==add){

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'��ӹ���Ա'));

    $cpforms->makehidden(array('name'=>'action',
                          'value'=>'adduser'));

    $cpforms->makeinput(array('text'=>'�û���:',
                               'name'=>'username',
                               ));

    $cpforms->makeinput(array('text'=>'����:',
                               'type'=>'password',
                               'name'=>'password'));

    $cpforms->makeinput(array('text'=>'ȷ������:',
                               'type'=>'password',
                               'name'=>'password2'));

    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";

}//end add


// ����Ա�б�ҳ��
if ($_GET['action']=="edit") {

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>\n";

    $cpforms->tableheader();
	echo "<tr>\n";
    echo " <td class=\"tbhead\" colspan=\"2\">\n";
    echo " <b><font color=\"#F5D300\">����Ա�б�</font></b>\n";
    echo " </td>\n";
    echo "</tr>\n";
	echo "<tr bgcolor=\"#999999\">\n";
	echo " <td><b>�û���</b></td>\n";
	echo " <td align=\"center\" width=\"14%\"><b>�������</b></td>\n";
	echo "</tr>\n";


	$query="SELECT * FROM ".$db_prefix."user";
    $total=$DB->num_rows($DB->query($query));

    $users = $DB->query("SELECT * FROM ".$db_prefix."user ORDER BY userid DESC");
    while ($user = $DB->fetch_array($users)) {
		echo "<tr class=\"".getrowbg()."\">";
		echo "<td>".htmlspecialchars($user['username'])."</td>\n";
		echo "<td align=\"center\">";
		echo "<a href=\"user.php?action=del&userid=$user[userid]\">ɾ��</a></td>\n";
        echo "</tr>\n";
    }
	echo "<tr class=".getrowbg().">";
	echo "<td colspan=\"2\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<td>���� <font color=\"#000000\"><b>".$total."</b></font> ������Ա</td>\n";
	echo "</tr></table>\n";
	echo "</td></tr>\n";
    $cpforms->tablefooter();
	echo "</td>\n</tr>\n</table>";
} //end edit

// �޸�����ҳ��
if ($_GET['action']=="modpass"){
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
	$user = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE username='".$_SESSION['username']."'");
    if (empty($user))
	{
		sa_exit("�ù���Ա������","javascript:history.go(-1);");
	}
    $cpforms->formheader(array('title'=>'�޸�����'));
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>'modpassword'));
	$cpforms->makehidden(array('name'=>'userid',
							   'value'=>intval($user['userid'])));
    $cpforms->makeinput(array('text'=>'������:',
                               'name'=>'oldpassword',
                               'type'=>'password'));
    $cpforms->makeinput(array('text'=>'������:',
                               'name'=>'newpassword',
                               'type'=>'password'));
    $cpforms->makeinput(array('text'=>'ȷ��������:',
                               'name'=>'comfirpassword',
                               'type'=>'password'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//end modpass

// ɾ���û�ҳ��
if($_GET['action'] == "del")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
	$user = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE userid='$userid'");
    if (empty($user))
	{
		sa_exit("�ù���Ա������","javascript:history.go(-1);");
	}
	$cpforms->formheader(array('title'=>'ɾ���û�',
							   'colspan'=>'1'));
	
    echo "<tr align=\"center\" class=\"firstalt\">\n";
    echo "  <td width=\"50%\">�û�����".htmlspecialchars($user['username'])."</td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\" class=\"secondalt\">\n";
	echo "  <td colspan=\"2\"><font color=\"#FF0000\">ע�⣺ɾ�����û���ȷ����</font></td>\n";
    echo "</tr>\n";
	
	$cpforms->makehidden(array('name'=>'userid','value'=>intval($user['userid'])));
    $cpforms->makehidden(array('name'=>'action',
                               'value'=>'deluser'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//del

cpfooter();
?>
