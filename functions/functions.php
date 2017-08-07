<?php

session_start();


add_filter("join_table_array", "change_join_table", 10, 1);

function change_join_table($join_table){
	$join_table = array(
	'id',
	'password',
	're_password',
	'name',
	'email',
	'address',
	'tel',
	'phone',
	'birth',
	'newsletter'
	);	

	return $join_table;
}
/* Product Loop 초기 스타일링 */

add_filter("display_option_change", "display_option_change", 30, 1);

function display_option_change($options){

	// thumbnail : 썸네일사진
	// title : 상품명
	// price : 상품가격
	// mileage : 적립금
	// shortdesc : 짤막소개
	// discount : 할인이전가격
/*
	$display_option = array(
		'thumbnail',
		'title',
		'price'
	);
*/
	// 관리자 대시보드 설정 정보대로 표기
	$display_option = explode("-", $options->display);

	return $display_option;

}

add_filter("product_loop_thumbnail", "product_loop_thumbnail", 30, 2);

function product_loop_thumbnail($options, $post_id){

	$thumbnail_ids = unserialize(get_post_meta($post_id,"kingkongcart_added_thumbnail_id", true));
	$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'full');

	if($options->grid_width){
		$result_thumbnail = "<li style='max-width:".$options->grid_width."'><a href='".get_permalink($post_id)."'><img src='".$thumbnail_url[0]."' style='width:100%; height:auto'></a></li>";
	} else {
		$result_thumbnail = "<li><a href='".get_permalink($post_id)."'><img src='".$thumbnail_url[0]."'></a></li>";	
	}

	return $result_thumbnail;
}


add_filter("product_loop_title", "product_loop_title", 30, 2);

function product_loop_title($options, $product_id){

	$result_title = "<li style='text-align:".$options->title_justify."'><span style='font-weight:".$options->title_bold."; color:".$options->title_color." !important; font-size:".$options->title_size."'><a href='".get_permalink($post_id)."' style='color:".$options->title_color." !important;'>".get_the_title($post_id)."</a></span></li>";

	return $result_title;

}

add_filter("product_loop_price", "product_loop_price", 30, 2);

function product_loop_price($options, $post_id){

	$info = new kingkong_product($post_id);

	$result_price = "<li style='text-align:".$options->price_justify."'><span style='font-weight:".$options->price_bold."; color:".$options->price_color."; font-size:".$options->price_size."'>".number_format($info->result_price).$currency_text."</span></li>";

	return $result_price;

}

add_filter("product_loop_mileage", "product_loop_mileage", 30, 2);

function product_loop_mileage($options, $post_id){

	$info = new kingkong_product($post_id);

	if($info->mileage_status == "T"){
		if($info->mileage_price){
			$result_mileage = "<li style='text-align:".$options->mileage_justify."'><span style='font-weight:".$options->mileage_bold."; color:".$options->mileage_color."; font-size:".$options->mileage_size."'>적립금 : ".number_format($info->mileage_price)."</span></li>";

			return $result_mileage;
		}
	}

}


add_filter("product_loop_shortdesc", "product_loop_shortdesc", 30, 2);

function product_loop_shortdesc($options, $post_id){

	$info = new kingkong_product($post_id);

	if($info->short_desc){
		$result_short_desc = "<li style='line-height:100%; text-align:".$options->shortdesc_justify."'><span style='font-weight:".$options->shortdesc_bold."; color:".$options->shortdesc_color."; font-size:".$options->shortdesc_size."'>".$info->short_desc."</span></li>";

		return $result_short_desc;
	}	
}

add_filter("product_loop_discount", "product_loop_discount", 30, 2);

function product_loop_discount($options, $post_id){

	$info = new kingkong_product($post_id);

	if($info->discount_price){
		$result_discount_price = "<li style='text-align:".$options->discount_justify."'><span style='font-weight:".$options->discount_bold."; color:".$options->discount_color."; font-size:".$options->discount_size."; text-decoration:line-through'>".number_format($info->original_price)."</span></li>";

		return $result_discount_price;
	}
}

add_filter("product_loop_no_have", "product_loop_no_have", 30);

function product_loop_no_have(){

	return "<li>등록된 상품이 없습니다.</li>";

}

/* 넘어온 스타일링 옵션이 존재하는지 유무를 체크 ********************************************************/

