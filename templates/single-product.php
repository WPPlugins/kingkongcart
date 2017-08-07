<?php

get_header();

      if ( have_posts() ) :

      	$post_id = $post->ID;

       while ( have_posts() ) : the_post();


		$original_mileage_text = MILEAGE_TEXT;
		$original_currency_text = CURRENCY_TEXT;

		// Apply Filter
		$mileage_text = apply_filters("change_mileage_text", $original_mileage_text);
		$currency_text = apply_filters("change_currency_text", $original_currency_text);

       	$thumbnail_ids = unserialize(get_post_meta($post_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'full');
		$option_shortdesc = check_exist_option("shortdesc");
		$option_discount = check_exist_option("discount");
		$option_mileage = check_exist_option("mileage");

		$product_code = get_post_meta($post_id, "kingkongcart-product-code", true);

		$info = unserialize(get_post_meta($post_id, "kingkongcart-product-info", true));

		$original_price 	= $info[0]; 	// 소비자 판매가격
		$result_price 		= $info[1];		// 할인적용 판매가격
		$discount_rate  	= $info[2];		// 할인율
		$discount_price 	= $info[3];		// 할인금액
		$mileage_rate		= $info[4];		// 마일리지
		$mileage_price		= $info[5];		// 마일리지 금액
		$short_desc			= $info[6];		// 짤막소개


		$added_options = get_post_meta($post_id, 'kingkongcart-product-option', true);
		$each_options = unserialize($added_options);

		$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
		$mileage_status = $mileage_config['mileage_use'];

		if($result_price){

			if($option_discount){
				$price = "<span style='text-decoration:line-through'>".number_format($original_price).$currency_text."</span> ".number_format($result_price).$currency_text;
			}
			else {
				$price = $result_price;
			}

		}
		else {
			$price = $original_price;
		}

?>
	<div class="entry-content">
		<div id="single-product">
			<input type="hidden" name="plugins_url" value="<?php echo KINGKONGCART_PLUGINS_URL;?>">
			<input type="hidden" name="cart_url" value="<?php echo get_permalink(KINGKONG_CART_ID);?>">
			<ul class="single-product-information-ul">
				<li style="max-width:<?php echo $thumbnail_url[1];?>px">
					<div class="product-info">
						<div class="product-info-thumbnail">
							<ul>
								<li class="main-thumbnail"><img src="<?php echo $thumbnail_url[0];?>"></li>
							</ul>
							<ul class="sub-thumbnail">
<?php
	for ($i=0; $i < count($thumbnail_ids); $i++) { 
		$full_size = wp_get_attachment_image_src( $thumbnail_ids[$i], 'full' );
?>
								<li label="<?php echo $post_id;?>-<?php echo ($i+1);?>"><?php echo wp_get_attachment_image( $thumbnail_ids[$i], array(40,40) );?><input type="hidden" class="hidden_full_size" value="<?php echo $full_size[0];?>"></li>
<?php		
	}
?>
							</ul>
						</div>
					</div>
				</li>
				<li>
					<div class="product-info-detail">
						<ul>
							<li><?php echo the_title();?></li>
<?php 
	
	if($short_desc){

?>
							<li><?php echo $short_desc;?></li>
<?php

	}

?>
							<li>
								<form id="product-info-form" action="<?php echo get_permalink(KINGKONGCART_ORDER);?>" method="post">
									<input type="hidden" name="post_id" value="<?php echo $post_id;?>">
								<table class="product-info-detail-table">
									<tr>
										<td>상품코드 : </td>
										<td><?php echo $product_code;?></td>
									</tr>
									<tr>
										<td>상품가격 : </td>
										<td><?php echo number_format($price).$currency_text; ?></td>
									</tr>
<?php

	if($mileage_status == "T"){

		if($mileage_price){


?>
									<tr>
										<td><?php echo $mileage_text;?> : </td>
										<td><?php echo $mileage_price; ?></td>
									</tr>
<?php
		}
	}
?>									
									<tr>
										<td>필수옵션</td>
										<td>
											<select name="option1" onchange="check_display_second_option(this.value);">
												<option value="-1">옵션을 선택하세요.</option>
<?php

	for ($i=0; $i < count($each_options); $i++) { 

		// 재고 안전수량보다 작거나 같다면
		if($each_options[$i]['main']['total_amount'] <= $safe_quantity){

			if($each_options[$i]['main']['option_status'] == 0){
				echo "<option value='".$i."*-*".$each_options[$i]['main']['name']."*-*".$each_options[$i]['main']['plus_price']."*-*disable'>".$each_options[$i]['main']['name']."(품절)</option>";
			} else {
				echo "<option value='".$i."*-*".$each_options[$i]['main']['name']."*-*".$each_options[$i]['main']['plus_price']."*-*disable'>".$each_options[$i]['main']['name']."(일시품절)</option>";				
			}


		} else {

			switch($each_options[$i]['main']['option_status']){

				case 2 :
					echo "<option value='".$i."*-*".$each_options[$i]['main']['name']."*-*".$each_options[$i]['main']['plus_price']."*-*enable'>".$each_options[$i]['main']['name']."</option>";
				break;

				case 1 :
					echo "<option value='".$i."*-*".$each_options[$i]['main']['name']."*-*".$each_options[$i]['main']['plus_price']."*-*disable'>".$each_options[$i]['main']['name']."(일시품절)</option>";
				break;

				case 0 :
					echo "<option value='".$i."*-*".$each_options[$i]['main']['name']."*-*".$each_options[$i]['main']['plus_price']."*-*disable'>".$each_options[$i]['main']['name']."(품절)</option>";
				break;

			}

		}


		if(count($each_options[$i]['sub']) > 0){

				$second_option[$i] .= "<option value='-1'>옵션을 선택하세요.</option>";

			for ($j=0; $j < count($each_options[$i]['sub']); $j++){

				$second_option_start[$i] = "<select name='option2' class='second-option second-option-".$i."' onchange='check_second_enable(this.value, ".$i.");'>";

				if($each_options[$i]['sub'][$j]['total_amount'] <= $safe_quantity){

					if($each_options[$i]['sub'][$j]['option_status'] == 0){
						$second_option[$i] .= "<option value='".$j."*-*".$each_options[$i]['sub'][$j]['name']."*-*".$each_options[$i]['sub'][$j]['plus_price']."*-*disable'>".$each_options[$i]['sub'][$j]['name']."(품절)</option>";						
					} else {
						$second_option[$i] .= "<option value='".$j."*-*".$each_options[$i]['sub'][$j]['name']."*-*".$each_options[$i]['sub'][$j]['plus_price']."*-*disable'>".$each_options[$i]['sub'][$j]['name']."(일시품절)</option>";						
					}



				} else {

						switch($each_options[$i]['sub'][$j]['option_status']){

							case 2 :
								$second_option[$i] .= "<option value='".$j."*-*".$each_options[$i]['sub'][$j]['name']."*-*".$each_options[$i]['sub'][$j]['plus_price']."*-*enable'>".$each_options[$i]['sub'][$j]['name']."</option>";
							break;

							case 1 :
								$second_option[$i] .= "<option value='".$j."*-*".$each_options[$i]['sub'][$j]['name']."*-*".$each_options[$i]['sub'][$j]['plus_price']."*-*disable'>".$each_options[$i]['sub'][$j]['name']."(일시품절)</option>";
							break;

							case 0 :
								$second_option[$i] .= "<option value='".$j."*-*".$each_options[$i]['sub'][$j]['name']."*-*".$each_options[$i]['sub'][$j]['plus_price']."*-*disable'>".$each_options[$i]['sub'][$j]['name']."(품절)</option>";
							break;

						}

				}
				$second_option_end[$i] = "</select>";
			}
		$second_options[$i] = $second_option_start[$i].$second_option[$i].$second_option_end[$i];
		}
		else {

		$second_options[$i] = "<span class='second-option second-option-".$i."'>none</span>";

		}

		
	}
?>
											</select>
										</td>
									</tr>


									<tr class="option2_tr">
										<td>옵션2 : </td>
										<td class="option2">
<?php
	for ($i=0; $i < count($second_options); $i++) { 
		echo $second_options[$i];
	}
?>
										</td>
									</tr>

									<tr>
										<td style="vertical-align:middle; padding-top:10px">수량 : </td>
										<td>
											<div class="quantity-div">
												<ul>
													<li style="margin-top:20px"><input type="text" name="quantity" style="width:50px" value="1"></li>
													<li style="margin-top:14px; margin-left:5px">
														<ul>
															<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-button-up.png" style="width:15px; height:auto; cursor:pointer" onclick="quantity_up();"></li>
															<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-button-down.png" style="width:15px; height:auto; cursor:pointer" onclick="quantity_down();"></li>
														</ul>
													</li>
												</ul>
											</div>
										</td>
									</tr>

									<tr>
										<td>SNS : </td>
										<td></td>
									</tr>

								</table>
								</form>
								<div class="single-product-buttons">
									<ul>
										<li onclick="go_cart(<?php echo $post_id;?>,'buy');">구매하기</li>
										<li onclick="go_cart(<?php echo $post_id;?>,'cart');">장바구니 담기</li>
										<li onclick="go_wish(<?php echo $post_id;?>);">위시리스트 추가</li>
									</ul>
									<ul class="single-product-display-notice">
										<li>
											<ul>
												<li>장바구니에 담는중...</li>
												<li><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-loading.gif"></li>
											</ul>
										</li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</li>
			</ul>
			<ul class="single-product-content">
				<li><?php the_content();?></li>
<?php
	$board_config = unserialize(get_option("kingkongcart_board_config"));
	$afternote = $board_config['afternote'];
	$qna = $board_config['qna'];

	if($afternote == "T"){
?>
				<li><?php echo do_shortcode('[kingkongcart_afternote]');?></li>
<?php
	}
	if($qna == "T"){
?>

				<li><?php echo do_shortcode('[kingkongcart_qna]');?></li>
<?php
	}
?>
			</ul>
		</div>
	</div>
<script>

	if( jQuery("[name=option1]").val() == "-1" ){
		jQuery(".option2_tr").hide();
	}

function check_display_second_option(value){

	var option_value = value.split("*-*");
	var option_id = option_value[0];
	var status = option_value[3]; //품절인지 아닌지 체크

	if(status == "disable"){
		alert("죄송합니다.해당 상품은 일시 품절 입니다.");
		jQuery(".option2_tr").hide();
		jQuery("[name=option1]").val("-1");
	} else {

		if( jQuery("[name=option1]").val() == "-1" ){
			jQuery(".option2_tr").hide();
		}
		else {
			jQuery(".option2_tr").show();
			jQuery(".second-option").hide();
			jQuery(".second-option").prop("disabled",true);

			if(jQuery(".second-option-"+option_id).html() == "none"){
				jQuery(".option2_tr").hide();
				jQuery(".option2_tr").addClass("disable-option");
				jQuery(".second-option-"+option_id).prop("disabled",true);
			}
			else {
				jQuery(".option2_tr").show();
				jQuery(".option2_tr").removeClass("disable-option");
				jQuery(".second-option-"+option_id).show();
				jQuery(".second-option-"+option_id).prop("disabled",false);
			}
		}
	}
}

function check_second_enable(value,id){

	var option_value = value.split("*-*");
	var status = option_value[3]; //품절인지 아닌지 체크

	if(status == "disable"){
		alert("죄송합니다.해당 상품은 일시 품절 입니다.");
		jQuery(".second-option-"+id).val("-1");
	} 

}
</script>
<?php			
		endwhile;
      else :
        //echo "no";// If no content, include the "No posts found" template.
      endif;
get_footer();
?>