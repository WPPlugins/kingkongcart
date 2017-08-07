<?php
session_start();

add_shortcode("kingkongcart_order","kingkongcart_order");

function kingkongcart_order($attr){



/* 	single product 의 구매 상품을 post 로 불러와 배열을 만들고
* 	해당 상품을 주문해야 한다.
*/


$dir 					= KINGKONGCART_ABSPATH;
$payments 				= unserialize(get_option("kingkong_payment"));
$payment_method 		= $payments['method'];
$yd_check 				= $payments['yd_check'];
$site_code 				= $payments['site_code'];
$site_key 				= $payments['site_key'];
$paykind				= $payments['paykind'];
$private_account 		= $payments['private_account'];
$private_account_bank 	= $payments['private_account_bank'];
$private_account_number = $payments['private_account_number'];

switch($payment_method){

	case "INICIS" :

		$inicis_key_id 	= $payments['inicis_key_id'];
		$inicis_key_pwd = $payments['inicis_key_pwd'];

	break;
}


$option1 	= sanitize_text_field( $_POST['option1'] );
$option1 	= explode("*-*",$option1);
$option2 	= sanitize_text_field( $_POST['option2'] );
$option2 	= explode("*-*",$option2);
$quantity 	= sanitize_text_field( $_POST['quantity'] );

if($results_price){
		$last_price = $results_price;
} else {
	$last_price = $original_price;
}


$cart[0] = array(
	'product_id'  => sanitize_text_field( $_POST['post_id'] ),
	'quantity' => $quantity,
	'discount_price' => $discount_price,
	'price'	=> $last_price,
	'provide_price' => $provide_price,
	'mileage_price' => $mileage_price,
	'first' => array(
		'id' 		 => $option1[0],
		'name'		 => $option1[1],
		'plus_price' => $option1[2]
	),
	'second' => array(
		'id' 		 => $option2[0],
		'name' 		 => $option2[1],
		'plus_price' => $option2[2]
	)
);

$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
$mileage_status = $mileage_config['mileage_use'];

if($option1[0] != ""){
// 단일 구매시
$_SESSION['temp_kingkongcart_product'] = $cart;
} else {
// 장바구니 구매시
			$_SESSION['temp_kingkongcart_product'] = get_kingkong_cart();
		
}

if (!is_user_logged_in() and sanitize_text_field( $_POST['type'] ) == false){ //로그인하지 않은 상태

    $templatefilename = 'login.php';
    $no_entry_content = true;

    if (file_exists(TEMPLATEPATH . '/' . "page-".$templatefilename)) {
        include(TEMPLATEPATH . '/' . "page-".$templatefilename);

    } else {
        include(KINGKONGCART_ABSPATH . '/templates/' . $templatefilename);
    }	

} else {
	
if (is_user_logged_in()){

	global $current_user;
	get_currentuserinfo();

	$user_id 			= $current_user->ID;
	$user_name 		 	= $current_user->user_login;
	$user_display_name	= $current_user->display_name;
	$user_email			= $current_user->user_email;

	$user_info = get_user_meta($user_id, "kingkong_user_info", true);
	$user_info = unserialize($user_info);
	$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));
 	$count_coupons = count($user_coupons);

	$tel = $user_info['tel'];
	$phone = $user_info['phone'];
	$address_doro = $user_info['address_doro'];
	$address_jibun = $user_info['address_jibun'];
	$address_else = $user_info['address_else'];
	$zipcode = $user_info['zipcode'];
	$zipcode = explode("-",$zipcode);
	$zipcode1 = $zipcode[0];
	$zipcode2 = $zipcode[1];

	$tel = explode("-",$tel);
	$tel1 = $tel[0];
	$tel2 = $tel[1];
	$tel3 = $tel[2];
	$phone = explode("-",$phone);
	$phone1 = $phone[0];
	$phone2 = $phone[1];
	$phone3 = $phone[2];

	// 보유 적립금
	$kingkong_mileage = get_user_meta($user_id, "kingkong_mileage", true);
	if(!$kingkong_mileage){
		$kingkong_mileage = 0;
	}
}

	/*
	* 단일 상품 바로구매로 들어온다면 세션을 불러오고 나머지는 카트를 불러온다.
	*/


	$cart = $_SESSION['temp_kingkongcart_product'];
	
	@usort($cart);

	$mileage_text = apply_filters("change_mileage_text", MILEAGE_TEXT);
	$currency_text = apply_filters("change_currency_text", CURRENCY_TEXT);

	if(count($cart) > 1){
		$payment_insert_title = get_the_title($cart[0]['product_id'])." 외 ".( count($cart) - 1)."건";
	} else {
		$payment_insert_title = get_the_title($cart[0]['product_id']);
	}



	$detect = new Mobile_Detect;
	if($detect->isMobile()) {
		include(KINGKONGCART_ABSPATH."/includes/order-mobile.php");
	} else if($detect->isTablet()){
		include(KINGKONGCART_PLUGINS_URL."/includes/order-mobile.php");
	} else {
?>

	<div id="kingkongcart-order">

		<h3>상품정보</h3>
		<div class="mypage-div">
			<table>
				<thead>
					<tr>
						<th>상품명</th>
						<th>상품형태</th>
						<th>상품가격</th>
						<th>수량</th>
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
						<th><?php echo $mileage_text;?></th>
<?php
		}
	}
