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

<input type="button" value="����" style="FONT-WEIGHT: bold" onclick="bbcode('[B][/B]')" title="���� (alt+b)" accesskey="b">
<input type="button" value="б��" style="FONT-STYLE: italic" onclick="bbcode('[I][/I]')" title="б�� (alt+i)" accesskey="i">
<input type="button" value="�»���" style="TEXT-DECORATION: underline" onclick="bbcode('[U][/U]')" title="�»��� (alt+u)" accesskey="u">
<input type="button" value="����" onclick="bbcode('[QUOTE][/QUOTE]')" title="����">
<input type="button" value="����" onclick="bbcode('[CODE][/CODE]')" title="����">
<input type="button" value="����" onclick="bbcode('[CENTER][/CENTER]')" title="����">
<input type="button" value="��ַ" style="TEXT-DECORATION: underline" onclick="bbcode('[URL][/URL]')" title="��ַ">
<input type="button" value="����" style="TEXT-DECORATION: underline" onclick="bbcode('[URL=][/URL]')" title="����">
<input type="button" value="E-mail" onclick="bbcode('[EMAIL][/EMAIL]')" title="Email">
<input type="button" value="ͼƬ" onclick="bbcode('[IMG][/IMG]')" title="ͼƬ"> 
