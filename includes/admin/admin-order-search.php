<?php

	global $wpdb;

	$search_element = sanitize_text_field( $_POST['search_element'] );
	$search_kind	= sanitize_text_field( $_POST['search_kind'] );
	$search_keyword	= sanitize_text_field( $_POST['search_keyword'] );

	if($search_element == "-1"){
		$search_element = "pid";
	}

	if($search_kind == "-1"){
		$status = "";
	} else {
		$status = "and status = ".$search_kind;
	}

	$order_table = $wpdb->prefix."kingkong_order";
	$orders = $wpdb->get_results("SELECT * from $order_table where $search_element like '%".$search_keyword."%' $status");

?>

<table>
	<thead>
		<tr>
			<th>주문번호</th>
			<th>주문상태</th>
			<th>결제유형</th>
			<th>제품명</th>
			<th>결제금액</th>
			<th>결제자 성명</th>
			<th>결제자 아이디</th>
			<th>결제일자</th>
			<th>배송일자</th>
			<th>택배사/유형</th>
			<th>송장번호</th>
		</tr>
	</thead>
	<tbody>
<?php
	if($orders){
		foreach($orders as $order){

			$user_info = get_userdata($order->order_id);
			$shipping_info = get_order_meta($order->ID, "shipping_info");
			$shipping_info = unserialize($shipping_info);
			$company = get_shipping_company_name($shipping_info['company']);
			$shipping_date = $shipping_info['date'];
			$shipping_account = $shipping_info['account'];
			$platform	= get_order_meta($order->ID, "platform");

				switch($platform){
					case "mobile" :
						$platform_title = "<img src='".KINGKONGCART_PLUGINS_URL."/files/images/icon-mobile.png' style='width:20px; height:auto'> ";
					break;

					case "pc" :
						$platform_title = "";
					break;

					default :
						$platform_title = "";
					break;
				}
?>
		<tr>
			<td><a href="?page=kkcart_order&order_type=view&id=<?php echo $order->ID;?>"><?php echo $order->pid;?></a></td>
			<td><?php echo get_order_status($order->status);?></td>
			<td><?php echo $platform_title.$order->kind;?></td>
			<td><?php echo $order->pname;?></td>
			<td><?php echo number_format($order->order_price);?>원</td>
			<td><?php echo $order->receive_name;?></td>
			<td><?php echo get_user_login_id($order->order_id);?></td>
			<td><?php echo $order->order_date;?></td>
			<td><?php echo $shipping_date;?></td>
			<td><?php echo $company;?></td>
			<td><a href="<?php echo get_tracking_link($shipping_info['company']).$shipping_account;?>" target="_blank"><?php echo $shipping_account;?></a></td>
		</tr>
<?php
		}
	} else {
?>
		<tr>
			<td colspan="11" style="text-align:center">검색된 물건이 없습니다.</td>
		</tr>
<?php
	}
?>
	</tbody>
</table>