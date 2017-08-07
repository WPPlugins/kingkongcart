<?php

/* 배열 인덱스 재정렬에 필요한 function *******************************************************************/

function cmp($value, $result){
	return strcmp($value[0], $result[0]);
}




/* 쿠폰을 받는다. **************************************************************************************/

add_action( 'wp_ajax_get_coupon', 'get_coupon' );
add_action( 'wp_ajax_nopriv_get_coupon', 'get_coupon' );

function get_coupon(){

	$post_id = $_POST['post_id'];

	if ( is_user_logged_in() ){ //로그인한 상태

		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		// 전체 쿠폰 개수
		$coupon_count = get_post_meta($post_id, "coupon_count", true);

		if($coupon_count > 0){
			
			// 쿠폰 전체 개수에서 차감
			$coupon_count = $coupon_count - 1;
			// 쿠폰 개수 업데이트
			update_post_meta($post_id, "coupon_count", $coupon_count);

			// 사용자 meta 정보에 쿠폰을 등록, 이미 받았으면 중복 방지
			$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));

				for ($i=0; $i < count($user_coupons); $i++) { 
					$user_coupon_id = $user_coupons[$i]['coupon_id'];

					if($user_coupon_id == $post_id){
						$duplicate = 1;
					}
				}
			

			if($duplicate == 1){
				echo "duplicate";
			} else {
				// 쿠폰을 등록한다.

				$get_coupon = unserialize(get_post_meta($post_id, "coupon_detail", true));

		    	$capability 		= $get_coupon['capability']; 		// 사용범위 (전체 : all, 일부 : limit)
		    	$coupon_kind 		= $get_coupon['coupon_kind']; 		// 쿠폰종류 (무료배송:1,정액제:2,할인:3)
		    	$coupon_discount 	= $get_coupon['coupon_discount'];	// 할인금액 (무료배송일 경우 배송설정에 책정된 배송비로 고정)
		    	$start_date 		= $get_coupon['start_date'];		// 사용기간 시작일
		    	$end_date 			= $get_coupon['end_date'];			// 사용기간 종료일
		    	$added_product 		= $get_coupon['added_product'];		// 등록된 카테고리나 상품 (카테고리는 cat-term_id, 상품은 prd-post_id)
		    	$min_price 			= $get_coupon['min_price'];
		    	$coupon_image_url	= $get_coupon['coupon_image_url'];	// 등록된 쿠폰 이미지

				$coupon_adding = array(
					'capability'		=> $capability,
					'coupon_id' 		=> $post_id,
					'coupon_name' 		=> get_the_title($post_id),
					'coupon_kind'		=> $coupon_kind,
					'coupon_discount' 	=> $coupon_discount,
					'start_date'		=> $start_date,
					'end_date'			=> $end_date,
					'min_price'			=> $min_price,
					'added_product'		=> $added_product
				);

				if($user_coupons){
					$count_coupons = count($user_coupons);
					$user_coupons[$count_coupons] = $coupon_adding;
					$result_option = $user_coupons;
					$result_option = serialize($result_option);
					update_user_meta($user_id, "added_coupon", $result_option);
				} else {
					$result_coupon[0] = $coupon_adding;
					$result_coupon = serialize($result_coupon);
					update_user_meta($user_id, "added_coupon", $result_coupon);
				}



			}

		} else {
			// 쿠폰 전체 개수 부족
			echo "empty";
		}

	} else {
		echo "not_login";
	}

	die();

}



/* 장바구니에서 해당 상품을 삭제 한다. **********************************************************************/

add_action( 'wp_ajax_kingkongcart_remove_cart', 'kingkongcart_remove_cart' );
add_action( 'wp_ajax_nopriv_kingkongcart_remove_cart', 'kingkongcart_remove_cart' );

function kingkongcart_remove_cart(){

	$id		= sanitize_text_field( $_POST['id'] );
	$cart 	= get_kingkong_cart(); // 장바구니 정보를 불러온다.

	unset($cart[$id]);
	rsort($cart);
	usort($cart, "cmp");


	if ( is_user_logged_in() ){ //로그인한 상태

		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		$cart = serialize($cart);
		
		update_user_meta($user_id, "kingkongcart-cart", $cart);

	} else {

		setcookie("kingkongcart-cart", base64_encode( serialize($cart) ), (time()+3600), "/");

	}

	return true;

	die();
}



 

/* 장바구니에서 해당 상품을 삭제 한다. **********************************************************************/

add_action( 'wp_ajax_kingkongcart_remove_wish', 'kingkongcart_remove_wish' );
add_action( 'wp_ajax_nopriv_kingkongcart_remove_wish', 'kingkongcart_remove_wish' );

function kingkongcart_remove_wish(){

	$id		= sanitize_text_field( $_POST['id'] );
	$wish 	= get_kingkong_wish(); // 장바구니 정보를 불러온다.

	unset($wish[$id]);
	rsort($wish);
	usort($wish, "cmp");


	if ( is_user_logged_in() ){ //로그인한 상태

		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		$wish = serialize($wish);
		
		update_user_meta($user_id, "kingkongcart-wish", $wish);

	} else {

		setcookie("kingkongcart-wish", base64_encode( serialize($wish) ), (time()+3600), "/");

	}

	return true;

	die();
}





