<?php
// ========================== 文件说明 ==========================// 
// 本文件说明：后台的常用函数 
// =============================================================// 

// ####################### 控制面版各页面页眉 #######################
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
// ####################### 操作成功提示页面 #######################
function redirect($msg,$url){
	cpheader();
	echo "$msg <a href=$url>[返回]</a>\n";
	echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">\n";
	echo "</body>\n</html>";
	exit;
}

// ####################### 控制面版各页面页脚 #######################
function cpfooter(){
	global $configuration;
	echo "\n<br>\n<center>Powered by: <a href=\"mailto:yyhjjk@163.com\" target=\"_blank\">天涯风云</a>  ".$configuration[version]."</center><br>\n";
	echo "</body>\n</html>";

}

// ####################### 表格行间的背景色替换 #######################
function getrowbg() {
	global $bgcounter;
	if ($bgcounter++%2==0) {
		return "firstalt";
	} else {
		return "secondalt";
	}
}

// ####################### 错误提示信息 #######################
function sa_exit($msg, $url) {
	cpheader();
    echo "<p>$msg</p>";
	echo "<p><a href=\"".$url."\">点击这里返回...</a></p>";
    echo "</body>\n</html>";
    exit;
}

// ####################### 获取客户端IP #######################
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

// 产生表格
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

// ####################### 用户登录 #######################
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

// ####################### 验证用户是否处于登陆状态 #######################
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

// ####################### 获取数据库大小单位 #######################
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