?>
						<th>합계</th>
						<th>선택</th>
					</tr>
				</thead>
				<tbody>

<?php

	if($cart){
		$check_product_kind = 0;
		for ($i=0; $i < count($cart); $i++) { 

			$product_id 		= $cart[$i]['product_id'];
			$option1 			= $cart[$i]['first']['name'];
			$option1_plus_price = $cart[$i]['first']['plus_price'];
			$option2			= $cart[$i]['second']['name'];
			$option2_plus_price = $cart[$i]['second']['plus_price'];
			$quantity			= $cart[$i]['quantity'];

			$info 				= get_product_info($product_id); //상품 기본 정보 불러옴

			$product_info 		= new kingkong_product($product_id);
			$product_kind 		= $product_info->kind;

				if($product_kind == "0" or !$product_kind){
					$check_product_kind++;
				}

			$each_total_mileage = $product_info->mileage_price * $quantity;
			$each_total_price	= ($product_info->result_price * $quantity) + ($option1_plus_price * $quantity) + ($option2_plus_price * $quantity);


			$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
			$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');

			// 일부 상품에 적용가능한 쿠폰일때
			$coupon_id = $_POST['will_using_coupon_id'];
			$coupon_product = unserialize(get_post_meta($coupon_id, "coupon_product", true));

			for ($j=0; $j < count($coupon_product); $j++) {
				$coupon_product_title 	= $coupon_product[$j];
				$coupon_product_explode = explode("-",$coupon_product[$j]);
				$coupon_product_prefix  = $coupon_product_explode[0];
				$coupon_product_id 	 	= $coupon_product_explode[1];

				if($coupon_product_prefix == "cat"){
					// 카테고리로 등록되었다면 해당 카테고리에 해당 상품이 있는지 판별
					$terms_list = wp_get_post_terms($product_id, "section", array("fields" => "all"));
					foreach($terms_list as $term){

						if($coupon_product_id == $term->term_id){

							for ($c=0; $c < count($user_coupons); $c++) { 
								if($user_coupons[$c]['coupon_id'] == $coupon_id){
									$get_coupon_name 			= $user_coupons[$c]['coupon_name'];
									$get_coupon_capability		= $user_coupons[$c]['capability'];
									$get_coupon_kind 			= $user_coupons[$c]['coupon_kind'];
									$get_coupon_discount 		= $user_coupons[$c]['coupon_discount'];
									$get_start_date 			= $user_coupons[$c]['start_date'];
									$get_end_date 				= $user_coupons[$c]['end_date'];
									$using_array_id 			= $c;
								}
							}

							$today_mktime = mktime(0,0,0,date("m"),date("d"),date("Y"));
							$start_explode = explode("-", $get_start_date);
							$start_mktime = mktime(0,0,0,$start_explode[1], $start_explode[2], $start_explode[0]);
							$end_explode = explode("-", $get_end_date);
							$end_mktime = mktime(0,0,0,$end_explode[1], $end_explode[2], $end_explode[0]);

							if($today_mktime >= $start_mktime and $today_mktime <= $end_mktime){
								$coupon_matched = "matched";
							}

						}
					}

				} else {
					// 상품이 등록된 경우, coupon_product_id 를 비교 판별
					if($coupon_product_id == $product_id){

							for ($c=0; $c < count($user_coupons); $c++) { 
								if($user_coupons[$c]['coupon_id'] == $coupon_id){
									$get_coupon_name 			= $user_coupons[$c]['coupon_name'];
									$get_coupon_capability		= $user_coupons[$c]['capability'];
									$get_coupon_kind 			= $user_coupons[$c]['coupon_kind'];
									$get_coupon_discount 		= $user_coupons[$c]['coupon_discount'];
									$get_start_date 			= $user_coupons[$c]['start_date'];
									$get_end_date 				= $user_coupons[$c]['end_date'];
									$using_array_id 			= $c;
								}
							}

							$today_mktime = mktime(0,0,0,date("m"),date("d"),date("Y"));
							$start_explode = explode("-", $get_start_date);
							$start_mktime = mktime(0,0,0,$start_explode[1], $start_explode[2], $start_explode[0]);
							$end_explode = explode("-", $get_end_date);
							$end_mktime = mktime(0,0,0,$end_explode[1], $end_explode[2], $end_explode[0]);

							if($today_mktime >= $start_mktime and $today_mktime <= $end_mktime){
								$coupon_matched = "matched";
							}

					}
				}
			}

?>
					<tr>
						<td style="text-align:left;">
							<ul class="cart-thumbnail-display">
								<li><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></li>
								<li>
									<ul>
										<li>
											상품명 : <a href="<?php echo get_the_permalink($product_id);?>"><?php echo get_the_title($product_id);?></a>
										</li>
										<li>
											옵션 1 : <?php echo $option1;?> (추가금액:<?php echo number_format($option1_plus_price).$currency_text;?>)
										</li>
<?php
	if($option2){
?>
										<li>
											옵션 2 : <?php echo $option2;?> (추가금액:<?php echo number_format($option2_plus_price).$currency_text;?>)
										</li>
<?php
}
?>
									</ul>
								</li>
							</ul>
						</td>
<?php
	switch($product_kind){
		case "0" :
			$product_kind_title = "배송상품";
		break;

		case "1" :
			$product_kind_title = "다운로드상품";
		break;

		default :
			$product_kind_title = "배송상품";
		break;
	}
?>
						<td><?php echo $product_kind_title;?></td>
						<td><?php echo number_format($info->final_price).$currency_text;?></td>
						<td>
							<?php echo $quantity;?>
						</td>
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
						<td><?php echo $each_total_mileage;?></td>
<?php
		}
	}
