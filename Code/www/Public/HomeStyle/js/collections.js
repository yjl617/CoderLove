$(function(){
	$(document).ajaxStart(function(){
		$(".collectionCancel").off("click");
	});
	//职位的收藏与取消收藏
	$('#collectionsForm').on('click','li .collectionCancel',function(e){
		e.preventDefault();
		if(e.target == this){
			var _this = $(this);
			collect(_this);
		}
	});
})

function collect(_this){
	var id = _this.parents('li').attr('data-id');
	var type = 0;
	var resubmitToken = $('#resubmitToken').val();
	if(_this.hasClass('collected')){
		type = 0;
	}else{
		type = 1;
	}
	$.ajax({
		url:ctx+'/mycenter/collectPositoin.json',
		type:'POST',
		async:false,
		data:{
			positionId:id,
			type:type,
			resubmitToken:resubmitToken
		},
		dataType:'json'
	}).done(function(result){
		if(null != result.resubmitToken && '' != result.resubmitToken){
			$('#resubmitToken').val(result.resubmitToken);
		}
		if(result.success){
			if(result.content.showStts == 1){//用于页面显示"收藏职位"
				_this.removeClass('collected').html('收藏<span>已取消收藏</span>');
				_this.children('span').fadeIn(200).delay(500).fadeOut(200);
			}else{//用于页面显示"取消收藏"
				_this.addClass('collected').html('取消收藏<span>已成功收藏该职位</span>');
				_this.children('span').fadeIn(200).delay(500).fadeOut(200);
			}
		}else{
			alert(result.msg);
		}
		$(".collectionCancel").on("click");
	});
}