/* 위시리스트에서 해당 상품을 장바구니로 이동시킨후 위시리스트에서 해당 상품을 삭제한다. ****************************/

add_action( 'wp_ajax_kingkongcart_go_cart', 'kingkongcart_go_cart' );
add_action( 'wp_ajax_nopriv_kingkongcart_go_cart', 'kingkongcart_go_cart' );


function kingkongcart_go_cart(){

		$id = $_POST['id'];
		$duplicate_cnt = 0;

	if ( is_user_logged_in() ){

		$wish = get_kingkong_wish();

		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		$get_cart = get_user_meta($user_id, "kingkongcart-cart", true);

		if($get_cart){
			$get_cart = unserialize($get_cart);
			$get_cart_count = count($get_cart);
		} else {
			$get_cart_count = 0;
		}

		$insert_cart[0] = array(
			'product_id' => $wish[$id]['product_id'],
			'quantity'	 => $wish[$id]['quantity'],
			'discount_price' => $wish[$id]['discount_price'],
			'price'	=> $wish[$id]['price'],
			'provide_price' => $wish[$id]['provide_price'],
			'mileage_price' => $wish[$id]['mileage_price'],
			'first'	=> array(
				'id' 			=> $wish[$id]['first']['id'],
				'name'			=> $wish[$id]['first']['name'],
				'plus_price' 	=> $wish[$id]['first']['plus_price']
			),
			'second' => array(
				'id' 			=> $wish[$id]['second']['id'],
				'name'			=> $wish[$id]['second']['name'],
				'plus_price' 	=> $wish[$id]['second']['plus_price']
			)
		);

		if($get_cart_count == 0){
			$insert_cart = serialize($insert_cart);
			update_user_meta($user_id, "kingkongcart-cart", $insert_cart);
		} else {

		for ($i=0; $i < count($get_cart); $i++) { 
			if( ( $get_cart[$i]['product_id'] 	== $product_id ) and 
				( $get_cart[$i]['first']['id'] 	== $option_first_id	) and 
				( $get_cart[$i]['second']['id'] == $option_second_id ) )
			{
				$duplicate_cnt++;
			}		
		}

		if ( $duplicate_cnt == 0 ){

			$get_cart[$get_cart_count] = array(
				'product_id' => $wish[$id]['product_id'],
				'quantity'	 => $wish[$id]['quantity'], 
				'discount_price' => $wish[$id]['discount_price'],
				'price'	=> $wish[$id]['price'],
				'provide_price' => $wish[$id]['provide_price'],
				'mileage_price' => $wish[$id]['mileage_price'],
				'first' => array(
					'id' 		 => $wish[$id]['first']['id'],
					'name'		 => $wish[$id]['first']['name'],
					'plus_price' => $wish[$id]['first']['plus_price']
				),
				'second' => array(
					'id' 		 => $wish[$id]['second']['id'],
					'name' 		 => $wish[$id]['second']['name'],
					'plus_price' => $wish[$id]['second']['plus_price']
				)		
			);

			$get_cart = serialize($get_cart);
			update_user_meta($user_id, "kingkongcart-cart", $get_cart);

		}
	}

} else {

	$duplicate_cnt = "000002";

}
	echo $duplicate_cnt;
	die();

}





/* 장바구니 비우기  ********************************************************************************/

add_action( 'wp_ajax_remove_cart_all', 'remove_cart_all' );
add_action( 'wp_ajax_nopriv_remove_cart_all', 'remove_cart_all' );


function remove_cart_all(){

	if ( is_user_logged_in() ){

	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;

	delete_user_meta($user_id, "kingkongcart-cart");
	setcookie("kingkongcart-cart", "", 3600, "/");

	} else {

	setcookie("kingkongcart-cart", "", 3600, "/");		

	}
	die();
}




/* 위시리스트 비우기  ********************************************************************************/

add_action( 'wp_ajax_remove_wish_all', 'remove_wish_all' );
add_action( 'wp_ajax_nopriv_remove_wish_all', 'remove_wish_all' );


function remove_wish_all(){

	if ( is_user_logged_in() ){

	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;

	delete_user_meta($user_id, "kingkongcart-wish");

	}
	die();
}





/* 장바구니에서 해당 상품을 위시리스트로 이동시킨후 장바구니에서 해당 상품을 삭제한다. *****************************/

add_action( 'wp_ajax_kingkongcart_go_wish', 'kingkongcart_go_wish' );
add_action( 'wp_ajax_nopriv_kingkongcart_go_wish', 'kingkongcart_go_wish' );


