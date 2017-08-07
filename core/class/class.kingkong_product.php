<?php

class kingkong_product{

	public $product_id, $title, $origin_price, $result_price, $discount_rate;
	public $discount_price, $mileage, $mileage_price, $short_desc, $provide_price;
	public $kind, $demo_url, $content, $product_code, $mileage_status;

	function __construct($product_id){

		if(isset($product_id)){
		$this->product_id 		= $product_id;
		$info = get_post_meta($this->product_id, 'kingkongcart-product-info', true);
		$info = unserialize($info);

		$this->title 			= get_the_title($this->product_id); 	// 상품명
		$this->content 			= apply_filters('the_content', get_post_field('post_content', $this->product_id));
		$this->product_code 	= get_post_meta($this->product_id, "kingkongcart-product-code", true);
		$this->origin_price 	= $info[0];								// 판매가격 (할인적용전)
			if($info[1]){
				$this->result_price = $info[1];							// 판매가격 (할인적용후)
			} else {
				$this->result_price = $info[0];							// 판매가격 (할인적용후)
			}
		$this->discount_rate 	= $info[2];								// 할인율
		$this->discount_price	= $info[3];								// 할인가격
		$this->mileage 			= $info[4];								// 적립금(마일리지)
		$this->mileage_price 	= $info[5];								// 적립금(마일리지) 원(점)
		$this->short_desc		= $info[6];								// 짤막소개
		$this->provide_price	= $info[7];								// 공급가(vat 포함)
		$this->kind 			= $info[8];								// 상품종류(배송상품 or 다운로드상품)
		$this->demo_url			= $info[9];								// 데모사이트 URL

		$this->safe_quantity 	= get_option("kingkongcart_safe_quantity"); // 재고안전수량
		$mileage_config 		= unserialize(get_option("mileage_config")); //마일리지 설정정보
		$this->mileage_status 	= $mileage_config['mileage_use'];

		}

	}

	public function get_product_options(){
		$options = get_post_meta($this->product_id, 'kingkongcart-product-option', true);
		$options = unserialize($options);
		return $options;
	}

	public function get_select_options_first(){

		$each_options = $this->get_product_options();

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
		}
	}

	public function get_select_options_second(){

		$each_options = $this->get_product_options();
		for ($i=0; $i < count($each_options); $i++) { 
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

		for ($i=0; $i < count($second_options); $i++) { 
			echo $second_options[$i];
		}

	}
}


?>