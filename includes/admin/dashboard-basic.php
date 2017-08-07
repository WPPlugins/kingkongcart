<?php


if(sanitize_text_field( $_POST['form_status'] ) ){

	$general_option = array(
		'fonts'				=> sanitize_text_field( $_POST['font-setup'] ),
		'display' 			=> sanitize_text_field( $_POST['custom-display'] ),
		'grid-width' 		=> sanitize_text_field( $_POST['grid-width'] ),
		'grid-height'		=> sanitize_text_field( $_POST['grid-height'] ),
		'title-color'		=> sanitize_text_field( $_POST['title-color'] ),
		'shortdesc-color' 	=> sanitize_text_field( $_POST['shortdesc-color'] ),
		'price-color'		=> sanitize_text_field( $_POST['price-color'] ),
		'mileage-color'		=> sanitize_text_field( $_POST['mileage-color'] ),
		'discount-color'	=> sanitize_text_field( $_POST['discount-color'] ),
		'title-size'		=> sanitize_text_field( $_POST['title-size'] ),
		'shortdesc-size'	=> sanitize_text_field( $_POST['shortdesc-size'] ),
		'price-size'		=> sanitize_text_field( $_POST['price-size'] ),
		'mileage-size'		=> sanitize_text_field( $_POST['mileage-size'] ),
		'discount-size'		=> sanitize_text_field( $_POST['discount-size'] ),
		'title-justify'		=> sanitize_text_field( $_POST['title-justify'] ),
		'shortdesc-justify' => sanitize_text_field( $_POST['shortdesc-justify'] ),
		'price-justify'		=> sanitize_text_field( $_POST['price-justify'] ),
		'mileage-justify'	=> sanitize_text_field( $_POST['mileage-justify'] ),
		'discount-justify'	=> sanitize_text_field( $_POST['discount-justify'] ),
		'title-bold'		=> sanitize_text_field( $_POST['title-bold'] ),
		'shortdesc-bold'	=> sanitize_text_field( $_POST['shortdesc-bold'] ),
		'price-bold'		=> sanitize_text_field( $_POST['price-bold'] ),
		'mileage-bold'		=> sanitize_text_field( $_POST['mileage-bold'] ),
		'discount-bold'		=> sanitize_text_field( $_POST['discount-bold'] )
	);

	$general_option = serialize($general_option);

	update_option("kingkongcart-display",$general_option);

}