// ####################### 后台成功登录记录 #######################
function loginsucceed($username="",$password="") {
	global $DB,$db_prefix;
	$extra .= "\nScript: ".getenv("REQUEST_URI");
	$DB->query("INSERT INTO ".$db_prefix."loginlog (username,password,date,ipaddress,result) VALUES
	('".$username."','密码正确','".time()."','".getip()."','1')");
}

// ####################### 后台失败登录记录 #######################
function loginfaile($username="",$password="") {
	global $DB,$db_prefix;
	$extra .= "\nScript: ".getenv("REQUEST_URI");
	$DB->query("INSERT INTO ".$db_prefix."loginlog (username,password,date,ipaddress,result) VALUES
	('".$username."','密码错误','".time()."','".getip()."','2')");
}

// ####################### 后台管理记录 #######################
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

// ####################### 检查分类名是否符合逻辑 #######################
function checksortLen($sortname)
{
	if(empty($sortname))
	{
		$result="分类名不能为空<br>";
		return $result;
	}
	if(strlen($sortname) > 16)
	{
		$result="分类名不能超过16个字符<br>";
		return $result;
	}
}
// ####################### 检查分类文件是否为空 #######################
function checksortdir($sortdir)
{
	if(empty($sortdir))
	{
		$result="分类文件夹不能为空<br>";
		return $result;
	}
}

// ####################### 检查分类是否已选择 #######################
function choosesort($sortid)
{
	if(trim($sortid) == "")
	{
		$result="你还没有选择分类<br>";
		return $result;
	}
}

// ####################### 检查标题是否合法 #######################
function checksubject($title)
{
	if(trim($title) == "")
	{
		$result="标题不能为空<br>";
		return $result;
	}
	if(strlen($title) > 120)
	{
		$result="标题不能超过120个字符<br>";
		return $result;
	}
}

// ####################### 检查作者合法性 #######################
function checkauthor($author)
{
	if(!empty($author))
	{
		if(strlen($author)>20)
		{
			$result.="作者名字不能超过20个字节！";
			return $result;
		}
	}
}

// ####################### 检查文章出处合法性 #######################
function checksource($source)
{
	if(!empty($source))
	{
		if(strlen($source)>100)
		{
			$result.="文章出处不能超过100个字节！";
			return $result;
		}
	}
}

// ####################### 检查EMAIL地址合法性 #######################
function checkemail($email)
{
    if(!trim($email)=="")
	{
		if(strlen($email)>100)
		{
			$result.="Email 地址过长<br>";
			return $result;
		}
		if(!eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email))
		{ 
			$result.="Email 格式不正确<br>";
			return $result;
		}
	}
}

// ####################### 检查提交内容合法性 #######################
function checkcontent($content)
{
	if(trim($content)=="")
	{
		$result.="内容不能为空<br>";
		return $result;
	}
	if(strlen($content)<4)
	{
		$result.="内容不能少于4个字符<br>";
		return $result;
	}
}

// ####################### 分页函数 #######################
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
// #######################分页函数2 #######################
function showpages($num, $perpage, $page, $mpurl)
{
    $pages = ceil($num / $perpage);
    $first="首页";
    $prev="上一页";
    if($page > 1){
        $first="<a href='".$mpurl.".html'>首页</a>";
        $prev= $page == '2'? "<a href='".$mpurl.".html'>上一页</a>":"<a href=\"".$mpurl."-".($page-1).".html\">上一页</a>";
    }
    $next="下一页";
    $last="尾页";
    if($page < $pages){
        $next="<a href=\"".$mpurl."-".($page+1).".html\">下一页</a>";
        $last="<a href=\"".$mpurl."-".$pages.".html\">尾页</a>";
    }
    $showPages="<select size=1 
 onchange=\"javascript:window.location.href=''+this.options[this.selectedIndex].value+'.html'\">";		        for($i=1;$i<=$pages;$i++){
       $value = $i==1 ? $mpurl:$mpurl."-".$i; 
       $i == $page ? $showPages.="<option value=".$value." selected>第".$i."页</option>" : $showPages .= "<option value=".$value.">第".$i."页</option>";
   }
   $showPages.="</select>";
   $showPages=$first."&nbsp;".$prev."&nbsp;".$next."&nbsp;".$last."&nbsp;&nbsp;转到:".$showPages."";
   return $showPages;
}
// ####################### 自动识别URL #######################
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
// ####################### UBB标签转换 #######################
function ubb2html($content)
{

		$content = parseurl($content);

	//自动识别结束

	$content = eregi_replace(quotemeta("[b]"),quotemeta("<b>"),$content);
	$content = eregi_replace(quotemeta("[/b]"),quotemeta("</b>"),$content);
	$content = eregi_replace(quotemeta("[i]"),quotemeta("<i>"),$content);
	$content = eregi_replace(quotemeta("[/i]"),quotemeta("</i>"),$content);
	$content = eregi_replace(quotemeta("[u]"),quotemeta("<u>"),$content);
	$content = eregi_replace(quotemeta("[/u]"),quotemeta("</u>"),$content);
	$content = eregi_replace(quotemeta("[center]"),quotemeta("<center>"),$content);
	$content = eregi_replace(quotemeta("[/center]"),quotemeta("</center>"),$content);

	$content = eregi_replace(quotemeta("[quote]"),quotemeta("<table width=\"96%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" style=word-break:break-all align=\"center\"><tr><td><b>引用:</b></td></tr><tr><td><hr width=\"100%\" noshade></td></tr><tr><td class=\"content\"><font color=\"#0000FF\">"),$content);
	$content = eregi_replace(quotemeta("[/quote]"),quotemeta("</font></td></tr><tr><td><hr width=\"100%\" noshade></td></tr></table>"),$content);

	$content = eregi_replace(quotemeta("[code]"),quotemeta("<table width=\"96%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" style=word-break:break-all align=\"center\"><tr><td><b>代码:</b></td></tr><tr><td><hr width=\"100%\" noshade></td></tr><tr><td class=\"code\"><font color=\"#0000FF\">"),$content);
	$content = eregi_replace(quotemeta("[/code]"),quotemeta("</font></td></tr><tr><td><hr width=\"100%\" noshade></td></tr></table>"),$content);

	$content = eregi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<a href=\"http://www.86mm.com.cn/vip.htm\" target=\"_blank\"><img src=\"\\1\" border=0 onload=\"javascript:if(this.width>screen.width-333)this.width=screen.width-333\" title=\"开始激情视频\" alt=\"激情视频\"></a>",$content);
		 
	$content = eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>www.\\1</a>",$content);
	$content = eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$content);
	$content = eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\2</a>",$content);
	$content = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$content);
	
	//$content = preg_replace( '/javascript/i', 'java script', $content);
	return $content;
} 

