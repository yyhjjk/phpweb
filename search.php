<?php
error_reporting(7);
// ����ǰ̨���ú���
require "functions.php";

$t->set_file(array("list_article"=>"list_article.html",
				   "list_sort"=>"list_sort.html",
		           "Search"=>"search.html"));

$t->set_block("list_sort","RowSort1","RowsSort1");
$t->set_block("list_article","RowArticle","RowsArticle");
$t->set_block("list_article","RowBr","RowsArticle");

// ��ѯ�����б�
$sorts = $DB->query("SELECT * FROM ".$db_prefix."sort where parentid='0' order by displayorder");
while ($sort=$DB->fetch_array($sorts))
{ 
	$t->set_var(array("sort_url"=>$url,
			          "sort_name"=>htmlspecialchars($sort['sortname']),
				      ));
	$t->parse("RowsSort1","RowSort1",true);
}
$t->parse("putsort","RowsSort1");

$keywords = $_GET['keywords'];
if (empty($keywords))
{
	$t->set_var("errorinfo","<b>������ؼ���������</b>");
	$t->parse("RowsArticle","RowArticle",true);
	$t->set_var(array("total"=>"0",
					  "pagetotals"=>intval($configuration[searchnum]),
					  "multipage"=>$multipage));
} else {
	$page = intval($_GET['page']);
	if(!empty($page)) {
		$start_limit = ($page - 1) * $configuration[searchnum];
	} else {
		$start_limit = 0;
		$page = 1;
	}
	// ��ȡ�ؼ��ֲ����й��˼�ת��
	$keywords = addslashes($keywords);
	$keywords = str_replace("_","\_",$keywords);
	$keywords = str_replace("%","\%",$keywords);
	// ���˽���

	// ������Χ����
	if($_GET['area'] == "title"){
		$search = "AND title LIKE '%$keywords%'";
	}elseif($_GET['area'] == "author"){
		$search = "AND author LIKE '%$keywords%'";
	}elseif($_GET['area'] == "content"){
		$search = "AND content LIKE '%$keywords%'";
	}elseif($_GET['area'] == "all"){
		$search = "AND title LIKE '%$keywords%' OR author LIKE '%keywords%' OR content LIKE '%$keywords%'";
	}else{
		$search = "AND title LIKE '%$keywords%' OR author LIKE '%keywords%' OR content LIKE '%$keywords%'";
	}

	// ִ������
	$query="SELECT * FROM ".$db_prefix."article WHERE visible='1' $search";
	$total=$DB->num_rows($DB->query($query));

	$t->set_var(array("total"=>intval($total),
					  "pagetotals"=>intval($configuration[searchnum]),
					  "multipage"=>$multipage));

	$multipage = multi($total, $configuration[searchnum], $page, "search.php?keywords=$keywords&area=$area");
	$sql = "SELECT * FROM ".$db_prefix."article WHERE visible='1' $search ORDER BY articleid DESC LIMIT $start_limit, $configuration[searchnum]";
	$result = $DB->query($sql);

	// ���û�м�¼
	if ($total <=0){
                $errorinfo="<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr><td align=\"center\">�Բ���ʲôҲû�ҵ�.</td></tr></table>";

	$t->set_var("errorinfo",$errorinfo);
	$t->parse("RowsArticle","RowArticle",true);
	} else {
		$i="0";
		while ($article=$DB->fetch_array($result))
		{
			//��ѯ�������ڷ���
			$sorts = $DB->query("SELECT * FROM ".$db_prefix."sort WHERE sortid=$article[sortid]");
			$sortname = $DB->fetch_array($sorts);

			if (!empty($article[author]))
			{
				$article['author'] = htmlspecialchars($article['author']);
			} else {
				$article['author'] = "δ֪";
			}
                       $article_title=str_replace($keywords,"<font color=red><b>".$keywords."</b></font>",htmlspecialchars($article['title']));
                       $article_url=$configuration[htmldir]."/".$sortname['sortdir']."/".$article['articleid'].".html";
			$t->set_var(array("i"=>$i = $i+"1",
					          "article_id"=>intval($article['articleid']),
					          "article_title"=>$article_title,
					          "article_url"=>$article_url,
						  "article_author"=>$article['author'],
					          "article_sorturl"=>$configuration[htmldir]."/".$sortname['sortdir'],
						  "article_sortname"=>htmlspecialchars($sortname['sortname']),
						  "article_addtime"=>date("Y-m-d",$article['addtime']),
						  "article_hits"=>intval($article['hits']),
						   ));
			$t->parse("RowsArticle","RowArticle",true);
                        if($i%$configuration[colnum]==0) {
	                    $t->parse("RowsArticle","RowBr",true);
                        }
		} //while
		$t->parse("putsearch","RowsArticle");
	}
}
$t->set_var(array("puttitle"=>$configuration[title],
				  "puturl"=>$configuration[url],
	    	                    "template"=>$configuration['template'],
                                     "multipage"=>$multipage));
$t->parse("OUT","Search");
$t->p("OUT");
?>
