<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵�������¹���
// =============================================================// 
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");
cpheader();

// ========================== ��ȡ���µ�ַ ==========================// 
$sortarid=$_GET['sort'];
$str = file($_GET['id']);
$count = count($str);
for ($i=0;$i<$count;$i++){ 
    $file .= $str[$i];
}

$name = explode("<name>",$file);
$name = explode("</name>",$name[1]);
$name= $name[0];
$tomtq = explode("<urls>",$file);
$tomtq = explode("</urls>",$tomtq[1]);
$tomtq= $tomtq[0];
$tomtq = str_replace("<url>/", "<url>http://pic.okxr.com/", $tomtq);
$tomtq = str_replace("<url>", "[img]", $tomtq);
$tomtq = str_replace("</url>", "[/img]", $tomtq);


// ========================== ��ȡ���µ�ַ���� ==========================// 

if ($_GET['action']=="add" OR $_GET['action']=="mod"){
?>
<script language="JavaScript">
	function ProcessArticle(){
		if(document.form.title.value == ''){
			alert('���������±���.');
			document.form.title.focus();
			return false;
		}
		if(document.form.sortid.value == ''){
			alert('��ѡ�����.');
			document.form.sortid.focus();
			return false;
		}
		if(document.form.content.value == ''){
			alert('����������.');
			return false;
		}
			return true;
	}
</script>
<?php
}
if (!isset($_GET['action'])) {
    $_GET['action']="add";
}

