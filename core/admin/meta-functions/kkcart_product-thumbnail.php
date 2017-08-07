<?php

/* 상품등록 썸네일 메타필드 html ***************************************************************/

function kkcart_product_meta_thumnail(){

	global $post;
	global $wpdb;

	$info = unserialize(get_post_meta($post->ID, 'kingkongcart-product-info', true));

	$original_price 	= $info[0];	// 소비자 판매가격
	$results_price 		= $info[1];	// 할인 적용 판매가격
	$discount_rate 		= $info[2];	// 할인율 %
	$discount_price		= $info[3];	// 할인가격
	$mileage_rate		= $info[4];	// 적립금(마일리지) %
	$mileage_price		= $info[5];	// 적립금(마일리지) 원(점)
	$short_desc			= $info[6];	// 짤막소개
	$provide_price		= $info[7];	// 공급가(vat포함)
	$product_kind 		= $info[8]; // 상품종류 (배송상품 or 다운로드상품)
	if($product_kind == "1"){
		$product_demo_url	= $info[9]; // 상품파일 데모 사이트 URL
		$product_files 		= get_post_meta($post->ID, "kingkong_download", true);
		$download_checked 	= "checked";
	} else {
		$shipping_checked 	= "checked";
	}
?>
<div>
	<ul>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">상품종류</span></li>
		<li class="h3-desc">
			해당 상품이 다운로드 상품인지 배송상품인지 선택하시기 바랍니다.
		</li>
		</h3>
	</ul>
	<ul>
		<li>
			<input type="radio" name="product_kind" value="0" <?php echo $shipping_checked;?> > 배송상품 <input type="radio" name="product_kind" value="1" <?php echo $download_checked;?> > 다운로드상품
		</li>
	</ul>
<?php
	if($product_kind == "1"){
?>
		<ul id="download_ul" style="display:block">
<?php
	} else {
?>
		<ul id="download_ul">
<?php
	}
?>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">상품파일 업로드</span></li>
		<li class="h3-desc">
			업로드할 상품 파일을 선택해 주세요. (여러파일이라면 압축하여 파일 하나로 올려주시기 바랍니다.)
		</li>
		</h3>
		<li style="margin-top:10px"><input type="file" value="파일 업로드" name="download_file"> 등록된 파일:
<?php
	if($product_files){
		echo $product_files;
	} else {
		echo "없음";
	}
?>
		</li>
	</ul>
<?php
	if($product_kind == "1"){
?>
		<ul id="download_ul" style="display:block">
<?php
	} else {
?>
		<ul id="download_ul">
<?php
	}
?>
	
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">데모사이트 링크</span></li>
		<li class="h3-desc">
			데모를 확인할 사이트가 있다면 입력 해 주시기 바랍니다.
		</li>
		</h3>
		<li style="margin-top:10px">
			<input type="text" style="width:100%" name="demo_site" value="<?php echo $product_demo_url;?>">
		</li>
	</ul>
	<ul>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">짤막소개</span></li>
		<li class="h3-desc">
			해당상품에 대한 짤막한 소갯글 입니다. 대쉬보드 설정에서 상품페이지 노출 여부를 설정 할 수 있습니다.
		</li>
		</h3>
	</ul>
	<ul>
		<li><textarea style="width:100%; height:80px" name="short_desc"><?php echo $short_desc;?></textarea></li>
	</ul>
	<ul>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">썸네일 설정</span></li>
		<li class="h3-desc">
			등록된 썸네일은 마우스로 드래그하시면 순번이 변경 됩니다. 발행하기(Publish) 버튼을 눌러야 썸네일이 저장 됩니다.
		</li>
		</h3>
	</ul>
<?php

	$added_thumbnail_ids = get_post_meta($post->ID, 'kingkongcart_added_thumbnail_id', true);

	$each_thumb_id = unserialize($added_thumbnail_ids);

	if($added_thumbnail_ids){

	$args = array(
		'post_type'   => 'attachment',
		'numberposts' => -1,
		'post_status' => 'any',
		'post__in' => $each_thumb_id,
		'orderby' => 'post__in'
		);

	$attachments = get_posts($args);

	} else {

		//$attachments = "";

	}

?>
<?php if ( count($attachments) > 1){
?>
	
	<input type="hidden" name="thumbnail_count" value="<?php echo count($attachments);?>">

<?php	
}
else{
?>
	<input type="hidden" name="thumbnail_count" value="1">
<?php
}
?>
	
	<input type="hidden" name="plugins_url" value="<?php echo KINGKONGCART_PLUGINS_URL;?>">
	<input type="hidden" name="added_thumbnail_list" value="<?php echo $all_thumb_id;?>">
	<ul class="upload-files">
		<li class="upload-files-button"><input type="button" class="button" value="추가하기" onclick="kkcart_add_file('<?php echo KINGKONGCART_PLUGINS_URL;?>');"></li>
	</ul>

	<ul class="kkcart-added-thumbnail">
<?php
	$cnt = 1;
	if($attachments){
		
		foreach ($attachments as $attachment){
			$thumbnail_images = wp_get_attachment_image_src( $attachment->ID);
			if($cnt == 1){
				$priority = " main";
			}
			else {
				$priority = "";
			}
?>
		<li class="added_thumbnail_each">
			<ul>
				<li class="kkcart-added-thumbnail-image" id="thumbnail-image-<?php echo $cnt;?>">
					<img src="<?php echo $thumbnail_images[0];?>" style="width:80px; height:80px; padding:10px 10px; background:#fff; border:1px solid #e0e0e0">
				</li>
				<li class="kkcart-added-thumbnail-filename">
					<div class="added_priority <?php echo $priority;?>">Priority #<?php echo $cnt;?></div>
					<button type="button" class="button button-primary" style="margin-top:10px" onclick="thumb_image_upload(<?php echo $cnt;?>);">이미지 업로드</button>
					<input type="hidden" name="added_thumb_file_name[]" id="each_thumb" class="each_thumb_image_url_<?php echo $cnt;?>" style="margin-top:10px; margin-left:10px">
					<input type="hidden" name="added_thumb_id[]" id="each_thumb_id" class="each_thumb_id_<?php echo $cnt;?>" value="<?php echo $attachment->ID;?>">
					<div class="added_path">
						
					</div>
				</li>
				<li class="kkcart-added-thumbnail-remove">
					<img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/icon-close.png" class="remove_button">
					<input type="hidden" name="thumbnail_id" value="<?php echo $attachment->ID;?>">
				</li>
			</ul>
		</li>
<?php
		$cnt++;

		}
	}
	else {	
?>
		<li class="added_thumbnail_each">
			<ul>
				<li class="kkcart-added-thumbnail-image" id="thumbnail-image-1">
					<img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-noimage-gray.png" style="width:80px; height:80px; padding:10px 10px; background:#fff; border:1px solid #e0e0e0">
				</li>
				<li class="kkcart-added-thumbnail-filename">
					<div class="added_priority main">Priority #1</div>
					<!--<input type="file" name="added_thumb_file_name[]" style="font-size:12px; color:gray; position:relative; top:10px" multiple/>-->
					<button type="button" class="button button-primary" style="margin-top:10px" onclick="thumb_image_upload(1);">이미지 업로드</button>
					<input type="hidden" name="added_thumb_file_name[]" id="each_thumb" class="each_thumb_image_url_1" style="margin-top:10px; margin-left:10px">
					<input type="hidden" name="added_thumb_id[]" id="each_thumb_id" class="each_thumb_id_<?php echo $cnt;?>">
					<div class="added_path">
						
					</div>
				</li>
				<li class="kkcart-added-thumbnail-remove">
					<img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/icon-close.png" class="remove_button">
				</li>
			</ul>
		</li>
<?php
}
?>
	</ul>

	<ul>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">판매가격 설정</span></li>
		<li class="h3-desc">콤마(,) 를 제외한 숫자로만 기입하시기 바랍니다. 할인 적용판매가격은 할인 설정이 기입되면 자동으로 계산됩니다.<br>공급가는 부가세가 포함된 금액을 입력하여주시고, 이는 정산관리 부가세관리 및 매출 판단의 근거가 되오니 정확하게 기입하시기 바랍니다.</li>
		</h3>
	</ul>
	<ul style="margin-left:14px;">
		<li style="float:left; padding-top:5px; padding-right:5px"><b>공급가(VAT 포함) : </b></li>
		<li style="float:left;"><input type="text" name="provide_price" value="<?php echo $provide_price;?>"></li>
	</ul>
	<ul style="margin-left:14px; clear:both; margin-top:20px">
		<li style="float:left; padding-top:5px; padding-right:5px">소비자 판매가격 :</li>
		<li style="float:left"><input type="text" name="origin_price" onkeyup="input_complete_price();" value="<?php echo $original_price;?>"></li>
		<li style="float:left; padding:5px 5px;">할인 적용 판매가격 :</li>
		<li><input type="text" name="results_price" value="<?php echo $results_price;?>" readonly></li>
	</ul>

	<ul>
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">할인 설정</span></li>
		<li class="h3-desc">
			소비자 판매가격에 할인율을 적용하거나 적립금 등을 설정할 수 있습니다. 가격정보는 콤마(,)를 제외한 숫자로만 기입하시기 바랍니다.<br>
			소비자 판매가격을 변경하시면 할인율의 계산 버튼을 눌러야 할인 적용 판매가격이 변경되오니 유의하시기 바랍니다.
		</li>
		</h3>
	</ul>

	<ul style="margin-left:14px">
		<li class="kkcart-added-price-li">할인율 :</li>
		<li style="float:left; position:relative; top:15px; margin-right:10px">
<?php
	if($discount_rate != ""){
?>
			<input type="text" style="width:36px" name="discount" value="<?php echo $discount_rate;?>">%
<?php
	} else {
?>
			<input type="text" style="width:36px" name="discount" disabled="true" value="<?php echo $discount_rate;?>">%
<?php
	}
?>
			
		</li>
		<li style="float:left; margin-right:10px">
			<div>
				<ul>
					<li onclick="calculate_DistoPrc();"><a class="button">계산 ></a></li>
					<li onclick="calculate_PrctoDis();"><a class="button">< 계산</a></li>
				</ul>
			</div>
		</li>
		<li style="float:left; position:relative; top:15px; margin-right:30px;">
			<input type="text" name="discount_price" style="width:150px; text-align:right" value="<?php echo $discount_price;?>">원
		</li>
		<li class="kkcart-added-price-li">적립금(마일리지)</li>
		<li style="float:left; position:relative; top:15px; margin-right:10px">
<?php
	if($mileage_rate != ""){
?>
			<input type="text" name="mileage" style="width:36px" value="<?php echo $mileage_rate;?>">%
<?php
	} else {
?>
			<input type="text" name="mileage" disabled="true" style="width:36px" value="<?php echo $mileage_rate;?>">%
<?php
	}
?>
			
		</li>
		<li style="float:left; position:relative; top:15px; margin-right:10px" onclick="calculate_Mileage();"><a class="button">계산 ></a></li>
		<li style="float:left;">
			<div style="position:relative; top:15px"><input type="text" name="mileage_price" style="width:150px; text-align:right;" value="<?php echo $mileage_price;?>">원(점)</div>
		</li>
	</ul>

	<ul style="clear:both">
		<h3>
		<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:22px; height:auto"><span style="position:relative; top:-5px; left:10px">옵션 설정</span></li>
		<li class="h3-desc">옵션명을 콤마(,)로 구분하여 입력 해 주시기 바랍니다.<br>2차옵션이 있다면 1차 옵션의 재고수량은 자동으로 계산됩니다. (입력하셔도 자동으로 계산되어 변경됩니다.)</li>
		</h3>
	</ul>
<?php

	$added_options = get_post_meta($post->ID, 'kingkongcart-product-option', true);
	$each_options = unserialize($added_options);	

?>
	<ul>
		<li style="float:left; position:relative; top:5px; margin-right:10px;">필수 옵션명 입력</li>
		<li style="float:left; margin-right:10px"><input type="text" style="width:400px;" name="input_option" value="<?php echo $option_names;?>"></li>
		<li style="float:left; margin-right:10px"><a class="button" onclick="input_complete_option();">추가/수정</a> <a class="button" onclick="remove_all_added_option();">초기화</a></li>
		<li style="float:left; position:relative; top:5px"><span style="color:red">ex)블랙,화이트,블루 등 콤마(,)로 구분됩니다.</span><input type="hidden" name="kkcart_total_number" value="<?php echo count($each_options); ?>"></li>
		<li style="clear:both; margin-top:10px">
			<table style="width:100%;">
				<thead>
					<tr style="text-align:center; background:#cecece;">
						<th style="padding:5px 5px">구분</th>
						<th style="padding:5px 5px">옵션명</th>
						<th style="padding:5px 5px">추가금액</th>
						<th style="padding:5px 5px">재고수량</th>
						<th style="padding:5px 5px">처리형태</th>
                        <th style="padding:5px 5px">하위옵션</th>
					</tr>
				</thead>
				<tbody class="display_option">
