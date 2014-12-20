

;(function ($,window,document,underfinded) {
	//浏览器宽度
	var SreenW = $(window).width();
	// //浏览器高度
	var SreenH = $(window).height();
	// alert(SreenH +'=>'+SreenW);
	var _this = null;
	var page = 1;
	//初始化配置
	var Options = {
		'outid'				: 'photo_hide',
		'inid'        		: 'photo_show',
		'outwidth'    		: 500,
		'outheight'   		: 300,
		'elewidth'    		: 200,
		'eleheight'   		: 280,
		'outborderwidth'  	: 1,
		'eleborderwidth'   	: 1,
		'eleinterval' 		: 10,
		'out_in_int'  		: 10,
		'elenum'      		: 5,
		'animate_time'		: 5,
		'animate_interval'  : 2,
		'showelenum'		: 2
	}
	//初始化
	var _init = function(Obj){
		//判断是个实例化对象
		if(Obj && typeof Obj === 'object'){
			//合并传入参数
			$.extend(Options,Obj);
		}else{
			alert('请传入存在的对像元素')
		}
		_style();
		Options.elenum = Math.ceil(_this.find('dd').length/Options.showelenum);
	}
	//样式
	var _style = function () {
		var sy = '<style>*{padding:0;margin:0;}#'+Options.outid+'{width:'+Options.outwidth+'px;height:'+Options.outheight+'px;border:'+Options.outborderwidth+'px solid #ccc;display:block;overflow:hidden;position:relative;}'+
				'#'+Options.inid+'{height:'+(Options.outheight - (Options.out_in_int*2))+'px;width:'+((Options.elewidth+Options.eleinterval)*Options.elenum)+'px;margin-top:'+Options.out_in_int+'px;position:absolute;}'+
				'#'+Options.inid+' dd{width:'+Options.elewidth+'px;height:'+Options.eleheight+'px;border:'+Options.eleborderwidth+'px solid #ccc;float:left;margin-right:'+Options.eleinterval+'px;overflow:hidden;}'+
				'#phpage{position:absolute;bottom:10px;height:22px;width:100%;}#phpage span{display:inline-block;width:15px;height:15px;border-radius:15px;background:red;margin:0 5px;}'+
				'</style>';
		$('head').append(sy);
	}
	
	var _animate = function () {
		if(! $('#'+Options.inid).is(':animated')){
			switch (style){
				case 1 : 
					if(page == Options.elenum){
						$('#'+Options.inid).stop(true,false).animate({left:'0px' },1000);
						page = 1;
						//$('#page').find('p').eq($page-1).addClass('stact').siblings('p').removeClass('stact');
					}else{
						$('#'+Options.inid).stop(true,false).animate({left:'-'+((Options.elewidth + Options.eleinterval + (Options.outborderwidth * 2)) * Options.showelenum * page)+'px'},1000);
						page ++;
						//$('#page').find('p').eq($page-1).addClass('stact').siblings('p').removeClass('stact');
					}
					$('#page').text(Options.showelenum);
					$("#wd").text((Options.elewidth + Options.eleinterval + (Options.outborderwidth * 2) * Options.showelenum) * page);
			}
		}
		
	}
	
	var _play = function (_time){
		$play = setInterval(_animate,_time);
		//$('.show span').hover(function (){clearInterval($play);},function (){$play = setInterval("$autoplay()",4000);});
	}
	
	var _controller = function (_style) {
		var _pagesy = '';
		switch (_style) {
			case 1 :
				for(var i = 0; i < Options.elenum; i++){
					_pagesy += '<span></span>';
				}
		}
		html = '<p id="phpage">'+_pagesy+'</p>';
		$('#'+Options.outid).append(html);
	}
	
	$.fn.autoImg = function (Obj) {
		_this = $(this);
		_init(Obj);
		_play(2000);
		_controller(1);
	}
	
	
})(jQuery,window,document);

