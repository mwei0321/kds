/**
 * @author MaWei
 * @time 2014-12-11
 * @home http://www.phpyrb.com
 */

var mwUI = {};
;(function ($,window,document) {
	//全选，返选，
	$.fn.extend(mwUI,{
		//全选
		CheckAll : function(Obj,checkclass){
			$(checkclass).prop('checked',Obj.prop('checked'));
		},
		//反选
		CheckRev : function(checkclass){
			$(checkclass).each(function (e) {
				$(this).prop('checked',!$(this).prop('checked'));
			});
		},
		//返回选中的值
		CheckIds : function (checkclass,toStr){
			var reIds = new Array();
			$(checkclass).each(function (e) {
				if($(this).prop('checked')){
					reIds.push($(this).val());	
				}
			});
			return toStr ? reIds.toString() : reIds; 
		},
	});
})(jQuery,window,document);
//ajax请求
;(function ($,window,document){
	$.fn.extend(mwUI,{
		ReqAjax : function (Obj){
			var url = Obj.attr('url');
			var data = Obj.attr('data');
			if(url){
				$.ajax({
					type : 'POST',
					url  : url,
					data : data
				}).done(function (msg) {
					layer.msg(msg,msg);
				});
			}else{
				layer.error('没有请求地址！');
			}
		},
	});
})(jQuery,window,document);