<?php
	if($each_options){
		for ($i=0; $i < count($each_options); $i++) { 

            switch($each_options[$i]['main']['option_status']){
                case 0 :
                $option_status = "<option value='2'>판매중</option><option value='1'>일시품절</option><option value='0' selected>품절</option>";
                break;
                case 1 :
                $option_status = "<option value='2'>판매중</option><option value='1' selected>일시품절</option><option value='0'>품절</option>";
                break;
                case 2 :
                $option_status = "<option value='2' selected>판매중</option><option value='1'>일시품절</option><option value='0'>품절</option>";
                break;
            }

?>


					<tr style="background:#e8e8e8; text-align:center" name="first_option" class="option_<?php echo $i;?>" id="input_first_option">
						<td>필수</td>
						<td><input type="text" name="kkcart_option_name_<?php echo $i;?>" value="<?php echo $each_options[$i]['main']['name'];?>"></td>
						<td><input type="text" name="kkcart_plus_price_<?php echo $i;?>" value="<?php echo $each_options[$i]['main']['plus_price'];?>"></td>
						<td><input type="text" name="kkcart_total_amount_<?php echo $i;?>" value="<?php echo $each_options[$i]['main']['total_amount'];?>"></td>
						<td><select name="kkcart_option_status_<?php echo $i;?>"><?php echo $option_status;?></select></td>
                        <td><input type="button" class="button button-primary" value="추가" onclick="add_second_option(<?php echo $i;?>,'<?php echo $options[0];?>');"></td>
                        <input type="hidden" name="second_option_<?php echo $i;?>_length"  class="second_option_<?php echo $i;?>_numbering" value="<?php echo count($each_options[$i]['sub']);?>">
					</tr>

<?php

		for ($o=0; $o < count($each_options[$i]['sub']); $o++) { 

            switch($each_options[$i]['sub'][$o]['option_status']){
                case 0 :
                $second_option_status = "<option value='2'>판매중</option><option value='1'>일시품절</option><option value='0' selected>품절</option>";
                break;
                case 1 :
                $second_option_status = "<option value='2'>판매중</option><option value='1' selected>일시품절</option><option value='0'>품절</option>";
                break;
                case 2 :
                $second_option_status = "<option value='2' selected>판매중</option><option value='1'>일시품절</option><option value='0'>품절</option>";
                break;
            }  
?>


                    <tr style="background:#f4e7e7; text-align:center" class="second_option_tr_<?php echo $i;?>_<?php echo ($o+1);?> option_<?php echo $i;?>_second second_option">
                        <td>보조</td>
                        <td><input type="text" name="kkcart_second_option_<?php echo $i;?>_<?php echo ($o+1);?>" value="<?php echo $each_options[$i]['sub'][$o]['name'];?>"></td>
                        <td><input type="text" name="kkcart_second_price_<?php echo $i;?>_<?php echo ($o+1);?>" value="<?php echo $each_options[$i]['sub'][$o]['plus_price'];?>"></td>
                        <td><input type="text" name="kkcart_second_amount_<?php echo $i;?>_<?php echo ($o+1);?>" value="<?php echo $each_options[$i]['sub'][$o]['total_amount'];?>"></td>
                        <td><select name="kkcart_second_option_status_<?php echo $i;?>_<?php echo ($o+1);?>"><?php echo $second_option_status;?></select></td>
                        <td><input type="button" class="button" value="삭제" onclick="remove_second_option(<?php echo $i;?>,<?php echo ($o+1);?>);"></td>
                    </tr>

<?php
		}


		}

	}
	else {
?>
				
					<tr style="background:#e8e8e8; text-align:center">
						<td colspan="6" style="height:50px"> 옵션명을 먼저 입력 해 주세요</td>
					</tr>
				
<?php
	}
?>
			</tbody>
			</table>
		</li>
	</ul>
</div>
<?php

}
?>