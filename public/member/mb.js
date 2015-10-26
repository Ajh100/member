function checkform(){
	if($('#nickname').val() == ''){
		alert('姓名不能为空');
		$('#nickname').focus();
		return false;
	}
	if($('#zhiwei').val() == ''){
		alert('职位不能为空');
		$('#zhiwei').focus();
		return false;
	}
	if($('#telphone').val() == ''){
		alert('手机号码不能为空');
		$('#telphone').focus();
		return false;
	}
	var reg_tel = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
	if(!reg_tel.test($('#telphone').val())){
		alert('手机号码格式不正确');
		$('#telphone').focus();
		return false;
	}
	if($('#account').val() == ''){
		alert('账号不能为空');
		$('#account').focus();
		return false;
	}
	var reg_user = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	if(!reg_user.test($('#account').val())){
		alert('账号格式为邮箱');
		$('#account').focus();
		return false;
	}
	if($('#pass').val() == ''){
		alert('密码不能为空');
		$('#pass').focus();
		return false;
	}
	if($('#faceval').val() == ''){
		alert('请上传头像');
		$('#faceval').focus();
		return false;
	}
	return true;	
}

//文件上传
function Upload(sendUrl, repath) {
	$("#form1").ajaxSubmit({
		beforeSubmit: function(formData, jqForm, options){
			$(".faceupload").addClass("loading-upload");				
		},
		success: function(data, textStatus) {
			if (data.msg == 1) {
				//$("#"+repath).val(data.msgbox);
				//$(".faceupload").removeClass("loading-upload");
				$('#faceval').val(data.msgbox);
				$("#faceupload .box").find('img').remove();
				$("#faceupload .box").append('<img src="/uploads'+ data.msgbox +'" />');
			} else {
				alert(data.msgbox);
			}
			
			//$("#"+repath).nextAll(".files").eq(0).show();
			//$("#"+repath).nextAll(".uploading").eq(0).hide();
		},
		error: function(data, status, e) {
			$(".faceupload").addClass("faceupload");
			alert("上传失败，错误信息：" + e);
		},
		url: sendUrl,
		dataType: "json",
		type: "post",
		timeout: 600000
	});
}