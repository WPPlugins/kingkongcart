jQuery(document).ready(function(){

jQuery("[name=product_kind]").click(function(){
	if(jQuery(this).val() == "1"){
		jQuery("#download_ul").show();
	} else {
		jQuery("#download_ul").hide();
	}
});

if(jQuery(".kkcart-added-thumbnail").html()){
	jQuery(".kkcart-added-thumbnail").sortable({
		items: '.added_thumbnail_each',
		opacity: 0.6,
		cursor: 'move',
		axis:'y',
		update: function(){
			measure_priority();
		}
	});
}

if(jQuery(".position-change-ul").html()){
	jQuery(".position-change-ul").sortable({
		items: '.position-change-li',
		opacity: 0.6,
		cursor: 'move',
		axis:'y',
		update: function(){
			preview_product_display();
		}
	});
}

jQuery(".kingkong-text-justify > li > img").click(function(){
	jQuery(this).parent().parent().find("li > img").each(function(){
		jQuery(this).css("opacity","0.3");
	})
	jQuery(this).css("opacity","1");

	jQuery(this).parent().parent().find("input").val(jQuery(this).attr("alt"));
	
	var this_class = jQuery(this).parent().parent().find("input").attr("name");
		this_class = this_class.split("-");

		switch(this_class[0]){

			case "title" :
				jQuery(".preview-title").css("text-align",jQuery(this).parent().parent().find("input").val());
			break;

			case "shortdesc" :
				jQuery(".preview-shortdesc").css("text-align", jQuery(this).parent().parent().find("input").val());
			break;

			case "price" :
				jQuery(".preview-price").css("text-align", jQuery(this).parent().parent().find("input").val());
			break;

			case "mileage" :
				jQuery(".preview-mileage").css("text-align", jQuery(this).parent().parent().find("input").val());
			break;

			case "discount" :
				jQuery(".preview-discount").css("text-align", jQuery(this).parent().parent().find("input").val());
			break;

		}
});


jQuery(".product-option-checkbox").click(function(){

		jQuery(".position-change-ul").html("");
		jQuery("#preview-product-display").html("");
		var plugins_url = jQuery(".plugins_url").val();
		//alert(plugins_url);
		var checked_count 		= 0;
		var unchecked_count 	= 0;
		var total_length 		= jQuery(".product-option-checkbox").length;
		var origin_display 		= jQuery("[name=custom-display]").val();
		var title_color 		= jQuery("[name=title-color]").val();
		var price_color 		= jQuery("[name=price-color]").val();
		var mileage_color		= jQuery("[name=mileage-color]").val();
		var shortdesc_color 	= jQuery("[name=shortdesc-color]").val();
		var discount_color		= jQuery("[name=discount-color]").val();
		var title_size			= jQuery("[name=title-size]").val();
		var price_size			= jQuery("[name=price-size]").val();
		var mileage_size		= jQuery("[name=mileage-size]").val();
		var shortdesc_size		= jQuery("[name=shortdesc-size]").val();
		var discount_size		= jQuery("[name=discount-size]").val();

		jQuery(".product-option-checkbox").each(function(i){
		var option_label = jQuery(this).parent().find(".option_label").html();

		//alert(option_label);
		if(jQuery(this).prop("checked") == true){
			if(i == 0){
				jQuery("[name=custom-display]").val(jQuery(this).val()); 
				checked_count++;
			}
			else {
				jQuery("[name=custom-display]").val(jQuery("[name=custom-display]").val()+"-"+jQuery(this).val());
				checked_count++;
			}

			switch(jQuery(this).val()){
				case "thumbnail" :
					var insert_value = '<li class="position-change-li" label="thumbnail"><ul style="position:relative"><li><img src="'+plugins_url+'/files/images/kingkong-noimage.png" style="border-radius:5px"></li></ul></li>';
					var preview_value = '<li><img src="'+plugins_url+'/files/images/kingkong-noimage.png"></li>';
				break;

				case "title" :
					var insert_value = '<li class="position-change-li position-change-box" label="title">'+option_label+'</li>';
					var preview_value = '<li style="text-align:center; font-weight:bold; font-size:'+title_size+'; color:'+title_color+'">멋쟁이 콩돌이 나시</li>';
				break;

				case "price" :
					var insert_value = '<li class="position-change-li position-change-box" label="price">'+option_label+'</li>';
					var preview_value = '<li style="text-align:center;font-weight:bold; font-size:'+price_size+'"><span style="font-family:arial; font-size:16px; color:'+price_color+'">1,800</span>원</li>';
				break;

				case "discount" :
					var insert_value = '<li class="position-change-li position-change-box" label="discount">'+option_label+'</li>';
					var preview_value = '<li style="text-align:center;font-weight:bold; font-size:'+discount_size+'"><span style="font-family:arial; font-size:16px; color:'+discount_color+'; text-decoration:line-through">3,000</span>원</li>';
				break;

				case "mileage" :
					var insert_value = '<li class="position-change-li position-change-box" label="mileage">'+option_label+'</li>';
					var preview_value = '<li style="text-align:center; color:'+mileage_color+'; font-size:'+mileage_size+'">적립금 : 180원</li>';
				break;

				case "shortdesc" :
					var insert_value = '<li class="position-change-li position-change-box" label="shortdesc">'+option_label+'</li>';
					var preview_value = '<li style="color:'+shortdesc_color+'; width:200px; font-size:'+shortdesc_size+'">이태리 장인이 한땀한땀 꿰멜 수 있었던 공장에서 갖나온 콩돌이 나시 입니다.</li>';
				break;

			}
			jQuery(".position-change-ul").append( insert_value );
			jQuery("#preview-product-display").append( preview_value );
		}
		else {

			unchecked_count++;
		}
		//alert(jQuery(this).prop("checked"));
		});
jQuery("[name=option-count]").val(checked_count);

	if( unchecked_count == total_length ){
		alert("최소 한개 이상은 등록되어야 합니다.");
		jQuery(".product-option-checkbox:first").prop("checked",true);
		jQuery(".position-change-ul").append( '<li class="position-change-li" label="thumbnail"><ul style="position:relative"><li><img src="'+plugins_url+'/files/images/kingkong-noimage.png" style="border-radius:5px"></li></ul></li>' );
		jQuery("#preview-product-display").append('<li><img src="'+plugins_url+'/files/images/kingkong-noimage.png"></li>');
		jQuery("[name=custom-display]").val("thumbnail"); 
		jQuery("[name=option-count]").val(1);

	}
});


jQuery("[name^='added_thumb_file_name']").change(function(){

var data = new FormData(jQuery(this));     
jQuery.each(jQuery(this)[0].files, function(i, file) {
	data.append("file", file);
});

var plugins_url = jQuery("[name=plugins_url]").val();
var this_id = jQuery(this).parent().parent().find(".kkcart-added-thumbnail-image").attr("id");

	   jQuery.ajax({
	      type:"POST",
	      url: plugins_url+"/ajax/admin/kkcart_upload_file.php",
	      cache: false,
	      data:data,
	      contentType: false,
	      processData: false,
	      error:function(html){
	         alert("실패하였습니다.");
	      },  
	      success:function(html){
	      	var added_array;
	      	eval(html); // ajax 로 받은 데이터를 자바스크립트로 실행한다.
	      	jQuery("#"+this_id+" > img").attr("src",added_array[0]['imgurl']);
	      } 
	   });	

});
	jQuery(".remove_button").click(function(){
		if( jQuery("[name=thumbnail_count]").val() == 1){
			alert("최소 1개 이상은 등록하셔야 합니다.");
		}
		else {
		jQuery(this).parent().parent().parent().remove();
		measure_priority();
		}
	});


});


