<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵�����������
// =============================================================// 
error_reporting(7);
require_once ("global.php");
require_once ("py.php");
//��ʼ�������ݿ�
//��ӷ������
if($_POST['action'] == "addsort")
{
	//��֤�߼���
	$result=checksortLen($sortname);
	if($result)
	{
		sa_exit($result, "javascript:history.go(-1);");
	}
	$result=checksortdir($sortdir);
	if($result)
	{
		sa_exit($result, "javascript:history.go(-1);");
	}
    $query="SELECT * FROM ".$db_prefix."sort WHERE sortname='$sortname'";
    $result=$DB->num_rows($DB->query($query));
    if($result > 0)
	{
		sa_exit("�÷����������ݿ����Ѵ���!", "javascript:history.go(-1);");
    }
    $query="SELECT * FROM ".$db_prefix."sort WHERE sortdir='$sortdir'";
    $result=$DB->num_rows($DB->query($query));
    if($result > 0)
	{
		sa_exit("Ŀ¼".$sortdir."/�ѱ���������ʹ��!", "javascript:history.go(-1);");
    }

	//��֤�߼��Խ���
	$query="INSERT INTO ".$db_prefix."sort (sortname,parentid,sortdir) VALUES ('".addslashes($_POST['sortname'])."', ".$_POST['parentid'].",'".addslashes($_POST['sortdir'])."')";
    $DB->query($query) or die("����·���ʧ��!");
    redirect("����·���ɹ�!", "./sort.php?action=add");
}

//�޸ķ������
if($_POST['action'] == "modsort")
{
	//��֤�߼���
	$result=checksortLen($sortname);
	$result.=checksortdir($sortdir);
	if($result)
	{
		sa_exit($result, "javascript:history.go(-1);");
	}
        if($_POST['oldsortname'] != $_POST['sortname'])
        {
            $query="SELECT * FROM ".$db_prefix."sort WHERE sortname='$sortname'";
            $result=$DB->num_rows($DB->query($query));
            if($result > 0)
            {
            sa_exit("�÷����������ݿ����Ѵ���<br>", "javascript:history.go(-1);");
            }
        }
if($_POST['oldsortdir'] != $_POST['sortdir'])
{
    $query="SELECT * FROM ".$db_prefix."sort WHERE sortdir='$sortdir'";
    $result=$DB->num_rows($DB->query($query));
    if($result > 0)
	{
		sa_exit("Ŀ¼".$sortdir."/�ѱ���������ʹ��!", "javascript:history.go(-1);");
    }
}
	//��֤�߼��Խ���
	$query="UPDATE ".$db_prefix."sort SET 
sortname='".addslashes($_POST['sortname'])."', sortdir='".addslashes($_POST['sortdir'])."', parentid='".$_POST['parentid']."' WHERE sortid='".intval($sortid)."'";
    $DB->query($query) or die("�޸ķ���ʧ��");
    if(is_dir("../".$configuration[htmlfolder]."/".$oldsortdir.""))             rename("../".$configuration[htmlfolder]."/".$oldsortdir."","../".$configuration[htmlolder]."/".$sortdir."");
    $query="UPDATE ".$db_prefix."article SET pid='".$_POST['parentid']."' WHERE sortid='".intval($sortid)."'";
    $DB->query($query);
    redirect("�޸ķ���ɹ�<br>", "./sort.php?action=edit");
}

//ɾ�����༰������²���
if($_POST['action'] == "delsort")
{
    $query = $DB->query("SELECT * FROM ".$db_prefix."sort where sortid='".intval($sortid)."'");
    $sort = $DB->fetch_array($query);
    $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".intval($sortid)."'");
    while ($tsort=$DB->fetch_array($tsorts))
    {
        if(is_dir("../".$configuration[htmldir]."/".intval($tsort['sortdir'])))         removeDir("../".$configuration[htmldir]."/".intval($tsort['sortdir'])); 
        $query="DELETE FROM ".$db_prefix."article WHERE sortid='".$tsort['sortid']."'";
        $DB->query($query);
    }//end while
    $query="DELETE FROM ".$db_prefix."sort WHERE sortid='".intval($sortid)."' or parentid='".intval($sortid)."'";
    $DB->query($query) or die("ɾ������ʧ��!");
    $query="DELETE FROM ".$db_prefix."article WHERE sortid='".intval($sortid)."' or pid='".intval($sortid)."'";
    $DB->query($query) or die("ɾ����ѡ����������ʧ��!");
    if(is_dir("../".$configuration[htmldir]."/".$sort['sortdir'])) removeDir("../".$configuration[htmldir]."/".$sort['sortdir']);
    redirect("ɾ��������¼�����������³ɹ�!", "./sort.php?action=edit");
}
//�޸ķ�������
if($_POST['action'] == "displayorder")
{
            if (empty($_POST['displayorder']) OR !is_array($_POST['displayorder'])) {
            sa_exit("������Ϊ��!", "javascript:history.go(-1);");
            }
            while (list($sortid,$display)=each($displayorder)) {
                 $DB->query("UPDATE ".$db_prefix."sort SET displayorder='".intval($display)."' WHERE sortid='".intval($sortid)."'");
            }
            redirect("�޸ķ�������ɹ�!", "./sort.php?action=edit");
}
if (!isset($_GET['action'])) {
    $_GET['action']="add";
}
cpheader();