// �������ҳ��
if ($_GET['action']=="add")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'�������',
                                'name'=>'form',
                                'action'=>'articleoperate.php',
                                'extra'=>"onSubmit=\"return ProcessArticle()\""));
    $cpforms->makeinput(array('text'=>'���±���:',
                                                          'value'=>$name,
							  'size'=>'50',
							  'maxlength'=>'140',
                              'name'=>'title'));
	$cpforms->makeinput(array('text'=>'��������:',
                                        'value'=>$_SESSION['username'],    
							  'maxlength'=>'50',
                              'name'=>'author'));
	$cpforms->makeinput(array('text'=>'���³���:',
                                        'value'=>$configuration[title],    
							  'maxlength'=>'120',
                              'name'=>'source'));
	echo "<tr class=\"".getrowbg()."\">\n";
    echo "<td>ѡ�����:</td>\n";
    echo "<td><select name=\"sortid\">\n";
    echo "<option value=".$sortarid." selected>".$sortarid."</option>\n";
	$query = "SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid";
	$result = $DB->query($query);
	while ($sort=$DB->fetch_array($result))
	{ 
		if ($sort[sortid] == $sortid){
			echo "<option value=".$sort['sortid']." selected>".htmlspecialchars($sort['sortname'])."</option>\n";
		} else {
			echo "<option value=".$sort['sortid'].">".htmlspecialchars($sort['sortname'])."</option>\n";
		}
	        $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$sort['sortid']."' ORDER BY sortid");
	        while ($tsort=$DB->fetch_array($tsorts))
                {
		     if ($tsort[sortid] == $sortid){
			     echo "<option value=".$tsort['sortid']." selected> �� ".htmlspecialchars($tsort['sortname'])."</option>\n";
		     } else {
			     echo "<option value=".$tsort['sortid']."> �� ".htmlspecialchars($tsort['sortname'])."</option>\n";
		     }
                }
	}
    echo "</select>\n";
    echo "</td>\n";
    echo "</tr>\n";

    echo "<tr class=\"".getrowbg()."\">\n";
    echo "<td>UBB��ǩ:</td>\n";
    echo "<td>\n";
	include("ubb.php");
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr class=\"".getrowbg()."\" id=uploadpic style=\"display:none;\">\n";
    echo "<td>��ʽ:jpg,gif,png</td>\n";
    echo "<td><iframe name=\"upload_pic\" frameborder=0 noresize width=100% height=22 scrolling=no src=upload_pic.php></iframe></td>\n";
    echo "</tr>\n";
    $cpforms->maketextarea(array('text'=>"��������:<a onclick=\"document.all.uploadpic.style.display=document.all.uploadpic.style.display=='none'?'':'none';return false;\" href='javascript:'><b>(�ϴ�ͼƬ)</b></a>",
								 'cols'=>'80',
								 'rows'=>'20',
                                 'value'=>$tomtq,
                                 'name'=>'content'));
	$cpforms->makeyesno(array('text'=>'�Ƿ��Ƽ�?','name'=>'iscommend','selected'=>0));
	$cpforms->makeyesno(array('text'=>'�Ƿ�֧��HTML?<br>֧��HTML��UBB������Ч','name'=>'ishtml','selected'=>0));
	$cpforms->makeyesno(array('text'=>'�Ƿ��Զ�����URL?','name'=>'isparseurl','selected'=>0));
	$cpforms->makeyesno(array('text'=>'��ʾ?','name'=>'visible','selected'=>1));
	$cpforms->makeyesno(array('text'=>'�Զ�����html?','name'=>'ismake','selected'=>1));
    $cpforms->makehidden(array('name'=>'action','value'=>'addarticle'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//end add



// �޸�����ҳ��
if($_GET['action'] == "mod")
{
    $result = $DB->query("SELECT * FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'");
	$article = $DB->fetch_array($result);	
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'�༭����',
                               'name'=>'form',
                               'action'=>'articleoperate.php',
                               'extra'=>"onSubmit=\"return ProcessArticle()\""));
$cpforms->makeinput(array('text'=>'���±���:','size'=>'50','maxlength'=>'140','name'=>'title','value'=>htmlspecialchars($article['title'])));
	$cpforms->makeinput(array('text'=>'��������:','name'=>'author','value'=>htmlspecialchars($article['author'])));
	$cpforms->makeinput(array('text'=>'���³���:','name'=>'source','value'=>htmlspecialchars($article['source'])));
	echo "<tr class=\"".getrowbg()."\">\n";
    echo "  <td>���·���:</td>\n";
    echo "  <td><select name=\"sortid\">\n";

	$result = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid");
	while ($sort=$DB->fetch_array($result))
	{ 
		if ($sort[sortid] == $article['sortid']){
			echo "<option value=".$sort['sortid']." selected>".htmlspecialchars($sort['sortname'])."</option>\n";
		} else {
			echo "<option value=".$sort['sortid'].">".htmlspecialchars($sort['sortname'])."</option>\n";
		}
	        $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$sort['sortid']."' ORDER BY sortid");
	        while ($tsort=$DB->fetch_array($tsorts))
                {
		     if ($tsort[sortid] == $article['sortid']){
			     echo "<option value=".$tsort['sortid']." selected> �� ".htmlspecialchars($tsort['sortname'])."</option>\n";
		     } else {
			     echo "<option value=".$tsort['sortid']."> �� ".htmlspecialchars($tsort['sortname'])."</option>\n";
		     }
                }
	}
    echo "  </select></td>\n";
    echo "</tr>\n";
	//��ȡ�������
    echo "<tr class=\"".getrowbg()."\">\n";
    echo "<td>UBB��ǩ:</td>\n";
    echo "<td>\n";
	include("ubb.php");
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr class=\"".getrowbg()."\" id=uploadpic style=\"display:none;\">\n";
    echo "<td>��ʽ:jpg,gif,png</td>\n";
    echo "<td><iframe name=\"upload_pic\" frameborder=0 noresize width=100% height=22 scrolling=no src=upload_pic.php></iframe></td>\n";
    echo "</tr>\n";
    $cpforms->maketextarea(array('text'=>"��������:<a onclick=\"document.all.uploadpic.style.display=document.all.uploadpic.style.display=='none'?'':'none';return false;\" href='javascript:'><b>(�ϴ�ͼƬ)</b></a>",
								 'cols'=>'75',
								 'rows'=>'20',
                                 'name'=>'content',
								 'value'=>htmlspecialchars($article['content'])));
	$cpforms->makeyesno(array('text'=>'�Ƿ��Ƽ�?','name'=>'iscommend','selected'=>intval($article['iscommend'])));
