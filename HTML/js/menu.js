var Menu = {};
;(function ($,window,document) {

	$.fn.extend(Menu,{
		slider : function (cls,type) {
			type = type == 'dl' ? 'dl' : 'ul';
			
		},
		
		//标签切换
		navtab : function (navclass,tabclass,action,init) {
			action = action ? action : 'cur';
			var nav = $(navclass).children();
			nav.click(function () {
				var obj = $(this);
				nav.removeClass(action);
				obj.addClass(action);
				$(tabclass).hide().eq(obj.index()).show();
			});
			init && _init(init);
			//初始化
			function _init(init){
				nav.removeClass(action);
				nav.eq(init).addClass(action);
				$(tabclass).hide().eq(init).show();
			}
		},
		
		//
		menu : function (cladiv,init,active,time,ele,ch) {
			cladiv = cladiv ? cladiv : '.slidemenu';
			init = init ? init : true;
			active = active ? active : '.cur';
			ele = ele ? ele : 'dd';
			ch = ch ? ch : 'dl';
			time = time ? time : 400;
			$(cladiv).find(ele).hover(function () {
				$(this).children(ch).stop(true,false).slideToggle(400);
			});
			if(init){
				$(cladiv).find(ele).children(ch).hide();
			};
		}
	});
	
})(jQuery,window,document)