?>				
						<td>
<?php
	if($coupon_matched == "matched"){

		switch($_POST['using_coupon_kind']){

			case "1" :
?>
				<?php echo number_format($each_total_price).$currency_text;?>
<?php
			break;

			case "2" :
				$coupon_use_price = $each_total_price - $_POST['using_coupon_discount'];
?>

				<span style="text-decoration:line-through"><?php echo number_format($each_total_price).$currency_text;?></span><br>
				<?php echo number_format($coupon_use_price).$currency_text; ?><br>
				쿠폰적용가

<?php
			break;

			case "3" :
				$pct = $_POST['using_coupon_discount'];
				$dec = str_replace("%", "", $pct) / 100;
				$coupon_use_price = $each_total_price - ($each_total_price * $dec);
?>

				<span style="text-decoration:line-through"><?php echo number_format($each_total_price).$currency_text;?></span><br>
				<?php echo number_format($coupon_use_price).$currency_text; ?><br>
				쿠폰적용가

<?php
			break;

		}

		$each_total_price = $coupon_use_price;

	} else {
?>
							<?php echo number_format($each_total_price).$currency_text;?>
<?php
	}
?>							
							

						</td>
						<td class="cart-each-choice">
<?php 
	if(count($cart) > 1){
?>
							<li onclick="kingkongcart_remove_cart(<?php echo $i;?>);">삭제</li>
<?php
	}
