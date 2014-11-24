/**
 * @author MaWei
 * @time 2014-08-26
 * @home http://www.phpyrb.com
 */
var layer = {};
;(function($, window, document, underfined) {
	//
	//浏览器宽度
	var SreenW = $(window).width();
	// //浏览器高度
	var SreenH = $(window).height();
	 //alert(SreenH +'=>'+SreenW);
	//初始化配置
	var Options = {
		bgOpacity : '0.8',
		bgColor : '#9f9f9f',
		EditW : 400,
		EditH : 600,
		ThumbW : 100,
		ThumbH : 100,
	}
	
	$.fn.extend(layer, {
		
		box : function(title,html,ajx) {
			layer._style();
			var t = title ? '<div id="lytitle"><h3>'+title+'<a href="javascript:;" onclick="layer.close(0.4);" title="关闭">X</a></h3></div>' : '';
			var html = '<div id="mwbg"></div>'+
						'<div id="outsidebox">'+ t +
						'<div id="box_content">'+
						'<div></div>';
			$('body').prepend(html);
			var h = ajx ? '<iframe>'+html+'</iframe>' : html;
			$('#box_content').append(h);
			layer._center();
			//close();
		},
		//错误提示
		error : function(msg,time) {
			layer._message(msg,0,time);
		},
		//操作成功提示
		success : function(msg,time) {
			layer._message(msg,1,time);
		},
		//可以url加载
		load : function (title,url,parem){
			layer._load(url,title,parem);
		},
		//关闭窗口
		close : function (time) {
			var t = time ? time : 1.5;
			var setmsg = setTimeout(function() {
				$('#outsidebox').fadeOut('1000', function() {
					$(this).remove();
					$('#mwbg').remove();
				});
			}, t * 1000);
		},
		
		_html : function (cont,title,ajx) {
			layer._style();
			var t = title ? '<div id="lytitle"><h3>'+title+'<a href="javascript:;" onclick="layer.close(0.4);" title="关闭">X</a></h3></div>' : '';
			var html = '<div id="mwbg"></div>'+
						'<div id="outsidebox">'+ t +
						'<div id="box_content">'+
						'<div></div>';
			$('body').prepend(html);
			var h = ajx ? '<iframe>'+cont+'</iframe>' : cont;
			$('#box_content').append(h);
			$('#mwbg').click(function () {layer.close(0.3);});
			layer._center();
		},
		//消息提示
		_message : function (msg,type,time) {
			var clas = type ? 'success' : 'error';
			var html = '<span class="' + clas + '">' + msg + '</span>';
			layer._html(html);
			layer.close(time);
			return false;
		},
		//居中
		_center : function (){
			var oboxw = $('#outsidebox').width();
			var oboxh = $('#outsidebox').height();
			var oboxl = $('#outsidebox').css({
				'left' : (SreenW - oboxw) / 2 + 'px',
				'top' : (SreenH - oboxh) / 2 + 'px',
			}).fadeTo('slow',0.99);
		},
		_iframe : function (){
			
		},
		_load : function (url,title,param){
			jQuery.ajax({
				url:url,
	  	   	    type:'post',
	  	   	    data:param,
	  	   	    cache:false,
	  	   	    dataType:'html',
	     	}).done(function (html) {
	  	   		  layer._html(html,title);
	     	});
		},
		_style : function (){
			var style = '<style>#mwbg{width:' + SreenW + 'px;height:' + SreenH + 'px;display:block;position:fixed;z-index:900;opacity: 0.8;filter:Alpha(Opacity=90);postion:fixed;background:' + Options.bgColor + ';top:0;left:0;}' + 
			'#outsidebox{border:5px solid #666;background:#fff;border-radius:10px;z-index:900;position:fixed;padding:10px;display:none;} #outsidebox h3{height:35px;line-height:35px;border-bottom:1px solid #ccc;background:#efefef;}' +
			 '#outsidebox {} #outsidebox .error{color:red;font-size:14px;} #outsidebox .success{color:green;font-size:14px;} ' + '#outsidebox .clr{clear:both;padding:0;margin:0;}' + 
			 '#outsidebox #box_content{padding:0 10px;max-height:800px;overflow:hidden;overflow-y:auto;}'+
			 '#outsidebox #lytitle{height:30px;margin-bottom:20px;} #outsidebox #lytitle h3{height:40px;line-height:40px;color:#999;padding:0 15px;border:1px solid #ccc;font-size:18px;margin:0;linear-gradient(to bottom, #f5f5f5 0%, #e8e8e8 100%);border-radius:5px;color:#666;font-family:微软雅黑} '+ 
			 '#outsidebox #lytitle h3 a{display:inline-block;width:20px;height:20px;font-size:18px;color:#333;float:right;cursor:pointer;text-align:center;line-height:40px;font-weight:bold;} #outsidebox #lytitle h3 a:hover{color:#a00;}'+
			 '</style>';
			$("head").append(style);
		},
	});
	
})(jQuery, window, document)
