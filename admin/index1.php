<?php
// ========================== �ļ�˵�� ==========================//
// ���ļ�˵������̨��ҳ���
// =============================================================//
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");

if (!isset($_GET['action'])) {
    $_GET['action']="frames";
}

if ($_GET['action']=="frames") {
?>
    <html>
    <head>
    <title>�������</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    </head>
	<frameset rows="*" cols="150,*" framespacing="0" frameborder="NO" border="0">
	<frame src="index.php?action=menu" name="menuFrame" scrolling="AUTO" noresize marginwidth="0" border="no" frameborder="0">
	<frame src="index.php?action=main" name="mainFrame" noresize marginwidth="0" border="no" frameborder="0">
	</frameset>
    <noframes>
	<body bgcolor="#FFFFFF" text="#000000">
    </body>
	</noframes>
    </html>
<?php
}

if ($_GET['action']=="menu") {
?>
<html>
<head>
<META content="text/html; charset=gb2312" http-equiv="Content-Type">
<link href="./cp.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" id="navbody">
<table width="100%" border="0" cellspacing="4" cellpadding="1" align="center">
 <tr>
  <td align="center">
   <b><a href="../index.php" target="_blank">վ����ҳ</a></b>
  </td>
 </tr>
 <tr>
  <td align="center">
   <b><a href="index.php?action=main" target="mainFrame">Control Panel</a></b>
  </td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
<?php
makenav("ϵͳ����",array("��������"=>"configurate.php",
						 "�鿴PHP��Ϣ"=>"configurate.php?action=phpinfo",
                            ));

makenav("���¹���",array("�������"=>"article.php?action=add",
						 "�༭����"=>"article.php?action=edit",
						 "�ɼ�����"=>"bas.php?action=edit",
						 "����html"=>"article.php?action=make",
                            ));
makenav("Զ�̶�ȡ",array("�½�����"=>"bas.php?action=add",
			 "�����б�"=>"bas.php?action=edit",
			 "���ݹ���"=>"bas_data.php",
                            ));
makenav("�������",array("��ӷ���"=>"sort.php?action=add",
						 "�༭����"=>"sort.php?action=edit",
                            ));

makenav("���ݿ����",array("�������ݿ�"=>"database.php?action=backup",
                           "�Ż����ݿ�"=>"database.php?action=optimize",
                           "�޸����ݿ�"=>"database.php?action=repair",
                            ));

makenav("������־",array("������¼"=>"log.php?action=admin",
                         "��½��¼"=>"log.php?action=login",
							));

makenav("����Աѡ��",array("��ӹ���Ա"=>"user.php?action=add",
						  "����Ա�б�"=>"user.php?action=edit",
						  "�޸��Լ�������"=>"user.php?action=modpass",
						  "�˳���½"=>"index.php?action=logout",
                            ));
echo "</table>\n";

?>
</body>
</html>
<?php
}
if ($_GET['action']=="main"){

	$serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
	$serverinfo .= @ini_get('safe_mode') ? ' / ��ȫģʽ' : NULL;

	if(@ini_get("file_uploads")) {
	$fileupload = "���� - �ļ� ".ini_get("upload_max_filesize")." - ����".ini_get("post_max_size");
	} else {
	$fileupload = "<font color=\"red\">��ֹ</font>";
	}
	
	if (get_cfg_var('register_globals')){
        $onoff="��";
    }else{
        $onoff="�ر�";
    }

	//��ȡ���ݿ��С
	$article_datasize = 0;
    $tables = $DB->query("SHOW TABLE STATUS");
    while ($table = $DB->fetch_array($tables)) {
           $datasize += $table['Data_length'];
           $indexsize += $table['Index_length'];
           if ($table['Name']==$db_prefix."article") {
               $article_datasize += $table['Data_length']+$table['Index_length'];
           }
    }

	//��ѯ������Ϣ
	$sorts="SELECT * FROM ".$db_prefix."sort";
    $sorttotal=$DB->num_rows($DB->query($sorts));
	$articles="SELECT * FROM ".$db_prefix."article";
    $arttotal=$DB->num_rows($DB->query($articles));
	$hiddenarticles="SELECT * FROM ".$db_prefix."article WHERE visible='0'";
    $hiddenarttotal=$DB->num_rows($DB->query($hiddenarticles));
cpheader()
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
<td align="center"><b>��ӭ�� <?=$_SESSION[username];?>,������ʱ����: <?=date("Y-m-d��H:i:s","".time()."");?></b></td>
</tr>
</table>
<br>
<table cellpadding="1" cellspacing="0" border="0" align="center" width="90%" class="tblborder"><tr><td>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr class="tblhead"><td colspan="2"><b><span class="tblhead">ϵͳ��Ϣ</span></a></td></tr><tr class='firstalt' valign='top'>
<td width="50%"><p><b>����������:</b></p></td>
<td width="50%"><p><?php echo "".$serverinfo.""; ?></p></td>
</tr>
<tr class='secondalt' valign='top'>
<td width="50%"><p><b>MySQL �汾:</b></p></td>
<td width="50%"><p><?php echo "".mysql_get_server_info().""; ?></p></td>
</tr>
<tr class='firstalt' valign='top'>
<td width="50%"><p><b>�ļ��ϴ�:</b></p></td>
<td width="50%"><p><?php echo "".$fileupload.""; ?></p></td>
</tr>
<tr class='secondalt' valign='top'>
<td width="50%"><p><b>register_globals:</b></p></td>
<td width="50%"><p><?php echo $onoff;?></p></td>
</tr>
</table></td></tr></table>
<br>
<table cellpadding="1" cellspacing="0" border="0" align="center" width="90%" class="tblborder"><tr><td>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr class="tblhead"><td colspan="2"><b><span class="tblhead">����ͳ��</span></a></td>
</tr>
<tr class='firstalt' valign='top'>
<td width="50%"><p><b>��������:</b></p></td>
<td width="50%"><p><?=$sorttotal?></p></td>
</tr>
<tr class='secondalt' valign='top'>
<td width="50%"><p><b>��������:</b></p></td>
<td width="50%"><p><?=$arttotal?></p></td>
</tr>
<tr class='firstalt' valign='top'>
<td width="50%"><p><b>������������:</b></p></td>
<td width="50%"><p><?=$hiddenarttotal?></p></td>
</tr>
<tr class='secondalt' valign='top'>
<td width="50%"><p><b>���ݿ��С:</b></p></td>
<td width="50%"><p><?php echo get_real_size($datasize+$indexsize);?></p></td>
</tr>
<tr class='firstalt' valign='top'>
<td width="50%"><p><b>�������ݴ�С:</b></p></td>
<td width="50%"><p><?php echo get_real_size($article_datasize);?></p></td>
</tr>
</table></td></tr></table>
<br>
<table cellpadding="1" cellspacing="0" border="0" align="center" width="90%" class="tblborder"><tr><td>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr class="tblhead"><td colspan="2"><b><span class="tblhead">�������������Ϣ</span></a></td></tr><tr class='firstalt' valign='top'>
<td width="50%"><p><b>��������:</b></p></td>
<td width="50%"><p><a href="mailto:wupei@china.com.cn" target="_blank">С�������</a></p></td>
</tr>
<tr class='secondalt' valign='top'>
<td width="50%"><p><b>�ٷ���ҳ:</b></p></td>
<td width="50%"><p><a href="http://www.ssfun.com" target="_blank">http://www.ssfun.com</a></p></td>
</tr>
<tr class='firstalt' valign='top'>
<td width="50%"><p><b>�ٷ���̳:</b></p></td>
<td width="50%"><p><a href="http://www.guan8.net" target="_blank">http://www.guan8.net</a></p></td>
</tr>
</table></td></tr></table>
<?php
cpfooter()
?>
</body>
</html>
<?php
}
?>