function check_exist_option($key){

	$get_options = unserialize(get_option("kingkongcart-display"));
	$display = $get_options['display'];
	if(strpos($display, $key) !== false){
		return true;
	}
	else {
		return false;
	}
}



/* 넘어온 스타일링 옵션 (가격,썸네일,짤막소개,적립금 등) 개개의 value 값을 넘겨줌 *****************************/

function display_option_value($key){

	if (check_exist_option($key)){

		$get_options = unserialize(get_option("kingkongcart-display"));

		$value = $get_options[$key];

		return $value;

	}
}



/* 쿠폰이 받은쿠폰인지 안받는 쿠폰인지를 체크한다. ********************************************************/

function check_coupon_get($post_id){

	if(is_user_logged_in()){

		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));

		for ($i=0; $i < count($user_coupons); $i++) { 
			$user_coupon_id = $user_coupons[$i]['coupon_id'];

			if($user_coupon_id == $post_id){
				$duplicate = 1;
			}
		}

		if($duplicate == 1){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}

}


/* 킹콩카트 product loop 페이지에 적용되는 관리자모드에서 설정한 스타일링 옵션 값들을 넘겨줌 *********************/

function get_kingkongcart_product_loop_options(){

	$get_options = unserialize(get_option("kingkongcart-display"));
	$options->fonts				= $get_options['fonts'];
	$options->display 			= $get_options['display'];
	$options->grid_width 		= $get_options['grid-width'];
	$options->row_num			= $get_options['row-num'];
	$options->title_color		= $get_options['title-color'];
	$options->shortdesc_color 	= $get_options['shortdesc-color'];
	$options->price_color		= $get_options['price-color'];
	$options->mileage_color		= $get_options['mileage-color'];
	$options->discount_color	= $get_options['discount-color'];
	$options->title_size		= $get_options['title-size'];
	$options->shortdesc_size	= $get_options['shortdesc-size'];
	$options->price_size		= $get_options['price-size'];
	$options->mileage_size		= $get_options['mileage-size'];
	$options->discount_size		= $get_options['discount-size'];
	$options->title_justify		= $get_options['title-justify'];
	$options->shortdesc_justify	= $get_options['shortdesc-justify'];
	$options->price_justify		= $get_options['price-justify'];
	$options->mileage_justify	= $get_options['mileage-justify'];
	$options->discount_justify	= $get_options['discount-justify'];
	$options->title_bold		= $get_options['title-bold'];
	$options->shortdesc_bold	= $get_options['shortdesc-bold'];
	$options->price_bold		= $get_options['price-bold'];
	$options->mileage_bold		= $get_options['mileage-bold'];
	$options->discount_bold		= $get_options['discount-bold'];

	if(!$options->title_size){
		$options->title_size = "14px";
	}

	if(!$options->shortdesc_size){
		$options->shortdesc_size = "12px";
	}

	if(!$options->price_size){
		$options->price_size = "14px";
	}

	if(!$options->mileage_size){
		$options->mileage_size = "11px";
	}

	if(!$options->discount_size){
		$options->discount_size = "14px";
	}

	return $options;

}





/* 폰트 value 값으로 일치하는 폰트체를 넘겨준다. ********************************************************/

function product_style_fonts($fonts){

		switch($fonts){
			case "gulim" :
				$fonts_value = "font-family:'굴림', Gulim, 'Apple SD Gothic Neo', '돋움', dotum, sans-serif;";
			break;

			case "dotum" :
				$fonts_value = "font-family:'돋움', dotum, 'Apple SD Gothic Neo', '굴림', Gulim, sans-serif;";
			break;

			case "malgun" :
				$fonts_value = "font-family:'맑은고딕', malgungothic, 'Apple SD Gothic Neo', '돋움', Dotum, '굴림', Gulim, sans-serif;";
			break;

			case "serif" :
				$fonts_value = "font-family:serif, sans-serif, Monospace;";
			break;

			case "sans-serif" :
				$fonts_value = "font-family:sans-serif, serif, Monospace;";
			break;

			default :
				$fonts_value = "";
			break;
		}

		return $fonts_value;
}





/* 상품의 가격, 할인가격, 적립금, 짤막소개등을 넘겨준다. ***********************************************************/

