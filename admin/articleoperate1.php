<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵�������¹���
// =============================================================// 
error_reporting(7);
// ���غ�̨��������
require_once ("globall.php");
require_once ("class/make_inc.php");
//������²���
if($_POST['action'] == "addarticle")
{
   //��֤�߼���
    $result=checksubject($title);
    $result.=checkauthor($author);
    $result.=choosesort($sortid);
    $result.=checksource($source);
    $result.=checkcontent($content);
    if($result)
    {
	sa_exit($result, "javascript:history.go(-1);");
    }
    $query="SELECT * FROM ".$db_prefix."article WHERE title='".addslashes($_POST['title'])."' and author='".addslashes($_POST['author'])."' and content='".addslashes($_POST['content'])."'";
    $result=$DB->num_rows($DB->query($query));
    if($result > 0)
    {
        sa_exit("�������Ѵ���!", "./article.php?action=add&sortid=$sortid");
    }
    //��֤�߼��Խ���

    $sort = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."sort WHERE sortid='".intval($_POST['sortid'])."'");
    $query="INSERT INTO ".$db_prefix."article (sortid, pid, title, author, source, addtime, ishtml, hits, content,  iscommend,isparseurl, visible) VALUES ('".intval($_POST['sortid'])."', '".$sort['parentid']."', '".addslashes($_POST['title'])."', '".addslashes($_POST['author'])."', '".addslashes($_POST['source'])."', '".time()."', '".intval($_POST['ishtml'])."', '".intval($_POST['hits'])."', '".addslashes($_POST['content'])."', '".intval($_POST['iscommend'])."', '".intval($_POST['isparseurl'])."', '".intval($_POST['visible'])."')";
    $DB->query($query) or die("�������ʧ��!");
    if($_POST['ismake'] == "1"){
         $makefile = $DB->query("SELECT * FROM ".$db_prefix."article order by articleid desc");
         $row = $DB->fetch_array($makefile);
         $MK->makearticle($row['articleid']);
    }
    redirect("������³ɹ�!", "javascript:history.go(-1);");
}

//�޸����²���
elseif($_POST['action'] == "modarticle")
{
    //��֤�߼���
    $result=checksubject($title);
    $result.=checkauthor($author);
    $result.=choosesort($sortid);
    $result.=checksource($source);
    $result.=checkcontent($content);
    if($result)
    {
	sa_exit($result, "javascript:history.go(-1);");
    }
    //��֤�߼��Խ���
    $sort = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."sort WHERE sortid='".intval($_POST['sortid'])."'");
    $query="UPDATE ".$db_prefix."article SET sortid='".intval($_POST['sortid'])."', pid='".$sort[parentid]."', title='".addslashes($_POST['title'])."', author='".addslashes($_POST['author'])."', source='".addslashes($_POST['source'])."', ishtml='".intval($_POST['ishtml'])."', content='".addslashes($_POST['content'])."', iscommend='".intval($_POST['iscommend'])."',isparseurl='".intval($_POST['isparseurl'])."', visible='".intval($_POST['visible'])."' WHERE articleid='".intval($articleid)."'";
    $DB->query($query) or die("�޸�����ʧ��!");
    if($_POST['ismake'] == "1"){
        if($_POST['oldsortid'] != $_POST['sortid']){
            $delfile="../".$configuration[htmldir]."/".$sort[sortdir]."/".intval($articleid).".html";
            @delete_file($delfile);
        }
        $MK->makearticle(intval($articleid));
    }
    redirect("�޸����³ɹ�!", "./article.php?action=edit");
}
//��ʾ���²���
elseif($_GET['action'] == "show")
{
        $DB->query("UPDATE ".$db_prefix."article SET visible='1' WHERE articleid='".intval($articleid)."'");
        redirect("��ʾ���³ɹ�!", "./article.php?action=edit");
}
//�������²���
elseif($_GET['action'] == "hide")
{
        $DB->query("UPDATE ".$db_prefix."article SET visible='0' WHERE articleid='".intval($articleid)."'");
        redirect("�������³ɹ�!", "./article.php?action=edit");
}
//ɾ�����²���
elseif($_POST['action'] == "delarticle")
{
    $articlequery = $DB->query("SELECT * FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'");
    $article = $DB->fetch_array($articlequery);
    $sortid = $article['sortid'];
    $query="DELETE FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'";
    $DB->query($query) or die("ɾ������ʧ��!");
    $sortquery = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid='".$sortid."'");
    $sort = $DB->fetch_array($sortquery);
    $delfile="../".$configuration[htmldir]."/".$sort['sortdir']."/".$articleid.".html";
    if(@delete_file($delfile)){
        redirect("ɾ�����³ɹ�!<br>ɾ��".$delfile."�ɹ�", "./article.php?action=edit");
    }else{
        redirect("ɾ�����³ɹ�!<br>ɾ��".$delfile."ʧ��", "./article.php?action=edit");
    }
}
//������ʾFlash����
elseif($_POST['action'] == "moreshow")
{
	//��֤�߼���
    if (empty($_POST['article']) OR !is_array($_POST['article'])) {
        sa_exit("δѡ���κ�����!", "javascript:history.go(-1);");
    }
	//��֤�߼��Խ���
    foreach ($article AS $articleid=>$value) {
             $DB->query("UPDATE ".$db_prefix."article SET visible='1' WHERE articleid='".intval($articleid)."'");
    }
    redirect("������ʾ���³ɹ�!", "./article.php?action=edit");
}
//��������Flash����
elseif($_POST['action'] == "morehide")
{
	//��֤�߼���
    if (empty($_POST['article']) OR !is_array($_POST['article'])) {
        sa_exit("δѡ���κ�����!", "javascript:history.go(-1);");
    }
	//��֤�߼��Խ���
    foreach ($article AS $articleid=>$value) {
             $DB->query("UPDATE ".$db_prefix."article SET visible='0' WHERE articleid='".intval($articleid)."'");
    }
    redirect("�����������³ɹ�!", "./article.php?action=edit");
}
//��������Flash����
elseif($_POST['action'] == "moremake")
{
	//��֤�߼���
    if (empty($_POST['article']) OR !is_array($_POST['article'])) {
        sa_exit("δѡ���κ�����!", "javascript:history.go(-1);");
    }
	//��֤�߼��Խ���
    foreach ($article AS $articleid=>$value) {
            $MK->makearticle(intval($articleid));
    }
    redirect("������������htmlҳ�ɹ�!", "./article.php?action=edit");
}
//����ɾ�����²���
elseif($_POST['action'] == "moredel")
{
	//��֤�߼���
    if (empty($_POST['article']) OR !is_array($_POST['article'])) {
        sa_exit("δѡ���κ�����!", "javascript:history.go(-1);");
    }
	//��֤�߼��Խ���
    foreach ($article AS $articleid=>$value) {
	$articlequery = $DB->query("SELECT * FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'");
	$article = $DB->fetch_array($articlequery);
        $sortid = $article['sortid'];
	$sortquery = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid='".$sortid."'");
	$sort = $DB->fetch_array($sortquery);
        $DB->query("DELETE FROM ".$db_prefix."article WHERE articleid='".intval($articleid)."'");
        $delfile="../".$configuration[htmldir]."/".$sort['sortdir']."/".$articleid.".html";
        @delete_file($delfile);
    }
    redirect("����ɾ�����³ɹ�!", "./article.php?action=edit");
} else {
    sa_exit("��������,������!", "javascript:history.go(-1);");
}
?>
