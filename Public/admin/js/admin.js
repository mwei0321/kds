/**
 *后台js
 * author : mawei
 * homepage : http://www.kandianshu.com
 */
$(function (){
	//全选
   $("#checkall").click(function (){
   		$('.checkalls').prop('checked',$(this).prop('checked'));
   });
   
   //标签页切换
   $('.tabnavigtion').find('.tabnavit').click(function () {
        $('.tabnavit').removeClass('cur');
        $(this).addClass('cur');
        $('.tabnavcont').hide();
        $('.tabnavcont').eq($(this).index()).show();
    });
});
//设置状态
var setstatus = function (Obj,id){
	var url = Obj.attr('url');
	var st = Obj.attr('status');
	var ids = id ? id : getallval();
	var rmclne = Obj.attr('removeclass');
	var addclne = Obj.attr('addclass');
	if(!ids){
		layer.error('请选择要设置状态ID');
	}
	$.ajax({
		type : 'post',
		url  : url,
		data : 'status='+st+'&id='+ids,
		dataType : 'json'
	}).done(function (e){
		if(e){
			Obj.parent('td').parent('tr').find('.upstat').text(e.msg);
			if(st == 1){
				Obj.attr('status',0);
				Obj.find('i').removeClass(rmclne).addClass(addclne).css('color','red');
			}else{
				Obj.attr('status',1);
				Obj.find('i').removeClass(rmclne).addClass(addclne).css('color','green');
			}
			Obj.attr('removeclass',addclne);
			Obj.attr('addclass',rmclne);
			layer.success('设置成功！');
		}else{
			layer.error('设置失败！');
		}
	});
}
//设置推荐
var recommend = function (Obj,id){
	var url = Obj.attr('url');
	var recomm = Obj.attr('recomm');
	var ids = id ? id : getallval();
	if(ids.length < 1){
		layer.error('请选择要推荐ID');
	}
	$.ajax({
		type : 'post',
		url  : url,
		data : 'recomm='+recomm+'&ids='+ids,
		dataType : 'json'
	}).done(function (e){
		if(e.status){
			Obj.parent('td').parent('tr').find('.recomm').text(e.text);
			if(recomm == 1){
				Obj.attr('recomm',0);
				Obj.find('i').css('color','red');
			}else{
				Obj.attr('recomm',1);
				Obj.find('i').css('color','green');
			}
			layer.success(e.msg);
		}else{
			layer.error('设置失败！');
		}
	});
}

function getallval(){
	var ids = new Array();
	$('.checkalls').each(function (e){
		if($(this).prop('checked')){
			ids.push($(this).val());
		}
	});
	return ids;
}

//全局删除
var delall = function (Obj,id,isrefresh){
	var ids = id ? id : getallval();
	if(ids.length < 1){
		layer.error('请选择要删除ID');
	}
	if(confirm('你确定要删除吗？')){
		var url = Obj.attr('url');
		$.ajax({
			type : 'post',
			url  : url,
			data : 'ids='+ids.toString(),
		}).done(function (e){
			if(e == 1){
				layer.success('删除成功！');
				if(ids == -1 || isrefresh){setTimeout('location.reload()',1200);};
				if(!$('#checkall').prop('checked')){
					// var list = ids.split(',');
					if(typeof(ids) == 'number'){
						$('#deleid_'+ids).remove();
					}else{
						for(var i=0;i<ids.length;i++){
							$('#deleid_'+ids[i]).remove();
						}
					}
				}
			}else{
				layer.error('删除失败！');
			}
		});
	}
}