function get_product_info($post_id){

		$all_info = unserialize(get_post_meta($post_id, "kingkongcart-product-info", true));

		$info->original_price 	= $all_info[0]; 	// 소비자 판매가격
		$info->result_price 	= $all_info[1];		// 할인적용 판매가격
		$info->discount_rate  	= $all_info[2];		// 할인율
		$info->discount_price 	= $all_info[3];		// 할인금액
		$info->mileage_rate		= $all_info[4];		// 마일리지
		$info->mileage_price	= $all_info[5];		// 마일리지 금액
		$info->short_desc		= $all_info[6];		// 짤막소개

		if(!$info->discount_price){
			$info->final_price = $info->original_price;
		}
		else {
			$info->final_price = $info->result_price;
		}
		return $info;
}

function get_kingkong_cart(){

	global $current_user;

	if ( is_user_logged_in() ){ //로그인한 상태

		get_currentuserinfo();
		$user_id = $current_user->ID;

		$carts = get_user_meta($user_id, "kingkongcart-cart", true);	
		$cart = unserialize($carts);	

	} else {

		$cart = unserialize( base64_decode( $_COOKIE['kingkongcart-cart'] ) );
	}

	return $cart;
}

function get_kingkong_wish(){

	global $current_user;

	get_currentuserinfo();
	$user_id = $current_user->ID;

	$wishs = get_user_meta($user_id, "kingkongcart-wish", true);	
	$wish = unserialize($wishs);

	return $wish;
}

function create_kingkong_order_number(){

	$rand_number = rand(100,999);

	return KINGKONGCART_ORDER_PREFIX.date("Y").date("m").date("d").date("i").date("s").$rand_number;

}

function get_bank_name($bank_code){

	switch($bank_code){

		case "03" :
			$bank_name = "기업은행";
		break;

		case "04" :
			$bank_name = "국민은행";
		break;

		case "05" :
			$bank_name = "외환은행";
		break;

		case "07" :
			$bank_name = "수협중앙회";
		break;

		case "11" :
			$bank_name = "농협중앙회";
		break;

		case "20" :
			$bank_name = "우리은행";
		break;

		case "23" :
			$bank_name = "SC 제일은행";
		break;

		case "31" :
			$bank_name = "대구은행";
		break;

		case "32" :
			$bank_name = "부산은행";
		break;

		case "34" :
			$bank_name = "광주은행";
		break;

		case "37" :
			$bank_name = "전북은행";
		break;

		case "39" :
			$bank_name = "경남은행";
		break;

		case "53" :
			$bank_name = "한국씨티은행";
		break;

		case "71" :
			$bank_name = "우체국";
		break;

		case "81" :
			$bank_name = "하나은행";
		break;

		case "88" :
			$bank_name = "통합신한은행 (신한,조흥은행)";
		break;

		case "D1" :
			$bank_name = "동양종합금융증권";
		break;

		case "D2" :
			$bank_name = "현대증권";
		break;

		case "D3" :
			$bank_name = "미래에셋증권";
		break;

		case "D4" :
			$bank_name = "한국투자증권";
		break;

		case "D5" :
			$bank_name = "우리투자증권";
		break;

		case "D6" :
			$bank_name = "하이투자증권";
		break;

		case "D7" :
			$bank_name = "HMC 투자증권";
		break;

		case "D8" :
			$bank_name = "SK 증권";
		break;

		case "D9" :
			$bank_name = "대신증권";
		break;

		case "DA" :
			$bank_name = "하나대투증권";
		break;

		case "DB" :
			$bank_name = "굿모닝신한증권";
		break;

		case "DC" :
			$bank_name = "동부증권";
		break;

		case "DD" :
			$bank_name = "유진투자증권";
		break;

		case "DE" :
			$bank_name = "메리츠증권";
		break;

		case "DF" :
			$bank_name = "신영증권";
		break;

		default :
			$bank_name = "알수없음";
		break;

	}

		return $bank_name;
}





function update_order_meta($order_id, $key, $value){

    global $wpdb;

    $search_table = $wpdb->prefix . "kingkong_order_meta";

    $order_meta = $wpdb->get_row("SELECT meta_value from $search_table where order_id = '".$order_id."' and meta_key = '".$key."' limit 1");

    if($order_meta->meta_value){

		$wpdb->update( 
			$search_table,
			array( 'meta_value' => $value ), 
			array( 'order_id' => $order_id ),
			array( '%s' ),
			array( '%d' ) 
		);

    }
    else {

		$wpdb->insert( 
			$search_table, 
			array( 
				'order_id' 			=> $order_id,
				'meta_key'			=> $key,
				'meta_value'		=> $value
			));
    }

}



