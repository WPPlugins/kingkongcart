<?php

class kingkongcart{

	var $items;

	// 장바구니에 담겨있는 상품을 불러온다.
	function get_cart($type){
		
		global $current_user;

		if ( is_user_logged_in() ){ //로그인한 상태
			get_currentuserinfo();
			$user_id = $current_user->ID;

			switch($type){
				case "cart" :
					$carts = get_user_meta($user_id, "kingkongcart-cart", true);	
					$cart  = unserialize($carts);
				break;

				case "wish" :
					$carts = get_user_meta($user_id, "kingkongcart-wish", true);
					$cart  = unserialize($carts);
				break;
			}

		} else {
			switch($type){
				case "cart" :
					$cart = unserialize( base64_decode( $_COOKIE['kingkongcart-cart'] ) );
				break;

				case "wish" :
					return false;
				break;
			}
		}

		$this->items = $cart;
		return $this->items;
	}

	// 장바구니에 담겨있는 상품의 총 합계를 리턴한다.
	function total_price(){
		$carts = $this->get_cart("cart");

		for ($i=0; $i < count($carts); $i++) { 

			$product_id 		= $carts[$i]['product_id'];
			$quantity			= $carts[$i]['quantity'];
			$option1_plus_price = $carts[$i]['first']['plus_price'];
			$option2_plus_price = $carts[$i]['second']['plus_price'];
			$info 				= get_product_info($product_id); //상품 기본 정보 불러옴
			$total_price 		+= ($info->final_price * $quantity) + ($option1_plus_price * $quantity) + ($option2_plus_price * $quantity);		

		}
		return $total_price;
	}

}
?>