function kingkongcart_go_wish(){

	$id = $_POST['id'];
	$duplicate_cnt = 0;

if ( is_user_logged_in() ){

	$cart = get_kingkong_cart();

	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;

	$get_wish = get_user_meta($user_id, "kingkongcart-wish", true);

	if($get_wish){
		$get_wish = unserialize($get_wish);
		$get_wish_count = count($get_wish);
	}
	else {
		$get_wish_count = 0;
	}

	$insert_wish[0] = array(
		'product_id' => $cart[$id]['product_id'],
		'quantity'	 => $cart[$id]['quantity'],
		'discount_price' => $cart[$id]['discount_price'],
		'price'	=> $cart[$id]['price'],
		'provide_price' => $cart[$id]['provide_price'],
		'mileage_price' => $cart[$id]['mileage_price'],
		'first' => array(
			'id' 		 => $cart[$id]['first']['id'],
			'name'		 => $cart[$id]['first']['name'],
			'plus_price' => $cart[$id]['first']['plus_price']
		),
		'second' => array(
			'id' 		 => $cart[$id]['second']['id'],
			'name' 		 => $cart[$id]['second']['name'],
			'plus_price' => $cart[$id]['second']['plus_price']
		)		
	);

	if($get_wish_count == 0){

		$insert_wish = serialize($insert_wish);
		update_user_meta($user_id, "kingkongcart-wish", $insert_wish);

	} else {

		for ($i=0; $i < count($get_wish); $i++) { 
			if( $get_wish[$i]['product_id'] == $product_id ){
				$duplicate_cnt++;
			}			
		}

		if ( $duplicate_cnt == 0 ){

			$get_wish[$get_wish_count] = array(
				'product_id' => $cart[$id]['product_id'],
				'quantity'	 => $cart[$id]['quantity'], 
				'discount_price' => $cart[$id]['discount_price'],
				'price'	=> $cart[$id]['price'],
				'provide_price' => $cart[$id]['provide_price'],
				'mileage_price' => $cart[$id]['mileage_price'],
				'first' => array(
					'id' 		 => $cart[$id]['first']['id'],
					'name'		 => $cart[$id]['first']['name'],
					'plus_price' => $cart[$id]['first']['plus_price']
				),
				'second' => array(
					'id' 		 => $cart[$id]['second']['id'],
					'name' 		 => $cart[$id]['second']['name'],
					'plus_price' => $cart[$id]['second']['plus_price']
				)		
			);

			$get_wish = serialize($get_wish);
			update_user_meta($user_id, "kingkongcart-wish", $get_wish);

		}
	}

} // end first if
else {

	$duplicate_cnt = "000002";

}
	echo $duplicate_cnt;
	die();

}




/* 싱글페이지에서 장바구니에 상품을 등록한다. **********************************************************************/

add_action( 'wp_ajax_go_cart', 'go_cart' );
add_action( 'wp_ajax_nopriv_go_cart', 'go_cart' );

function go_cart(){

	parse_str($_POST['data'], $options);

	$product_id = $_POST['post_id'];
	$option1 	= $options['option1'];
	$option1	= explode("*-*", $option1);
	$option2	= $options['option2'];
	$option2	= explode("*-*", $option2);
	$quantity	= $options['quantity'];


	$option_first_id 		 = $option1[0];
	$option_first_name 		 = $option1[1];
	$option_first_plus_price = $option1[2];

	$option_second_id 			= $option2[0];
	$option_second_name 		= $option2[1];
	$option_second_plus_price 	= $option2[2];

	$info = unserialize(get_post_meta($product_id, 'kingkongcart-product-info', true));

	$original_price 	= $info[0];	// 소비자 판매가격
	$results_price 		= $info[1];	// 할인 적용 판매가격
	$discount_price		= $info[3];	// 할인가격
	$mileage_price		= $info[5];	// 적립금(마일리지) 원(점)
	$provide_price		= $info[7];	// 공급가(vat포함)	

	if($results_price){
		$last_price = $results_price;
	} else {
		$last_price = $original_price;
	}


	$insert_cart[0] = array(
		'product_id'  => $product_id,
		'quantity' => $quantity,
		'discount_price' => $discount_price,
		'price'	=> $last_price,
		'provide_price' => $provide_price,
		'mileage_price' => $mileage_price,
		'first' => array(
			'id' 		 => $option_first_id,
			'name'		 => $option_first_name,
			'plus_price' => $option_first_plus_price
		),
		'second' => array(
			'id' 		 => $option_second_id,
			'name' 		 => $option_second_name,
			'plus_price' => $option_second_plus_price
		)
	);

if ( is_user_logged_in() ){ //로그인한 상태

	global $current_user;
	get_currentuserinfo();

	$user_id = $current_user->ID;

	$user_cart = get_user_meta($user_id, "kingkongcart-cart", true);

	if($user_cart){
		$user_cart = unserialize($user_cart);
		$user_cart_count = count($user_cart);
			$duplicate_cnt = 0;
			for ($i=0; $i < count($user_cart); $i++) { 

				if( 
					( $user_cart[$i]['product_id'] 		== $product_id	) and 
					( $user_cart[$i]['first']['id'] 	== $option_first_id	) and 
					( $user_cart[$i]['second']['id'] 	== $option_second_id )
				){
					$duplicate_cnt++;
				}
			}
			if($duplicate_cnt == 0){
				$user_cart[$user_cart_count] = array(
					'product_id'  => $product_id,
					'quantity'	=> $quantity,
					'discount_price' => $discount_price,
					'price'	=> $last_price,
					'provide_price' => $provide_price,
					'mileage_price' => $mileage_price,
					'first' => array(
						'id' 		 => $option_first_id,
						'name'		 => $option_first_name,
						'plus_price' => $option_first_plus_price
					),
					'second' => array(
						'id' 		 => $option_second_id,
						'name' 		 => $option_second_name,
						'plus_price' => $option_second_plus_price
					)
				);

				$user_cart = serialize($user_cart);
				update_user_meta($user_id, "kingkongcart-cart", $user_cart);
			}
	}
	else {
		$insert_cart = serialize($insert_cart);
		update_user_meta($user_id, "kingkongcart-cart", $insert_cart);
	}

}
else { //로그인 하지 않은 상태

if($_COOKIE['kingkongcart-cart']){ //쿠키를 가지고 있다면 넘어온 상품정보가 값에 들어가 있는지 확인

		$user_cart = unserialize( base64_decode( $_COOKIE['kingkongcart-cart'] ) );
		$user_cart_count = count($user_cart);
			$duplicate_cnt = 0;
			for ($i=0; $i < count($user_cart); $i++) { 

				if( 
					( $user_cart[$i]['product_id'] 		== $product_id	) and 
					( $user_cart[$i]['first']['id'] 	== $option_first_id	) and 
					( $user_cart[$i]['second']['id'] 	== $option_second_id )
				){
					$duplicate_cnt++;
				}
			}
			if($duplicate_cnt == 0){
				$user_cart[$user_cart_count] = array(
					'product_id'  => $product_id,
					'quantity'	=> $quantity,
					'discount_price' => $discount_price,
					'price'	=> $last_price,
					'provide_price' => $provide_price,
					'mileage_price' => $mileage_price,
					'first' => array(
						'id' 		 => $option_first_id,
						'name'		 => $option_first_name,
						'plus_price' => $option_first_plus_price
					),
					'second' => array(
						'id' 		 => $option_second_id,
						'name' 		 => $option_second_name,
						'plus_price' => $option_second_plus_price
					)
				);

				setcookie("kingkongcart-cart", base64_encode( serialize($user_cart) ), (time()+3600), "/");
			}
}
else {

	setcookie("kingkongcart-cart", base64_encode( serialize($insert_cart) ), (time()+3600), "/");
}

}

	if($duplicate_cnt == 0){
		$duplicate_cnt = "000010"; //장바구니 등록 완료 코드
	}

	echo $duplicate_cnt;

	die();
}





