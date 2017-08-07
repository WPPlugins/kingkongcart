<?php

	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";
	$query = "(SELECT * from $order_table where status = '1')";


	$total = $wpdb->get_var( "SELECT COUNT(1) FROM (${query}) AS combined_table" );
	$items_per_page = 20;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;
	$orders = $wpdb->get_results( $query . " ORDER BY mktime LIMIT ".$offset.", ".$items_per_page );

?>

<table>
	<thead>
		<tr>
			<th>주문번호</th>
			<th>주문상태</th>
			<th>결제유형</th>
			<th>제품명</th>
			<th>결제금액</th>
			<th>결제자 아이디</th>
			<th>결제일자</th>
		</tr>
	</thead>
	<tbody>
<?php
	if($orders){
		foreach($orders as $order){

			$user_info 	= get_userdata($order->order_id);
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
			<td><?php echo get_user_login_id($order->order_id);?></td>
			<td><?php echo $order->order_date;?></td>
		</tr>
<?php
		}
	} else {
?>
		<tr>
			<td colspan="7" style="text-align:center">신규 주문이 없습니다.</td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<div class="pagination">
<?php 

	echo paginate_links( array(
    'base' => add_query_arg( 'cpage', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $items_per_page),
    'current' => $page
	));
?>
</div>