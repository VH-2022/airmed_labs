
$("#chngPwd").change(function(){
	if($(this).is(":checked")) {
		$("#change_div").css("display","block");
	}else {
		$("#change_div").css("display","none");
	}
	
});