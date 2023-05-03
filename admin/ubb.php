<script language="javascript">
<!--
function validate(theform){
if (document.all||document.getElementById){
for (i=0;i<theform.length;i++){
var tempobj=theform.elements[i]
if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
tempobj.disabled=true
}
}
}
//-->
</script>

<script language="javascript">
<!--
function bbcode(thebbcode) {
if (thebbcode!=""){
document.form.content.value += thebbcode+" ";
document.form.content.focus();
}
}
//-->
</script>

<input type="button" value="粗体" style="FONT-WEIGHT: bold" onclick="bbcode('[B][/B]')" title="粗体 (alt+b)" accesskey="b">
<input type="button" value="斜体" style="FONT-STYLE: italic" onclick="bbcode('[I][/I]')" title="斜体 (alt+i)" accesskey="i">
<input type="button" value="下划线" style="TEXT-DECORATION: underline" onclick="bbcode('[U][/U]')" title="下划线 (alt+u)" accesskey="u">
<input type="button" value="引用" onclick="bbcode('[QUOTE][/QUOTE]')" title="引用">
<input type="button" value="代码" onclick="bbcode('[CODE][/CODE]')" title="代码">
<input type="button" value="居中" onclick="bbcode('[CENTER][/CENTER]')" title="居中">
<input type="button" value="网址" style="TEXT-DECORATION: underline" onclick="bbcode('[URL][/URL]')" title="网址">
<input type="button" value="链接" style="TEXT-DECORATION: underline" onclick="bbcode('[URL=][/URL]')" title="链接">
<input type="button" value="E-mail" onclick="bbcode('[EMAIL][/EMAIL]')" title="Email">
<input type="button" value="图片" onclick="bbcode('[IMG][/IMG]')" title="图片"> 