function thumb_image_upload(id){
	wp.media.editor.send.attachment = function(props, attachment){
		jQuery(".each_thumb_image_url_"+id).val(attachment.url);
		jQuery("#thumbnail-image-"+id+" > img").attr("src", attachment.url);
		jQuery(".each_thumb_id_"+id).val(attachment.id);
	}
	wp.media.editor.open(this);
	return false;
}


function preview_product_display(){
	jQuery("#preview-product-display").html("");

	var title_color 		= jQuery("[name=title-color]").val();
	var price_color 		= jQuery("[name=price-color]").val();
	var mileage_color 		= jQuery("[name=mileage-color]").val();
	var shortdesc_color 	= jQuery("[name=shortdesc-color]").val();
	var discount_color		= jQuery("[name=discount-color]").val();
	var title_size			= jQuery("[name=title-size]").val();
	var price_size			= jQuery("[name=price-size]").val();
	var mileage_size		= jQuery("[name=mileage-size]").val();
	var shortdesc_size		= jQuery("[name=shortdesc-size]").val();
	var discount_size		= jQuery("[name=discount-size]").val();

	jQuery(".position-change-li").each(function(i){


		if(i == 0){
			jQuery("[name=custom-display]").val(jQuery(this).attr("label")); 
		}
		else {
			jQuery("[name=custom-display]").val(jQuery("[name=custom-display]").val()+"-"+jQuery(this).attr("label"));
		}


		switch(jQuery(this).attr("label")){
			case "thumbnail":
			jQuery("#preview-product-display").append('<li id="display-preview-thumbnail"><img src="'+jQuery(".plugins_url").val()+'/files/images/kingkong-noimage.png"></li>');
			break;

			case "title":
			jQuery("#preview-product-display").append('<li id="display-preview-title" style="text-align:center; font-weight:bold; font-size:'+title_size+'; color:'+title_color+'">멋쟁이 콩돌이 나시</li>');
			break;

			case "shortdesc":
			jQuery("#preview-product-display").append('<li id="display-preview-shortdesc" style="color:'+shortdesc_color+'; width:200px; font-size:'+shortdesc_size+'">이태리 장인이 한땀한땀 꿰멜 수 있었던 공장에서 갖나온 콩돌이 나시 입니다.</li>');
			break;

			case "price":
			jQuery("#preview-product-display").append('<li id="display-preview-price" style="text-align:center;font-weight:bold; font-size:'+price_size+'"><span style="font-family:arial; font-size:16px; color:'+price_color+'">1,800</span>원</li>');
			break;

			case "mileage":
			jQuery("#preview-product-display").append('<li id="display-preview-mileage" style="text-align:center; color:'+mileage_color+'; font-size:'+mileage_size+'">적립금 : 180원</li>');
			break;

			case "discount":
			jQuery("#preview-product-display").append('<li id="display-preview-discount" style="text-align:center; color:'+discount_color+'; font-size:'+discount_size+'; text-decoration:line-through">3,000원</li>');
			break;

		}
	});
}