$cpforms->makeyesno(array('text'=>'�Ƿ�֧��HTML?<br>֧��HTML��UBB������Ч','name'=>'ishtml','selected'=>intval($article['ishtml'])));
$cpforms->makeyesno(array('text'=>'�Ƿ��Զ�����URL?','name'=>'isparseurl','selected'=>intval($article['isparseurl'])));
	$cpforms->makeyesno(array('text'=>'��ʾ?','name'=>'visible','selected'=>intval($article['visible'])));
	$cpforms->makeyesno(array('text'=>'�Զ���������htmlҳ?','name'=>'ismake','selected'=>1));
	$cpforms->makehidden(array('name'=>'oldsortid','value'=>$flash['sortid']));
	$cpforms->makehidden(array('name'=>'articleid','value'=>intval($article['articleid'])));
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>'modarticle'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//end mod

// ɾ������ҳ��
if($_GET['action'] == "del")
{
	$articles = $DB->query("SELECT * FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'");
	$article=$DB->fetch_array($articles);

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
	$cpforms->formheader(array('title'=>'ɾ������',
                                    'action'=>'articleoperate.php',
							   'colspan'=>'1'));
	
    echo "<tr align=\"center\" class=\"firstalt\">\n";
    echo "  <td width=\"50%\">���⣺".htmlspecialchars($article['title'])."</td>\n";
	echo "</tr>\n";
	echo "<tr align=\"center\" class=\"secondalt\">\n";
	echo "  <td colspan=\"2\"><font color=\"#FF0000\">ע�⣺ɾ�������£�ȷ����</font></td>\n";
    echo "</tr>\n";
	
	$cpforms->makehidden(array('name'=>'articleid','value'=>intval($article['articleid'])));
    $cpforms->makehidden(array('name'=>'action','value'=>'delarticle'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//del
//�����б�ҳ��
if($_GET['action'] == "edit")
{
		if (!empty($sortid))
	{
		$sortid = intval($_GET['sortid']);
		$issort = "WHERE sortid=$sortid";
		$sortnamequery = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid=$sortid");
		$sortnamerow = $DB->fetch_array($sortnamequery);
		$sortname=$sortnamerow['sortname'];
        } elseif (!empty($pid)){
		$issort = "WHERE pid=$pid or sortid=$pid";
		$sortnamequery = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid=$pid");
		$sortnamerow = $DB->fetch_array($sortnamequery);
		$sortname=$sortnamerow['sortname'];
        } elseif (!empty($keywords)){
		$sortname="�������";
		$issort = "where title LIKE '%$keywords%'";
	} elseif($_GET['type'] == "hide") {
		$issort = "where visible=0";
		$sortname="��������";
	} else {
		$sortname="��������";
		$issort = "";
	}
	echo "<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"1\">\n";
	echo "<tr>\n";
	echo "	<td width=\"75%\"><a href=article.php?action=edit><b>��������</b></a>\n";
        echo " | <a href=article.php?action=edit&type=hide><b>��ʾ����</b></a>";
	$query = "SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid";
	$result = $DB->query($query);
	while ($sort=$DB->fetch_array($result))
	{ 
	    if ($sort[sortid] == $pid){
		echo " | <a href=\"article.php?action=edit&pid=".$sort['sortid']."\"><font color=red><b>".htmlspecialchars($sort['sortname'])."</b></font></a>\n";
	    } else {
		echo " | <a href=\"article.php?action=edit&pid=".$sort['sortid']."\"><b>".htmlspecialchars($sort['sortname'])."</b></a>\n";
	    }
	}
        echo "</td></tr></table>\n";
	echo "<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"1\">\n";
	echo "<tr>\n";
	//$sortquery = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid");
	$tsortquery = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$_GET['pid']."' and parentid>'0' ORDER BY sortid");
        $i=0;
	while ($tsort=$DB->fetch_array($tsortquery)) 
	{
            $i++;
            if($_GET['sortid'] == $tsort['sortid'])
            {
                 echo "<td><font color=red><li><a href=article.php?action=edit&pid=$pid&sortid=".$tsort['sortid']."><font color=red><b>".$tsort['sortname']."</b></font></a></font></td>\n"; 
            } else {
                 echo "<td><li><a href=article.php?action=edit&pid=$pid&sortid=".$tsort['sortid']."><b>".$tsort['sortname']."</b></a></td>\n"; 
            }
            if ($i%8==0) {
                 echo "</tr><tr>\n";
            }

	} 
        if($i%8!=0) {
            for ($t = 0;$t<(10-$i%8); $t++){
            echo "<td>&nbsp;</td>";
            }
        }
        echo "</tr></table>\n";
	//�����б����
	echo "<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"1\"><form method=\"post\" action=\"article.php?action=edit\">\n";
	echo "<tr>\n";
	echo "<td align=\"right\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"edit\">";
	echo "<input type=\"text\" name=\"keywords\" size=\"20\" maxlength=\"140\" value=\"\" >\n";
	echo "	<input type=\"submit\" value=\"����\" />\n";
	echo "  </td></tr></form></table>\n";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>\n";
    $cpforms->formheader(array('title'=>$sortname,
                                    'action'=>'articleoperate.php',
							   'colspan'=>'6'));
	echo "<tr bgcolor=\"#999999\">\n";
	echo " <td align=\"center\" width=\"7%\"><b>ѡ��</b></td>\n";
	echo " <td width=\"36%\"><b>���±���</b></td>\n";
	echo " <td align=\"center\" width=\"12%\"><b>����</b></td>\n";
	echo " <td align=\"center\" width=\"12%\"><b>����</b></td>\n";
	echo " <td align=\"center\" width=\"12%\"><b>ʱ��</b></td>\n";
	echo " <td align=\"center\" width=\"21%\"><b>����</b></td>\n";
	echo "</tr>\n";

	$page = intval($_GET['page']);
	if(!empty($page)) {
		$start_limit = ($page - 1) * 30;
	} else {
		$start_limit = 0;
		$page = 1;
	}

	$query="SELECT * FROM ".$db_prefix."article $issort";
    $total=$DB->num_rows($DB->query($query));
	$multipage = multi($total, "30", $page, "article.php?action=edit&type=$type&pid=$pid&sortid=$sortid&keywords=$keywords","php");

    $articles = $DB->query("SELECT * FROM ".$db_prefix."article $issort ORDER BY articleid DESC LIMIT $start_limit,30");
    while ($article = $DB->fetch_array($articles)) {
		//��ȡ������
		$sorts = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid='$article[sortid]'");
		$sortname = $DB->fetch_array($sorts);
		echo "<tr class=".getrowbg().">";
		echo "<td align=\"center\">";
		echo "<input type=\"checkbox\" name=\"article[".$article['articleid']."]\" value=\"1\">";
		echo "</td>\n";
		echo "<td><a href=\"../".$configuration[htmldir]."/".$sortname['sortdir']."/".$article[articleid].".html\" target=_blank>".$article[title]."</a></td>\n";
		echo "<td align=\"center\">".htmlspecialchars($article['author'])."</td>\n";
		echo "<td align=center>".htmlspecialchars($sortname['sortname'])."</td>\n";
		echo "<td align=\"center\"><font color=\"#666666\" style=\"font-size:11px\">".date("Y-m-d",$article['addtime'])."</font></td>\n";
		echo "<td align=\"center\">";
		if ($article[visible] == '1') {
			echo "<a href=\"articleoperate.php?action=hide&articleid=$article[articleid]\">[����]</a> ";
		} else {
			echo "<a href=\"articleoperate.php?action=show&articleid=$article[articleid]\"><font color=\"red\">[��ʾ]</a> </font>";
		}
		echo "<a href=\"article.php?action=mod&articleid=$article[articleid]\">[�޸�]</a> ";
		echo "<a href=\"article.php?action=del&articleid=$article[articleid]\">[ɾ��]</a> ";
		echo "<a href=\"make.php?action=article&articleid=$article[articleid]\">[����]</a>";
		echo "</td>\n";
        echo "</tr>";
    }
	echo "<tr class=\"".getrowbg()."\">";
	echo "<td colspan=\"6\"><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	echo "<tr>";
	echo "<td><input type='button' name='chkall' value='ȫ ѡ' onclick='CheckAll(this.form)'> ���� <font color=\"#000000\" style=\"font-size:11px\"><b>".$total."</b></font> ƪ���� | <font color=\"#000000\" style=\"font-size:11px\"><b>30</b></font> ƪ/ҳ</td>\n";
	echo "<td align=\"right\">\n";
	echo $multipage;
	echo "</td></tr></table>\n";
	echo "</td></tr>\n";
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>''));
	echo "<tr class=\"tbhead\">\n";
	echo "<td colspan=\"6\" align=\"center\">\n";
	echo " <input class=\"bginput\" type=\"submit\" name=\"submit\" value=\" ��ʾ \"  onclick=\"this.form['action'].value='moreshow'\" / >\n";
	echo " <input class=\"bginput\" type=\"submit\" name=\"submit\" value=\" ���� \"  onclick=\"this.form['action'].value='morehide'\" / >\n";
	echo " <input class=\"bginput\" type=\"submit\" name=\"submit\" value=\" ɾ�� \"  onclick=\"this.form['action'].value='moredel'\" / >\n"; 
	echo " <input class=\"bginput\" type=\"submit\" name=\"submit\" value=\" ���� \"  onclick=\"this.form['action'].value='moremake'\" / >\n"; 
	echo "</td>\n</tr>\n</form>\n</table>\n";
	echo "</td>\n</tr>\n</table>";
}//endedit