function update_board_meta($order_id, $key, $value){

    global $wpdb;

    $search_table = $wpdb->prefix . "kingkong_board_meta";

    $board_meta = $wpdb->get_row("SELECT meta_value from $search_table where order_id = '".$order_id."' and meta_key = '".$key."' limit 1");

    if($board_meta->meta_value){

		$wpdb->update( 
			$search_table,
			array( 'meta_value' => $value ), 
			array( 'order_id' => $order_id ),
			array( '%s' ),
			array( '%d' ) 
		);

    }
    else {

		$wpdb->insert( 
			$search_table, 
			array( 
				'order_id' 			=> $order_id,
				'meta_key'			=> $key,
				'meta_value'		=> $value
			));
    }

}



function get_order_meta($order_id,$key){
    global $wpdb;
    $search_table = $wpdb->prefix . "kingkong_order_meta";
	$order_meta = $wpdb->get_row("SELECT * from $search_table where order_id = '".$order_id."' and meta_key = '".$key."' limit 1");
	if($order_meta){
		return $order_meta->meta_value;
	}

}

function get_order_status($status){

	switch($status){

		case 0 :
			$value = "<span style='color:#cecece'>입금대기</span>";
		break;

		case 1 :
			$value = "<span style='color:#f28b00'>결제완료</span>";
		break;

		case 2 :
			$value = "<span style='color:#3ad531'>배송대기</span>";
		break;

		case 3 :
			$value = "<span style='color:#00a1e0'>배송완료</span>";
		break;
	}

	return $value;
}

function get_user_login_id($id){

	$user_info = get_userdata($id);

	if($user_info){
		$value = $user_info->user_login;
	} else {
		$value = "비회원";
	}

	return $value;

}

function get_shipping_company(){

	$shipping_company = array(
		array(
				'name' => '우체국택배',
				'code' => 'PO'
			),
		array(
				'name' => '경동택배',
				'code' => 'GD'
			),
		array(
				'name' => '로젠택배',
				'code' => 'LG'
			),
		array(
				'name' => '대한통운',
				'code' => 'DH'
			),
		array(
				'name' => 'CJ GLS',
				'code' => 'CG'
			),
		array(
				'name' => '한진택배',
				'code' => 'HJ'
			),
		array(
				'name' => '현대택배',
				'code' => 'HD'
			),
		array(
				'name' => 'KGB택배',
				'code' => 'KB'
			),
		array(
				'name' => '옐로우캡',
				'code' => 'YC'
			),
		array(
				'name' => '방문수령',
				'code' => 'NN'
			)
	);

	return $shipping_company;
}

function get_shipping_company_name($code){

	switch($code){

		case "PO" :
		$value = "우체국택배";
		break;
		case "GD" :
		$value = "경동택배";
		break;
		case "LG" :
		$value = "로젠택배";
		break;
		case "DH" :
		$value = "대한통운";
		break;
		case "CG" :
		$value = "CJ GLS";
		break;
		case "HJ" :
		$value = "한진택배";
		break;
		case "HD" :
		$value = "현대택배";
		break;
		case "KB" :
		$value = "KGB택배";
		break;
		case "YC" :
		$value = "옐로우캡";
		break;
		case "NN" :
		$value = "방문수령";
		break;
	}

	return $value;
}

function get_tracking_link($code){

	switch($code){

		case "PO" :
		$value = "http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=";
		break;
		case "GD" :
		$value = "http://www.kdexp.com/sub4_1.asp?stype=1&p_item=";
		break;
		case "LG" :
		$value = "http://d2d.ilogen.com/d2d/delivery/invoice_tracesearch_quick.jsp?slipno=";
		break;
		case "DH" :
		$value = "http://www.doortodoor.co.kr/servlets/cmnChnnel?tc=dtd.cmn.command.c03condiCrg01Cmd&amp;invc_no=";
		break;
		case "CG" :
		$value = "http://www.cjgls.co.kr/kor/service/service02_01.asp?slipno=";
		break;
		case "HJ" :
		$value = "http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=";
		break;
		case "HD" :
		$value = "http://www.hlc.co.kr/hydex/jsp/tracking/trackingViewCus.jsp?InvNo=";
		break;
		case "KB" :
		$value = "http://www.kgbls.co.kr/sub5/trace.asp?f_slipno=";
		break;
		case "YC" :
		$value = "http://www.yellowcap.co.kr/custom/inquiry_result.asp?INVOICE_NO=";
		break;
		case "NN" :
		$value = "#";
		break;
	}

	return $value;

}