/* 싱글페이지에서 위시리스트에 상품을 등록한다. **********************************************************************/

add_action( 'wp_ajax_go_wish', 'go_wish' );
add_action( 'wp_ajax_nopriv_go_wish', 'go_wish' );

function go_wish(){

	$duplicate_cnt = 0;

if ( is_user_logged_in() ){

	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;

	parse_str($_POST['data'], $options);

	$product_id = $_POST['post_id'];
	$option1 	= $options['option1'];
	$option1	= explode("*-*", $option1);
	$option2	= $options['option2'];
	$option2	= explode("*-*", $option2);
	$quantity	= $options['quantity'];

	$option_first_id 		 	= $option1[0];
	$option_first_name 		 	= $option1[1];
	$option_first_plus_price 	= $option1[2];

	$option_second_id 			= $option2[0];
	$option_second_name 		= $option2[1];
	$option_second_plus_price 	= $option2[2];


	$info = unserialize(get_post_meta($product_id, 'kingkongcart-product-info', true));

	$original_price 	= $info[0];	// 소비자 판매가격
	$results_price 		= $info[1];	// 할인 적용 판매가격
	$discount_price		= $info[3];	// 할인가격
	$mileage_price		= $info[5];	// 적립금(마일리지) 원(점)
	$provide_price		= $info[7];	// 공급가(vat포함)	


	$insert_wish[0] = array(
		'product_id'  => $product_id,
		'quantity' => $quantity,
		'discount_price' => $discount_price,
		'price'	=> $last_price,
		'provide_price' => $provide_price,
		'mileage_price' => $mileage_price,
		'first' => array(
			'id' 		 => $option_first_id,
			'name'		 => $option_first_name,
			'plus_price' => $option_first_plus_price
		),
		'second' => array(
			'id' 		 => $option_second_id,
			'name' 		 => $option_second_name,
			'plus_price' => $option_second_plus_price
		)
	);

	$get_wish = get_user_meta($user_id, "kingkongcart-wish", true);

	if($get_wish){
		$get_wish = unserialize($get_wish);
		$get_wish_count = count($get_wish);
	}
	else {
		$get_wish_count = 0;
	}

	if($get_wish_count == 0){

		$insert_wish = serialize($insert_wish);
		update_user_meta($user_id, "kingkongcart-wish", $insert_wish);

	} else {

		for ($i=0; $i < count($get_wish); $i++) { 
			if( $get_wish[$i]['product_id'] == $product_id ){
				$duplicate_cnt++;
			}			
		}

		if ( $duplicate_cnt == 0 ){

			$get_wish[$get_wish_count] = array(
				'product_id'  => $product_id,
				'quantity' => $quantity,
				'discount_price' => $discount_price,
				'price'	=> $last_price,
				'provide_price' => $provide_price,
				'mileage_price' => $mileage_price,
				'first' => array(
					'id' 		 => $option_first_id,
					'name'		 => $option_first_name,
					'plus_price' => $option_first_plus_price
				),
				'second' => array(
					'id' 		 => $option_second_id,
					'name' 		 => $option_second_name,
					'plus_price' => $option_second_plus_price
				)
			);

			$get_wish = serialize($get_wish);
			update_user_meta($user_id, "kingkongcart-wish", $get_wish);

		}
	}
} else {

	$duplicate_cnt = "000002"; // 위시리스트는 로그인을 해야 이용가능함 코드

}
	if($duplicate_cnt == 0){
		$duplicate_cnt = "000020"; //위시리스트 등록 완료
	}

	echo $duplicate_cnt;

	die();
}


