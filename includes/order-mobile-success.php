<?php

	$uname 			= $_POST['uname']; 					// 구매자 성명
	$tel 			= $_POST['tel'];					// 구매자 연락처
	$email 			= $_POST['email'];					// 구매자 이메일
	$product_title 	= $_POST['product_title'];			// 주문상품명
	$price 			= $_POST['price'];					// 결제금액
	$receive_name 	= $_POST['receive_name'];			// 수취인 성명
	$postcode1		= $_POST['postcode1'];				// 우편번호1
	$postcode2 		= $_POST['postcode2'];				// 우편번호2
	$address 		= $_POST['address'];				// 기본 주소
	$else_address 	= $_POST['receive_else_address'];	// 나머지 주소
	$receive_tel 	= $_POST['receive_tel'];			// 수취인 연락처
	$product_code 	= $_POST['product_code'];			// 상품 주문번호
	$memo			= $_POST['memo'];					// 배송메모
	$pay_method		= $_POST['pay_method_type']; 		// 결제수단
	$shipping_cost 	= $_POST['shipping_cost'];			// 배송비
	$mileage 		= $_POST['total_mileage'];			// 전체 예정 적립금
	$using_mileage 	= $_POST['using_mileage'];			// 지불에 사용한 적립금

	$inserts 		= array(
		'order_code' 		=> $product_code,
		'pay_kind'			=> 'mobile',
		'buyer_name' 		=> $uname,
		'shipping_cost'		=> $shipping_cost,
		'pay_method_type'	=> $pay_method,
		'buyer_tel'			=> $tel,
		'buyer_email'		=> $email,
		'receive_name'		=> $receive_name,
		'postcode1'			=> $postcode1,
		'postcode2'			=> $postcode2,
		'address'			=> $address,
		'else_address'		=> $else_address,
		'receive_tel'		=> $receive_tel,
		'receive_memo'		=> $memo,
		'product_title'		=> $product_title,
		'buying_product' 	=> $_SESSION['mobile_temp_cart'],
		'price'				=> $price,
		'mileage'			=> $mileage,
		'using_mileage'		=> $using_mileage
	);
 
	$order_insert = new order_insert($inserts);
	$check_insert = $order_insert->insert_order_db();

	if($check_insert){

?>
<div style="max-width:100%; padding:0px 10px; font-size:20px; font-weight:bold; text-align:center;">감사합니다.<br>정상적으로 주문이 완료 되었습니다.</div>

<div style="padding:0px 10px; width:100%; margin-top:20px">
	<table style="width:100%; border:1px solid #e0e0e0">
		<tr>
			<td style="width:110px; border-right:1px solid #e0e0e0">주문번호</td>
			<td><?php echo $product_code;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">구매자 성명</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $uname;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">구매자 연락처</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $tel;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">구매자 이메일</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $email;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">주문상품</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $product_title;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">결제금액</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo number_format($price);?>원</td>
		</tr>
	</table>

	<table style="width:100%; border:1px solid #e0e0e0">
		<tr>
			<td style="width:110px; border-right:1px solid #e0e0e0">수취인 성명</td>
			<td><?php echo $receive_name;?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">수취인 주소</td>
			<td style="border-top:1px solid #e0e0e0">
				[<?php echo $postcode1."-".$postcode2;?>] <?php echo $address." ".$else_address;?>
			</td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">수취인 연락처</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $receive_tel; ?></td>
		</tr>
		<tr>
			<td style="border-top:1px solid #e0e0e0; border-right:1px solid #e0e0e0">배송메모</td>
			<td style="border-top:1px solid #e0e0e0"><?php echo $memo;?></td>
		</tr>
	</table>
	<button style="width:100%; height:40px">확인</button>
</div>
<?php
	} else {
?>
	<script>
		alert("잘못된 접근 혹은 이미 주문 완료된 건 입니다. 홈 화면으로 이동합니다.");
		location.href="<?php echo home_url();?>";
	</script>
<?php
	}
?>