//��ӷ���ҳ��
if ($_GET['action']=="add")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'��ӷ���','name'=>'form'));
	echo "<tr class=\"".getrowbg()."\">\n";
    echo "<td>�ϼ�����:</td>\n";
    echo "<td><select name=\"parentid\">\n";
    echo "<option value=\"0\" selected>��</option>\n";
	$query = "SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid";
	$result = $DB->query($query);
	while ($sort=$DB->fetch_array($result))
	{ 
          	echo "<option value=".$sort['sortid'].">".htmlspecialchars($sort['sortname'])."</option>\n";
	}
    echo "</select>\n";
    echo "</td>\n";
    echo "</tr>\n";
    $cpforms->makeinput(array('text'=>'��������:',
                               'name'=>'sortname',
                                'extra'=>"onpropertychange=\"ping(sortname.value);\""));
    $cpforms->makeinput(array('text'=>'����Ŀ¼:',
                               'name'=>'sortdir',
                                'value'=>""));
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>'addsort'));
    $cpforms->formfooter();

	echo "</td>\n</tr>\n</table>";


} //end add


//�����б�ҳ��
if($_GET['action'] == "edit")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->tableheader();
	echo "<form method=\"post\" action=\"sort.php\">\n";
	//��ȡ�������
	$query = "SELECT * FROM ".$db_prefix."sort";
	$total=$DB->num_rows($DB->query($query));
	echo "<tr>\n";
    echo "	<td class=\"tbhead\" colspan=\"4\">\n";
    echo "	<b><font color=\"#F5D300\">���з����б�Ŀǰ���� ".$total." �����ࣩ</font></b>\n";
    echo "	</td>\n";
    echo "</tr>\n";

	echo "<tr bgcolor=\"#999999\">\n";
	echo "	<td align=\"center\" width=\"10%\"><b>��������</b></td>\n";
	echo "	<td align=\"center\" width=\"20%\"><b>��������</b></td>\n";
	echo "	<td align=\"center\" width=\"20%\"><b>����Ŀ¼</b></td>\n";
	echo "	<td align=\"center\" width=\"20%\"><b>��������</b></td>\n";
	echo "	<td align=\"center\" width=\"30%\"><b>�������</b></td>\n";
	echo "</tr>\n";
        if($total =="0"){
	echo "<tr class=".getrowbg().">\n";
	echo "	<td align=\"center\" colspan=\"5\">��û�з���...  <a href=sort.php?action=add>[��ӷ���]</a></td>\n";
	echo "</tr>\n";
        }
	$osorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY displayorder");
	while ($osort=$DB->fetch_array($osorts))
		{ 
			$query = "SELECT * FROM ".$db_prefix."article WHERE sortid='".intval($osort['sortid'])."' or pid='".intval($osort['sortid'])."'";
			$articletotal=$DB->num_rows($DB->query($query));
			echo "<tr class=secondalt>\n";
			echo "<td align=center><input type=\"text\" name=\"displayorder[".$osort['sortid']."]\" size=\"2\" maxlength=\"5\" value=\"".$osort['displayorder']."\" ></td></td>";
			echo "<td align=center><b>".htmlspecialchars($osort['sortname'])."</b></td>";
			echo "<td align=center>".$osort['sortdir']."/</td>";
			echo "<td align=center>$articletotal ��</td>\n";
			echo "<td align=center>[<a href=sort.php?sortid=$osort[sortid]&action=mod>�༭</a>][<a href=sort.php?sortid=$osort[sortid]&action=del>ɾ��</a>]</td>\n";
			echo "</tr>\n";
	                $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$osort['sortid']."' ORDER BY displayorder");
	                while ($tsort=$DB->fetch_array($tsorts)){
			       $query = "SELECT * FROM ".$db_prefix."article WHERE sortid='".intval($tsort['sortid'])."'";
			       $articletotal=$DB->num_rows($DB->query($query));
			       echo "<tr class=firstalt>\n";
			       echo "<td align=center><input type=\"text\" name=\"displayorder[".$tsort['sortid']."]\" size=\"2\" maxlength=\"5\" value=\"".$tsort['displayorder']."\" ></td></td>";
			       echo "<td align=center>".htmlspecialchars($tsort['sortname'])."</td>";
			       echo "<td align=center>".$tsort['sortdir']."/</td>";
			       echo "<td align=center>$articletotal ��</td>\n";
			       echo "<td align=center>[<a href=sort.php?sortid=$tsort[sortid]&action=mod>�༭</a>][<a href=sort.php?sortid=$tsort[sortid]&action=del>ɾ��</a>]</td>\n";
		               echo "</tr>\n";
                        }
		}//end while
        echo "<tr class=\"tbhead\">\n";
        echo "<td colspan=\"5\" align=\"center\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"displayorder\" />\n";
        echo "<input class=\"button\" type=\"submit\" name=\"submit\" value=\" �ύ \" > \n";
        echo "<input class=\"bginput\" type=\"reset\" name=\"\" value=\" ���� \" > \n";
        echo "</td></tr>\n";
	echo "</form>\n";
    $cpforms->tablefooter();
	echo "</td>\n</tr>\n</table>";
} //end edit