//����htmlҳ��
if($_GET['action'] == "make")
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'������������ҳ','action'=>'make.php','method'=>'GET'));
	echo "<tr class=".getrowbg().">";
	echo "<td><table width=100% border=0 align=center cellpadding=0 cellspacing=0>";
	echo "<tr>";
    echo "  <td><select name=\"sortid\">\n";
    echo "  <option value=\"\" selected>��������</option>\n";
	$result = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY sortid");
	while ($sort=$DB->fetch_array($result))
	{ 
	    echo "<option value=".$sort['sortid'].">".htmlspecialchars($sort['sortname'])."</option>\n";
	    $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$sort['sortid']."' ORDER BY sortid");
	    while ($tsort=$DB->fetch_array($tsorts))
            {
		echo "<option value=".$tsort['sortid']."> �� ".htmlspecialchars($tsort['sortname'])."</option>\n";
            }
	}
    echo "  </select>\n";
        echo "&nbsp;&nbsp;���� <input  type=\"text\" name=\"new\" size=\"5\" maxlength=\"5\" value=\"100\" > ��\n";
        echo "&nbsp;&nbsp;ÿ�� <input  type=\"text\" name=\"percount\" size=\"5\" maxlength=\"4\" value=\"50\" > ��\n";
        echo "<input class=\"bginput\" type=\"submit\" name=\"submit\" value=\"ȷ��\"></td>\n";
	echo "</tr></table>\n";
	echo "</td></tr>\n";
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>'article'));
        echo "</form></table>";
	echo "</td>\n</tr>\n</table><br>";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>\n";
    $cpforms->formheader(array('title'=>'��������������ҳ','action'=>'make.php','method'=>'GET'));
	echo "<tr class=".getrowbg().">";
	echo "<td><table width=100% border=0 align=center cellpadding=0 cellspacing=0>";
	echo "<tr>";
        echo "  <td><select name=\"Dyear\">";
        for ($i = 2004;$i<=date("Y",time()); $i++){
              echo "<option value=".$i."";
              if ($i==date("Y",time())) echo " selected";
              echo ">".$i."</option>";
        }
        echo "</select> ";
        echo "  <select name=\"Dmonth\">";
        for ($i = 1;$i<=12; $i++){
              if ($i < 10) $i="0".$i;
              echo "<option value=".$i."";
              if ($i==date("m",time())) echo " selected";
              echo ">".$i."</option>";
        }
        echo "</select> ";
        echo "  <select name=\"Dday\">";
        echo "<option value=\"\"></option>";
        for ($i = 1;$i<=31; $i++){
              if ($i < 10) $i="0".$i;
              echo "<option value=".$i."";
              if ($i==date("d",time())) echo " selected";
              echo ">".$i."</option>";
        }
        echo "</select> ";
        echo "&nbsp;&nbsp;ÿ�� <input  type=\"text\" name=\"percount\" size=\"5\" maxlength=\"4\" value=\"50\" > ��\n";
        echo "<input class=\"bginput\" type=\"submit\" name=\"submit\" value=\"ȷ��\"></td>\n";
	echo "</tr></table>\n";
	echo "</td></tr>\n";
    $cpforms->makehidden(array('name'=>'action',
                                'value'=>'article'));
    $cpforms->makehidden(array('name'=>'type',
                                'value'=>'date'));
        echo "</form></table>";
	echo "</td>\n</tr>\n</table><br>";
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"96%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->tableheader();
	//��ȡ�������
	$query = "SELECT * FROM ".$db_prefix."sort";
	$total=$DB->num_rows($DB->query($query));
	echo "<tr>\n";
    echo "	<td class=\"tbhead\" colspan=\"4\">\n";
    echo "	<b><font color=\"#F5D300\">����html����".$total."�����ࣩ</font> <a href=make.php?action=sort><font color=red>[�������з���ҳ]</font></a> <a href=make.php?action=article><font color=red>[������������ҳ]</font></a> <a href=make.php?action=index><font color=red>[������ҳ]</font></a></b>\n";
    echo "	</td>\n";
    echo "</tr>\n";

	echo "<tr bgcolor=\"#999999\">\n";
	echo "	<td align=\"center\" width=\"25%\"><b>��������</b></td>\n";
	echo "	<td align=\"center\" width=\"25%\"><b>����Ŀ¼</b></td>\n";
	echo "	<td align=\"center\" width=\"25%\"><b>��������</b></td>\n";
	echo "	<td align=\"center\" width=\"25%\"><b>�������</b></td>\n";
	echo "</tr>\n";
        if($total =="0"){
	echo "<tr class=".getrowbg().">\n";
	echo "	<td align=\"center\" colspan=\"4\"><b>��û�з���</b>  <a href=sort.php?action=add>[��ӷ���]</a></td>\n";
	echo "</tr>\n";
        }

	$sorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' ORDER BY displayorder DESC");
	while ($sort=$DB->fetch_array($sorts))
		{ 
			$query = "SELECT * FROM ".$db_prefix."article WHERE sortid='".intval($sort['sortid'])."' or pid='".intval($sort['sortid'])."'";
			$articletotal=$DB->num_rows($DB->query($query));
			echo "<tr class=secondalt>\n";
			echo "<td align=center><a href=article.php?action=edit&sortid=".$sort[sortid]."><b>".htmlspecialchars($sort['sortname'])."</b></td>";
			echo "<td align=center>".$sort['sortdir']."/</td>";
			echo "<td align=center>$articletotal ��</td>\n";
			echo "<td align=center><b><a href=make.php?action=sort&sortid=".$sort['sortid']." title=\"���ɸ������ҳ\"><font color=red>[���ɷ���ҳ]</font></a> <a href=make.php?action=article&sortid=".$sort['sortid']." title=\"���ɸ��ಥ��ҳ\"><font color=red>[��������ҳ]</font></a></b></td>\n";
			echo "</tr>\n";
	                $tsorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='".$sort['sortid']."' ORDER BY displayorder");
	                while ($tsort=$DB->fetch_array($tsorts)){
			       $query = "SELECT * FROM ".$db_prefix."article WHERE sortid='".intval($tsort['sortid'])."'";
			       $articletotal=$DB->num_rows($DB->query($query));
			       echo "<tr class=firstalt>\n";
			       echo "<td align=center><a href=article.php?action=edit&sortid=".$tsort[sortid].">".htmlspecialchars($tsort['sortname'])."</td>";
			       echo "<td align=center>".$tsort['sortdir']."/</td>";
			       echo "<td align=center>$articletotal ��</td>\n";
			       echo "<td align=center><b><a href=make.php?action=sort&sortid=".$tsort['sortid']." title=\"���ɸ������ҳ\"><font color=red>[���ɷ���ҳ]</font></a> <a href=make.php?action=article&sortid=".$tsort['sortid']." title=\"���ɸ��ಥ��ҳ\"><font color=red>[��������ҳ]</font></a></b></td>\n";
			       echo "</tr>\n";
                        }
		}//end while
    $cpforms->tablefooter();
	echo "</td>\n</tr>\n</table>";
}//endmake
cpfooter();
?>  
<script language=JavaScript>
function CheckAll(form)
{
	for (var i=0;i<form.elements.length;i++)
	{
		var e = form.elements[i];
		e.checked == true ? e.checked = false : e.checked = true;
	}
}
</script>
