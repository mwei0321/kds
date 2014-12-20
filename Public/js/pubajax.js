/**
 * 网站ajax请求js 
 */

//评论
var comment = function (url) {
	var replyid = $('#replytid').val();
	var comment = $('#commt').val();
	if(comment.length < 5){
		layer.error('评论不能少于5个字！');
		return false;
	}
	$.ajax({
		type  : 'post',
		url   : url,
		data  : 'content='+comment+'&replyid='+replyid,
		dataType : 'json'
	}).done(function (data) {
		if(data.status){
			layer.success(data.msg);
			if(replyid > 0){
				$('#reply_'+replyid).prepend(data.html);
			}else{
				$(data.html).insertAfter($('.comment>h3'));
			}
			$('#replyname').text('');
			$('#replytid').val(0);
			$('#commt').val('');
		}else{
			layer.error(data.msg);
		}
	});
}
//回复评论
var reply = function (name,replyid) {
	$('#replyname').text('回复  :  '+name);
	$('#commt').val('').focus();
	$('#replytid').val(replyid);
}

//点赞
var parise = function (Obj,url) {
	$.ajax({
		url : url,
		dataType : 'json',
	}).done(function (data) {
		if(data.status){
			Obj.parent('span').html('<i></i>&nbsp;&nbsp;( '+data.num+' )');
		}
	});
}