/* 주문관리에서 상품 상태를 변경한다.  ******************************************************************************/

add_action( 'wp_ajax_order_status_change', 'order_status_change' );

function order_status_change(){

	/*
	* - 적용해야 될것
	* - 단계별로 구매자에게 메일발송 (주문이 접수됨, 배송준비중임, 배송완료됨)
	*/

	$id 		= sanitize_text_field( $_POST['id'] );
	$status 	= sanitize_text_field( $_POST['status'] );
	$company 	= sanitize_text_field( $_POST['company'] );
	$account 	= sanitize_text_field( $_POST['account'] );
	$shipping_date = date("Y-m-d H:i:s");

	switch($status){
		case 0 :
			$update_status = 1;
		break;

		case 1 :
			$update_status = 2;
		break;

		case 2 :
			$update_status = 3;
		break;

		case "back" :

		break;
	}

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";

	$wpdb->update( 
		$order_table, 
		array( 
			'status'			=> $update_status
		),
		array( 'ID' => $id ),
		array( '%s' ),
		array( '%d' ) 
	);

	if($company && $account){

		$shipping_info = array(
			'company' => $company,
			'account' => $account,
			'date'	=> $shipping_date
		);

		$shipping_info = serialize($shipping_info);

		update_order_meta($id, "shipping_info", $shipping_info);
	}


	$order_table = $wpdb->prefix."kingkong_order";
	$order = $wpdb->get_row("SELECT * from $order_table where ID = '".$id."' ");
	$buyer_info = unserialize(get_order_meta($order->ID, "buyer_info"));

	$buyer_id 		= $buyer_info['buyer_id'];
	$buyer_name 	= $buyer_info['buyer_name'];
	$buyer_email 	= $buyer_info['buyer_email'];
	$buyer_phone 	= $buyer_info['buyer_phone'];
	$buyer_product	= $order->pname;
	$buyer_price 	= $order->price;

	$buyer_detail   = array(
		'order_id'	=> $order->ID,
		'buyer_id'	=> $buyer_id,
		'name'		=> $buyer_name,
		'email'		=> $buyer_email,
		'phone'		=> $buyer_phone,
		'product'   => $buyer_product,
		'price'		=> $buyer_price
	);


	switch($update_status){
		case 1 :
			// 입금확인됨 HOOK
			do_action('order_paid_complete_after', $buyer_detail);
		break;

		case 2 :
			// 배송준비중 HOOK
			do_action('order_shipping_ready_after', $buyer_detail);
		break;

		case 3 :
			// 배송완료 HOOK
			do_action('order_shipping_complete_after', $buyer_detail);
		break;
	}

	echo admin_url()."/admin.php?page=kkcart_order";

	die();

}


/* 해당주문을 취소(삭제)한다.  *************************************************************************/

add_action( 'wp_ajax_order_cancle', 'order_cancle' );

function order_cancle(){

	$id = sanitize_text_field( $_POST['id'] );

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";
	$order_meta_table = $wpdb->prefix."kingkong_order_meta";

	$order = $wpdb->get_row("SELECT ID, status, order_id from $order_table where ID = '".$id."' ");


	// 사용된 적립금을 반환 시킨다. //////////////////////////////////////////////////////////////////////
		$using_mileage = get_order_meta($id, "using_mileage");
		$current_mileage = get_user_meta($order->order_id, "kingkong_mileage", true);
		$calculate_mileage = $current_mileage + $using_mileage;

		update_user_meta($order->order_id, "kingkong_mileage", $calculate_mileage);
	////////////////////////////////////////////////////////////////////////////////////////////////

	// 자동 재고 관리 설정
	$auto_quantity 	= get_option("kingkongcart_auto_quantity");


	if($auto_quantity == "T"){
	// 재고 수량을 다시 반환한다. ////////////////////////////////////////////////////////////////////////

		// 주문 옵션 정보
		$buying	 = get_order_meta($order->ID, 'buying_product');
		$buying  = unserialize($buying);

		for ($i=0; $i < count($buying); $i++) { 
			
			// 해당 상품 id
			$product_id 				= $buying[$i]['product_id'];

			// 해당 상품 첫번째 옵션명
			$buying_option_first_name 	= $buying[$i]['first']['name'];

			// 해당 상품 두번째 옵션명
			$buying_option_second_name 	= $buying[$i]['second']['name'];

			// 해당 상품 주문수량
			$buying_option_quantity		= $buying[$i]['quantity'];


			// 해당 상품 옵션 정보
			$options = get_post_meta($product_id, 'kingkongcart-product-option', true );
			$options = unserialize($options);

			for ($o=0; $o < count($options); $o++) { 
			
				// 제품 옵션 명 및 두번째 옵션 카운트
				$product_option_first_name 		= $options[$o]['main']['name'];
				$product_option_first_amount	= $options[$o]['main']['total_amount'];
				$product_option_second_count 	= count($options[$o]['sub']);

					if($buying_option_first_name == $product_option_first_name){

						$options[$o]['main']['total_amount'] = $product_option_first_amount + $buying_option_quantity;

						for ($s=0; $s < $product_option_second_count; $s++) { 
							
							$product_option_second_name 	= $options[$o]['sub'][$s]['name'];
							$product_option_second_amount	= $options[$o]['sub'][$s]['total_amount'];

							if($buying_option_second_name == $product_option_second_name){

								$options[$o]['sub'][$s]['total_amount'] = $product_option_second_amount + $buying_option_quantity;

							}

						}

					}

			}

			$options = serialize($options);
			update_post_meta($product_id, 'kingkongcart-product-option', $options );

		}

	/////////////////////////////////////////////////////////////////////////////////////////////////

	}

	$status = $order->status;

	$wpdb->delete( $order_table, array( 'ID' => $id ) );
	$wpdb->delete( $order_meta_table, array( 'order_id' => $id ) );

	switch($status){
		case 0 :
			echo admin_url()."/admin.php?page=kkcart_order&order_type=pending";
		break;

		case 1 :
			echo admin_url()."/admin.php?page=kkcart_order&order_type=new-order";
		break;

		case 2 :
			echo admin_url()."/admin.php?page=kkcart_order&order_type=shipping-ready";
		break;
	}

	die();
}





