<?php

class order_insert{

	public $inserts;

	function __construct($inserts){

		if (is_user_logged_in()){

			global $current_user;
			get_currentuserinfo();

			$user_id = $current_user->ID;
		} else {
			$user_id = "";
		}

		if($inserts['pay_method_type'] == "vbank" or $inserts['pay_method_type'] == "VBank"){
			$paid_status = "0";
		} else {
			$paid_status = "1";
		}
		$this->insert_content	= $inserts;
		$this->user_id 			= $user_id;
		$this->order_code 		= $inserts['order_code'];
		$this->pay_kind 		= $inserts['pay_kind'];
		$this->buyer_name		= $inserts['buyer_name'];
		$this->buyer_email		= $inserts['buyer_email'];
		$this->buyer_tel		= $inserts['buyer_tel'];
		$this->receive_name 	= $inserts['receive_name'];
		$this->postcode1		= $inserts['postcode1'];
		$this->postcode2 		= $inserts['postcode2'];
		$this->address 			= $inserts['address'];
		$this->else_address 	= $inserts['else_address'];
		$this->receive_tel		= $inserts['receive_tel'];
		$this->receive_memo 	= $inserts['receive_memo'];
		$this->product_title 	= $inserts['product_title'];
		$this->buying_product 	= $inserts['buying_product'];
		$this->pay_method_type	= $inserts['pay_method_type'];
		$this->mileage 			= $inserts['mileage'];
		$this->paid_status		= $paid_status;
		$this->price 			= $inserts['price'];
		$this->using_mileage 	= $inserts['using_mileage'];
		$this->shipping_cost 	= $inserts['shipping_cost'];

	}

	// DB 에 주문내용을 입력하기 위해 먼저 주문번호를 검색하여 없을 경우 입력하고 있으면 false 를 반환한다.
	public function insert_order_db(){

		global $wpdb;
		$order_exist 	= $this->order_code_exist();
		$order_table 	= $wpdb->prefix."kingkong_order";

		if(!$order_exist){

			$wpdb->insert( 
				$order_table, 
				array( 
					'pid' 				=> $this->order_code,
					'status'			=> $this->paid_status,
					'kind'				=> $this->get_pay_kind_name(),
					'pname'				=> $this->product_title,
					'order_id'			=> $this->user_id,
					'order_price' 		=> $this->price,
					'receive_name'		=> $this->receive_name,
					'receive_contact' 	=> $this->receive_tel,
					'address_doro'		=> "[".$this->postcode1."-".$this->postcode2."] ".$this->address,
					'address_jibun'		=> "[".$this->postcode1."-".$this->postcode2."] ".$this->address,
					'address_detail'	=> $this->else_address,
					'mktime'			=> mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
					'order_date'		=> date("Y-m-d H:i:s")
				) 
			);

		$orders = $wpdb->get_row("SELECT ID from $order_table where pid = '".$this->order_code."' limit 1");
		update_order_meta($orders->ID, "buying_product", serialize($this->buying_product) );
		update_order_meta($orders->ID, "shipping_cost", $this->shipping_cost ); 	// 배송비 저장
		update_order_meta($orders->ID, "order_memo", $this->receive_memo);				// 배송메모

		$buyer_info = array(
			'buyer_id' 		=> $this->user_id,
			'buyer_name' 	=> $this->buyer_name,
			'buyer_email'	=> $this->buyer_email,
			'buyer_phone'	=> $this->buyer_tel		 
		);

		$buyer_info = serialize($buyer_info);
		update_order_meta($orders->ID, "buyer_info", $buyer_info );

		// 주문후 유저정보 업데이트
		$this->do_update_user_info();
		// 주문후 재고 업데이트
		$this->do_update_quantity();
		// 마일리지 업데이트
		$this->do_update_mileage($orders->ID);
		// 모바일 결제와 PC 결제를 구분하기 위한 메타값 업데이트
		$this->do_update_platform($orders->ID);

		session_start();
		unset($_SESSION['temp_kingkongcart_product']); 	//세션 제거
		unset($_SESSION['mobile_temp_cart']);			// 세션 제거
		// Action Hook : 주문정보 저장후 동작하는 액션 훅
		do_action('order_save_after', $this->user_id, $this->insert_content);

			return true;

		} else {
			return false;
		}

	}

	// 모바일결제? PC 결제?
	function do_update_platform($ID){
		update_order_meta($ID, "platform", $this->pay_kind );	// 결제 플랫폼 오더 메타정보로 업데이트
	}

