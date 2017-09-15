/*
			$(document).ajaxStart(function() {
		        $.blockUI({
		            message: "<img src='Public/admin/images/busy.gif' /><label style='margin-left:6px;line-height:26px;'>正在处理，请稍后...</label>",
		            css: {
		                padding: '4px 10px 4px 10px',
		                border: '2px solid #3d85c6',
		                backgroundColor: '#fef4ea',
		                color: '#000',
		                width: '260px',
		                left: ($(window).width() - 260) / 2 + 'px',
		                '-webkit-border-radius': '10px',
		                '-moz-border-radius': '10px'
		            }
		        });
		    }).ajaxStop(function() {
		        setTimeout($.unblockUI, 100);
		    });
*/	
function wuliu($name,$order){
	$index = layer.load(0,{time:6000});
	$.getJSON("?s=Kuaidi/getOrderInfo",{'name':$name,'order':$order},function(data){
	  	if(data['status'] == 200){
	  	    $html="<table class='formtable text_center' ><thead><th width=170>时间</th><th>地点和跟踪进度</th></thead><tbody id='info'>";
	  		for(var i=0;i<data['data'].length;i++){
		  		$html += "<tr><td>"+data['data'][i]['time']+"</td><td>"+data['data'][i]['context']+"</td></tr>";
		  	}
	  		$html+="</tbody></table>";
			 layer.open({
				  type: 1,  
				  title:'物流信息',
				  area: ['600px', '360px'],
				  shadeClose: false, //点击遮罩关闭
				  content: $html,
				  closeBtn:1,
				  success:function(){
				  	layer.close($index);
			 	  }
			  });
		}else{
			layer.close($index);
			layer.msg(data['message']);
	 }
	});
}	
    function showSuccessMessage(msg,closeDelay) {
        $.blockUI({
            message: "<label style='margin-left:6px;line-height:26px;color:#3b751d;font-size:14px;'>"+msg+"</label>",
            css: {
                padding: '4px 10px 4px 10px',
                border: '2px solid #3b751d',
                backgroundColor: '#fef4ea',
                left: ($(window).width() - 200) / 2 + 'px',
                width: '200px',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px'
            },
            showOverlay: true
        });
        setTimeout($.unblockUI, closeDelay);
    }

    function showErrorMessage(msg,closeDelay) {
        $.blockUI({
            message: "<label style='margin-left:6px;line-height:26px;color:#a61c00;font-size:14px;'>"+msg+"</label>",
            css: {
                padding: '4px 10px 4px 10px',
                border: '2px solid #a61c00',
                backgroundColor: '#fef4ea',
                left: ($(window).width() - 200) / 2 + 'px',
                width: '200px',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px'
            },
            showOverlay: true
        });

        setTimeout($.unblockUI, closeDelay);
    }