wp_enqueue_script('kkcart_jquery_ui', "http://code.jquery.com/ui/1.10.3/jquery-ui.js", array('jquery'), '1.0', false);
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'sl-script-handle', KINGKONGCART_PLUGINS_URL.'/files/js/admin/wpcolorpicker-controll.js', array( 'wp-color-picker','jquery' ), false, true );

	$get_options = unserialize(get_option("kingkongcart-display"));
	$fonts 				= $get_options['fonts'];
	$get_display 		= $get_options['display'];
	$grid_width 		= $get_options['grid-width'];
	$grid_height 		= $get_options['grid-height'];
	$title_color		= $get_options['title-color'];
	$shortdesc_color 	= $get_options['shortdesc-color'];
	$price_color		= $get_options['price-color'];
	$mileage_color		= $get_options['mileage-color'];
	$discount_color		= $get_options['discount-color'];
	$title_size			= $get_options['title-size'];
	$shortdesc_size		= $get_options['shortdesc-size'];
	$price_size			= $get_options['price-size'];
	$mileage_size		= $get_options['mileage-size'];
	$discount_size		= $get_options['discount-size'];
	$title_justify		= $get_options['title-justify'];
	$shortdesc_justify	= $get_options['shortdesc-justify'];
	$price_justify		= $get_options['price-justify'];
	$mileage_justify	= $get_options['mileage-justify'];
	$discount_justify	= $get_options['discount-justify'];
	$title_bold			= $get_options['title-bold'];
	$shortdesc_bold		= $get_options['shortdesc-bold'];
	$price_bold			= $get_options['price-bold'];
	$mileage_bold		= $get_options['mileage-bold'];
	$discount_bold		= $get_options['discount-bold'];

	if(!$title_size){
		$title_size = "14px";
	}

	if(!$shortdesc_size){
		$shortdesc_size = "12px";
	}

	if(!$price_size){
		$price_size = "14px";
	}

	if(!$mileage_size){
		$mileage_size = "11px";
	}

	if(!$discount_size){
		$discount_size = "14px";
	}

	$display = explode("-", $get_display); 

	$list = array(
				"thumbnail" => array(
					'title' 	=> '썸네일',
					'slug'		=> 'thumbnail',
					'status' 	=> 0
				),
				"title" => array(
					'title' 	=> '상품명',
					'slug'		=> 'title',
					'status' 	=> 0
				),
				"price" => array(
					'title' 	=> '가격',
					'slug'		=> 'price',
					'status' 	=> 0
				),
				"discount" => array(
					'title' 	=> '할인표시',
					'slug'		=> 'discount',
					'status' 	=> 0
				),
				"mileage" => array(
					'title' 	=> '적립금',
					'slug'		=> 'mileage',
					'status' 	=> 0
				),
				"shortdesc" => array(
					'title' 	=> '짤막소개',
					'slug'		=> 'shortdesc',
					'status' 	=> 0
				)
			);

	for ($i=0; $i < count($display); $i++) { 
		switch($display[$i]){
			case "thumbnail":
				$list['thumbnail']['status'] = 1;
				$position_value .= '<li class="position-change-li" label="'.$list['thumbnail']['slug'].'"><ul style="position:relative"><li><img src="'.KINGKONGCART_PLUGINS_URL.'/files/images/kingkong-noimage.png" style="border-radius:5px"></li></ul></li>';
				$preview_value .= '<li><img src="'.KINGKONGCART_PLUGINS_URL.'/files/images/kingkong-noimage.png"></li>';
			break;
			case "title":
				$list['title']['status'] = 1;
				$position_value .= '<li class="position-change-li position-change-box" label="'.$list['title']['slug'].'">'.$list['title']['title'].'</li>';
				$preview_value .= '<li style="font-weight:'.$title_bold.'; font-size:'.$title_size.'; color:'.$title_color.'; text-align:'.$title_justify.'" class="preview-title">멋쟁이 콩돌이 나시</li>';
			break;
			case "price":
				$list['price']['status'] = 1;
				$position_value .= '<li class="position-change-li position-change-box" label="'.$list['price']['slug'].'">'.$list['price']['title'].'</li>';
				$preview_value .= '<li style="font-weight:'.$price_bold.'; font-size:'.$price_size.'; text-align:'.$price_justify.'" class="preview-price"><span style="font-family:arial; font-size:16px; color:'.$price_color.'">1,800</span>원</li>';
			break;
			case "discount":
				$list['discount']['status'] = 1;
				$position_value .= '<li class="position-change-li position-change-box" label="'.$list['discount']['slug'].'">'.$list['discount']['title'].'</li>';
				$preview_value .= '<li style="font-weight:'.$discount_bold.'; font-size:'.$discount_size.'; text-align:'.$discount_justify.'" class="preview-discount"><span style="font-family:arial; font-size:16px; color:'.$discount_color.'; text-decoration:line-through">3,000</span>원</li>';
			break;
			case "mileage":
				$list['mileage']['status'] = 1;
				$position_value .= '<li class="position-change-li position-change-box" label="'.$list['mileage']['slug'].'">'.$list['mileage']['title'].'</li>';
				$preview_value .= '<li style="font-weight:'.$mileage_bold.'; color:'.$mileage_color.'; font-size:'.$mileage_size.'; text-align:'.$mileage_justify.'" class="preview-mileage">적립금 : 180원</li>';
			break;
			case "shortdesc":
				$list['shortdesc']['status'] = 1;
				$position_value .= '<li class="position-change-li position-change-box" label="'.$list['shortdesc']['slug'].'">'.$list['shortdesc']['title'].'</li>';
				$preview_value .= '<li style="font-weight:'.$shortdesc_bold.'; color:'.$shortdesc_color.'; width:200px; font-size:'.$shortdesc_size.'; text-align:'.$shortdesc_justify.'" class="preview-shortdesc">이태리 장인이 한땀한땀 꿰멜 수 있었던 공장에서 갖나온 콩돌이 나시 입니다.</li>';
			break;
		}
	}

	foreach($list as $each){
		if($each['status'] == 0){
			$option_value .= "<li><input type='checkbox' value='".$each['slug']."' class='product-option-checkbox'> <span class='option_label'>".$each['title']."</span></li>";
		}
		else{

			$option_value .= "<li><input type='checkbox' value='".$each['slug']."' class='product-option-checkbox' checked> <span class='option_label'>".$each['title']."</span></li>";			
		}
	}

?>

<ul>
	<li><span class="dashboard-content-title">리스트 설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		상품 표시 항목과 스타일을 수정하실 수 있습니다.<br>
		적립금 설정에서 적립금 사용유무를 미사용 으로 설정시 적립금 표기 설정을 체크해도 화면에 표기 되지 않습니다.
		</span>
	</li>
