<?php

	global $wpdb;
	$get_id = sanitize_text_field( $_GET['id'] );
	$order_table = $wpdb->prefix."kingkong_order";
	$order = $wpdb->get_row("SELECT * from $order_table where ID = '".$get_id."' ");
	$order_product = unserialize(get_order_meta($get_id, "buying_product"));
	$mileage = get_order_meta($order->ID, "mileage");
	$using_mileage = get_order_meta($order->ID, "using_mileage");
	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];
	$platform	= get_order_meta($order->ID, "platform");
	switch($platform){
		case "mobile" :
			$platform_title = "<img src='".KINGKONGCART_PLUGINS_URL."/files/images/icon-mobile.png' style='width:20px; height:auto' alt='모바일로 접속하여 결제하였습니다.'> ";
		break;

		case "pc" :
			$platform_title = "";
		break;

		default :
			$platform_title = "";
		break;
	}
?>
<h3>주문상세</h3>
<table>
	<tr>
		<th>주문번호</th>
		<td><?php echo $order->pid;?></td>
	</tr>
	<tr>
		<th>주문상태</th>
		<td><?php echo get_order_status($order->status);?></td>
	</tr>
	<tr>
		<th>주문상품</th>
		<td>
			<table style="width:100%;">

				<tr style="height:40px; background:#e0e0e0">
					<th>썸네일</th>
					<th>상품명</th>
					<th>옵션1</th>
					<th>옵션2</th>
					<th>수량</th>
				</tr>
<?php
	for ($i=0; $i < count($order_product); $i++) { 
		$product_id = $order_product[$i]['product_id'];
		$product_title = get_the_title($product_id);
		$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
?>
				<tr>
					<td style="text-align:center"><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
					<td><a href="<?php echo get_the_permalink($product_id);?>" target="_blank"><?php echo $product_title;?></a></td>
					<td><?php echo $order_product[$i]['first']['name'];?></td>
					<td><?php echo $order_product[$i]['second']['name'];?></td>
					<td style="text-align:center"><?php echo $order_product[$i]['quantity'];?></td>
				</tr>
<?php
	}
?>
			</table>
		</td>
	</tr>
	<tr>
		<th>결제금액</th>
		<td><?php echo number_format($order->order_price);?>원</td>
	</tr>
<?php
	if($mileage_status == "T"){
?>
	<tr>
		<th>사용한 적립금</th>
		<td><?php echo number_format($using_mileage);?>원</td>
	</tr>
	<tr>
		<th>적립 예정금액</th>
		<td><?php echo number_format($mileage);?>원</td>
	</tr>
<?php
	}
?>
	<tr>
		<th>결제유형</td>
		<td><?php echo $platform_title.$order->kind;?></td>
	</tr>
	<tr>
		<th>결제자 아이디</td>
		<td><?php echo get_user_login_id($order->order_id);?></td>
	</tr>
	<tr>
		<th>결제일</th>
		<td><?php echo $order->order_date;?></td>
	</tr>
</table>
<h3>수취자 정보</h3>
<table>
	<tr>
		<th>수취자 명</th>
		<td><?php echo $order->receive_name;?></td>
	</tr>
	<tr>
		<th>수취인 연락처</th>
		<td><?php echo $order->receive_contact;?></td>
	</tr>
	<tr>
		<th>수취인 주소</th>
		<td>
			<table>
				<tr>
					<th>도로명주소</th>
					<td><?php echo $order->address_doro;?></td>
				</tr>
				<tr>
					<th>지번주소</th>
					<td><?php echo $order->address_jibun;?></td>
				</tr>
				<tr>
					<th>상세주소</th>
					<td><?php echo $order->address_detail;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>배송메모</th>
<?php
	$order_memo = get_order_meta($order->ID, "order_memo");
?>
		<td><?php echo $order_memo;?></td>
	</tr>
</table>

<?php 
	switch($order->status){
		case 0 :
		$status_button = "입금확인";
		break;

		case 1 :
		$status_button = "주문확인";
		break;

		case 2 :
		$status_button = "배송완료";
?>

<h3>배송정보</h3>
<table>
	<tr>
		<th>택배사</th>
		<td>
			<select name="shipping_company">
<?php 

$shipping_company = get_shipping_company();
$get_shipping_option = unserialize(get_option("kingkong_shipping"));

for ($i=0; $i < count($shipping_company); $i++) { 
	if($shipping_company[$i]['code'] == $get_shipping_option['company']){
		echo "<option value='".$shipping_company[$i]['code']."' selected>".$shipping_company[$i]['name']."</option>";
	} else {
		echo "<option value='".$shipping_company[$i]['code']."'>".$shipping_company[$i]['name']."</option>";
	}
}

?>
			</select>
		</td>
	</tr>
	<tr>
		<th>송장번호</th>
		<td><input type="text" name="shipping_account"></td>
	</tr>
</table>

<?php
		break;

		case 3 :

		$shipping_info = get_order_meta($order->ID, "shipping_info");
		$shipping_info = unserialize($shipping_info);
?>
<h3>배송정보</h3>
<table>
	<tr>
		<th>택배사</th>
		<td><?php echo get_shipping_company_name($shipping_info['company']);?></td>
	</tr>
	<tr>
		<th>송장번호</th>
		<td><a href="<?php echo get_tracking_link($shipping_info['company']).$shipping_info['account'];?>" target="_blank"><?php echo $shipping_info['account'];?></a></td>
</table>
<?php
		break;
	}
	if ($order->status < 3){
?>
<input type="button" class="button button-primary button-large" value="<?php echo $status_button;?>" onclick="status_change(<?php echo $order->ID;?>, <?php echo $order->status;?>);">
<input type="button" class="button button-large" value="주문취소" onclick="cancle_order(<?php echo $order->ID;?>);">
<?php
	} else {
?>
<input type="button" class="button button-large" value="확인" onclick="history.back();">
<?php
	}
?>

