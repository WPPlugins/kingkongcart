<?php

add_shortcode("kingkongcart_coupon","kingkongcart_coupon");

function kingkongcart_coupon($attr){

	$args = array(
		'post_type' => 'kkcart_coupon',
		'post_status' => 'publish'
		);

	$coupons = new WP_Query($args);	
	
	$content = "<div style='display:table; max-width:99%; margin:0 auto; margin-bottom:30px'><h1>".$attr['title']."</h1><ul style='list-style:none; margin:0; padding:0; text-align:center'>";

	if ( $coupons->have_posts() ) {

		$cnt = 1;
		while ( $coupons->have_posts() ){
			$coupons->the_post();

			$post_id = get_the_ID();

			$get_coupon = unserialize(get_post_meta($post_id, "coupon_detail", true));
			$coupon_count = get_post_meta($post_id, "coupon_count", true);

	    	$capability 		= $get_coupon['capability']; 		// 사용범위 (전체 : all, 일부 : limit)
	    	$coupon_kind 		= $get_coupon['coupon_kind']; 		// 쿠폰종류 (무료배송:1,정액제:2,할인:3)
	    	$coupon_discount 	= $get_coupon['coupon_discount'];	// 할인금액 (무료배송일 경우 배송설정에 책정된 배송비로 고정)
	    	$start_date 		= $get_coupon['start_date'];		// 사용기간 시작일
	    	$end_date 			= $get_coupon['end_date'];			// 사용기간 종료일
	    	$added_product 		= $get_coupon['added_product'];		// 등록된 카테고리나 상품 (카테고리는 cat-term_id, 상품은 prd-post_id)
	    	$min_price 			= $get_coupon['min_price'];
	    	$coupon_image_url	= $get_coupon['coupon_image_url'];	// 등록된 쿠폰 이미지
	    	$add_column 		= "";
	    	switch($capability){
	    		case "all" :
	    			$using_capability = "모든상품";
	    		break;

	    		case "limit" :
	    			$using_capability = "일부상품";
	    		break;

	    	}

	    	switch($coupon_kind){
	    		case "1" :
	    			$coupon_type = "무료배송쿠폰";
	    		break;

	    		case "2" :
	    			$coupon_type = "정액할인쿠폰";
	    		break;

	    		case "3" :
	    			$coupon_type = "할인쿠폰";
	    		break;

	    	}

	    	if(check_coupon_get($post_id)){
	    		$result_text = "이미 받은 쿠폰입니다.";
	    	} else {
	    		$result_text = "<input type='button' class='kingkong_button' value='쿠폰받기' onclick='get_coupon(".$post_id.");'>";
	    	}

	    	if(!$coupon_image_url){
	    		$coupon_image_url = "
	    		<div style='width:200px; height:100px; border:5px dashed #e0e0e0; padding:10px 10px; margin-bottom:6px'>
	    			<ul style='list-style:none; margin:0; padding:0'>
	    				<li style='text-align:center'>".get_the_title($post_id)."</li>
	    				<li>".$coupon_type."</li>
	    				<li><span style='font-family:arial; font-weight:bold; font-size:18px'>".$coupon_discount."</span></li>
	    				<li><span style='font-size:11px'>잔여수량:".number_format($coupon_count)."</span></li>
	    			</ul>
	    		</div>";
	    	} else {
	    		$coupon_image_url = "<img src='".$coupon_image_url."' style='cursor:pointer; max-width:200px; height:auto' onclick='get_coupon(".$post_id.");'>";
	    	}


	    	$list_columns = array(
	    		'coupon_image' 	=> true,
	    		'title' 		=> true,
	    		'discount' 		=> true,
	    		'count' 		=> true,
	    		'min_price'		=> true,
	    		'capability' 	=> true
	    	);

	    	$list_column = apply_filters('coupon_list_column_change', $list_columns);

	    	if($list_column['coupon_image']){
	    		$add_column .= "<li style='position:relative'>".$coupon_image_url."</li>";
	    	}

	    	if($list_column['title']){
	    		$add_column .= "<li>".get_the_title($post_id)."</li>";
	    	}

	    	if($list_column['discount']){
	    		$add_column .= "<li>할인금액: ".$coupon_discount."</li>";
	    	}

	    	if($list_column['count']){
	    		$add_column .= "<li>잔여수량: ".number_format($coupon_count)."</li>";
	    	}

	    	if($list_column['min_price']){
	    		$add_column .= "<li>".number_format($min_price)."원 이상 사용가능</li>";
	    	}

	    	if($list_column['capability']){
	    		$add_column .= "<li>사용범위: ".$using_capability."</li>";
	    	}

	    	$content .= "
	    	<li style='display:inline-block; width:auto; text-align:center; margin:0px 5px 0px 5px'>
	    		<ul style='list-style:none; margin:0; padding:0'>
	    			".$add_column.$coupon_list_column_change."
	    			<li>".$result_text."</li>
	    		</ul>
	    	</li>
	    	";

		}

	}

	$content .= "</ul></div>";

	$content = wpautop(trim($title.$content));
	return $content;

}








?>