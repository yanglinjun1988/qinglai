// ------------------ (start) -------------------
window.__base = "http://web.qinglai.com/index.php" ; // 基路径 < 可配置 >
window.__api = "http://127.0.0.1"  ; // api 接口路径 < 可配置 >
window.__cache_ver = 1.1 ; // 缓存版本 < 可配置 >
window.__jui_path = "http://www.youniupin.com:100" ; // jui 的远程路径 < 可配置 >
window.__image_base = "" ; // 图片基址 < 可配置 >

window.__jses = { // 第三方 js
	
} ;

function postdata(formid, action, callback)
{
	$("#" + formid).ajaxSubmit(
	{			
		type:"post",
		url:__base+action,
		success:function(data){	
			eval(callback + '((' + data + '))');
			console.log(data);
		}
	})
}