//�޸ķ���ҳ��
if($_GET['action'] == "mod")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $sortid = intval($_GET['sortid']);
    $sort = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."sort WHERE sortid='".intval($sortid)."'");
    $cpforms->formheader(array('title'=>'�༭����'));
    echo "<tr class=\"".getrowbg()."\">\n";
    echo "  <td>�ϼ�����:</td>\n";
    echo "  <td><select name=\"parentid\">\n";
    if ($sort['parentid'] == '0') $tt="selected";
    echo "<option value=\"0\" $tt>��</option>\n";
	$result = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid");
	while ($osort=$DB->fetch_array($result))
	{ 
		if ($sort['parentid'] == $osort['sortid']){
			echo "<option value=".$osort['sortid']." selected>".htmlspecialchars($osort['sortname'])."</option>\n";
		} else {
			echo "<option value=".$osort['sortid'].">".htmlspecialchars($osort['sortname'])."</option>\n";
		}
	}
    echo "  </select></td>\n";
    echo "</tr>\n";
    $cpforms->makeinput(array('text'=>'��������:',
                               'name'=>'sortname',
                               'value'=>htmlspecialchars($sort['sortname'])));
    if (empty($sort['sortdir'])) $sort['sortdir']=c($sort['sortname']);
    $cpforms->makeinput(array('text'=>'����Ŀ¼:',
                               'name'=>'sortdir',
                               'value'=>htmlspecialchars($sort['sortdir'])));
    $cpforms->makehidden(array('name'=>'oldsortname','value'=>$sort['sortname']));
    $cpforms->makehidden(array('name'=>'oldsortdir','value'=>$sort['sortdir']));
    $cpforms->makehidden(array('name'=>'sortid','value'=>$sort['sortid']));
    $cpforms->makehidden(array('name'=>'action',
                               'value'=>'modsort'));
    $cpforms->formfooter(array('confirm'=>'1'));
	echo "</td>\n</tr>\n</table>";
} //end mod

//ɾ������ҳ��
if($_GET['action'] == "del")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $sortid = intval($_GET['sortid']);
    $sort = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."sort WHERE sortid='".intval($sortid)."'");
	$cpforms->formheader(array('title'=>'ɾ������','colspan'=>'1'));
    echo "<tr align=\"center\" class=\"firstalt\">\n";
    echo "  <td width=\"50%\">".htmlspecialchars($sort['sortname'])."</td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\" class=\"secondalt\">\n";
	echo "  <td colspan=\"2\"><font color=\"#FF0000\">ע�⣺ɾ���˷��ཫͬʱɾ���˷����¼�����������µ����м�¼��ȷ����</font></td>\n";
    echo "</tr>\n";
	
	$cpforms->makehidden(array('name'=>'sortid','value'=>intval($sort['sortid'])));
    $cpforms->makehidden(array('name'=>'action',
                               'value'=>'delsort'));
    $cpforms->formfooter(array('confirm'=>'1'));
	echo "</td>\n</tr>\n</table>";
}//end del
cpfooter()
?>
<script src=GB2312.js></script>
<script language="javascript">
<!--
function ping(msg) {
    if (msg!=""){
        document.form.sortdir.value = getSpell(msg)+"";
    }
}
//-->
</script>