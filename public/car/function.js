$(function(){ 
	$("#CarImgsUl").dragsort({dragSelector:"li",placeHolderTemplate:"<li class='placeHolder'><div></div></li>"});
	$("#DamageImgsUl").dragsort({dragSelector:"li",placeHolderTemplate:"<li class='placeHolder'><div></div></li>"});

	SlectMenu.init();
	DragDamage.init();
	
	$('#CarSubmit').click(function(){
		if(CarValidate.validate()){
			$('#CarSubmit').attr('disabled','disabled');
			$('#form1').submit();
		}
	});
	
	var maxl=500//总长
	document.onkeyup=function(){
	   var s = $('#description').val().length;
	   $('#description_count').html("已输入："+s+"/"+maxl+" 字符");
	}
});

var swfu,swfu2;
window.onload = function () {
	swfu = new SWFUpload({
		// Backend Settings
		upload_url: "__APP__/upload/ajaxupload",
		post_params: {"PHPSESSID": "5oqnpb0cb6hiqkkamr94o4cmc5"},

		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg",
		file_types_description : "JPG Images",
		file_upload_limit : 0,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		button_image_url : "",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 155,
		button_height: 135,
		button_text : '',
		button_text_style : '',
		button_text_top_padding: 0,
		button_text_left_padding: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		flash_url : "__PUBLIC__/swfupload/swfupload.swf",
		custom_settings : {
			upload_target : "divFileProgressContainer",
			cancelButtonId: "clearUploadImage"
		},
		debug: false
	});
	
	swfu2 = new SWFUpload({
		// Backend Settings
		upload_url: "__APP__/upload/ajaxupload",
		post_params: {"PHPSESSID": "5oqnpb0cb6hiqkkamr94o4cmc5"},

		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg",
		file_types_description : "JPG Images",
		file_upload_limit : 0,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess2,
		upload_complete_handler : uploadComplete,
		button_image_url : "",
		button_placeholder_id : "spanButtonPlaceholder2",
		button_width: 155,
		button_height: 135,
		button_text : '',
		button_text_style : '',
		button_text_top_padding: 0,
		button_text_left_padding: 18,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		flash_url : "__PUBLIC__/swfupload/swfupload.swf",
		custom_settings : {
			upload_target : "divFileProgressContainer2"
		},
		debug: false
	});
}