function get_order_count($kind){

		global $wpdb;
		$order_table = $wpdb->prefix."kingkong_order";
		$order = $wpdb->get_results("SELECT ID from $order_table where status = '".$kind."' ");
		return count($order);
}

add_action( 'admin_post_download.csv', 'download_csv' );

function download_csv(){

    if ( ! current_user_can( 'manage_options' ) )
        return;

    $download ="order-list-".date('Y-m-d')."-".date('H').date('i').date('s').".csv";

	$kind = sanitize_text_field( $_GET['kind'] );
	$status = sanitize_text_field( $_GET['status'] );

    header('Content-Type: application/csv; charset=EUC-KR');
    header('Content-Disposition: attachment; filename='.$download);
    header('Pragma: no-cache');

	if($kind == "all" && $status == 2){
		global $wpdb;
		$order_table = $wpdb->prefix."kingkong_order";
		$orders = $wpdb->get_results("SELECT * from $order_table where status = '".$status."' ");

			echo iconv("UTF-8", "EUC-KR", "주문번호,결제유형,주문상품명,주문자명,주문자연락처,배송주소(도로명),배송주소(지번),배송주소(나머지),주문일자,택배사,송장번호\n");
		foreach($orders as $order){
			echo iconv("UTF-8", "EUC-KR", $order->pid.",".$order->kind.",".$order->pname.",".$order->receive_name.",".$order->receive_contact.",".$order->address_doro.",".$order->address_jibun.",".$order->address_detail.",".$order->order_date."\n");
		}
	}
}

add_action( 'admin_post_download_balance.xls', 'download_balance_xls' );

function download_balance_xls(){
    if ( ! current_user_can( 'manage_options' ) )
        return;

    $start = sanitize_text_field( $_GET['start'] );
    $end = sanitize_text_field( $_GET['end'] );

    $start_date = date("Y-m-d", $start);
    $end_date = date("Y-m-d", $end);

	header( "Content-type: application/vnd.ms-excel; charset=utf-8");  
	header( "Content-Disposition: attachment; filename = balance.xls" );

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";
	$orders = $wpdb->get_results("SELECT * from $order_table where status = '3' and mktime >= $start and mktime <= $end");

	//테이블 상단
	$excel = '
	<table border=1>
	<thead>
		<tr>
			<th colspan="15">정산자료 : '.$start_date.' ~ '.$end_date.'</th>
		</tr>
		<tr>
			<th>번호</th>
			<th>주문번호</th>
			<th>구분</th>
			<th>매출액(배송비포함)</th>
			<th>배송비</th>
			<th>매입가(VAT포함)</th>
			<th>매입원가</th>
			<th>매입가 부가세</th>
			<th>판매가(VAT포함)</th>
			<th>판매원가</th>
			<th>판매부가세</th>
			<th>실수익</th>
			<th>납부할부가세</th>
			<th>결제자명</th>
			<th>거래발생일</th>
		</tr>
	</thead>
	<tbody>';

	foreach($orders as $order){
		$order_product = unserialize(get_order_meta($order->ID, "buying_product"));	
		$shipping_cost = get_order_meta($order->ID, "shipping_cost");

			for ($i=0; $i < count($order_product); $i++) { 

				$product_id 		= $order_product[$i]['product_id'];
				$last_price 		= $order_product[$i]['price'];	// 판매가격
				$discount_price		= $order_product[$i]['discount_price'];	// 할인가격
				$mileage_price		= $order_product[$i]['mileage_price'];	// 적립금(마일리지) 원(점)
				$provide_price		= $order_product[$i]['provide_price'];	// 공급가(vat포함)	

				// 할인된 가격이 있다면 판매가격에서 제외시킨다.
				$last_price = $last_price - $discount_price;

				$gg = $provide_price;
				$gg_original = $gg / 1.1;
				$gg_vat = $gg - $gg_original;
				$gg_vat = round($gg_vat);
				$gg_original = $gg - $gg_vat;

				$sel_price = $last_price;
				$sel_original = $sel_price / 1.1;
				$sel_vat = $sel_price - $sel_original;
				$sel_vat = round($sel_vat);
				$sel_original = $sel_price - $sel_vat;

				$benefit = $sel_original - $gg_original;
				$real_vat = $sel_vat - $gg_vat;	

				$excel .= '<tr>';

				if($i == 0){
					$excel .= '
					<td rowspan="'.$order_product.'">1</td>
					<td rowspan="'.$order_product.'">'.$order->pid.'</td>
					<td rowspan="'.$order_product.'">'.$order->kind.'</td>
					<td rowspan="'.$order_product.'">'.$order->order_price.'</td>
					<td rowspan="'.$order_product.'">'.$shipping_cost.'</td>					
					';
				}

				$excel .= '
					<td>'.$gg.'</td>
					<td>'.$gg_original.'</td>
					<td>'.$gg_vat.'</td>
					<td>'.$sel_price.'</td>
					<td>'.$sel_original.'</td>
					<td>'.$sel_vat.'</td>
					<td>'.$benefit.'</td>
					<td>'.$real_vat.'</td>
					<td>'.$order->receive_name.'</td>
					<td>'.$order->order_date.'</td>
					';

				$excel .= '</tr>';
			}		
	}

	$excel .= 
	'</tbody>
	</table>
	';
  
	echo $excel;  	

}