?>
						</td>
					</tr>

<?php

			$total_price 	+= $each_total_price;
			$total_mileage 	+= $each_total_mileage;

		} // end for

		$shipping_cost = check_shipping_cost($total_price);

		if($shipping_cost == 0){
			$shipping_cost = "무료배송";
			$shipping_cost_data = 0;
			$with_shipping_price = $total_price - sanitize_text_field( $_POST['using_mileage'] );
		} else {
			$shipping_cost_data = $shipping_cost;
			$with_shipping_price = $total_price + $shipping_cost - sanitize_text_field( $_POST['using_mileage'] );
			$shipping_cost = number_format($shipping_cost).$currency_text;
		}

		if( $_POST['using_coupon_capability'] == "all" ){

			// 모든상품에 적용가능한 쿠폰일때

			switch($_POST['using_coupon_kind']){

				case "1" :
					$with_shipping_price = $with_shipping_price - $_POST['using_coupon_discount'];
					$shipping_coas = "무료배송 쿠폰사용";
					$coupon_type = "무료배송";
				break;

				case "2" :
					$with_shipping_price = $with_shipping_price - $_POST['using_coupon_discount'];
					$coupon_type = "정액할인";
				break;

				case "3" :
					$with_shipping_price = ($total_price - ($total_price * $_POST['using_coupon_discount'])) + $shipping_cost - sanitize_text_field( $_POST['using_mileage'] );
					$coupon_type = "할인";
				break;

			}
		}


?>


				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" class="cart-tfoot-td">총 구매금액 : <?php echo number_format($with_shipping_price).$currency_text;?> 
<?php
	if (sanitize_text_field( $_POST['using_mileage'] )){
?>
						(적립금 <?php echo number_format($_POST['using_mileage']);?>원 사용)
<?php
	}
?>

<?php
	if ($_POST['using_coupon_discount']){
?>
						(<?php echo $coupon_type;?>쿠폰 <?php echo $_POST['using_coupon_discount'];?> 사용)
<?php
	}
?>

<?php 
	if ($_POST['using_coupon_kind'] != "1"){
?>
						(배송비:<?php echo $shipping_cost;?> 포함)
<?php
	}
?>
						
<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
 (적립금 <?php echo number_format($total_mileage);?>)
 <?php
 		}
 	}
 ?>
						</td>
					</tr>
				</tfoot>
			</table>

<?php 
	} // end if
	else {
?>
		<tr>
			<td colspan="6">장바구니가 비어있습니다.</td>
		</tr>
	</table>

<?php
	}
?>
		</div>

<?php

		switch($payment_method){
			case "KCP" :
			include $dir.'payment/KCP/cfg/site_conf_inc.php'; 	// KCP 결제 Config
			include $dir.'payment/KCP/order.php';				// KCP 결제폼
			$button = '<input type="button" class="kingkongtheme_button" value="결제하기" onclick="return jsf__pay(this.form);">';
			break;

			case "INICIS" :
			include $dir.'payment/INICIS/order.php';			// INICIS 결제폼
			$button = "<input type='submit' class='kingkongtheme_button' value='결제하기'>";
			break;
		}

