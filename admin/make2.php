<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵��������htmҳ
// =============================================================// 
error_reporting(7);
set_time_limit(0);
// ���غ�̨��������
require_once ("global.php");
require_once ("class/make_inc.php");
cpheader();
//��������ҳ
if($_GET['action'] == "article"){
    if (!empty($_GET['articleid'])){
        $MK->makearticle($articleid);
        redirect("����".$articleid.".html�ɹ�!", "javascript:history.go(-1);");
    }
    !$step && $step=1;
    !$percount && $percount=100;
    $sstart=($step-1)*$percount;
    $next=$sstart+$percount;
    $step++;
    $goon=0;
    if (!empty($_GET['sortid'])){
        $maketype = "WHERE sortid='".$sortid."' and visible='1'";
    } else {
        $maketype = "WHERE visible='1'";
    }
    if ($_GET['type']=="date"){
        $Ddate = $Dday? mktime (0, 0,0 ,$Dmonth, $Dday,$Dyear):mktime (0, 0,0 ,$Dmonth, 1,$Dyear);
        $Ddate1 = $Dday? mktime (0, 0,0 ,$Dmonth, $Dday+1,$Dyear):mktime (0, 0,0 ,$Dmonth+1, 1,$Dyear);
        $maketype = "WHERE addtime>'".$Ddate."' and addtime<'".$Ddate1."' and visible='1'";
    }
    $articles="SELECT articleid FROM ".$db_prefix."article $maketype";
    $maketotal=$DB->num_rows($DB->query($articles));
    if($new > 0) $maketotal=$new;
    $makefile = $DB->query("SELECT articleid FROM ".$db_prefix."article $maketype order by articleid desc LIMIT $sstart,$percount");
    if($maketotal < $next){
        $next=$maketotal;
    }
    $i=0;
    while ($row=$DB->fetch_array($makefile)) 
    {
        $i++;
        if(($i+$sstart) <= $maketotal && $row['articleid']) {
	   $goon=1;
           $MK->makearticle($row['articleid']);
        }
    }
    if($goon){
        $db=debuginfo();
        $ds=$db+$d;
        $jumpurl="make.php?action=article&type=$type&Dyear=$Dyear&Dmonth=$Dmonth&Dday=$Dday&sortid=$sortid&new=$new&step=$step&percount=$percount&d=$ds";
        echo "<font color=red><b>�����������µ�Htmlҳ,��ȴ�......</b></font><br><br>";
        echo "��������(".$sstart."-".$next.")��ҳ��&nbsp;&nbsp;&nbsp;&nbsp;��<font color=red><b>$maketotal</b></font> ��";
        echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=$jumpurl'>";
    } else {
        echo "<b>�����ɹ���������<font color=red>".$maketotal."</font>��ҳ��! �ܷ�ʱ<font color=red>".$d."</font>�� <a href=./article.php?action=make>[����]</a></b>";
    }

}
//��������ҳ����
//���ɷ���ҳ
if($_GET['action'] == "sort"){
    !$step && $step=0;
    $goon=0;
    if (!empty($_GET['sortid'])){
        $maketype = "WHERE sortid='$sortid'";
    }
    else
    {
        $maketype = "order by sortid LIMIT $step,1";
        $makesorts="SELECT * FROM ".$db_prefix."sort";
        $maketotal=$DB->num_rows($DB->query($makesorts));
    }
    $makefile = $DB->query("SELECT * FROM ".$db_prefix."sort $maketype");
    while ($row=$DB->fetch_array($makefile)) 
    {
        $sid=$row['sortid'];
	$query = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid=$sid");
	$sort = $DB->fetch_array($query);
        if ($sort['parentid']=='0'){
          $type = "WHERE pid = '$sid' or sortid = '$sid' and visible='1'";
        } else {
          $type = "WHERE sortid = '$sid' and visible='1'";
        }
        $pagequery="SELECT * FROM ".$db_prefix."article $type";
        $total=$DB->num_rows($DB->query($pagequery));
        $show  =  $configuration[articlenum];  
        $totalPage  =  ceil($total/$show);
        if ($totalPage=="0") $totalPage="1";
	    if(!$row['sortid'])continue;
	    $goon=1;
     echo "<font color=red><b>�����������·����Htmlҳ,��ȴ�......</b></font><br><br><span id=showImport></span>";
     echo "<IE:Download ID=\"oDownload\" STYLE=\"behavior:url(#default#download)\">";
        for ($i = 1;$i<=$totalPage; $i++){
echo "<script>function onDownloadDone(downDate){showImport.innerHTML=downDate}oDownload.startDownload('makesort.php?total=".$total."&sortid=".$row['sortid']."&sortdir=".$row['sortdir']."&i=".$i."',onDownloadDone)</script>";
        }
    }
    cpheader();
    if (!empty($_GET['sortid']))
    {
        echo "<b>���ɸ÷���ҳ�ɹ�!��<font color=red>".($i-1)."</font>��ҳ��! �ܷ�ʱ<font color=red>".debuginfo()."</font>�� <a href=./article.php?action=make>[����]</a></b>";
        exit;
    }
    $step++;
    $page=$page+$i-1;
    if($goon){
        $db=debuginfo();
        $ds=$db+$d;
        $jumpurl="make.php?action=sort&sortid=$sortid&step=$step&page=$page&d=$ds";
        echo "(".($i-1)."ҳ)<b><font color=red>".$step."</font>/<font color=red>".$maketotal."</font><b>";
        echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=$jumpurl'>";
    } else {
        echo "<b>�����ɹ��������ɷ���<font color=red>".$maketotal."</font>����ҳ��<font color=red>".($page+1)."</font>�� <a href=./article.php?action=make>[����]</a></b>";
    }
}
//������ҳ
if($_GET['action'] == "index"){
    $MK->makeindex();
    redirect("����index.html�ɹ�", "./article.php?action=make");
}
?>