add_action( 'admin_post_downwithproduct.csv', 'downwithproduct_csv' );

function downwithproduct_csv(){

    if ( ! current_user_can( 'manage_options' ) )
        return;

    $download ="order-list-".date('Y-m-d')."-".date('H').date('i').date('s').".csv";

	$kind = sanitize_text_field( $_GET['kind'] );
	$status = sanitize_text_field( $_GET['status'] );

    header('Content-Type: application/csv; charset=EUC-KR');
    header('Content-Disposition: attachment; filename='.$download);
    header('Pragma: no-cache');

	if($kind == "all" && $status == 2){
		global $wpdb;
		$order_table = $wpdb->prefix."kingkong_order";
		$orders = $wpdb->get_results("SELECT * from $order_table where status = '".$status."' ");

			echo iconv("UTF-8", "EUC-KR", "주문번호,결제유형,주문상품명,주문자명,주문자연락처,배송주소(도로명),배송주소(지번),배송주소(나머지),주문일자,택배사,송장번호\n");
		foreach($orders as $order){
			echo iconv("UTF-8", "EUC-KR", $order->pid.",".$order->kind.",".$order->pname.",".$order->receive_name.",".$order->receive_contact.",".$order->address_doro.",".$order->address_jibun.",".$order->address_detail.",".$order->order_date."\n");

			$buying = get_order_meta($order->ID, "buying_product");
			$buying = unserialize($buying);

			for ($i=0; $i < count($buying); $i++) { 
				if($i == 0){
					echo iconv("UTF-8", "EUC-KR", "주문번호,상세 상품명,옵션1,옵션2,수량\n");
					echo iconv("UTF-8", "EUC-KR", $order->pid.",".get_the_title($buying[$i]['product_id']).",".$buying[$i]['first']['name'].",".$buying[$i]['second']['name'].",".$buying[$i]['quantity']."\n");
					if($i == (count($buying) - 1) ){
						echo "\n";
					}
				} else {
					echo iconv("UTF-8", "EUC-KR", $order->pid.",".get_the_title($buying[$i]['product_id']).",".$buying[$i]['first']['name'].",".$buying[$i]['second']['name'].",".$buying[$i]['quantity']."\n");
					if($i == (count($buying) - 1) ){
						echo "\n";
					}
				}
			}
		}
	}
}


add_action( 'admin_post_upload.csv', 'upload_csv' );

