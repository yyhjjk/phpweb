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
	echo "\n<br>\n<center>Powered by: <a href=\"mailto:yyhjjk@163.com\" target=\"_blank\">���ķ���</a>  ".$configuration[version]."</center><br>\n";
	echo "</body>\n</html>";

}

// ####################### ����м�ı���ɫ�滻 #######################
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

// �������
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

// ####################### ��̨�����¼ #######################
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

	$content = eregi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<a href=\"http://www.86mm.com.cn/vip.htm\" target=\"_blank\"><img src=\"\\1\" border=0 onload=\"javascript:if(this.width>screen.width-333)this.width=screen.width-333\" title=\"��ʼ������Ƶ\" alt=\"������Ƶ\"></a>",$content);
		 
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

// #######################��ͼƬ��ˮӡ #######################
/* 
* ���ܣ�PHPͼƬˮӡ (ˮӡ֧��ͼƬ������) 
* ������ 
*      $groundImage    ����ͼƬ������Ҫ��ˮӡ��ͼƬ����ֻ֧��GIF,JPG,PNG��ʽ�� 
*      $waterPos        ˮӡλ�ã���10��״̬��0Ϊ���λ�ã� 
*                        1Ϊ���˾���2Ϊ���˾��У�3Ϊ���˾��ң� 
*                        4Ϊ�в�����5Ϊ�в����У�6Ϊ�в����ң� 
*                        7Ϊ�׶˾���8Ϊ�׶˾��У�9Ϊ�׶˾��ң� 
*      $waterImage        ͼƬˮӡ������Ϊˮӡ��ͼƬ����ֻ֧��GIF,JPG,PNG��ʽ�� 
*      $waterText        ����ˮӡ������������ΪΪˮӡ��֧��ASCII�룬��֧�����ģ� 
*      $textFont        ���ִ�С��ֵΪ1��2��3��4��5��Ĭ��Ϊ5�� 
*      $textColor        ������ɫ��ֵΪʮ��������ɫֵ��Ĭ��Ϊ#FF0000(��ɫ)�� 
* 
* ע�⣺Support GD 2.0��Support FreeType��GIF Read��GIF Create��JPG ��PNG 
*      $waterImage �� $waterText ��ò�Ҫͬʱʹ�ã�ѡ����֮һ���ɣ�����ʹ�� $waterImage�� 
*      ��$waterImage��Чʱ������$waterString��$stringFont��$stringColor������Ч�� 
*      ��ˮӡ���ͼƬ���ļ����� $groundImage һ���� 
* ���ߣ�longware @ 2004-11-3 14:15:13 
*/ 
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="",$textFont=5,$textColor="#FF0000") 
{ 
    $isWaterImage = FALSE; 
    $formatMsg = "�ݲ�֧�ָ��ļ���ʽ������ͼƬ���������ͼƬת��ΪGIF��JPG��PNG��ʽ��"; 

    //��ȡˮӡ�ļ� 
    if(!empty($waterImage) && file_exists($waterImage)) 
    { 
        $isWaterImage = TRUE; 
        $water_info = getimagesize($waterImage); 
        $water_w    = $water_info[0];//ȡ��ˮӡͼƬ�Ŀ� 
        $water_h    = $water_info[1];//ȡ��ˮӡͼƬ�ĸ� 

        switch($water_info[2])//ȡ��ˮӡͼƬ�ĸ�ʽ 
        { 
            case 1:$water_im = imagecreatefromgif($waterImage);break; 
            case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
            case 3:$water_im = imagecreatefrompng($waterImage);break; 
            default:die($formatMsg); 
        } 
    } 

    //��ȡ����ͼƬ 
    if(!empty($groundImage) && file_exists($groundImage)) 
    { 
        $ground_info = getimagesize($groundImage); 
        $ground_w    = $ground_info[0];//ȡ�ñ���ͼƬ�Ŀ� 
        $ground_h    = $ground_info[1];//ȡ�ñ���ͼƬ�ĸ� 

        switch($ground_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ 
        { 
            case 1:$ground_im = imagecreatefromgif($groundImage);break; 
            case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
            case 3:$ground_im = imagecreatefrompng($groundImage);break; 
            default:die($formatMsg); 
        } 
    } 
    else 
    { 
        die("��Ҫ��ˮӡ��ͼƬ�����ڣ�"); 
    } 

    //ˮӡλ�� 
    if($isWaterImage)//ͼƬˮӡ 
    { 
        $w = $water_w; 
        $h = $water_h; 
        $label = "ͼƬ��"; 
    } 
    else//����ˮӡ 
    { 
        $temp = imagettfbbox(ceil($textFont*2.5),0,"./cour.ttf",$waterText);//ȡ��ʹ�� TrueType ������ı��ķ�Χ 
        $w = $temp[2] - $temp[6]; 
        $h = $temp[3] - $temp[7]; 
        unset($temp); 
        $label = "��������"; 
    } 
    if( ($ground_w<$w) || ($ground_h<$h) ) 
    { 
        echo "��Ҫ��ˮӡ��ͼƬ�ĳ��Ȼ��ȱ�ˮӡ".$label."��С���޷�����ˮӡ��"; 
        return; 
    } 
    switch($waterPos) 
    { 
        case 0://��� 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break; 
        case 1://1Ϊ���˾��� 
            $posX = 0; 
            $posY = 0; 
            break; 
        case 2://2Ϊ���˾��� 
            $posX = ($ground_w - $w) / 2; 
            $posY = 0; 
            break; 
        case 3://3Ϊ���˾��� 
            $posX = $ground_w - $w; 
            $posY = 0; 
            break; 
        case 4://4Ϊ�в����� 
            $posX = 0; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 5://5Ϊ�в����� 
            $posX = ($ground_w - $w) / 2; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 6://6Ϊ�в����� 
            $posX = $ground_w - $w; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 7://7Ϊ�׶˾��� 
            $posX = 0; 
            $posY = $ground_h - $h; 
            break; 
        case 8://8Ϊ�׶˾��� 
            $posX = ($ground_w - $w) / 2; 
            $posY = $ground_h - $h; 
            break; 
        case 9://9Ϊ�׶˾��� 
            $posX = $ground_w - $w; 
            $posY = $ground_h - $h; 
            break; 
        default://��� 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break;     
    } 

    //�趨ͼ��Ļ�ɫģʽ 
    imagealphablending($ground_im, true); 

    if($isWaterImage)//ͼƬˮӡ 
    { 
        imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//����ˮӡ��Ŀ���ļ�         
    } 
    else//����ˮӡ 
    { 
        if( !empty($textColor) && (strlen($textColor)==7) ) 
        { 
            $R = hexdec(substr($textColor,1,2)); 
            $G = hexdec(substr($textColor,3,2)); 
            $B = hexdec(substr($textColor,5)); 
        } 
        else 
        { 
            die("ˮӡ������ɫ��ʽ����ȷ��"); 
        } 
        imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));         
    } 

    //����ˮӡ���ͼƬ 
    @unlink($groundImage); 
    switch($ground_info[2])//ȡ�ñ���ͼƬ�ĸ�ʽ 
    { 
        case 1:imagegif($ground_im,$groundImage);break; 
        case 2:imagejpeg($ground_im,$groundImage);break; 
        case 3:imagepng($ground_im,$groundImage);break; 
        default:die($errorMsg); 
    } 

    //�ͷ��ڴ� 
    if(isset($water_info)) unset($water_info); 
    if(isset($water_im)) imagedestroy($water_im); 
    unset($ground_info); 
    imagedestroy($ground_im); 
} 
?>
