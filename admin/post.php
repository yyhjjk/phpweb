


<title>上传图片</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<STYLE><!--
BODY {
	COLOR: #3F3849; 
	line-height: 140%;
	font-family: "Verdana", "Tahoma", "宋体";
	FONT-SIZE: 12px;
	SCROLLBAR-ARROW-COLOR: #F5D300;
	SCROLLBAR-BASE-COLOR:  #40364d;
	SCROLLBAR-HIGHLIGHT-COLOR: #90869E;
}
A:LINK		{COLOR: #3F3849; TEXT-DECORATION: none}
A:VISITED	{COLOR: #3F3849; TEXT-DECORATION: none}
A:HOVER		{COLOR: #F5D300; BACKGROUND-COLOR: #40364d}
A:ACTIVE	{COLOR: #F5D300; BACKGROUND-COLOR: #40364d}
FORM		{FONT-FAMILY: "Verdana", "Tahoma", "宋体"; FONT-SIZE: 12px}
INPUT		{FONT-FAMILY: "Verdana", "Tahoma", "宋体"; COLOR: #51485F; FONT-SIZE: 12px}
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
<?php
if ($_GET['search']=="ok")
{

$str = file($_GET['id']);
$count = count($str);
for ($i=0;$i<$count;$i++){ 
    $file .= $str[$i];
}
$tomtq = explode("<urls>",$file);
$tomtq = explode("</urls>",$tomtq[1]);
$tomtq= $tomtq[0];
$tomtq = str_replace("<url>/", "<url>http://pic.okxr.com/", $tomtq);
$tomtq = str_replace("<url>", "[img]", $tomtq);
$tomtq = str_replace("</url>", "[/img]", $tomtq);




echo $tomtq;         
} else {
?>



  <FORM name=search action=http://www.86mm.com.cn/phpokxr.php method=get>
  <INPUT size=35 name=id> <INPUT type=submit value="ok" name=search> 
</FORM>

<?
}
?>
</body>
</html>