function upload_csv(){

setlocale(LC_CTYPE, 'ko_KR.eucKR'); //CSV데이타 추출시 한글깨짐방지 
if($_FILES['csv_upfile']['tmp_name']){ // upfile 로 입력된 파일이 있는가? 
		$row_count = count(file($_FILES['csv_upfile']['tmp_name'])); //전체 라인 수
    if (($handle = fopen($_FILES['csv_upfile']['tmp_name'], "r")) !== FALSE) { // 파일 열기 성공?
   		$Cnt = 0;
    while (($data = fgetcsv($handle,1000, ",")) !== FALSE) { // csv 읽기 성공?
        $pid = $data[0];
        $company = $data[1];
        $account = $data[2];
        $shipping_date = date("Y-m-d H:i:s");

		$shipping_info = array(
			'company' => $company,
			'account' => $account,
			'date'	=> $shipping_date
		);

		global $wpdb;
		$order_table = $wpdb->prefix."kingkong_order";
		$order = $wpdb->get_row("SELECT ID from $order_table where pid = '".$pid."' limit 1");


		$wpdb->update( 
			$order_table, 
			array( 
				'status'			=> '3'
			),
			array( 'pid' => $pid ),
			array( '%s' ),
			array( '%d' ) 
		);



		$shipping_info = serialize($shipping_info);

		update_order_meta($order->ID, "shipping_info", $shipping_info);

         $Cnt++;

         if($Cnt == $row_count){
         	echo "
         	<script>
         	parent.complete_csv_import('success');
         	</script>";
         }
      }
   } else {
   		echo "
   		<script>
   		parent.complete_csv_import('failed');
   		</script>
   		";
   }
}
}

function check_shipping_cost($price){

		$shipping = get_option("kingkong_shipping");
		$shipping = unserialize($shipping);

		if($price < $shipping['free']){
			return $shipping['basic'];
		} else {
			return 0;
		}

}


function get_board_meta($order_id,$key){
    global $wpdb;
    $search_table = $wpdb->prefix . "kingkong_board_meta";
	$board_meta = $wpdb->get_row("SELECT * from $search_table where order_id = '".$order_id."' and meta_key = '".$key."' limit 1");
	if($board_meta){
		return $board_meta->meta_value;
	}

}





function get_user_buying_total_price($user_id){
	global $wpdb;
	$order_table = $wpdb->prefix . "kingkong_order";
	$orders = $wpdb->get_results("SELECT ID, order_price from $order_table where order_id = '".$user_id."' and status > '0' ");

	if($orders){
		foreach ($orders as $order){
			$shipping_cost = get_order_meta($order->ID, "shipping_cost");
			if($shipping_cost){
				$price += ($order->order_price - $shipping_cost);
			} else {
				$price += ($order->order_price);
			}
		}
	} else {
		$price = 0;
	}

	return $price;
}

function get_paykind_option_name($paykind){

	switch($paykind){

		case "Card" :
			$value = "신용카드 결제";
		break;

		case "VCard" :
			$value = "신용카드 ISP결제";
		break;

		case "DirectBank" :
			$value = "실시간 은행계좌이체";
		break;

		case "HPP" :
			$value = "핸드폰 결제";
		break;

		case "PhoneBill" :
			$value = "받는전화결제";
		break;

		case "Ars1588Bill" :
			$value = "1588 전화 결제";
		break;

		case "VBank" :
			$value = "무통장 입금(가상계좌)";
		break;

		case "OCBPoint" :
			$value = "OK 캐쉬백포인트 결제";
		break;

		case "Culture" :
			$value = "문화상품권 결제";
		break;

		case "kmerce" :
			$value = "K-merce 상품권 결제";
		break;

		case "TeenCash" :
			$value = "틴캐시 결제";
		break;

		case "dgcl" :
			$value = "스마트 문상 결제";
		break;

		case "BCSH" :
			$value = "도서문화 상품권 결제";
		break;

		case "MMLG" :
			$value = "M마일리지 결제";
		break;

		case "YPAY" :
			$value = "옐로페이 결제";
		break;
		
	}

	return $value;
}


function added_email_domain(){

	$email_domain = array(
		"naver.com",
		"hotmail.com",
		"hanmail.net",
		"yahoo.co.kr",
		"paran.com",
		"nate.com",
		"empal.com",
		"dreamwiz.com",
		"hanafos.com",
		"korea.com",
		"gmail.com",
		"lycos.co.kr",
		"netian.com",
		"hanmir.com",
		"sayclub.com"
	);

	return $email_domain;
}



