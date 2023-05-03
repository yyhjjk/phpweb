<?php
// ========================== �ļ�˵�� ==========================// 
// ���ļ�˵������̨�ĳ��ú��� 
// =============================================================// 

// ####################### ��������ҳ��ҳü #######################
function cpheader($extraheader=""){
	global $configuration;
	echo "<html>\n";
	echo "<head>\n";
	echo "<title> $configuration[title]</title>\n";
	echo "<meta content=\"text/html; charset=gb2312\" http-equiv=\"Content-Type\">\n";
	echo "<link rel=\"stylesheet\" href=\"./cp.css\" type=\"text/css\">\n";
	echo "".$extraheader."\n";
	echo "</head>\n";
	echo "<body leftmargin=\"20\" topmargin=\"20\" marginwidth=\"20\" marginheight=\"20\"  style=\"table-layout:fixed; word-break:break-all\">\n";
}
function ShowMsg($msg,$gotoPage)
{
	$msg = str_replace("'","`",trim($msg));
	$gotoPage = str_replace("'","`",trim($gotoPage));
	echo "<script language='javascript'>\n";
	echo "alert('$msg');";
	if($gotoPage=="back")
	{
		echo "history.go(-1);\r\n";
	}
	else if(ereg("^-",$gotoPage))
	{
		echo "history.go($gotoPage);\r\n";
	}
	else if($gotoPage!="")
	{
		echo "location.href='$gotoPage';\r\n";
	}
	echo "</script>";
}
// ####################### �����ɹ���ʾҳ�� #######################
function redirect($msg,$url){
	cpheader();
	echo "$msg <a href=$url>[����]</a>\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">\n";
	echo "</body>\n</html>";
	exit;
}

// ####################### ��������ҳ��ҳ�� #######################
function cpfooter(){
	global $configuration;
	echo "\n<br>\n<center>Powered by: <a href=\"mailto:wupei@china.com.cn\" target=\"_blank\">С��������</a>  ".$configuration[version]."</center><br>\n";
	echo "</body>\n</html>";

}

// ####################### �����м�ı���ɫ�滻 #######################
function getrowbg() {
	global $bgcounter;
	if ($bgcounter++%2==0) {
		return "firstalt";
	} else {
		return "secondalt";
	}
}

// ####################### ������ʾ��Ϣ #######################
function sa_exit($msg, $url) {
	cpheader();
    echo "<p>$msg</p>";
	echo "<p><a href=\"".$url."\">������ﷵ��...</a></p>";
    echo "</body>\n</html>";
    exit;
}