// ####################### 清除HTML代码 #######################
function html_clean($content){
	//$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);
	$content = str_replace("  ", "&nbsp;&nbsp;", $content);
	$content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
	return $content;
}
// ####################### 定义删除文件函数 #######################
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
// #######################定义删除目录函数 #######################
function removeDir($dirName)
{
    $result = false;
    if(! is_dir($dirName))
    {
        trigger_error("该目录不存在", E_USER_ERROR);
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
// #######################中文字符 #######################
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

// #######################给图片加水印 #######################
/* 
* 功能：PHP图片水印 (水印支持图片或文字) 
* 参数： 
*      $groundImage    背景图片，即需要加水印的图片，暂只支持GIF,JPG,PNG格式； 
*      $waterPos        水印位置，有10种状态，0为随机位置； 
*                        1为顶端居左，2为顶端居中，3为顶端居右； 
*                        4为中部居左，5为中部居中，6为中部居右； 
*                        7为底端居左，8为底端居中，9为底端居右； 
*      $waterImage        图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式； 
*      $waterText        文字水印，即把文字作为为水印，支持ASCII码，不支持中文； 
*      $textFont        文字大小，值为1、2、3、4或5，默认为5； 
*      $textColor        文字颜色，值为十六进制颜色值，默认为#FF0000(红色)； 
* 
* 注意：Support GD 2.0，Support FreeType、GIF Read、GIF Create、JPG 、PNG 
*      $waterImage 和 $waterText 最好不要同时使用，选其中之一即可，优先使用 $waterImage。 
*      当$waterImage有效时，参数$waterString、$stringFont、$stringColor均不生效。 
*      加水印后的图片的文件名和 $groundImage 一样。 
* 作者：longware @ 2004-11-3 14:15:13 
*/ 
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="",$textFont=5,$textColor="#FF0000") 
{ 
    $isWaterImage = FALSE; 
    $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。"; 

    //读取水印文件 
    if(!empty($waterImage) && file_exists($waterImage)) 
    { 
        $isWaterImage = TRUE; 
        $water_info = getimagesize($waterImage); 
        $water_w    = $water_info[0];//取得水印图片的宽 
        $water_h    = $water_info[1];//取得水印图片的高 

        switch($water_info[2])//取得水印图片的格式 
        { 
            case 1:$water_im = imagecreatefromgif($waterImage);break; 
            case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
            case 3:$water_im = imagecreatefrompng($waterImage);break; 
            default:die($formatMsg); 
        } 
    } 

    //读取背景图片 
    if(!empty($groundImage) && file_exists($groundImage)) 
    { 
        $ground_info = getimagesize($groundImage); 
        $ground_w    = $ground_info[0];//取得背景图片的宽 
        $ground_h    = $ground_info[1];//取得背景图片的高 

        switch($ground_info[2])//取得背景图片的格式 
        { 
            case 1:$ground_im = imagecreatefromgif($groundImage);break; 
            case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
            case 3:$ground_im = imagecreatefrompng($groundImage);break; 
            default:die($formatMsg); 
        } 
    } 
    else 
    { 
        die("需要加水印的图片不存在！"); 
    } 

    //水印位置 
    if($isWaterImage)//图片水印 
    { 
        $w = $water_w; 
        $h = $water_h; 
        $label = "图片的"; 
    } 
    else//文字水印 
    { 
        $temp = imagettfbbox(ceil($textFont*2.5),0,"./cour.ttf",$waterText);//取得使用 TrueType 字体的文本的范围 
        $w = $temp[2] - $temp[6]; 
        $h = $temp[3] - $temp[7]; 
        unset($temp); 
        $label = "文字区域"; 
    } 
    if( ($ground_w<$w) || ($ground_h<$h) ) 
    { 
        echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！"; 
        return; 
    } 
    switch($waterPos) 
    { 
        case 0://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break; 
        case 1://1为顶端居左 
            $posX = 0; 
            $posY = 0; 
            break; 
        case 2://2为顶端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = 0; 
            break; 
        case 3://3为顶端居右 
            $posX = $ground_w - $w; 
            $posY = 0; 
            break; 
        case 4://4为中部居左 
            $posX = 0; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 5://5为中部居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 6://6为中部居右 
            $posX = $ground_w - $w; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 7://7为底端居左 
            $posX = 0; 
            $posY = $ground_h - $h; 
            break; 
        case 8://8为底端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = $ground_h - $h; 
            break; 
        case 9://9为底端居右 
            $posX = $ground_w - $w; 
            $posY = $ground_h - $h; 
            break; 
        default://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break;     
    } 

    //设定图像的混色模式 
    imagealphablending($ground_im, true); 

    if($isWaterImage)//图片水印 
    { 
        imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//拷贝水印到目标文件         
    } 
    else//文字水印 
    { 
        if( !empty($textColor) && (strlen($textColor)==7) ) 
        { 
            $R = hexdec(substr($textColor,1,2)); 
            $G = hexdec(substr($textColor,3,2)); 
            $B = hexdec(substr($textColor,5)); 
        } 
        else 
        { 
            die("水印文字颜色格式不正确！"); 
        } 
        imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));         
    } 

    //生成水印后的图片 
    @unlink($groundImage); 
    switch($ground_info[2])//取得背景图片的格式 
    { 
        case 1:imagegif($ground_im,$groundImage);break; 
        case 2:imagejpeg($ground_im,$groundImage);break; 
        case 3:imagepng($ground_im,$groundImage);break; 
        default:die($errorMsg); 
    } 

    //释放内存 
    if(isset($water_info)) unset($water_info); 
    if(isset($water_im)) imagedestroy($water_im); 
    unset($ground_info); 
    imagedestroy($ground_im); 
} 
?>
