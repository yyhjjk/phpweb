<html>
<head>
<title>�ϴ�ͼƬ</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<STYLE><!--
BODY {
	COLOR: #3F3849; 
	line-height: 140%;
	font-family: "Verdana", "Tahoma", "����";
	FONT-SIZE: 12px;
	SCROLLBAR-ARROW-COLOR: #F5D300;
	SCROLLBAR-BASE-COLOR:  #40364d;
	SCROLLBAR-HIGHLIGHT-COLOR: #90869E;
}
A:LINK		{COLOR: #3F3849; TEXT-DECORATION: none}
A:VISITED	{COLOR: #3F3849; TEXT-DECORATION: none}
A:HOVER		{COLOR: #F5D300; BACKGROUND-COLOR: #40364d}
A:ACTIVE	{COLOR: #F5D300; BACKGROUND-COLOR: #40364d}
FORM		{FONT-FAMILY: "Verdana", "Tahoma", "����"; FONT-SIZE: 12px}
INPUT		{FONT-FAMILY: "Verdana", "Tahoma", "����"; COLOR: #51485F; FONT-SIZE: 12px}
//-->
</STYLE>
</head>
<script language="javascript">
<!--
function bbcode(thebbcode) {
    if (thebbcode!=""){
        parent.form.content.value += "\n"+thebbcode+"\n";
        parent.form.content.focus();
    }
}
function preview(url, width, height)
{
    var feature = "dialogWidth:" + width + "px;dialogHeight:" + height + "px;help:no;status:no;"
    window.showModalDialog(url, null, feature);
}
//-->
</script>
<body bgcolor=#C5C5C5 topmargin=0 leftmargin=0>
<table><tr><td height=80><div align=center>����ϴ�����</div></td></tr></table>
<?
if($_POST['action'] == "upload_pic")
{
   require_once ("global.php");
    function getExtention($type)
    {
        switch ($type)
        {  
            case "image/gif":
                $extention=".gif";
                break;
            case "image/pjpeg":
                $extention=".jpg";
                break;
            case "mage/x-png":
               $extention=".png";
               break;
        }
        return $extention;
    }
    function MakeName($length) 
    { 
        $possible = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
        $str = ""; 
        while(strlen($str) < $length) 
        { 
            $str .= substr($possible, (rand() % strlen($possible)), 1); 
        } 
        return($str); 
    }
    function GetImageInfo($file) 
    {
	$data	= getimagesize($file);
	$imageInfo["width"]	= $data[0];
	$imageInfo["height"]= $data[1];
	$imageInfo["type"]	= $data[2];
	$imageInfo["name"]	= basename($file);
	$imageInfo["size"]  = filesize($file);
	return $imageInfo;		
    } 
    if (($upfile_type=="image/gif") || ($upfile_type=="image/pjpeg") || ($upfile_type=="image/x-png"))
    {
    // �����ļ���
        $datetime = $QQ;
        $pic_name = $datetime.MakeName(6).getExtention($upfile_type);
        $filename = "../mgirls/albums/".$pic_name;
       
        // ���ļ���ŵ�������
        if (copy($upfile,$filename))
        {
	    $imageInfo	= GetImageInfo($filename);
            $size = get_real_size($imageInfo["size"]);
            echo "<a href=\"javascript:\" title=Ԥ��ͼƬ onclick=\"preview('".$filename."',".($imageInfo['width']+6).",".($imageInfo['height']+32).")\">$pic_name</a> (".$size.") �ϴ��ɹ� <a href=\"upload_img.php\">[�����ϴ�]</a>";
   
        } else {
            echo "�ϴ�ʧ��! \n";
            echo "<a href=javascript:history.back(1)>[����]</a>\n";
        }
    } else {
    echo "������Ч��ͼƬ�ļ�, \n";
    echo "<a href=javascript:history.back(1)>[����]</a>\n";
    }
} else {
?>
<form name="form" method="post" action="upload_img.php" enctype="multipart/form-data" >
<input type="hidden" name="action" value="upload_pic">
QQ�ţ�<input type="text" name="QQ" size=35 value="">
<input type="file" name="upfile" size=35 value=""> <input type="submit" name="Submit" value="�ϴ�">
</form>
</body>
</html>
<?
}
?>
