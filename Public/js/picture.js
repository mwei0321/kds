
;(function ($,window) {
	//焦点图
	$.fn.FocusPic = function (obj) {
		//初始设置
		var pset = {
			'outName'   	:   '.picout',
			'inName'    	:   '.picin',
			'outH' 			:	100,
			'outW' 			:	200,
			'eleTag'    	:	'dd',
			'eleH'      	: 	100,
			'eleW'			:	200,
			'eleBW' 		:   0,  //元素边框的宽
			'showNum'   	:   1,
			'eleInterval'   :   0,
			'animateType'	:   1,//动画方式：1为左右，2为上下,3无间隙滚动
			'time'			:   2,
			'isAuto'		:   1,//1为自动动画，0不用
			'eventType'		:   1,//事件方式
		};
		//合并参数
		if(obj && typeof(obj) === 'object'){
			$.extend(pset,obj);
		};
		//常量定义
		var num = $(pset.inName).find(pset.eleTag).length;//元素个数
		var page = ipage = 0;//页数
		var pageCount = Math.ceil(num/pset.showNum);//分页后总页数,进一取整;
		var mPx = 0;//动画移动像素
		var EleW = pset.eleW + pset.eleBW * 2 + pset.eleInterval;
		var EleH = pset.eleH + pset.eleBW * 2 + pset.eleInterval;
		var autoplay = null;
		pset.time *= 1000;
		
		//启动焦点图
		_init();
		
		//初始化		 
		function _init(obj) {
			switch(pset.style){
				case 1 :
					mPx = EleW * pset.showNum;
					$(pset.inName).css({'width':EleW * (num+1) + 'px','height':pset.eleH+'px'});//设置样式
					break;
				case 2 :
					mPx = EleH * pset.showNum;
					$(pset.inName).css({'height':EleH * (num+1) + 'px','width':pset.eleW+'px'});//设置样式
					break;
				default:
					alert('没有该动画样式！');
					return false;
			}
			//写入样式
			_css();
			//自动动画
			if(pset.isAuto){
				_autopaly();
				$(pset.outName).mouseover(function () {
					clearInterval(autoplay);
				}).mouseout(function () {
					_autopaly();
				});
			}
		};
		
		//自动动画
		function _autopaly () {
			autoplay = setInterval(function () {
				if(! $(pset.inName).is(':animated')){//是否在动画
					_page();
					_animate(ipage);
				}
			},pset.time);
		};
		//页码的计算
		function _page(){
			//分页
			page ++;
			ipage ++;
			var obj = $(pset.inName).find(pset.eleTag).first();
			//计算分页当前的页数
			if(page >= pageCount-1){
				//无封动画处理
				switch(pset.animateType){
					case 1 :
						obj.css({'position':'relative','left': EleW * pageCount +'px'});
						break;
					case 2 :
						obj.css({'position':'relative','top': EleH * pageCount +'px'});
						break;
					default :
						alert('没有该动画特效！');
						return false;
				}
				page = 0;
			}else if(page <= 0){
				page = pageCount - 1;
				
			}
			if(ipage > pageCount){
				ipage = 1;
				//无封动画处理
				obj.css({'position':'static'});
				$(pset.inName).css({'left':'0px','top':'0px'});
			}
		}
		
		//动画特效
		function _animate (_index) {
			//动画样式
			switch(pset.animateType){
				case 1 :
					//左右动画
					$(pset.inName).stop(true,false).animate({'left':-mPx * _index + 'px'},pset.time);
					break;
				case 2 :
					//上下动画
					$(pset.inName).stop(true,false).animate({'top':-mPx * _index + 'px'},pset.time);
					break;
				default:
					alert('没有该动画特效！');
					return false;
			}
		};
		
		function _operation(){
			
		}
		
		//样式设置
		function _css () {
			var css = '<style>'+pset.outName+'{width:'+ pset.outW +'px;height:'+ pset.outH +'px;position:relative;overflow:hidden;}'+
					  pset.inName+'{position:absolute;overflow:hidden;}'+
					  pset.inName+' '+pset.eleTag+'{width:'+pset.eleW+'px;height:'+pset.eleH+'px;overflow:hidden;float:left;display:inline-block;}'+
					  pset.outName+' *{margin:0;padding:0;}</style>';
			$('head').append(css);
		};
	};
	
})(jQuery,window);