/* 게시판 Q&A 글 작성 *****************************************************************************/

add_action( 'wp_ajax_board_qna_write', 'board_qna_write' );
add_action( 'wp_ajax_nopriv_board_qna_write', 'board_qna_write' );


function board_qna_write(){

	global $wpdb, $current_user;

	get_currentuserinfo();

	parse_str( $_POST['data'], $board);

	$id 		= sanitize_text_field( $_POST['id'] );
	$type 		= $board['type'];
	$kind 		= $board['kind'];
	$title		= $board['title'];								// 제목
	$writer 	= $board['writer'];								// 작성자
	$email		= $board['email']."@".$board['email_domain'];	// 이메일
	$content	= sanitize_text_field( $_POST['content'] );							// 내용
	$password	= $board['pwd'];								// 비밀번호
	$pwd_setup	= $board['private'];							// 비밀글 설정여부
	if(!$pwd_setup){
		$pwd_setup = "F";
	}
	$date 		= date("Y-m-d h:i:s");							// 작성일

	$content 	= stripslashes_deep( $content );
	$board_info = array(
		'email' 	=> $email,
		'password' 	=> $password,
		'pwd_setup' => $pwd_setup
	);

	$board_info = serialize($board_info);

	$board_table 		= $wpdb->prefix . "kingkong_board";
	$board_meta_table 	= $wpdb->prefix . "kingkong_board_meta";

		$wpdb->insert( 
			$board_table, 
			array( 
				'pid' 			=> $id,
				'kind'			=> $kind,
				'type'			=> $type,
				'title'			=> $title,
				'content'		=> $content,
				'writer'		=> $writer,
				'user'			=> $current_user->ID,
				'date'			=> $date
			));

		$board_id = $wpdb->insert_id;

		$wpdb->insert( 
			$board_meta_table, 
			array( 
				'order_id' 		=> $board_id,
				'meta_key'		=> 'board_info',
				'meta_value'	=> $board_info
			));

	die();

}





/* 게시판 답변달기  *****************************************************************************/

add_action( 'wp_ajax_board_reply_proc', 'board_reply_proc' );
add_action( 'wp_ajax_nopriv_board_reply_proc', 'board_reply_proc' );

function board_reply_proc(){
	
	global $wpdb;

	parse_str(sanitize_text_field( $_POST['data'] ), $reply);

	$id = sanitize_text_field( $_POST['id'] );
	$content = $reply['reply_content'];

	if( current_user_can('administrator') && $content && $id ){

	$board_meta_table 	= $wpdb->prefix . "kingkong_board_meta";

		$wpdb->insert( 
			$board_meta_table, 
			array( 
				'order_id' 		=> $id,
				'meta_key'		=> 'board_reply',
				'meta_value'	=> $content
			));

		echo "1";
	} else {

		echo "0";
		
	}

	die();
}




/* 게시판 비공개글 보기  *****************************************************************************/

add_action( 'wp_ajax_board_pwd_check', 'board_pwd_check' );
add_action( 'wp_ajax_nopriv_board_pwd_check', 'board_pwd_check' );

function board_pwd_check(){
	
	parse_str(sanitize_text_field( $_POST['data'] ), $pwd );
	$id = sanitize_text_field( $_POST['id'] );
	$password = $pwd['pwd'];

	global $wpdb;
	$board_info = get_board_meta($id, "board_info");
	$board_info = unserialize($board_info);
	$board_reply 	= get_board_meta($id, "board_reply");

	if($password == $board_info['password']){

	$board_table = $wpdb->prefix."kingkong_board";
	$board = $wpdb->get_row("SELECT content from $board_table where ID = '".$id."' ");

		echo $board->content;
?>

<?php
	if ($board_reply){
?>
		<div class="board-content-answer">
			<ul>
				<li><b>관리자 답변</b></li>
				<li><?php echo $board_reply;?></li>
			</ul>
		</div>
<?php
	}
?>

<?php

	} else {
		return false;
	}

	die();
}





