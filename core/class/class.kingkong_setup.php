<?php

	class kingkong_setup {

		function __construct(){
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkong_setup_contents') ); 
		}

		public function kingkong_setup_contents(){

			$general_option = array(
				'fonts'				=> "malgun",
				'display' 			=> "thumbnail-title-price",
				'grid-width' 		=> "100%",
		    	'grid-height'   	=> "300px",
				'title-color'		=> "#000000",
				'shortdesc-color' 	=> "#000000",
				'price-color'		=> "#000000",
				'mileage-color'		=> "#000000",
				'discount-color'	=> "#000000",
				'title-size'		=> "14px",
				'shortdesc-size'	=> "14px",
				'price-size'		=> "14px",
				'mileage-size'		=> "14px",
				'discount-size'		=> "14px",
				'title-justify'		=> "center",
				'shortdesc-justify' => "center",
				'price-justify'		=> "center",
				'mileage-justify'	=> "center",
				'discount-justify'	=> "center",
				'title-bold'		=> "normal",
				'shortdesc-bold'	=> "normal",
				'price-bold'		=> "normal",
				'mileage-bold'		=> "normal",
				'discount-bold'		=> "normal"
			);

			$general_option = serialize($general_option);

		  	$current_general = get_option("kingkongcart-display");

			if(!$current_general){
				update_option("kingkongcart-display",$general_option);
			}

			/// 상품 게시판 설정 //////////////////////////////////////////////////

			$board_config = array(
			    'afternote' => 'T',
			    'qna'   => 'T',
			    'vote'    => 'T',
			    'private' => 'T',
			    'line'    => 20,
			    'skin'    => 'modern_white'
			);

			$board_config = serialize($board_config);
			$current_board = get_option("kingkongcart_board_config");

			if(!$current_board){
			    update_option("kingkongcart_board_config",$board_config);
			}


			/// 프리픽스 및 재고관리 재고수량 설정 //////////////////////////////////////

			$product_prefix = "TB";
			$order_prefix   = "RD";

			if(!get_option("kingkongcart_product_prefix")){
			    update_option("kingkongcart_product_prefix", $product_prefix);
			}

			if(!get_option("kingkongcart_order_prefix")){
		    	update_option("kingkongcart_order_prefix", $order_prefix);
		  	}

			$auto_quantity = "T";
			$safe_quantity = 10;

			if(!get_option("kingkongcart_auto_quantity")){
			    update_option("kingkongcart_auto_quantity", $auto_quantity);
			}

			if(!get_option("kingkongcart_safe_quantity")){
			    update_option("kingkongcart_safe_quantity", $safe_quantity);
			}

		  
			/// 쿠폰설정         //////////////////////////////////////////////////  

			/// 적립금 설정       //////////////////////////////////////////////////

			$mileage_config = array(
			    'mileage_use'     => 'T',
			    'join_mileage'    => 0,
			    'afternote_mileage' => 0,
			    'min_mileage'   => 100,
			    'max_mileage'   => 5000
			);

		  	$mileage_config = serialize($mileage_config);

			if(!get_option("mileage_config")){
			    update_option("mileage_config",$mileage_config);
			}

			/// 배송 설정         /////////////////////////////////////////////////////

		    $shipping = array();
		    $shipping['basic']    = 2500;
		    $shipping['free']   = 50000;
		    $shipping['company']  = "-1";

		    $shipping = serialize($shipping);

		    if(!get_option("kingkong_shipping")){
		    	update_option("kingkong_shipping", $shipping);
		    }

			/// 결제모듈설정       /////////////////////////////////////////////////////

			$payment = array();
			$payment['method'] = 'INICIS';
			$payment['inicis_key_id'] = 'INIpayTest';
			$payment['inicis_key_pwd'] = '1111';
			$payment['yd_check'] = 0;
			$payment['site_code'] = "";
			$payment['site_key'] = "";
			$payment['paykind'][0] = 'Card';

			$payment = serialize($payment);

			if(!get_option("kingkong_payment")){
			    update_option("kingkong_payment", $payment);
			}

		}
	}

	if(is_admin()){
		new kingkong_setup();
	}

?>