	// 주문시 재고처리 로직
	function do_update_quantity(){

		$auto_quantity 	= get_option("kingkongcart_auto_quantity");

		if($auto_quantity == "T"){ //자동 재고관리 활성화시

			$all_products 	= $this->buying_product;

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
							} // end if
						} // end for
					} // end if
				} //end for

				// 변경된 옵션 재고수량을 다시 업데이트 한다.
				$poptions = serialize($poptions);
				update_post_meta($all_products[$i]['product_id'], 'kingkongcart-product-option', $poptions );
			} // end for
		} // end if
	}

	// 적립금 업데이트
	function do_update_mileage($ID){

		$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
		$mileage_status = $mileage_config['mileage_use'];

		if($mileage_status == "T"){
			update_order_meta($ID, "mileage", $this->mileage );							// 마일리지 저장
			update_order_meta($ID, "using_mileage", $this->using_mileage);				// 사용한 마일리지

			// 현재마일리지를 불러와 입력 한 마일리지를 공제 후 다시 업데이트 한다.
			$current_user_mileage 	= get_user_meta($this->user_id, "kingkong_mileage", true);
			$calculated_milage 		= $current_user_mileage - $this->paid_mileage;

			update_user_meta($this->user_id, "kingkong_mileage", $calculated_milage);
		}

	}

	// 주문시 입력하는 정보를 회원정보로 수정해서 들어가도록 설정
	function do_update_user_info(){

		if($this->user_id != ""){

			$origin_user_info 					= get_user_meta($this->user_id,"kingkong_user_info",true);
			$origin_user_info 					= unserialize($origin_user_info);

			$origin_user_info['zipcode'] 		= $this->postcode1."-".$this->postcode2;
			$origin_user_info['address_doro'] 	= $this->address;
			$origin_user_info['address_jibun'] 	= $this->address;
			$origin_user_info['address_else'] 	= $this->else_address;
			$origin_user_info['tel']			= $this->buyer_tel;
			$origin_user_info['phone']			= $this->buyer_tel;

			$origin_user_info 					= serialize($origin_user_info);

			update_user_meta($this->user_id,"kingkong_user_info", $origin_user_info);
		}

	}

	function order_code_exist(){
		global $wpdb;
		$order_code = $this->order_code;
		$order_table = $wpdb->prefix."kingkong_order";

		$order_exist = $wpdb->get_row("SELECT ID from $order_table where pid = '".$order_code."' limit 1");

		if($order_exist){
			return true;
		} else {
			return false;
		}
	}

	public function get_pay_kind_name(){
		
		switch($this->pay_kind){
			case "mobile" :
				switch($this->pay_method_type){
					case "mobile" :
						$pay_kind_name 		= "휴대폰결제";
					break;

					case "wcard" :
						$pay_kind_name = "신용카드";
					break;

					case "DBANK" :
						$pay_kind_name = "계좌이체";
					break;

					case "vbank" :
						$pay_kind_name = "가상계좌";
					break;

					case "culture" :
						$pay_kind_name = "문화상품권";
					break;

					case "hpmn" :
						$pay_kind_name = "해피머니상품권";
					break;

					default :
						$pay_kind_name = $this->pay_method_type;
					break;
				}
			break;

			case "pc" :
				switch($this->pay_method_type){

					case "VCard" :
						$pay_kind_name = "신용카드(ISP)";
					break;

					case "Card" :
						$pay_kind_name = "신용카드(안심클릭)";
					break;

					case "OCBPoint" :
						$pay_kind_name = "OK 캐시백 포인트";
					break;

					case "DirectBank" : //은행계좌이체
						$pay_kind_name = "은행계좌이체";
					break;

					case "HPP" : // 핸드폰 결제
						$pay_kind_name = "핸드폰";
					break;

					case "VBank" : // 무통장입금(가상계좌)
						$pay_kind_name = "무통장입금(가상계좌)";
					break;

					case "PhoneBill" :
						$pay_kind_name = "폰빌전화결제";
					break;

					case "Culture" : // 문화 상품권 결제
						$pay_kind_name = "문화 상품권";
					break;

					case "TEEN" : // 틴캐시(TeenCash) 결제
						$pay_kind_name = "탠캐시(TeenCash)";
					break;

					case "DGCL" :
						$pay_kind_name = "스마트 문상";
					break;

					case "BCSH" :
						$pay_kind_name = "도서문화 상품권";
					break;

					case "HPMN" : // 해피머니 상품권
						$pay_kind_name = "해피머니 상품권";
					break;

					case "MMLG" :
						$pay_kind_name = "M 마일리지";
					break;

					case "YPAY" : // 옐로페이
						$pay_kind_name = "옐로페이";
					break;

					default :
						$pay_kind_name = $this->pay_method_type;
					break;
				}
			break;
		}

		return $pay_kind_name;
	}
}

?>