function measure_priority(){

	var added_thumbnail;

	jQuery("[name=added_thumbnail_list]").val("");

	if( jQuery(".added_thumbnail_each").html() ){
	jQuery(".added_thumbnail_each").each(function(i){

		if(jQuery(this).find("[name=thumbnail_id]").val() != undefined){
			added_thumbnail = jQuery(this).find("[name=thumbnail_id]").val();
			if(i == 0){
				jQuery("[name=added_thumbnail_list]").val(jQuery("[name=added_thumbnail_list]").val()+added_thumbnail);
			}
			else {
				jQuery("[name=added_thumbnail_list]").val(jQuery("[name=added_thumbnail_list]").val()+","+added_thumbnail);
			}
		}


		if(i == 0){
			jQuery(this).find(".added_priority").addClass("main");	
		}
		else {
			jQuery(this).find(".added_priority").removeClass("main");
		}
		jQuery("[name=thumbnail_count]").val(jQuery(".added_thumbnail_each").length );
		jQuery(this).find(".added_priority").html("Priority #"+(i+1));
		jQuery(this).find(".kkcart-added-thumbnail-image").attr("id","thumbnail-image-"+(i+1));
		jQuery(this).find(".button").attr("onclick", "thumb_image_upload("+(i+1)+");");
		jQuery(this).find("#each_thumb").attr("class", "each_thumb_image_url_"+(i+1));
		jQuery(this).find("#each_thumb_id").attr("class", "each_thumb_id_"+(i+1));
	});
	}
	else {
		jQuery("[name=thumbnail_count]").val(0);
	}

	

}

