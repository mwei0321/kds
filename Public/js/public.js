//公共函数库

;(function ($,window,document) {
	
	$.fn.tabnav = function (Obj){
		//初始化设置
		var options = {
			'init'		: true,　//是否初始化
			'tabtclass' : 'tabnavtitle', //切换标题class
			'tabcclass' : 'tabnavcont', //切换内容class
			'tabstyle'  : 'cur'	
		}
		
		var Obj = $(this);
		var tab = Obj.find('.'+options.tabtclass);
		if(options.init){
			_init();
		}
		tab.click(function (e) {
			tab.removeClass(options.tabstyle);
			$(this).addClass(options.tabstyle);
			var tabcont = Obj.find('.'+options.tabcclass);
			tabcont.hide().eq($(this).index()).show();
		});
		
		function _init () {
			tab.removeClass(options.tabstyle);
			tab.eq(0).addClass(options.tabstyle);
			var tabcont = Obj.find('.'+options.tabcclass);
			tabcont.hide().eq(0).show();
		}
	}
})(jQuery,window,document);

//滑动菜单和标签页
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
		menu : function (cladiv,active,type,time,ele,ch) {
			cladiv = cladiv ? cladiv : '.slidemenu';
			active = active ? active : '.cur';
			ele = ele ? ele : 'dd';
			ch = ch ? ch : 'dl';
			time = time ? time : 400;
			//初始化
			$(cladiv).find(ele).children(ch).hide();
			//点击切换
			$(cladiv).find(ele).hover(function () {
				$(this).children(ch).stop(true,false).slideToggle(400);
			});
			
		}
	});
	
})(jQuery,window,document)
