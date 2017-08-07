<?php
if ( is_user_logged_in() ){

	global $wpdb, $current_user;
	get_currentuserinfo();

	$user_id = $current_user->ID;

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];

	$order_table = $wpdb->prefix . "kingkong_order";
	$orders = $wpdb->get_results("SELECT * from $order_table where order_id = '".$user_id."' order by ID desc");

?>
<div class="mypage-order mypage-div">
	<table>
		<thead>
			<tr>
				<th>썸네일</th>
				<th>상품명</th>
				<th>상품가격</th>
<?php
	if($mileage_status == "T"){
?>
				<th>적립금</th>
<?php
	}
?>
				<th>배송비</th>
				<th>결제금액</th>
				<th>주문일자</th>
				<th>주문상태</th>
				<th>배송상태</th>
			</tr>
		</thead>
		<tbody>
<?php
	if($orders){
		foreach($orders as $order){

			$buying_products = unserialize(get_order_meta($order->ID, "buying_product"));
			$major_product_id = $buying_products[0]['product_id'];
			$shipping_cost = get_order_meta($order->ID, "shipping_cost");
			$mileage	   = get_order_meta($order->ID, "mileage");
			$thumbnail_ids = unserialize(get_post_meta($major_product_id,"kingkongcart_added_thumbnail_id", true));
			$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
			$without_shipping_cost = $order->order_price - $shipping_cost;
			$info = unserialize(get_post_meta($major_product_id, 'kingkongcart-product-info', true));
			$product_kind = $info[8];
?>
			<tr>
				<td><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
				<td><?php echo $order->pname;?>[<?php echo count($buying_products);?>]</td>
<?php
	if(count($buying_products) > 1){
?>
				<td rowspan="<?php echo count($buying_products);?>"><?php echo number_format($without_shipping_cost);?>원</td>
<?php
	} else {
?>
				<td><?php echo number_format($without_shipping_cost);?>원</td>
<?php
	}
?>

<?php
	if($mileage_status == "T"){

		if(count($buying_products) > 1){
?>
				<td rowspan="<?php echo count($buying_products);?>"><?php echo $mileage;?></td>
<?php
		} else {
?>
				<td><?php echo $mileage;?></td>
<?php
		}
	}
?>

<?php
	if(count($buying_products) > 1){
?>
				<td rowspan="<?php echo count($buying_products);?>"><?php echo number_format($shipping_cost);?>원</td>
<?php
	} else {
?>
				<td><?php echo number_format($shipping_cost);?>원</td>
<?php
	}
?>

<?php
	if(count($buying_products) > 1){
?>
				<td rowspan="<?php echo count($buying_products);?>"><?php echo number_format($order->order_price);?>원</td>
<?php
	} else {
?>
				<td><?php echo number_format($order->order_price);?>원</td>
<?php
	}
?>

<?php
	if(count($buying_products) > 1){
?>
				<td rowspan="<?php echo count($buying_products);?>"><?php echo $order->order_date;?></td>
<?php
	} else {
?>
				<td><?php echo $order->order_date;?></td>
<?php
	}
?>		
				
				
<?php
	if($product_kind == "1"){
?>
				<td>다운로드상품</td>
<?php
	} else {
?>
				<td><?php echo get_order_status($order->status);?></td>
<?php
	}
?>				
				
				<td>
<?php
	if($order->status == 3){

			$shipping_info = get_order_meta($order->ID, "shipping_info");
			$shipping_info = unserialize($shipping_info);
			$company = get_shipping_company_name($shipping_info['company']);
			$shipping_account = $shipping_info['account'];

		if($product_kind == "1"){
?>
			<button onclick="location.href='<?php echo home_url();?>?page_type=download&product_id=<?php echo $major_product_id;?>';">다운로드</button>
<?php
		} else {
?>
			<a href="<?php echo get_tracking_link($shipping_info['company']).$shipping_account;?>" target="_blank">배송추적</a>
<?php
		}
	} else {

			if($order->status >= 1 and $product_kind == "1"){
?>
				<button onclick="location.href='<?php echo home_url();?>?page_type=download&product_id=<?php echo $major_product_id;?>';">다운로드</button>
<?php
			} else {
?>
				대기중
<?php
			}
?>
<?php
	}
?>
				</td>
			</tr>

<?php
		

		for ($i=1; $i < count($buying_products); $i++) {
			$product_id = $buying_products[$i]['product_id'];
			$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
			$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
			$info = unserialize(get_post_meta($product_id, 'kingkongcart-product-info', true));
			$product_kind = $info[8];
?>
			<tr>
				<td><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
				<td><?php echo get_the_title($product_id);?></td>
<?php
	if($product_kind == "1"){
?>
				<td>다운로드상품</td>
<?php
	} else {
?>
				<td><?php echo get_order_status($order->status);?></td>
<?php
	}
?>	
				<td>
<?php
	if($order->status == 3){

			$shipping_info = get_order_meta($order->ID, "shipping_info");
			$shipping_info = unserialize($shipping_info);
			$company = get_shipping_company_name($shipping_info['company']);
			$shipping_account = $shipping_info['account'];

		if($product_kind == "1"){
?>
			<button onclick="location.href='<?php echo home_url();?>?page_type=download&product_id=<?php echo $major_product_id;?>';">다운로드</button>
<?php
		} else {
?>
			<a href="<?php echo get_tracking_link($shipping_info['company']).$shipping_account;?>" target="_blank">배송추적</a>
<?php
		}
	} else {

			if($order->status >= 1 and $product_kind == "1"){
?>
				<button onclick="location.href='<?php echo home_url();?>?page_type=download&product_id=<?php echo $major_product_id;?>';">다운로드</button>
<?php
			} else {
?>
				대기중
<?php
			}
?>
<?php
	}
?>
				</td>
			</tr>
<?php
	}
		}

	} else {
?>
			<tr>
				<td colspan="9">주문내역이 없습니다.</td>
			</tr>
<?php
	}
?>

		</tbody>
	</table>	
</div>
<?php
} else {
?>
주문내역은 로그인 후 이용하실 수 있습니다.
<?php
}
?>