function kkcart_upload_file(plugins_url,id){

var data = new FormData(jQuery('input[name^="added_thumb_file_name"]'));     
jQuery.each(jQuery('input[name^="added_thumb_file_name"]')[0].files, function(i, file) {
	data.append("file", file);
});

	   jQuery.ajax({
	      type:"POST",
	      url: plugins_url+"/ajax/admin/kkcart_upload_file.php",
	      cache: false,
	      data:data,
	      contentType: false,
	      processData: false,
	      error:function(html){
	         alert("실패하였습니다.");
	      },  
	      success:function(html){
	      	var added_array;
	      	eval(html); // ajax 로 받은 데이터를 자바스크립트로 실행한다.
	      	jQuery(".kkcart-added-thumbnail-image > img").attr("src",added_array[0]['imgurl']);
	      }
	   });	
}

function kkcart_add_file(plugins_url){

	jQuery(".kkcart-added-thumbnail").append('<li class="added_thumbnail_each"><ul><li class="kkcart-added-thumbnail-image"><img src="'+plugins_url+'/files/images/kingkong-noimage-gray.png" style="width:80px; height:80px; padding:10px 10px; background:#fff; border:1px solid #e0e0e0"></li><li class="kkcart-added-thumbnail-filename"><div class="added_priority"></div><button type="button" class="button button-primary" style="margin-top:10px" onclick="thumb_image_upload(1);">이미지 업로드</button><input type="hidden" name="added_thumb_file_name[]" id="each_thumb" class="each_thumb_image_url_1" style="margin-top:10px; margin-left:10px"><input type="hidden" name="added_thumb_id[]" id="each_thumb_id"></li><li class="kkcart-added-thumbnail-remove"><img src="'+plugins_url+'/files/images/icon-close.png" class="remove_button"></li></ul></li>');

	measure_priority();

	jQuery(".remove_button").click(function(){
		if( jQuery("[name=thumbnail_count]").val() == 1){
			alert("최소 1개 이상은 등록하셔야 합니다.");
		}
		else {
		jQuery(this).parent().parent().parent().remove();
		measure_priority();
		}
	});


jQuery("[name^='added_thumb_file_name']").change(function(){

var data = new FormData(jQuery(this));     
jQuery.each(jQuery(this)[0].files, function(i, file) {
	data.append("file", file);
});

var plugins_url = jQuery("[name=plugins_url]").val();
var this_id = jQuery(this).parent().parent().find(".kkcart-added-thumbnail-image").attr("id");

	   jQuery.ajax({
	      type:"POST",
	      url: plugins_url+"/ajax/admin/kkcart_upload_file.php",
	      cache: false,
	      data:data,
	      contentType: false,
	      processData: false,
	      error:function(html){
	         alert("실패하였습니다.");
	      },  
	      success:function(html){
	      	var added_array;
	      	eval(html); // ajax 로 받은 데이터를 자바스크립트로 실행한다.
	      	jQuery("#"+this_id+" > img").attr("src",added_array[0]['imgurl']);
	      }
	   });	

});


}

function input_complete_price(){ //소비자 판매가격 input 이벤트
   if( jQuery("input[name=origin_price]").val().length > 0 ){
   jQuery("input[name=discount]").prop('disabled',false);
   jQuery("input[name=discount_price]").prop('disabled',false);
   jQuery("input[name=mileage]").prop('disabled',false);
   jQuery("input[name=mileage_price]").prop('disabled',false);
   }
   else {
   jQuery("input[name=discount]").prop('disabled',true);
   jQuery("input[name=discount_price]").prop('disabled',true);
   jQuery("input[name=mileage]").prop('disabled',true);
   jQuery("input[name=mileage_price]").prop('disabled',true);
   }
}



