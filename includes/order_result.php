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

$product_kind		= $_POST['check_product_kind'];

if (is_user_logged_in()){

	global $current_user;
	get_currentuserinfo();

	$user_id = $current_user->ID;	
} else {
	$user_id = "";
}


switch($payment_method){

	case "INICIS" :
	include $dir.'payment/INICIS/order_result.php';			// INICIS 결과페이지

	$inserts 		= array(
		'order_code' 		=> $kingkongcart_pay_pid,
		'pay_kind'			=> 'pc',
		'buyer_name' 		=> $kingkongcart_pay_receive_name,
		'shipping_cost'		=> $kingkongcart_pay_shippig_cost,
		'pay_method_type'	=> $kingkongcart_pay_method,
		'buyer_tel'			=> $kingkongcart_pay_receive_contact,
		'buyer_email'		=> $kingkongcart_buyer_email,
		'receive_name'		=> $kingkongcart_pay_receive_name,
		'postcode1'			=> $_POST['postcode1'],
		'postcode2'			=> $_POST['postcode2'],
		'address'			=> $kingkongcart_pay_address_jibun,
		'else_address'		=> $kingkongcart_pay_address_detail,
		'receive_tel'		=> $kingkongcart_pay_receive_contact,
		'receive_memo'		=> $kingkongcart_pay_memo,
		'product_title'		=> $kingkongcart_pay_pname,
		'buying_product' 	=> $_SESSION['temp_kingkongcart_product'],
		'price'				=> $kingkongcart_pay_price,
		'mileage'			=> $kingkongcart_pay_mileage,
		'using_mileage'		=> $kingkongcart_pay_input_mileage
	);

	break;
}

	// 지불 성공 이라면
	if($kingkongcart_pay_result == "00"){

	$order_insert 		= new order_insert($inserts);
	$check_insert 		= $order_insert->insert_order_db();
	$get_pay_kind_name 	= $order_insert->get_pay_kind_name();
/*
* 쿠폰을 사용했다면 사용된 쿠폰을 사용자 목록에서 삭제시킨다.
*/
	if($kingkongcart_paid_coupon){

		$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));

			for ($c=0; $c < count($user_coupons); $c++) { 
				if($user_coupons[$c]['coupon_id'] == $kingkongcart_paid_coupon){
					$check_index = $c;
				}
			}

			unset($user_coupons[$check_index]);
			$user_coupons = array_values($user_coupons);
			$user_coupons = serialize($user_coupons);
			update_user_meta($user_id, "added_coupon", $user_coupons);
	}

?>
	<div class="entry-content">
	<h2>감사합니다. 정상적으로 주문처리 되었습니다.</h2>
	<br>
	<div id="order-result">

<?php
	if($product_kind > 0){
?>
		<h3>수취자 정보</h3>
		<table>
			<tr>
				<th>수취자명</th>
				<td><?php echo $kingkongcart_pay_receive_name;?></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><?php echo $kingkongcart_pay_receive_contact;?></td>
			</tr>
		</table>
<?php
	}
?>
		<h3>주문내역</h3>
		<table>
			<tr>
				<th>주문번호</th>
				<td><?php echo $kingkongcart_pay_pid;?></td>
			</tr>
			<tr>
				<th>주문상품</th>
				<td><?php echo $kingkongcart_pay_pname;?></td>
			</tr>
			<tr>
				<th>결제방법</th>
				<td><?php echo $get_pay_kind_name;?></td>
			</tr>
			<tr>
				<th>결제금액</th>
				<td>
					<?php echo number_format($kingkongcart_pay_price);?>

<?php 
	if($paid_status == 0){

		echo "(입금예정)";
	}
?>

				</td>
			</tr>

<?php
	if($paid_status == 0){
?>

			<tr>
				<th>입금계좌</th>
				<td><?php echo get_bank_name($kingkongcart_pay_bank);?>, 계좌번호 : <?php echo $kingkongcart_pay_account; ?></td>
			</tr>

			<tr>
				<th>입금예정일</th>
				<td><?php echo $kingkongcart_pay_bank_date;?></td>
			</tr>

			<tr>
				<th>예금자명</th>
				<td><?php echo $kingkongcart_pay_send_name;?></td>
			</tr>

<?php
	}
?>			
		</table>

	</div>
	<input type="button" value="확인" onclick="location.href='<?php echo home_url();?>';">
	</div>

<?php
	} else {
		echo "<script>
		alert('잘못된 접근 혹은 이미 주문 완료된 건 입니다. 홈 화면으로 이동합니다.');
		location.href='".home_url()."';
		</script>";
	}
get_footer();
?>