?>

		<input type="hidden" name="status_type" value="order_result">
		<input type="hidden" name="shipping_cost" value="<?php echo $shipping_cost_data;?>">
		<input type="hidden" name="total_mileage" value="<?php echo $total_mileage;?>">
		<input type="hidden" name="with_shipping_price" value="<?php echo $with_shipping_price;?>">
		<input type="hidden" name="product_desc_title" value="<?php echo $payment_insert_title;?>">
		<input type="hidden" name="paid_coupon" value="<?php echo $_POST['will_using_coupon_id'];?>">
		<input type="hidden" name="product_kind" value="<?php echo $check_product_kind;?>">

		<h3>주문자 정보</h3>
		<div class="kingkongcart-order-info">


			<table>
				<tr>
					<th>주문하시는 분</th>
					<td><input type="text" name="order_name" class="order_name" value="<?php echo $user_display_name;?>" onchange="change_order_value(this.value, 'name', '<?php echo $payment_method;?>');"></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="order_tel1" class="kingkong_input_s" value="<?php echo $phone1;?>"> - <input type="text" name="order_tel2" class="kingkong_input_s" value="<?php echo $phone2;?>"> - <input type="text" name="order_tel3" class="kingkong_input_s" onchange="change_order_value('','tel', '<?php echo $payment_method;?>');" value="<?php echo $phone3;?>"></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><input type="text" name="order_email" class="order_email" value="<?php echo $user_email;?>" onchange="change_order_value(this.value, 'email', '<?php echo $payment_method;?>');"></td>
				</tr>
			</table>
		</div>
<?php
	if($check_product_kind > 0){
?>
		<h3>배송지 정보</h3>
		<div class="kingkongcart-shipping-info">
			<table>
				<tr>
					<th>배송지</th>
					<td>
						<ul class="postcode_input_ul">
							<li><input type="checkbox" name="equal_info"> 주문자와 동일</li>
							<li><input type="text" name="postcode1" class="kingkong_input_s" value="<?php echo $zipcode1;?>"> - <input type="text" name="postcode2" class="kingkong_input_s" value="<?php echo $zipcode2;?>"> <input type="button" class="kingkongtheme_button" value="우편번호 검색" onclick="showDaumPostcode();"></li>
							<li><input type="text" name="address" class="kingkong_input_l" value="<?php echo $address_jibun;?>"> 기본 주소</li>
							<li><input type="text" name="else_address" class="kingkong_input_l" value="<?php echo $address_else;?>"> 나머지주소</li>
						</ul>
						<div id="layer" style="display:none;border:5px solid;position:fixed;width:400px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()">
						</div>
					</td>
				</tr>
				<tr>
					<th>받으시는 분</th>
					<td><input type="text" name="shipping_name"></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="shipping_tel1" class="kingkong_input_s"> - <input type="text" name="shipping_tel2" class="kingkong_input_s"> - <input type="text" name="shipping_tel3" class="kingkong_input_s"></td>
				</tr>
				<tr>
					<th>배송메모</th>
					<td><textarea name="input_memo"></textarea></td>
				</tr>
			</table>
		</div>
<?php
	}
?>

<?php
	if(sanitize_text_field( $_POST['type'] ) != false){
?>
		<h3>개인정보 보호정책 동의</h3>
		<div class="kingkongcart-coupon-use">
			<table>
				<tr>
					<th style="vertical-align:middle">개인정보동의</th>
					<td>
						<ul class="private_info_ul">
							<li><textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_PRIVACY);?></textarea></li>
							<li><input type="radio" name="input_privacy" value="T" class="agree_input_privacy"> 약관에 동의합니다. <input type="radio" name="input_privacy" value="F" class="input_privacy_false" checked> 동의하지 않습니다.</li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
<?php
	}
?>

<?php
	if($mileage_status == "T"){
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
		<h3><?php echo $mileage_text;?> 사용</h3>
		<div class="kingkongcart-mileage-use">
			<table>
				<tr>
					<th>사용할 적립금</th>
					<td><input type="text" name="input_mileage" value="<?php echo $_POST['using_mileage'];?>"> <input type="button" class="kingkongtheme_button" value="적립금 사용" onclick="use_mileage(<?php echo $user_id;?>);"> 
						(보유적립금: <?php echo number_format($kingkong_mileage);?>원
<?php
	if(sanitize_text_field( $_POST['using_mileage'] )){
?>
						- <?php echo number_format(sanitize_text_field( $_POST['using_mileage'] ));?>원
<?php
	}
?>
						)
					</td>
				</tr>
			</table>
		</div>
<?php
		}
	}
