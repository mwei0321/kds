$(function(){

 //调用无缝滚动
	 wufeng(".noticeul",30);

 //无缝滚动
	function wufeng(xuanzeqi,sudu){
		var myul = $(xuanzeqi)
		
		var mytimer = 0;
		var nowleft = 0;
		var zhefandian = 0;

		myul.children("li").each(
			function(){
				zhefandian = zhefandian + $(this).outerWidth(true);
			}
		);

		myul.html(myul.html()+myul.html());

		autoRun();

		function autoRun(){
			window.clearInterval(mytimer);
			mytimer = window.setInterval(
				function(){
					if(nowleft == -zhefandian){
						nowleft = 0;
					}else{
						nowleft = nowleft - 1;
					}
					myul.css("left",nowleft);
				}
			,sudu);
		}

		myul.mouseenter(
			function(){
				window.clearInterval(mytimer);
			}
		);

		myul.mouseleave(autoRun);
	}
});