function calculate_DistoPrc(){ // 입력된 할인율을 할인가격으로 계산 버튼 클릭시 이벤트
   jQuery("input[name=mileage]").val("");
   jQuery("input[name=mileage_price]").val("");
   var origin_price = jQuery("input[name=origin_price]").val();
   var discount = jQuery("input[name=discount]").val();
   var discount_price;
   if(origin_price){
      discount_price = origin_price * (discount/100);
      var Mathround_price = Math.round(discount_price);
      jQuery("input[name=discount_price]").val(Mathround_price);
      jQuery("input[name=results_price]").val(origin_price - jQuery("input[name=discount_price]").val());
      jQuery("input[name=results_price]").prop('disabled',false);
   }
}

function calculate_PrctoDis(){ // 입력된 할인 가격을 할인율로 계산 버튼 클릭시 이벤트
   jQuery("input[name=mileage]").val("");
   jQuery("input[name=mileage_price]").val("");
   var origin_price = jQuery("input[name=origin_price]").val();
   var discount_price = jQuery("input[name=discount_price]").val();
   var discount;
   if(origin_price){
      discount = (discount_price / origin_price) * 100;
      var Mathround_discount = discount.toFixed(1);
      
      jQuery("input[name=discount]").val(Mathround_discount);
      jQuery("input[name=results_price]").val(origin_price - jQuery("input[name=discount_price]").val());
      jQuery("input[name=results_price]").prop('disabled',false);
   }
}

function calculate_Mileage(){ // 적립금(마일리지) 계산 이벤트
   var origin_price = jQuery("input[name=origin_price]").val();
   var results_price = jQuery("input[name=results_price]").val();
   var mileage = jQuery("input[name=mileage]").val();
   var result;
   var mileage_result;

   if( results_price ){
      result = results_price * (mileage / 100);
   }
   else {
      result = origin_price * (mileage / 100 );
   }

   mileage_result = parseInt(result);

   jQuery("input[name=mileage_price]").val(mileage_result);
}

function input_complete_option(){ //옵션명 이벤트
   var input_option = jQuery("input[name=input_option]").val();

   var option_name;
   var option_length; 

      if(!input_option){
         alert("옵션명을 입력하시기 바랍니다.");
      }
      else {
         //jQuery(".display_option").html(""); // 옵션 테이블 초기화
         var last_index;
         var last_tr = jQuery(".display_option > #input_first_option:last").attr("class");
         	if(last_tr){
         	 last_tr = last_tr.split("_");
         	 last_index = last_tr[1];
         	 last_index = (last_index*1) + 1;
         	} else {
         		jQuery(".display_option").html("");
         		last_index = 0;
         	}

         option_name = input_option.split(",");
         option_length = option_name.length + last_index;
         jQuery("input[name=kkcart_total_number]").val(option_length);
         for (var i = last_index; i < option_length; i++) {
            jQuery(".display_option").append("<tr style='background:#e8e8e8; text-align:center' name='first_option' class='option_"+i+"'><td>"+(i+1)+"</td><td><input type='text' value='"+option_name[i - last_index]+"' name='kkcart_option_name_"+i+"'></td><td><input type='text' name='kkcart_plus_price_"+i+"' value='0'></td><td><input type='text' name='kkcart_total_amount_"+i+"' value='9999'></td><td><select name='kkcart_option_status_"+i+"'><option value='2'>판매중</option><option value='1'>일시품절</option><option value='0'>품절</option></select></td><td><input type='button' class='button button-primary' value='추가' onclick=\"add_second_option("+i+",'"+option_name[i - last_index]+"');\"><input type='hidden' name='second_option_"+i+"_length' class='second_option_"+i+"_numbering' value='0'></td></tr>");
         };
      }
}