?>
<?php
		if(sanitize_text_field( $_POST['type'] ) == false){
?>
		<h3>쿠폰 사용</h3>
		<div class="kingkongcart-coupon-use">
			<table>
				<tr>
					<th>사용할 쿠폰 선택</th>
					<td>
						<input type="hidden" name="using_coupon_id"> 
						<input type="hidden" name="select_coupon_capability">
						<input type="hidden" name="select_coupon_kind">
						<input type="hidden" name="select_coupon_product">
<?php
	if($_POST['will_using_coupon_id']){
?>
						<input type="text" style="width:300px" name="using_coupon" value="<?php echo get_the_title($_POST['will_using_coupon_id']);?>" readonly>
<?php
	} else {
?>
						<input type="text" style="width:300px" name="using_coupon" readonly placeholder="사용할 쿠폰을 선택하세요">
<?php
	}
?>
						<input type="button" class="kingkongtheme_button" value="쿠폰적용" onclick="use_coupon(<?php echo $user_id;?>,<?php echo $total_price;?>);"> <input type="button" class="kingkongtheme_button" value="쿠폰적용취소" onclick="location.href='';">
					</td>
				</tr>
				<tr>
					<th>보유중인 쿠폰 목록</th>
					<td>
						<ul style="list-style:none; margin:0; padding:0">
<?php
	
	for ($i=0; $i < count($user_coupons); $i++) {

		switch($user_coupons[$i]['coupon_kind']){

			case "1" :
				$coupon_type = "무료배송";
			break;

			case "2" :
				$coupon_type = "정액할인";
			break;

			case "3" :
				$coupon_type = "할인쿠폰";
			break;
		}

		if($_POST['will_using_coupon_id'] == $user_coupons[$i]['coupon_id']){
			$coupon_checked = "checked";
		} else {
			$coupon_checked = "";
		} 

		if($user_coupons[0]['coupon_kind']){
?>

						
						<li><input type="radio" name="select_coupon" <?php echo $coupon_checked;?>> <span class="coupon_title">[<?php echo $coupon_type;?>]<?php echo $user_coupons[$i]['coupon_name'];?> | <?php echo $user_coupons[$i]['coupon_discount'];?> 할인</span> <input type="hidden" name="coupon_id" value="<?php echo $user_coupons[$i]['coupon_id'];?>"><input type="hidden" name="coupon_discount_price" value="<?php echo $user_coupons[$i]['coupon_discount'];?>"><input type="hidden" name="coupon_capability" value="<?php echo $user_coupons[$i]['capability'];?>"><input type="hidden" name="coupon_kind" value="<?php echo $user_coupons[$i]['coupon_kind'];?>"></li>

<?php 
		} else {
?>
						<li>없음</li>
<?php
		}
	}
?>
						</ul>
					</td>
				</tr>
			</table>
		</div>
<?php 
		}
?>

		<h3>결제수단</h3>
		<div class="kingkongcart-coupon-use">
			<table>
				<tr>
					<th>결제수단 선택</th>
					<td>
<?php
	
	if($private_account == "T"){

		echo "<input type='radio' name='selectpaymethod' class='selectpaymethod' id='private_account' value='private_account'> 무통장 입금 ";

	}

	for ($i=0; $i < count($paykind); $i++) { 
		echo "<input type='radio' name='selectpaymethod' class='selectpaymethod' value='".$paykind[$i]."'> ".get_paykind_option_name($paykind[$i])." ";
	}