/* 아이디 존재여부 체크  *****************************************************************************/

add_action( 'wp_ajax_chk_uid_exist', 'chk_uid_exist' );
add_action( 'wp_ajax_nopriv_chk_uid_exist', 'chk_uid_exist' );

function chk_uid_exist(){

	$uid = sanitize_text_field( $_POST['uid'] );

	if ( username_exists( $uid ) ){
		echo "1";
	} else {
		echo "0";
	}

	die();
}





/* 이메일 존재여부 체크  *****************************************************************************/

add_action( 'wp_ajax_chk_email_exist', 'chk_email_exist' );
add_action( 'wp_ajax_nopriv_chk_email_exist', 'chk_email_exist' );

function chk_email_exist(){

	$email = sanitize_text_field( $_POST['email'] );

	if ( email_exists( $email ) ){
		echo "1";
	} else {
		echo "0";
	}

	die();
}





/* 적립금을 회원 메타 정보에 적립한다.  *************************************************************************/

add_action( 'wp_ajax_insert_order_mileage', 'insert_order_mileage' );

function insert_order_mileage(){
	
	$user_id 	= sanitize_text_field( $_POST['user_id'] );
	$id 		= sanitize_text_field( $_POST['id'] );

	$mileage = get_order_meta($id, "mileage");
	$current_user_mileage = get_user_meta($user_id, "kingkong_mileage", true);
	$sum_mileage = $current_user_mileage + $mileage;
	update_user_meta($user_id, "kingkong_mileage", $sum_mileage);
	update_order_meta($id, "mileage_send", "true");

	die();
}


/* 보유중인 쿠폰을 적용한다. ******************************************************************************/

add_action( 'wp_ajax_use_coupon', 'use_coupon' );
add_action( 'wp_ajax_nopriv_use_coupon', 'use_coupon' );

function use_coupon(){

if ( is_user_logged_in() ){
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
}
	
	$get_id   		= $_POST['user_id'];
	$coupon_id 		= $_POST['coupon_id'];
	$total_price 	= $_POST['total_price'];

	if($user_id == $get_id){
		
		$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));

		for ($i=0; $i < count($user_coupons); $i++) { 
			if($user_coupons[$i]['coupon_id'] == $coupon_id){
				$get_coupon_name 			= $user_coupons[$i]['coupon_name'];
				$get_coupon_capability		= $user_coupons[$i]['capability'];
				$get_coupon_kind 			= $user_coupons[$i]['coupon_kind'];
				$get_coupon_discount 		= $user_coupons[$i]['coupon_discount'];
				$get_start_date 			= $user_coupons[$i]['start_date'];
				$get_end_date 				= $user_coupons[$i]['end_date'];
				$min_price					= $user_coupons[$i]['min_price'];
				$using_array_id 			= $i;
			}
		}

		$result = $get_coupon_discount;

		//echo $result;
// mktime : 시간,분,초,월.일.년 2014-08-30

		$today_mktime = mktime(0,0,0,date("m"),date("d"),date("Y"));
		
		$start_explode = explode("-", $get_start_date);
		$start_mktime = mktime(0,0,0,$start_explode[1], $start_explode[2], $start_explode[0]);

		$end_explode = explode("-", $get_end_date);
		$end_mktime = mktime(0,0,0,$end_explode[1], $end_explode[2], $end_explode[0]);

		if($today_mktime >= $start_mktime and $today_mktime <= $end_mktime){

			if($total_price >= $min_price){
				echo $result;
			} else {
				echo "min_price_error";
			}
			
		} else {
			echo "time_error";
		}

	}

	die();


}



/* 적립금 사용 전 사용가능 금액 비교  ***********************************************************************/

add_action( 'wp_ajax_use_mileage', 'use_mileage' );
add_action( 'wp_ajax_nopriv_use_mileage', 'use_mileage' );

function use_mileage(){


if ( is_user_logged_in() ){
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
}

	$mileage_config = unserialize(get_option("mileage_config")); 	// 마일리지 설정 정보를 가져온다.
	$min_mileage 	= $mileage_config['min_mileage']; 				// 최소 사용 적립금
	$max_mileage 	= $mileage_config['max_mileage']; 				// 최대 사용 적립금

	$get_id 		= sanitize_text_field( $_POST['user_id'] );
	$input_mileage 	= sanitize_text_field( $_POST['mileage'] );
	$original_price = sanitize_text_field( $_POST['price'] ); 								// 배송비 제외한 상품 오리지널 가격

	if($user_id == $get_id){

		$current_mileage = get_user_meta($user_id, "kingkong_mileage", true);

		if($current_mileage >= $input_mileage){
			// 보유 마일리지가 입력한 마일리지 보다 크다면
			// 상품 가격에서 마일리지를 제외한 가격이 1000원 이상이라면 사용 가능
			if( ($original_price - $input_mileage) >= 1000 ){
				
				if($input_mileage >= $min_mileage){
					
					if($input_mileage <= $max_mileage){
						echo "1"; // 사용가능
					} else {
						echo "4";	// 최대 사용금액을 넘음, 사용불가
					}

				} else {
					echo "3";	// 최소 사용금액 미달, 사용불가
				}

			} else {
				echo "2"; //사용불가(마일리지는 상품가격이상 일 수 없고 최소 1000원 상품가액이 결제되어야 함)
			}

		} else {
			// 보유 마일리지가 입력한 마일리지 보다 작다면 사용불가
			echo "0";
		}

	}

	die();
}





