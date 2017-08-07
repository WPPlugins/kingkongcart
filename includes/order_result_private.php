<?php
/*
* Kingkongcart Order Result Page
*/

/****************************
* 0. 세션 시작				*
****************************/
session_start(); 					//주의:파일 최상단에 위치시켜주세요!!

get_header();

$dir = KINGKONGCART_ABSPATH;
$payments = unserialize(get_option("kingkong_payment"));
$payment_method 	= $payments['method'];
$yd_check 			= $payments['yd_check'];
$site_code 			= $payments['site_code'];
$site_key 			= $payments['site_key'];


if($_SESSION['temp_kingkongcart_product'] == ""){

?>

중복 결제를 시도하실 경우 현재 페이지가 노출 되게 됩니다.

처음부터 결제를 다시 진행 해 주시기 바랍니다.

<?php

get_footer();

return false;

}

if (is_user_logged_in()){

	global $current_user;
	get_currentuserinfo();

	$user_id = $current_user->ID;	
} else {
	$user_id = "";
}


	$order_name 		= $_POST['order_name'];
	$order_tel1 		= $_POST['order_tel1'];
	$order_tel2 		= $_POST['order_tel2'];
	$order_tel3 		= $_POST['order_tel3'];
	$order_email 		= $_POST['order_email'];
	$postcode1 			= $_POST['postcode1'];
	$postcode2			= $_POST['postcode2'];
	$address 			= $_POST['address'];
	$else_address 		= $_POST['else_address'];
	$order_memo 		= $_POST['order_memo'];
	$input_mileage 	 	= $_POST['input_mileage'];
	$private_account 	= $_POST['private_account_select'];
	$private_accnt_name = $_POST['private_account_name'];
	$total_price 		= $_POST['with_shipping_price'];
	$shipping_cost 		= $_POST['shipping_cost'];
	$product_title 		= $_POST['product_desc_title'];
	$total_mileage 		= $_POST['total_mileage'];
	$memo 				= $_POST['input_memo'];

	$pid 				= create_kingkong_order_number();
	$status 			= 0;
	$kind 				= "무통장입금(전용계좌)";
	$pname 				= $product_title;
	$order_id 			= $user_id;
	$order_price 		= $total_price;
	$receive_name 		= $_POST['shipping_name'];
	$receive_contact 	= $_POST['shipping_tel1']."-".$_POST['shipping_tel2']."-".$_POST['shipping_tel3'];
	$zipcode 			= $postcode1."-".$postcode2;


	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";

	$wpdb->insert( 
		$order_table, 
		array( 
			'pid' 				=> $pid,
			'status'			=> $status,
			'kind'				=> $kind,
			'pname'				=> $pname,
			'order_id'			=> $user_id,
			'order_price' 		=> $order_price,
			'receive_name'		=> $receive_name,
			'receive_contact' 	=> $receive_contact,
			'address_doro'		=> "[".$zipcode."] ".$address,
			'address_jibun'		=> "[".$zipcode."] ".$address,
			'address_detail'	=> $else_address,
			'mktime'			=> mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
			'order_date'		=> date("Y-m-d H:i:s")
		) 
	);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];

	$orders = $wpdb->get_row("SELECT ID from $order_table where pid = '".$pid."' limit 1");

	update_order_meta($orders->ID, "buying_product", serialize($_SESSION['temp_kingkongcart_product']) );
	update_order_meta($orders->ID, "shipping_cost", $shippig_cost ); 	// 배송비 저장
	update_order_meta($orders->ID, "order_memo", $memo);				// 배송메모
 
	$buyer_info = array(
		'buyer_id' 		=> $user_id,
		'buyer_name' 	=> $receive_name,
		'buyer_email'	=> $order_email,
		'buyer_phone'	=> $receive_contact		 
	);

	$buyer_info = serialize($buyer_info);

	update_order_meta($orders->ID, "buyer_info", $buyer_info );


	$auto_quantity 	= get_option("kingkongcart_auto_quantity");

	if($user_id != ""){

	$origin_user_info 	= get_user_meta($user_id,"kingkong_user_info",true);
	$origin_user_info 	= unserialize($origin_user_info);

	$origin_user_info['user_id']		= $user_id;
	$origin_user_info['zipcode'] 		= $postcode1."-".$postcode2;
	$origin_user_info['address_doro'] 	= $address;
	$origin_user_info['address_jibun'] 	= $address;
	$origin_user_info['address_else'] 	= $else_address;
	$origin_user_info['tel']			= $receive_contact;
	$origin_user_info['phone']			= $receive_contact;

	$origin_user_info = serialize($origin_user_info);

	update_user_meta($user_id,"kingkong_user_info", $origin_user_info);

	}

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
		update_order_meta($orders->ID, "mileage", $total_mileage );				// 마일리지 저장
		update_order_meta($orders->ID, "using_mileage", $input_mileage);	// 사용한 마일리지
		// 현재마일리지를 불러와 입력 한 마일리지를 공제 후 다시 업데이트 한다.
		$current_user_mileage = get_user_meta($user_id, "kingkong_mileage", true);
		$calculated_milage = $current_user_mileage - $input_mileage;
		update_user_meta($user_id, "kingkong_mileage", $calculated_milage);
	}
/* wp_mail 관리자 전송 */

	//$admin_email = get_option( 'admin_email' );
	//$email_contents = "<p>신규주문이 들어왔습니다.</p><p>결제금액 :".$kingkongcart_pay_price."</p>";
	//wp_mail($admin_email, '신규주문이 추가 되었습니다.', $email_contents);


	$_SESSION['temp_kingkongcart_product'] = ""; //세션 제거

	do_action("kingkong_order_complete",$origin_user_info);

?>

	<div class="entry-content">
	<h2>감사합니다. 정상적으로 주문처리 되었습니다.</h2>
	<br>
	<div id="order-result">
		<h3>수취자 정보</h3>
		<table>
			<tr>
				<th>수취자명</th>
				<td><?php echo $receive_name;?></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><?php echo $receive_contact;?></td>
			</tr>
		</table>

		<h3>주문내역</h3>
		<table>
			<tr>
				<th>주문번호</th>
				<td><?php echo $pid;?></td>
			</tr>
			<tr>
				<th>주문상품</th>
				<td><?php echo $pname;?></td>
			</tr>
			<tr>
				<th>결제방법</th>
				<td>무통장 입금</td>
			</tr>
			<tr>
				<th>결제금액</th>
				<td>
					<?php echo number_format($total_price);?>

					(입금예정)

				</td>
			</tr>

			<tr>
				<th>입금계좌</th>
				<td><?php echo $private_account;?></td>
			</tr>

			<tr>
				<th>예금자명</th>
				<td><?php echo $private_accnt_name;?></td>
			</tr>
		
		</table>

	</div>
	<input type="button" value="확인" onclick="location.href='<?php echo home_url();?>';">
	</div>

<?php
get_footer();
?>
