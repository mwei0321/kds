//公共函数库

;(function ($,window,document) {
	$.fn.tabnav = function (Obj){
		//初始化设置
		var options = {
			'init'		: 0,　//是否初始化
			'tabtitle' : 'tabtitle', //切换标题对象
			'tabcont' : 'tabcont', //切换内容class
			'tabcur'  : 'cur'
		}
		if(Obj && typeof(Obj) === 'object'){
			$.extend(Obj,options);
		}
		var Obj = $(this);
		var tab = Obj.find('.'+options.tabtitle);
		if(options.init >= 0){
			_init(options.init);
		}
		tab.click(function (e) {
			tab.removeClass(options.tabcur);
			$(this).addClass(options.tabcur);
			var tabcont = Obj.find('.'+options.tabcont);
			tabcont.hide().eq($(this).index()).show();
		});
		//初始化选择
		function _init (num) {
			tab.removeClass(options.tabcur);
			tab.eq(num).addClass(options.tabcur);
			var tabcont = Obj.find('.'+options.tabcont);
			tabcont.hide().eq(num).show();
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