/* 패스워드 일치 비교 (회원탈퇴) ***********************************************************************/

add_action( 'wp_ajax_chk_broke_pwd', 'chk_broke_pwd' );
add_action( 'wp_ajax_nopriv_chk_broke_pwd', 'chk_broke_pwd' );

function chk_broke_pwd(){

	parse_str(sanitize_text_field( $_POST['data'] ), $password);

	$pwd = $password['pwd'];

	global $current_user;
	get_currentuserinfo();
	$user_login = $current_user->user_login;

	$user = get_user_by( 'login', $user_login );

	$result = wp_check_password($pwd, $user->user_pass, $user->ID);

	if($result){
		echo "1";
	} else {
		echo "0";
	}

	die();
}





/* 문의관리 문의글 상세페이지 답변완료/수정  ***************************************************************/

add_action( 'wp_ajax_admin_board_reply', 'admin_board_reply' );

function admin_board_reply(){

	$id 	= sanitize_text_field( $_POST['id'] );
	$reply 	= sanitize_text_field( $_POST['reply'] );

	if($id && $reply){
		update_board_meta($id, "board_reply", $reply);
		echo "1";
	} else {
		echo "0";
	}

	die();

}


/* 등록된 쿠폰을 삭제한다. ******************************************************************************/

add_action ( 'wp_ajax_remove_coupon', 'remove_coupon' );

function remove_coupon(){

	$post_id = $_POST['post_id'];

	wp_delete_post($post_id);

	die();
}

/* 설정된 배송비를 불러온다. ****************************************************************************/

add_action( 'wp_ajax_get_shipping_cost', 'get_shipping_cost' );

function get_shipping_cost(){

	$get_shipping = unserialize(get_option("kingkong_shipping"));

	echo $get_shipping['basic'];

	die();

}

/* 상품 카테고리 리스트를 불러온다. **********************************************************************/

add_action( 'wp_ajax_get_products_category', 'get_products_category' );

function get_products_category(){

	$terms = get_terms('section', array(
		'hide_empty' => 0
	));

	foreach($terms as $term){
		$option_value .= "<option value='".$term->term_id."'>".$term->name."</option>";
	}

	echo "<select class='select_product_category'><option value='-1'>등록할 카테고리를 선택하세요.</option>".$option_value."</select> <input type='button' class='button' value='등록' onclick=\"add_product_into_coupon('category');\">";

	die();

}


/* 상품 리스트를 불러온다. *****************************************************************************/

add_action( 'wp_ajax_get_product_list', 'get_product_list' );

function get_product_list(){

$args = array(
	'post_type' => 'kkcart_product',
	'post_status' => 'publish'
	);

$products = new WP_Query($args);

	if ( $products->have_posts() ) {
		$cnt = 1;
		while ( $products->have_posts() ){
			$products->the_post();

		$post_id = get_the_ID();
		$thumbnail_ids 	= unserialize(get_post_meta($post_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url 	= wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
		$product_info 	= unserialize(get_post_meta($post_id, 'kingkongcart-product-info', true));
		$get_title 		= get_the_title($post_id);

		$sell_price 	= $product_info[0];
		$discount_price = $product_info[1];

		if($discount_price){
			$result_price = $discount_price;
		} else {
			$result_price = $sell_price;
		}

		$contents .= "
					<tr>
						<td>".$post_id."</td>
						<td><img src='".$thumbnail_url[0]."' style='width:50px; height:auto'></td>
						<td>".get_the_title($post_id)."</td>
						<td>".number_format($result_price)."원</td>
						<td><input type='button' class='button' value='등록' onclick=\"add_product_into_coupon('product',".$post_id.",'".$get_title."');\"></td>
					</tr>
					";

		$cnt++;

		}

	}

	$return_value = "
	<table>
		<tr>
			<th>ID</th>
			<th>썸네일</th>
			<th>상품명</th>
			<th>판매가격</th>
			<th>등록</th>
		</tr>
	".$contents."</table>";

	echo $return_value;

	die();
}

/* 문의관리 문의글 상세페이지 답변완료/수정  ***************************************************************/

add_action( 'wp_ajax_remove_board_content', 'remove_board_content' );

function remove_board_content(){

	$id = sanitize_text_field( $_POST['id'] );

	if( current_user_can('administrator') ){

		global $wpdb;

		$board_table = $wpdb->prefix."kingkong_board";
		$board_meta_table = $wpdb->prefix."kingkong_board_meta";
		$wpdb->delete( $board_table, array( 'ID' => $id ) );
		$wpdb->delete( $board_meta_table, array( 'order_id' => $id ) );

		echo "1";

	} else {
		echo "0";
	}

	die();

}



?>