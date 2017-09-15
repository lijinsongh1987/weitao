function dologin(nickname,imageurl,openid,leadid){
	$.ajax({
		type:"post",
		url:"../index.php?s=Api/savetbuser",
		data:{'nickname':nickname,'imageurl':imageurl,'openid':openid,'leadid':leadid},
		dataType : "json",			
		success : function(data){
			localStorage.setItem("nickname",nickname);
     		localStorage.setItem("imageurl",imageurl);
     		localStorage.setItem("openid",openid);
     		localStorage.setItem("leadid",leadid);
		}
	});
}

function dologout(){
	localStorage.clear();
}