// ####################### ��ȡ�ͻ���IP #######################
function getip() {
	if (isset($_SERVER)) {
		if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
			$realip = $_SERVER[HTTP_X_FORWARDED_FOR];
		} elseif (isset($_SERVER[HTTP_CLIENT_IP])) {
			$realip = $_SERVER[HTTP_CLIENT_IP];
		} else {
			$realip = $_SERVER[REMOTE_ADDR];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

// ��������
function makenav($ctitle="",$nav=array()) {
	echo "<tr class=\"tblhead\">\n";
	echo "  <td class=\"space\"><span class='tblhead'><b>$ctitle</b></span></td>\n";
	echo "</tr>\n";
    foreach ($nav AS $title=>$link)	{
		echo "<tr>\n";
		echo "  <td style=\"PADDING-LEFT: 10px;\"><a href=\"$link\" target=\"mainFrame\">$title</a></td>\n";
		echo "</tr>\n";
	}
}

// ####################### �û���¼ #######################
function checkuser($username,$password){
	global $DB,$db_prefix,$userinfo;
	$username = htmlspecialchars(trim($username));
	$username = trim($username);
	$userinfo = $DB->fetch_one_array("SELECT * FROM ".$db_prefix."user WHERE username='".addslashes($username)."' AND password='".addslashes($password)."'");
	if (empty($userinfo)) {
		return false;
	} else {
		return true;
	}
}

// ####################### ��֤�û��Ƿ��ڵ�½״̬ #######################
function islogin($username,$password){
	global $DB,$db_prefix;
	if ($username=="" or $password=="")
	{
		loginpage();
	}
	$result = $DB->query("SELECT password FROM ".$db_prefix."user WHERE username='$username'");
	$getpass = $DB->fetch_array($result);
	if ($getpass[password] != $password)
	{
		loginpage();
	}
}

// ####################### ��ȡ���ݿ��С��λ #######################
function get_real_size($size) {
	$kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte

    if($size < $kb) {
		return $size." B";
	}else if($size < $mb) {
		return round($size/$kb,2)." KB";
	}else if($size < $gb) {
		return round($size/$mb,2)." MB";
	}else if($size < $tb) {
		return round($size/$gb,2)." GB";
	}else {
		return round($size/$tb,2)." TB";
	}
}

// ####################### ��̨�ɹ���¼��¼ #######################
function loginsucceed($username="",$password="") {
	global $DB,$db_prefix;
	$extra .= "\nScript: ".getenv("REQUEST_URI");
	$DB->query("INSERT INTO ".$db_prefix."loginlog (username,password,date,ipaddress,result) VALUES
	('".$username."','������ȷ','".time()."','".getip()."','1')");
}

// ####################### ��̨ʧ�ܵ�¼��¼ #######################
function loginfaile($username="",$password="") {
	global $DB,$db_prefix;
	$extra .= "\nScript: ".getenv("REQUEST_URI");
	$DB->query("INSERT INTO ".$db_prefix."loginlog (username,password,date,ipaddress,result) VALUES
	('".$username."','�������','".time()."','".getip()."','2')");
}

// ####################### ��̨������¼ #######################
function getlog() {
	global $DB,$db_prefix;
	if (isset($_POST[action])) {
		$action = $_POST[action];
	} elseif (isset($_GET[action])) {
		$action = $_GET[action];
	}
	if (isset($action)) {
		$script = "".getenv("REQUEST_URI");
		$DB->query("INSERT INTO ".$db_prefix."adminlog (action,script,date,ipaddress) VALUES ('".htmlspecialchars(trim($action))."','".htmlspecialchars(trim($script))."','".time()."','".getip()."')");
	}
}

// ####################### ���������Ƿ�����߼� #######################
function checksortLen($sortname)
{
	if(empty($sortname))
	{
		$result="����������Ϊ��<br>";
		return $result;
	}
	if(strlen($sortname) > 16)
	{
		$result="���������ܳ���16���ַ�<br>";
		return $result;
	}
}
// ####################### �������ļ��Ƿ�Ϊ�� #######################
function checksortdir($sortdir)
{
	if(empty($sortdir))
	{
		$result="�����ļ��в���Ϊ��<br>";
		return $result;
	}
}

// ####################### �������Ƿ���ѡ�� #######################
function choosesort($sortid)
{
	if(trim($sortid) == "")
	{
		$result="�㻹û��ѡ�����<br>";
		return $result;
	}
}

// ####################### �������Ƿ�Ϸ� #######################
function checksubject($title)
{
	if(trim($title) == "")
	{
		$result="���ⲻ��Ϊ��<br>";
		return $result;
	}
	if(strlen($title) > 120)
	{
		$result="���ⲻ�ܳ���120���ַ�<br>";
		return $result;
	}
}

// ####################### ������ߺϷ��� #######################
function checkauthor($author)
{
	if(!empty($author))
	{
		if(strlen($author)>20)
		{
			$result.="�������ֲ��ܳ���20���ֽڣ�";
			return $result;
		}
	}
}

// ####################### ������³����Ϸ��� #######################
function checksource($source)
{
	if(!empty($source))
	{
		if(strlen($source)>100)
		{
			$result.="���³������ܳ���100���ֽڣ�";
			return $result;
		}
	}
}

// ####################### ���EMAIL��ַ�Ϸ��� #######################
function checkemail($email)
{
    if(!trim($email)=="")
	{
		if(strlen($email)>100)
		{
			$result.="Email ��ַ����<br>";
			return $result;
		}
		if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
		{ 
			$result.="Email ��ʽ����ȷ<br>";
			return $result;
		}
	}
}

// ####################### ����ύ���ݺϷ��� #######################
function checkcontent($content)
{
	if(trim($content)=="")
	{
		$result.="���ݲ���Ϊ��<br>";
		return $result;
	}
	if(strlen($content)<4)
	{
		$result.="���ݲ�������4���ַ�<br>";
		return $result;
	}
}

// ####################### ��ҳ���� #######################
function multi($num, $perpage, $curr_page, $mpurl, $type) {
	$multipage = '';
	if($num > $perpage) {
		$page = 5;
		$offset = 2;

		$pages = ceil($num / $perpage);
		$from = $curr_page - $offset;
		$to = $curr_page + $page - $offset - 1;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			if($from < 1) {
				$to = $curr_page + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curr_page - $pages + $to;
				$to = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
		}
                if ($type == html) {
		   $multipage .= "<a href=\"".$mpurl.".html\"><Font face=webdings>9</font></a>  ";
                } else {
		   $multipage .= "<a href=\"$mpurl&page=1\"><Font face=webdings>9</font></a>  ";
                }
		for($i = $from; $i <= $to; $i++) {
			if($i != $curr_page) {
                                if ($type == html) {
				$multipage .=($i==1)? "<a href=\"".$mpurl.".html\">$i</a> ":"<a href=\"".$mpurl."-$i.html\">$i</a> ";
			        } else {
				$multipage .= "<a href=\"$mpurl&page=$i\">$i</a> ";
			        }
			} else {
				$multipage .= '<u><b>'.$i.'</b></u> ';
			}
		}
                if ($type == html) {
		$multipage .= $pages > $page ? " ... <a href=\"".$mpurl."-$pages.html\"> $pages <Font face=webdings>:</font></a>" : " <a href=\"".$mpurl."-$pages.html\"><Font face=webdings>:</font></a>";
                } else {
		$multipage .= $pages > $page ? " ... <a href=\"$mpurl&page=$pages\"> $pages <Font face=webdings>:</font></a>" : " <a href=\"$mpurl&page=$pages\"><Font face=webdings>:</font></a>";
                }

	}
	return $multipage;
}
// #######################��ҳ����2 #######################
function showpages($num, $perpage, $page, $mpurl)
{
    $pages = ceil($num / $perpage);
    $first="��ҳ";
    $prev="��һҳ";
    if($page > 1){
        $first="<a href='".$mpurl.".html'>��ҳ</a>";
        $prev= $page == '2'? "<a href='".$mpurl.".html'>��һҳ</a>":"<a href=\"".$mpurl."-".($page-1).".html\">��һҳ</a>";
    }
    $next="��һҳ";
    $last="βҳ";
    if($page < $pages){
        $next="<a href=\"".$mpurl."-".($page+1).".html\">��һҳ</a>";
        $last="<a href=\"".$mpurl."-".$pages.".html\">βҳ</a>";
    }
    $showPages="<select size=1 
 onchange=\"javascript:window.location.href=''+this.options[this.selectedIndex].value+'.html'\">";		        for($i=1;$i<=$pages;$i++){
       $value = $i==1 ? $mpurl:$mpurl."-".$i; 
       $i == $page ? $showPages.="<option value=".$value." selected>��".$i."ҳ</option>" : $showPages .= "<option value=".$value.">��".$i."ҳ</option>";
   }
   $showPages.="</select>";
   $showPages=$first."&nbsp;".$prev."&nbsp;".$next."&nbsp;".$last."&nbsp;&nbsp;ת��:".$showPages."";
   return $showPages;
}
// ####################### �Զ�ʶ��URL #######################
function parseurl($content) {
	return preg_replace(	array(
					"/(?<=[^\]A-Za-z0-9-=\"'\\/])(https?|ftp|gopher|news|telnet|mms){1}:\/\/([A-Za-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/is",
					"/([\n\s])www\.([a-z0-9\-]+)\.([A-Za-z0-9\/\-_+=.~!%@?#%&;:$\[\]\\()|]+)((?:[^\x7f-\xff,\s]*)?)/is",
					"/(?<=[^\]A-Za-z0-9\/\-_.~?=:.])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si"
				), array(
					"[url]\\1://\\2[/url]",
					"\\1[url]www.\\2.\\3\\4[/url]",
					"[email]\\0[/email]"
				), ' '.$content);
}
// ####################### UBB��ǩת�� #######################
function ubb2html($content)
{

		$content = parseurl($content);

	//�Զ�ʶ�����

	$content = eregi_replace(quotemeta("[b]"),quotemeta("<b>"),$content);
	$content = eregi_replace(quotemeta("[/b]"),quotemeta("</b>"),$content);
	$content = eregi_replace(quotemeta("[i]"),quotemeta("<i>"),$content);
	$content = eregi_replace(quotemeta("[/i]"),quotemeta("</i>"),$content);
	$content = eregi_replace(quotemeta("[u]"),quotemeta("<u>"),$content);
	$content = eregi_replace(quotemeta("[/u]"),quotemeta("</u>"),$content);
	$content = eregi_replace(quotemeta("[center]"),quotemeta("<center>"),$content);
	$content = eregi_replace(quotemeta("[/center]"),quotemeta("</center>"),$content);

	$content = eregi_replace(quotemeta("[quote]"),quotemeta("<table width=\"96%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" style=word-break:break-all align=\"center\"><tr><td><b>����:</b></td></tr><tr><td><hr width=\"100%\" noshade></td></tr><tr><td class=\"content\"><font color=\"#0000FF\">"),$content);
	$content = eregi_replace(quotemeta("[/quote]"),quotemeta("</font></td></tr><tr><td><hr width=\"100%\" noshade></td></tr></table>"),$content);

	$content = eregi_replace(quotemeta("[code]"),quotemeta("<table width=\"96%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" style=word-break:break-all align=\"center\"><tr><td><b>����:</b></td></tr><tr><td><hr width=\"100%\" noshade></td></tr><tr><td class=\"code\"><font color=\"#0000FF\">"),$content);
	$content = eregi_replace(quotemeta("[/code]"),quotemeta("</font></td></tr><tr><td><hr width=\"100%\" noshade></td></tr></table>"),$content);

	$content = eregi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<a href=\"\\1\" target=\"_blank\"><img src=\"\\1\" border=0 onload=\"javascript:if(this.width>screen.width-333)this.width=screen.width-333\" title=\"���´������ԭʼͼƬ\" align=\"left\"></a>",$content);
		 
	$content = eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>www.\\1</a>",$content);
	$content = eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$content);
	$content = eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\2</a>",$content);
	$content = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$content);
	
	//$content = preg_replace( '/javascript/i', 'java script', $content);
	return $content;
} 

// ####################### ���HTML���� #######################
function html_clean($content){
	//$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);
	$content = str_replace("  ", "&nbsp;&nbsp;", $content);
	$content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
	return $content;
}
// ####################### ����ɾ���ļ����� #######################
function delete_file($file){
         $delete = @unlink($file);
         clearstatcache();
         if(@file_exists($file)){
              $filesys = eregi_replace("/","\\",$file);
              $delete = @system("del $filesys");
              clearstatcache();
              if(@file_exists($file)){
                   $delete = @chmod ($file, 0777);
                   $delete = @unlink($file);
                   $delete = @system("del $filesys");
              }
         }
         clearstatcache();
         if(@file_exists($file)){
              return false;
         }else{
              return true;
         }
}
// #######################����ɾ��Ŀ¼���� #######################
function removeDir($dirName)
{
    $result = false;
    if(! is_dir($dirName))
    {
        trigger_error("��Ŀ¼������", E_USER_ERROR);
    }
    $handle = opendir($dirName);
    while(($file = readdir($handle)) !== false)
    {
        if($file != '.' && $file != '..')
        {
            $dir = $dirName . DIRECTORY_SEPARATOR . $file;
            is_dir($dir) ? removeDir($dir) : unlink($dir);
        }
    }
    closedir($handle);
    $result = rmdir($dirName) ? true : false;
    return $result;
}
// #######################�����ַ� #######################
function cn_substr($str,$len)
{
    $r_str="";
    $i=0;
    while($i<$len)
    {
        $ch=substr($str,$i,1);
 	if(ord($ch)>0x80) $i++;
 	$i++;
    }
    $r_str=substr($str,0,$i);
    return $r_str;
}
?>