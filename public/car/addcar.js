var DragImage = {
	
	imgListCount: 0,
	currentEditIndex: 0,
	
	addImage:function(sname) {
		$('#div_prompt').hide();
		var snames = sname.split('.');
		var src = WEB_IMG_URL + sname;
		DragImage.imgListCount = $('#CarImgsUl li').length;
		DragImage.imgListCount += 1;
		
		str = '<li id="imageLi' + DragImage.imgListCount + '"><dl><dt class="click_cover"><img width="120" height="90" src="'+ src +'" /></dt><dd style="display:block;"><a href="javascript:void(0);" onclick="DragImage.editImageLi(\''+ src +'\',' + DragImage.imgListCount + ')">编辑</a></dd><dd style="display:block;"><a href="javascript:void(0);" onclick="DragImage.delImageLi(' + DragImage.imgListCount + ')">删除</a></dd></dl><input name="filelist[]" type="hidden" value="'+ sname +'" /></li>';
		
		$('#CarImgsUl').append(str);
	},
	

	/* 删除图片 */
	delImageLi:function(imageIndex){
		if(confirm('确定删除图片？')){
			var curObj = $('#CarImgsUl #imageLi' + imageIndex),
			picName = curObj.find('img').attr('src');
			$.ajax({
				type: 'GET',
				dataType: 'text',
				url: '/ajax/ajax_delimg',
				data: {'picname': picName},
				success: function (data) {
					curObj.remove();	
				}
			});
		}
		return;
	},	
	
	
	editImageLi:function(f, a){
		DragImage.currentEditIndex = a;
		var c = {
			app: "02",
			callback: "api",
			backurl: "",
			image_url: f,
			upload_url: "/upload/meituupload",
			id: f,
			meituUploadImgsResponseFun: "DragImage.meituUploadImgsResponse",
			closePhotoEditorFun: "DragImage.closePhotoEditor"
		},
			d = {
				menu: "false",
				scale: "noScale",
				allowFullscreen: "true",
				allowScriptAccess: "always",
				bgcolor: "#ffffff",
				wmode: "window"
			},
			b = {
				id: "PhotoEditor"
			},
			e = "/public/car/ImageEditor.swf?v=20140818";
		swfobject.embedSWF(e, "flashEditorContent", "800", "550", "10.0.0", "/public/swfobject/expressInstall.swf", c, d, b);
		$('.mydivbox').show();
		$('.bgpopo').show();
	},
	meituUploadImgsResponse:function (a, h){
		var b = eval("(" + h + ")");
		if(b.msg == 1){
			var cImageA = $('#imageLi' + DragImage.currentEditIndex);
			var srcolder = cImageA.find('dl>dt>img').attr('src');
			var imgUrl =  WEB_IMG_URL + b.msgbox.substr(9);
			cImageA.find('dl>dt>img').attr('src', imgUrl);
			cImageA.find('input').val(b.msgbox.substr(9));
			cImageA.find('dl>dd').eq(0).html('<a href="javascript:void(0);" onclick="DragImage.editImageLi(\''+ imgUrl +'\',' + DragImage.currentEditIndex + ')">编辑</a>');
		
		}else{

			alert('编辑失败');	
		}
		DragImage.closePhotoEditor();
	},
	closePhotoEditor:function (){
		$('.mydivbox').hide();
		$('.bgpopo').hide();
		DragImage.currentEditIndex = 0;
		$(".mydivbox").html('<div id="flashEditorContent"></div>');
	}
};


