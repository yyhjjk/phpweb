<!--
var ImgSrc = new Array();//图片地址
ImgSrc[0] = "http://www.huazhongpc.com/templates/default/images/newim/0701.gif"
ImgSrc[1] = "http://www.huazhongpc.com/templates/default/images/newim/0702.gif"
ImgSrc[2] = "http://www.huazhongpc.com/templates/default/images/newim/0703.gif"
ImgSrc[3] = "http://www.huazhongpc.com/templates/default/images/newim/0704.gif"
ImgSrc[4] = "http://www.huazhongpc.com/templates/default/images/newim/0705.gif"
ImgSrc[5] = "http://www.huazhongpc.com/templates/default/images/newim/0706.gif"
ImgSrc[6] = "http://www.huazhongpc.com/templates/default/images/newim/0707.gif"
ImgSrc[7] = "http://www.huazhongpc.com/templates/default/images/newim/0708.gif"


for (var i=0;i<ImgSrc.length;i++){(new Image()).src = ImgSrc[i];}//预加载图片

<!--var ImgAlt = new Array();//鼠标放上去显示的文字-->
<!--ImgAlt[0] = "ddddd"-->
<!--ImgAlt[1] = "bbbbb"-->
<!--ImgAlt[2] = "ccccc"-->

var ImgHerf =  new Array();//链接
ImgHerf[0] = "http://www.86mm.com.cn"
ImgHerf[1] = "http://www.86mm.com.cn"
ImgHerf[2] = "http://www.86mm.com.cn"
ImgHerf[3] = "http://www.86mm.com.cn"
ImgHerf[4] = "http://www.86mm.com.cn"
ImgHerf[5] = "http://www.86mm.com.cn"
ImgHerf[6] = "http://www.86mm.com.cn"
ImgHerf[7] = "http://www.86mm.com.cn"
var step=0;
function slideit(){
	//rewrite by haiwa@2005-7-7
	var oImg = document.getElementById("javascript.img");
	if (document.all){oImg.filters.blendTrans.apply();}
	oImg.src=ImgSrc[step];
	document.getElementById("javascript.a").href=ImgHerf[step];
	<!--oImg.title=ImgAlt[step];-->
	if (document.all){oImg.filters.blendTrans.play();}
	step = (step<(ImgSrc.length-1))?(step+1):0;
	(new Image()).src = ImgSrc[step];//加载下一个图片
}
setInterval("slideit()",3000);
