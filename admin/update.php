<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵����������������
// =============================================================// 
error_reporting(7);
set_time_limit(0);
// ���غ�̨��������
require "global.php";
cpheader();
if($_GET['action'] == "update"){
    !$step && $step=1;
    !$percount && $percount=1000;
    $sstart=($step-1)*$percount;
    $next=$sstart+$percount;
    $step++;
    $goon=0;
    if (!empty($_GET['sortid'])){
      $istype = "WHERE sortid='".$sortid."'";
    }
    else
    {
      $istype = "";
    }
    $query="SELECT * FROM ".$db_prefix."article $istype";
    $total=$DB->num_rows($DB->query($query));
    $result = $DB->query("SELECT * FROM ".$db_prefix."article $sqltype order by articleid desc LIMIT $sstart,$percount");
    if($total < $next){
        $next=$total;
    }
    $i=0;
    while ($row=$DB->fetch_array($result)) 
    {
        $i++;
	if(!$row['articleid'])continue;
	$goon=1;
	$content = str_replace("<br>", "\n", $row['content']);
	$content = str_replace("[IMAGES]", "[IMG]", $content);
	$content = str_replace("[/IMAGES]", "[/IMG]", $content);
        $DB->query("UPDATE ".$db_prefix."article SET content='".addslashes($content)."' WHERE articleid='".$row['articleid']."'");
    }
    if($goon){
        $db=debuginfo();
        $ds=$db+$d;
        $jumpurl="update.php?action=update&sortid=$sortid&step=$step&percount=$percount&d=$ds";
        echo "<font color=red><b>���ڸ�������,��ȴ�......</b></font><br><br>";
        echo "���ڸ���(".$sstart."-".$next.")��&nbsp;&nbsp;&nbsp;&nbsp;��<font color=red><b>$total</b></font> ��";
        echo "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=$jumpurl'>";
    } else {
        echo "<b>�����ɹ���������<font color=red>".$maketotal."</font>������! �ܷ�ʱ<font color=red>".$d."</font>��</b>";
    }

} else {
    echo "��������!";
}

?>
