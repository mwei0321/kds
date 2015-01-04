/**
 * 表单操作js
 * @author MaWei
 * @time 2014-0908
 */

var form = {};

;(function ($,window,document,underfinded) {
	$.fn.extend(form,{
		//表单不能为空验证
		check : function (check,fmid){
			var Obj = check ? check : 'check';
			var i = 0;
			$('.'+check).each(function (e){
				if(! $(this).val()){
					i = 1;
					$(this).removeClass('normal').addClass('inerror');
					$(this).parent().append('<span class="emsg" style="color:red;">' + $(this).attr('emsg') + '</span>');
					$(this).blur(function () {
						$(this).removeClass('inerror').addClass('insuccess');
					});
				}else{
					$(this).removeClass('normal').addClass('insuccess');
				}
			});
			if(i){
				return false;
			}
			if(fmid){
				$('#'+fmid).submit();
			}else{
				return 1;
			}
		},
		//ajax提交
		ajaxsubmit : function (check,url,param) {
			var ch = form.check(check);
			if(ch){
				var data = '';
				if(param == 'self'){
					$('.'+check).each(function (e){
						data += $(this).attr('name')+'='+$(this).val()+'&';
					});
				}else{
					data = param;
				}
				var redata = null;
				//提交数据
				$.ajax({
					async : false,
					type : 'post',
					url  : url,
					data : data,
					dataType : 'json'
				}).done(function (e) {
					redata = e;
				});
				return redata;
			}
		},
		
		checkemail : function (email) {
			
		},
	});
	
})(jQuery,window,document);