var SlectMenu = {
	
	init:function(){
		$(document).bind('click',function(){ 
			$('.dropdownUlList').hide(); 
		}); 

		$(".input_select").click(function(e){
			e.stopPropagation();
			$('.dropdownUlList').hide(); 
			var ul = $(this).next('.dropdown ul'); 
			if(ul.css("display")=="none"){ 
				ul.show();
			}else{ 
				ul.hide(); 
			} 
		});
		
		/*
		$.ajax({ 
			url: "/ajax/ajax_brand/",
			cache: true,
			success: function(data){ 
				var str = '';
				var mydata = eval("(" + data + ")");
				for(var i=0; i<mydata.length; i++){
					str += "<li><a href=\"javascript:void(0);\" onclick=\"SlectMenu.brand_click('"+ mydata[i].title +"',"+ mydata[i].id +");\">"+ mydata[i].letter +" "+ mydata[i].title +"</a></li>";
				}
				$('#brand_div').html(str);
			}
		});		
		*/
		if($('#brand_value').val() !== ''){
			$.ajax({ 
				url: "/ajax/ajax_series", 
				data: {id:$('#brand_value').val()},
				cache: true,
				success: function(data, textStatus){
					$('#series_div').html('');
					$('#series_div').html(data);
				}
			});				
		};
		
		if($('#series_value').val() !== ''){
			$.ajax({ 
				url: "/ajax/ajax_carclass", 
				data: {id:$('#series_value').val(),brand_id:$('#brand_value').val()},
				cache: true,
				success: function(data, textStatus){
					$('#class_div').html('');
					$('#class_div').html(data);
				}
			});				
		};
		
		$('.zhijian').toggle(
			function () {
			  $('#quality').slideDown();
			},
			function () {
			  $('#quality').slideUp();
			}
		);
	},
	
	brand_click:function (title, id){
		$('#brand_div').hide();
		$('#brand_title').val(title);
		$('#brand_value').val(id);
		$('#series_title').val('请选择车系').next('.dropdown ul').hide().html('');
		$('#class_title').val('请选择车型').next('.dropdown ul').hide().html('');
		$.ajax({ 
			url: "/ajax/ajax_series", 
			data: {id:id},
			cache: false,
			success: function(data, textStatus){
				$('#series_div').html('');
				$('#series_div').html(data).show();
			}
		});	
	},
	
	series_click:function(title, id, obj){
        var brand_id = $('#brand_value:input').val();
		$('#series_div').hide();
		$('#series_title').val(title);
		$('#series_value').val(id);
		$('#class_title').val('请选择车型').next('.dropdown ul').hide().html('');
		$.ajax({ 
			url: "/ajax/ajax_carclass", 
			data: {brand_id:brand_id, id:id},
			cache: false,
			success: function(data, textStatus){
				$('#class_div').html('');
				$('#class_div').html(data).show();
			}
		});	
	},
	
	class_click:function(title, id){
		$('#class_div').hide();
		$('#class_title').val(title);
		$('#class_value').val(id);	
	},
	
	saleman_click:function(uid,name){
		$('#saleman').val(name);
		$('#saleman_id').val(uid);
		$('#saleman_div').hide();
	},
	//上牌时间
	regtime_click:function(value){
		$('#regtime_title').val(value);
		$('#regtime_div').hide();
		$('#regtimedate_checkclick').attr('class','');
		$('#regtimedate_checkclick').addClass('checkclick');
	},
	
	regtimedate_click:function(value){
		$('#regtimedate_title').val(value);
		$('#regtimedate_div').hide();
		$('#regtimedate_checkclick').attr('class','');
		$('#regtimedate_checkclick').addClass('checkclick');
	},
	
	//年检到期
	inspeyear_click:function(value){
		$('#inspeyear_title').val(value);
		$('#inspeyear_div').hide();
		$('#inspeyear_checkclick').attr('class','');
		$('#inspeyear_checkclick').addClass('checkclick');
	},
	
	inspemonth_click:function(value){
		$('#inspemonth_title').val(value);
		$('#inspemonth_div').hide();
		$('#inspeyear_checkclick').attr('class','');
		$('#inspeyear_checkclick').addClass('checkclick');
	},
	
	//交强险到期
	inuyear_click:function(value){
		$('#inuyear_title').val(value);
		$('#inuyear_div').hide();
		$('#inuyear_checkclick').attr('class','');
		$('#inuyear_checkclick').addClass('checkclick');
	},
	
	inumonth_click:function(value){
		$('#inumonth_title').val(value);
		$('#inumonth_div').hide();
		$('#inuyear_checkclick').attr('class','');
		$('#inuyear_checkclick').addClass('checkclick');
	},
	
	
	/* 选择车体颜色 */
	SelectColor:function(value) {
		$('#color_id').val(value);
		$("#colorall").find('.click').attr('class', '');
		$("#color" + value).attr("class", "click");
	},	
	
	
	SelectShangPai:function(obj) {
		$('#regtime_title').val('年份');
		$('#regtimedate_title').val('日期');
		$(obj).attr('class', '');
		$(obj).attr("class", "checkclickon");
	},	
	
	SelectInspeyear:function(obj) {
		$('#inspeyear_title').val('年份');
		$('#inspemonth_title').val('日期');
		$(obj).attr('class', '');
		$(obj).attr("class", "checkclickon");
	},
	
	SelectInuyear:function(obj) {
		$('#inuyear_title').val('年份');
		$('#inumonth_title').val('日期');
		$(obj).attr('class', '');
		$(obj).attr("class", "checkclickon");
	}
};