?>
					<input type="hidden" name="gopaymethod" value="Card">
					</td>
				</tr>
			</table>
		</div>
		<div class="kingkongcart-coupon-use" id="private_account_select">

		</div>
		<div class="kingkongcart-order-buttons">
			<?php echo $button;?>
			<input type="button" class="kingkongtheme_button" value="주문취소" onclick="history.back();">
		</div>
	</form>
	<form method="post" id="use_mileage_form">
		<input type="hidden" name="using_coupon_capability" value="<?php echo sanitize_text_field( $_POST['using_coupon_capability'] );?>">
		<input type="hidden" name="using_coupon_kind" value="<?php echo sanitize_text_field( $_POST['using_coupon_kind'] );?>">
		<input type="hidden" name="using_coupon_discount" value="<?php echo sanitize_text_field( $_POST['using_coupon_discount'] );?>">
		<input type="hidden" name="using_mileage" value="<?php echo sanitize_text_field( $_POST['using_mileage'] );?>">
		<input type="hidden" name="without_shipping_cost" value="<?php echo $total_price;?>">
		<input type="hidden" name="will_using_coupon_id" value="<?php echo $_POST['will_using_coupon_id'];?>">
	</form>
	<form method="post" id="use_coupon_form">
		<input type="hidden" name="using_coupon_capability" value="<?php echo sanitize_text_field( $_POST['using_coupon_capability'] );?>">
		<input type="hidden" name="using_coupon_kind" value="<?php echo sanitize_text_field( $_POST['using_coupon_kind'] );?>">
		<input type="hidden" name="using_coupon_discount" value="<?php echo sanitize_text_field( $_POST['using_coupon_discount'] );?>">
		<input type="hidden" name="using_mileage" value="<?php echo sanitize_text_field( $_POST['using_mileage'] );?>">
		<input type="hidden" name="without_shipping_cost" value="<?php echo $total_price;?>">
		<input type="hidden" name="will_using_coupon_id" value="<?php echo $_POST['will_using_coupon_id'];?>">
	</form>
	</div>
<?php

	for ($i=0; $i < count($private_account_bank); $i++) { 
		if($private_account_number[$i] != ""){
			$option_value .= "<option>".$private_account_bank[$i]."(계좌번호 : ".$private_account_number[$i].")</option>";
		}
	}

?>
<script>

	jQuery(".selectpaymethod").click(function(){
		if(jQuery("#private_account").prop("checked") == true ){
			jQuery("#private_account_select").html("<h3>결제은행 선택</h3><table><tr><th>은행선택</th><td><select name='private_account_select'><?php echo $option_value;?></select></td></tr><tr><th>입금자명</th><td><input type='text' name='private_account_name'> * 입금자명이 다를시 주문 및 배송에 차질이 있을 수 있으니 반드시 정확하게 기입하시기 바랍니다.</td></tr></table>");
			jQuery(".kingkongcart-order-buttons").html("<input type='button' class='kingkongtheme_button' value='결제하기' onclick='private_pay();'> <input type='button' class='kingkongtheme_button' value='주문취소' onclick='history.back();'>");
			jQuery(".kingkong_order_form").attr("id", "private_order_form");
			jQuery(".kingkong_order_form").attr("action", "<?php echo get_the_permalink(KINGKONG_ORDER_RESULT);?>");
		} else {
			jQuery("#private_account_select").html("");
			jQuery(".kingkongcart-order-buttons").html("<input type='submit' class='kingkongtheme_button' value='결제하기'> <input type='button' class='kingkongtheme_button' value='주문취소' onclick='history.back();'>");
		}
	});

	jQuery("[name=select_coupon]").click(function(){
		jQuery("[name=using_coupon]").val(jQuery(this).parent().find(".coupon_title").html());
		jQuery("[name=using_coupon_id]").val(jQuery(this).parent().find("[name=coupon_id]").val());
		jQuery("[name=select_coupon_capability]").val(jQuery(this).parent().find("[name=coupon_capability]").val());
		jQuery("[name=select_coupon_kind]").val(jQuery(this).parent().find("[name=coupon_kind]").val());
	});

</script>

<?php
	include "zipcode.php";
} // end else is_user_logged_in()
}
}



















?>