function remove_all_added_option(){
	jQuery(".display_option").html("<tr style='background:#e8e8e8; text-align:center'><td colspan='6' style='height:50px'>옵션명을 먼저 입력 해 주세요</td></tr>");
}

function remove_second_option(option_id,option_num){
   jQuery(".second_option_tr_"+option_id+"_"+option_num).remove();
   var prev_number = jQuery(".second_option_"+option_id+"_numbering").val();
       prev_number = prev_number * 1;

   jQuery(".second_option_"+option_id+"_numbering").val(prev_number - 1);
}


function add_second_option(option_id,option_name){

   var numbering = jQuery(".second_option_"+option_id+"_numbering").val();
       numbering = (numbering * 1) + 1;

   jQuery(".option_"+option_id).after("<tr style='background:#f4e7e7; text-align:center' class='second_option_tr_"+option_id+"_"+numbering+" option_"+option_id+"_second second_option'><td>보조</td><td><input type='text' name='kkcart_second_option_"+option_id+"_"+numbering+"'></td><td><input type='text' name='kkcart_second_price_"+option_id+"_"+numbering+"'></td><td><input type='text' name='kkcart_second_amount_"+option_id+"_"+numbering+"'></td><td><select name='kkcart_second_option_status_"+option_id+"_"+numbering+"'><option value='2'>판매중</option><option value='1'>일시품절</option><option value='0'>품절</option></select></td><td><input type='button' class='button' value='삭제' onclick='remove_second_option("+option_id+","+numbering+");'></td></tr>");
   jQuery(".second_option_"+option_id+"_numbering").val(numbering);
}


function change_text_size(kind,value){
	switch(kind){

		case "title" :
			jQuery(".preview-title").css("font-size", value);
		break;

		case "mileage" :
			jQuery(".preview-mileage").css("font-size", value);
		break;

		case "price" :
			jQuery(".preview-price").css("font-size", value);
		break;

		case "shortdesc" :
			jQuery(".preview-shortdesc").css("font-size", value);
		break;

		case "discount" :
			jQuery(".preview-discount").css("font-size", value);
		break;
	}
}


function status_change(id, status){
	var data = {
		'action': 'order_status_change',
		'id' : id,
		'status' : status,
		'company' : jQuery("[name=shipping_company]").val(),
		'account' : jQuery("[name=shipping_account]").val()
	};

  	jQuery.post(ajaxurl, data, function(response) {
  		location.href = response;
 	});
}


function open_modal_insert_csv(){
	jQuery(".csv_modal").show();
}

function csv_modal_close(){
	jQuery(".csv_modal").hide();
}


function complete_csv_import(){
	alert('정상적으로 일괄배송등록 처리 되었습니다.');
	jQuery(".csv_modal").hide();
	location.reload();
}

function cancle_order(id){

	if(confirm("취소하시면 해당 주문은 영구히 삭제됩니다.\n삭제하시겠습니까?") === true){

		var data = {
			'action': 'order_cancle',
			'id' : id
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		location.href = response;
	 	});

	}
}


function insert_order_mileage(user_id, id){

		var data = {
			'action': 'insert_order_mileage',
			'user_id' : user_id,
			'id' : id
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		location.reload();
	 	});

}

function search_this_month_balance(){
	search_balance_form.submit();
}


function admin_board_reply(id){

		var data = {
			'action': 'admin_board_reply',
			'reply' : jQuery("[name=reply]").val(),
			'id' : id
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		if(response == "1"){
	  			alert("정상적으로 답변이 등록되었습니다.");
	  		} else {
	  			alert("답변 등록에 실패 하였습니다.");
	  		}
	 	});
}


function remove_board_content(id){

	if(confirm("해당 글을 삭제하시겠습니까?") == true){

		var data = {
			'action' : 'remove_board_content',
			'id' : id
		};

	  	jQuery.post(ajaxurl, data, function(response) {
	  		if(response == "1"){
	  			alert("정상적으로 삭제 되었습니다.");
	  			location.href='admin.php?page=kkcart_board';
	  		}
	 	});
	}
}