var CarValidate = {
	patrn:{
		'telphone':/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/,
		'peopel':/^\s*[\u4e00-\u9fa5]{1,}[\u4e00-\u9fa5.·]{0,15}[\u4e00-\u9fa5]{1,}\s*$/,
		'price':/^(d*.d{0,2}|d+).*$/,
		'mileage':/^(([1-9][0-9]{0,1})|([0-9]{1,2}\.[0-9]{1,2}))$/
	},
	validate:function(){
		//验证品牌
		if($('#brand_title').val() == '请选择品牌'){
			alert('请选择品牌');
			$('#brand_title').focus().click();
			return;
		}
		//验证车系
		if($('#series_title').val() == '请选择车系'){
			alert('请选择车系');
			$('#series_title').focus().click();
			return;
		}
		//验证车型
		if($('#class_title').val() == '请选择车型'){
			alert('请选择车型');
			$('#class_title').focus().click();
			return;
		}
		//验证上牌时间
		if($('#regtime_title').val() != '年份' && $('#regtimedate_title').val() == '日期'){
			alert('上牌时间');
			$('#regtimedate_title').focus();
			return;
		}
		if($('#regtime_title').val() == '年份' && $('#regtimedate_title').val() != '日期'){
			alert('上牌时间');
			$('#regtime_title').focus();
			return;
		}
		//验证行驶公里
		if($('#mileage').val() == ''){
			alert('行驶里程不能为空');
			$('#mileage').focus();
			return;
		}else if(!CarValidate.patrn['mileage'].exec($('#mileage').val())){
			alert('行驶里程格式不正确，最多为2位整数，2位小数');
			$('#mileage').focus();
			return;
		}
		//验证年间到期
		if($('#inspeyear_title').val() != '年份' && $('#inspemonth_title').val() == '日期'){
			alert('请选择年检到期时间');
			$('#regtimedate_title').focus();
			return;
		}
		if($('#inspemonth_title').val() == '年份' && $('#inspeyear_title').val() != '日期'){
			alert('请选择年检到期时间');
			$('#inspemonth_title').focus();
			return;
		}
		//验证交强险
		if($('#inuyear_title').val() != '年份' && $('#inumonth_title').val() == '日期'){
			alert('请选择交强险到期时间');
			$('#inuyear_title').focus();
			return;
		}
		if($('#inuyear_title').val() == '年份' && $('#inumonth_title').val() != '日期'){
			alert('请选择交强险到期时间');
			$('#inumonth_title').focus();
			return;
		}
		//验证价格
		if($('#price').val() == ''){
			alert('请输入售价');
			$('#price').focus();
			return;
		}else if(!CarValidate.patrn['price'].exec($('#price').val())){
			alert('请输入正确的价格');
			$('#price').focus();
			return;		
		}
		//验证价格
		if($('#shoufu').val() !== '' && !CarValidate.patrn['price'].exec($('#shoufu').val())){
			alert('请输入正确的价格');
			$('#shoufu').focus();
			return;
		}
		//验证上传图片
		if($('#CarImgsUl').find('li').length == 0){
			alert('请上传图片');
			return;
		}
		//验证车身颜色
		if($('#color_id').val() == ''){
			alert('车身颜色');
			return;
		}
		//验证销售员
		if($('#saleman').val() == ''){
			alert('请选择销售员');
			$('#saleman').focus().click();
			return;
		}
		//验证描述
		if($('#description').val() == ''){
			alert('请输入描述');
			$('#description').focus();
			return;
		}else if($('#description').val().length > 500){
			alert('描述最多500个字符');
			$('#description').focus();
			return;
		}
		
		return true;
	}
};