function kingkong_order_proc($pid,$status,$kind,$pname,$order_id,$order_price,$receive_name,$reseive_contact,$address,$else_address,$shipping_cost,$mileage,$input_mileage){

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";

	$wpdb->insert( 
		$order_table, 
		array( 
			'pid' 				=> $pid,
			'status'			=> $status,
			'kind'				=> $kind,
			'pname'				=> $pname,
			'order_id'			=> $order_id,
			'order_price' 		=> $order_price,
			'receive_name'		=> $receive_name,
			'receive_contact' 	=> $receive_contact,
			'address_doro'		=> $address,
			'address_jibun'		=> $address,
			'address_detail'	=> $else_address,
			'mktime'			=> mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
			'order_date'		=> date("Y-m-d H:i:s")
		) 
	);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];

	$orders = $wpdb->get_row("SELECT ID from $order_table where pid = '".$pid."' limit 1");

	update_order_meta($orders->ID, "buying_product", serialize($_SESSION['temp_kingkongcart_product']) );
	update_order_meta($orders->ID, "shipping_cost", $shipping_cost ); 	// 배송비 저장


	$auto_quantity 	= get_option("kingkongcart_auto_quantity");

	if($auto_quantity == "T"){
/*
*
* 주문시 재고 처리 로직 시작 ///////////////////////////////////////////////////////////////////////////////////////////////////
*
*/
	$all_products = $_SESSION['temp_kingkongcart_product'];

		for ($i=0; $i < count($all_products); $i++) { 

			// 주문상품 제고 처리 시작

			// 첫번째 옵션명, 두번째 옵션명
			$first_option_name 	= $all_products[$i]['first']['name'];
			$second_option_name = $all_products[$i]['second']['name'];

			// 개개 메타값을 불러온다.
			$poptions = get_post_meta($all_products[$i]['product_id'], 'kingkongcart-product-option', true );
			$poptions = unserialize($poptions);

			for ($o=0; $o < count($poptions); $o++) { 
				$main_option_name 	= $poptions[$o]['main']['name'];
				$main_option_price	= $poptions[$o]['main']['plus_price'];
				$main_option_amount = $poptions[$o]['main']['total_amount'];
				$main_option_status = $poptions[$o]['main']['option_status'];

				// 두번째 옵션의 개수
				$sub_option_count	= count($poptions[$o]['sub']);

				// 첫번째 옵션 명이 일치 한다면
				if($main_option_name == $first_option_name){
					
					// 첫번째 전체 제고 개수에서 주문 수량만큼을 제외시킨다.
					$poptions[$o]['main']['total_amount'] = $main_option_amount - $all_products[$i]['quantity'];


					// 두번째 옵션 리스트 만큼 반복
					for ($s=0; $s < $sub_option_count; $s++) { 
						
						$sub_option_name 	= $poptions[$o]['sub'][$s]['name'];
						$sub_option_price	= $poptions[$o]['sub'][$s]['plus_price'];
						$sub_option_amount	= $poptions[$o]['sub'][$s]['total_amount'];
						$sub_option_status	= $poptions[$o]['sub'][$s]['option_status'];

						// 두번째 옵션 명이 일치 한다면
						if($second_option_name == $sub_option_name){

							// 두번째 전체 재고 개수에서 주문 수량만큼을 감한다.
							$poptions[$o]['sub'][$s]['total_amount'] = $sub_option_amount - $all_products[$i]['quantity'];

						} 

					}					

				}

			}

			// 변경된 옵션 재고수량을 다시 업데이트 한다.
			$poptions = serialize($poptions);
			update_post_meta($all_products[$i]['product_id'], 'kingkongcart-product-option', $poptions );
		}

/*
*
* 주문시 재고 처리 로직 끝 /////////////////////////////////////////////////////////////////////////////////////////////////////
*
*/
		} // if auto_quantity == 'T'


	if($mileage_status == "T"){
		update_order_meta($orders->ID, "mileage", $kingkongcart_pay_mileage );				// 마일리지 저장
		update_order_meta($orders->ID, "using_mileage", $kingkongcart_pay_input_mileage);	// 사용한 마일리지
		// 현재마일리지를 불러와 입력 한 마일리지를 공제 후 다시 업데이트 한다.
		$current_user_mileage = get_user_meta($user_id, "kingkong_mileage", true);
		$calculated_milage = $current_user_mileage - $kingkongcart_pay_input_mileage;
		update_user_meta($order_id, "kingkong_mileage", $calculated_milage);
	}
/* wp_mail 관리자 전송 */

	//$admin_email = get_option( 'admin_email' );
	//$email_contents = "<p>신규주문이 들어왔습니다.</p><p>결제금액 :".$kingkongcart_pay_price."</p>";
	//wp_mail($admin_email, '신규주문이 추가 되었습니다.', $email_contents);


	$_SESSION['temp_kingkongcart_product'] = ""; //세션 제거

}


?>