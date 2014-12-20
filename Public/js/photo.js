/**
 *
 *  
 */

;(function ($,window) {
	var Photo = {};
	
	var aptions = {
		
	};
	
	$.fn.extend(Photo,{
		slider : function (){
			
		},
		
		cover : function () {
			
		}
	});
	
	$.fn.Cover = function (Obj){
		//初始化分页
		var Page = 1;
		//初始化
		var _confing = {
			EleW		: 400,  //每个元素的宽度
			ELeH		: 600,　//每个元素的高度
			EleLRM		: 0,　　//每个元素的左右间隙
			EleName     : 'dd', //元素标签名称
 			EleUDM		: 0,  //每个元素的上下间隙
 			ShowID		: '#show',　//显示层ID
 			ShowEleNum  : 1, //遮照显示元素个数
 			EleBorderW  : 1,　//元素边框的宽度
 			AnimateTime : 2,　//转播速度
 			IsAnimate   : 1, //默认自动动画　
 			AnimateStyle: 0　 //动画方式，　0为左右，1为上下
		};
		
		//判断是个实例化对象
		if(Obj && typeof Obj === 'object'){
			//合并传入参数
			$.extend(_config,Obj);
		}else{
			alert('请传入存在的对像元素')
		}
		//数值初始化计算
		var ShowID = _config.ShowID;
		var EW = _config.EleW + EleBorderW * 2;
		var EH = _config.EleH + EleBorderW * 2;
		var ENUM = $(ShowID).find(_config.EleName).length;
		var ShowW = _config.AnimateStyle ? EW * ENUM : EW;
		var ShowH = _config.AnimateStyle ? EH * ENUM : EH;
		var PageNum = Math.ceil(ENUM / _config.ShowEleNum);
		var Time = _config.AnimateTime;
		var PagePx = _config.AnimateStyle ? EW * _config.ShowEleNum : EH * _config.ShowEleNum;
		var AnimateStyle = _config.AnimateStyle ? 'left' : 'top';
		$(ShowID).width(ShowW + 10);
		$(ShowID).height(ShowH + 10);
		
		//起动动画
		if(_config.IsAnimate){
			var play = setInterval("_Animate()",Time);
			//鼠标经过停止
			$(ShowID).hover(function (){
				clearInterval(play);
			},function (){
				play = setInterval("_Animate()",Time);
			});
		}else{
			
		}
		
		//动画特效
		function _Animate() {
			if(! $(ShowID).is('animated')){
				if(Page == PageNum){
					$(ShowID).animate({AnimateStyle:'0px' },Time);
					$page = 1;
				}else{
					$('ShowID').animate({AnimateStyle:'-='+PagePx+'px'},Time);
					$page ++;
				}
			}
		}
		
	}
	
})(jQuery,window);