</ul>
<form method="post">
	<input type="hidden" name="form_status" value="1">
	<input type="hidden" class="plugins_url" value="<?php echo KINGKONGCART_PLUGINS_URL;?>">
	<ul style="margin-left:10px">
		<li class="dashboard-content-basic-li">
			<ul>
				<li class="dashboard-content-basic-title">상품 리스트 표기 설정</li>
				<li class="dashboard-content-basic-content">
					<ul>
						<?php echo $option_value;?>
					</ul>
				</li>
			</ul>
		</li>
		<input type="hidden" name="custom-display" value="<?php echo $get_display;?>">
		<input type="hidden" name="option-count" value="<?php echo count($display);?>">
		<li class="dashboard-content-basic-li">
			<ul>
				<li class="dashboard-content-basic-title">위치변경</li>
				<li class="dashboard-content-basic-content">
					<div id="position-change">
						<ul class="position-change-ul">
							<?php echo $position_value;?>
						</ul>
					</div>
				</li>
			</ul>
		</li>
		<li class="dashboard-content-basic-li">
			<ul>
				<li class="dashboard-content-basic-title">미리보기</li>
				<li class="dashboard-content-basic-content">
					<div>
						<ul id="preview-product-display">
							<?php echo $preview_value;?>
						</ul>
					</div>
				</li>
			</ul>
		</li>
	</ul>
	<ul style="clear:both; margin-left:10px; position:relative; top:10px">
		<table class="dashboard-content-basic-li" style="width:652px">
			<tr>
				<td>기본 글씨체</td>
				<td colspan="3">
					<select name="font-setup">
						<option value='default'>Default</option>
						<option value='gulim'>굴림체</option>
						<option value='dotum'>돋움체</option>
						<option value='malgun'>맑은고딕</option>
						<option value='serif'>Serif</option>
						<option value='sans-serif'>Sans-Serif</option>
						<option value='monospace'>Monospace</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>상품 폭 (width)</td>
				<td colspan="3"><input type="text" name="grid-width" value="<?php echo $grid_width;?>" style="width:70px"></td>
			</tr>
			<tr>
				<td>상품 하단 여백</td>
				<td colspan="3">
					<input type="text" name="grid-height" value="<?php echo $grid_height;?>" style="width:70px">
				</td>
				</tr>
				<tr>
					<td>상품명 스타일</td>
					<td><input type="text" class="color-picker-title" name="title-color" id='color-picker' value="<?php echo $title_color;?>" /></td>
					<td>글씨 크기</td>
					<td><input type="text" style="width:60px" name="title-size" value="<?php echo $title_size;?>" onkeyup="change_text_size('title', this.value);"></td>
					<td><input type="checkbox" name="title-bold" value="bold" <?php if($title_bold == "bold"){ echo "checked"; } ?>>굵게</td>
				</tr>
				<tr>
					<td>상품명 정렬</td>
					<td colspan="3">
						<ul class="kingkong-text-justify">
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-left.png" style="width:24px; height:auto" alt="left" label="left" class="justify-left"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-center.png" style="width:24px; height:auto" alt="center" label="center" class="justify-center"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-right.png" style="width:24px; height:auto" alt="right" label="right" class="justify-right"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-fill.png" style="width:24px; height:auto" alt="fill" label="justify" class="justify-fill"></li>
							<input type="hidden" name="title-justify" value="<?php echo $title_justify;?>">
						</ul>
					</td>
				</tr>
				<tr>
					<td>짤막소개 스타일</td>
					<td><input type="text" class="color-picker-shortdesc" name="shortdesc-color" id='color-picker' value="<?php echo $shortdesc_color;?>" /></td>
					<td>글씨 크기</td>
					<td><input type="text" style="width:60px" name="shortdesc-size" value="<?php echo $shortdesc_size;?>" onkeyup="change_text_size('shortdesc', this.value);"></td>
					<td><input type="checkbox" name="shortdesc-bold" value="bold" <?php if($shortdesc_bold == "bold"){ echo "checked"; } ?>>굵게</td>
				</tr>
				<tr>
					<td>짤막소개 정렬</td>
					<td colspan="3">
						<ul class="kingkong-text-justify">
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-left.png" style="width:24px; height:auto" alt="left" label="left" class="justify-left"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-center.png" style="width:24px; height:auto" alt="center" label="center" class="justify-center"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-right.png" style="width:24px; height:auto" alt="right" label="right" class="justify-right"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-fill.png" style="width:24px; height:auto" alt="fill" label="justify" class="justify-fill"></li>
							<input type="hidden" name="shortdesc-justify" value="<?php echo $shortdesc_justify;?>">
						</ul>
					</td>
				</tr>
				<tr>
					<td>가격 스타일</td>
					<td><input type="text" class="color-picker-price" name="price-color" id='color-picker' value="<?php echo $price_color;?>" /></td>
					<td>글씨 크기</td>
					<td><input type="text" style="width:60px" name="price-size" value="<?php echo $price_size;?>" onkeyup="change_text_size('price', this.value);"></td>
					<td><input type="checkbox" name="price-bold" value="bold" <?php if($price_bold == "bold"){ echo "checked"; } ?>>굵게</td>
				</tr>
				<tr>
					<td>가격 정렬</td>
					<td colspan="3">
						<ul class="kingkong-text-justify">
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-left.png" style="width:24px; height:auto" alt="left" label="left" class="justify-left"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-center.png" style="width:24px; height:auto" alt="center" label="center" class="justify-center"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-right.png" style="width:24px; height:auto" alt="right" label="right" class="justify-right"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-fill.png" style="width:24px; height:auto" alt="fill" label="justify" class="justify-fill"></li>
							<input type="hidden" name="price-justify" value="<?php echo $price_justify;?>">
						</ul>
						
					</td>
				</tr>
				<tr>
					<td>적립금 스타일</td>
					<td><input type="text" class="color-picker-mileage" name="mileage-color" id='color-picker' value="<?php echo $mileage_color;?>" /></td>
					<td>글씨 크기</td>
					<td><input type="text" style="width:60px" name="mileage-size" value="<?php echo $mileage_size;?>" onkeyup="change_text_size('mileage', this.value);"></td>
					<td><input type="checkbox" name="mileage-bold" value="bold" <?php if($mileage_bold == "bold"){ echo "checked"; } ?>>굵게</td>
				</tr>
				<tr>
					<td>적립금 정렬</td>
					<td colspan="3">
						<ul class="kingkong-text-justify">
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-left.png" style="width:24px; height:auto" alt="left" label="left" class="justify-left"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-center.png" style="width:24px; height:auto" alt="center" label="center" class="justify-center"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-right.png" style="width:24px; height:auto" alt="right" label="right" class="justify-right"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-fill.png" style="width:24px; height:auto" alt="fill" label="justify" class="justify-fill"></li>
							<input type="hidden" name="mileage-justify" value="<?php echo $mileage_justify;?>">
						</ul>
						
					</td>
				</tr>
				<tr>
					<td>할인표시 스타일</td>
					<td><input type="text" class="color-picker-discount" name="discount-color" id='color-picker' value="<?php echo $discount_color;?>" /></td>
					<td>글씨 크기</td>
					<td><input type="text" style="width:60px" name="discount-size" value="<?php echo $discount_size;?>" onkeyup="change_text_size('discount', this.value);"></td>
					<td><input type="checkbox" name="discount-bold" value="bold" <?php if($discount_bold == "bold"){ echo "checked"; } ?>>굵게</td>
				</tr>
				<tr>
					<td>할인표시 정렬</td>
					<td colspan="3">
						<ul class="kingkong-text-justify">
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-left.png" style="width:24px; height:auto" alt="left" label="left" class="justify-left"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-center.png" style="width:24px; height:auto" alt="center" label="center" class="justify-center"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-right.png" style="width:24px; height:auto" alt="right" label="right" class="justify-right"></li>
							<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-text-justify-fill.png" style="width:24px; height:auto" alt="fill" label="justify" class="justify-fill"></li>
							<input type="hidden" name="discount-justify" value="<?php echo $discount_justify;?>">
						</ul>
					</td>
				</tr>
			</table>
		</li>
	</ul>
	<ul style="clear:both">
		<li><input type="submit" class="button button-primary" value="확인" style="margin-top:20px; margin-left:10px;"></li>
	</ul>
</form>
<script>
	jQuery("[name=row-product-number]").val("<?php echo $row_num;?>");
	var fonts = "<?php echo $fonts; ?>";
	if(fonts){
		jQuery("[name=font-setup]").val("<?php echo $fonts;?>");
	}
	jQuery(".kingkong-text-justify > li > img").each(function(){

		jQuery(this).css("opacity","0.3");

		switch(jQuery(this).parent().parent().find("input").val()){
			case "left" :
				jQuery(this).parent().parent().find("li > .justify-left").css("opacity", "1");
			break;
			case "right" :
				jQuery(this).parent().parent().find("li > .justify-right").css("opacity", "1");
			break;
			case "center" :
				jQuery(this).parent().parent().find("li > .justify-center").css("opacity", "1");
			break;
			case "fill" :
				jQuery(this).parent().parent().find("li > .justify-fill").css("opacity", "1");
			break;
		}
	});
</script>



















