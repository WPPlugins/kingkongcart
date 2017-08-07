<?php

function kkcart_order(){

	$order_type = sanitize_text_field( $_GET['order_type'] );
	$get_id = sanitize_text_field( $_GET['id'] );
	if($get_id){
		global $wpdb;
		$order_table = $wpdb->prefix."kingkong_order";
		$order = $wpdb->get_row("SELECT status from $order_table where ID = '".$get_id."' ");

		switch($order->status){

			case 0 :
				$pending_active = "active";
			break;

			case 1 :
				$new_order_active = "active";
			break;

			case 2 :
				$shipping_ready_active = "active";
			break;

			case 3 :
				$shipping_complete_active = "active";
			break;
		}

	} else {

		switch($order_type){

			case "new-order" :
				$new_order_active = "active";
			break;

			case "shipping-ready" :
				$shipping_ready_active = "active";
			break;

			case "shipping-complete" :
				$shipping_complete_active = "active";
			break;

			case "pending" :
				$pending_active = "active";
			break;

			default :
				$new_order_active = "active";
			break;

		}

	}

?>
<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">주문관리</span></h2>
<div id="kkcart-admin-dashboard-btn">
	<ul>
		<li class="<?php echo $new_order_active;?>">
			<a href="?page=kkcart_order&order_type=new-order">신규주문</a>
<?php 
	if ( get_order_count(1) > 0 ){
?>
			<div class="numbering"><?php echo get_order_count(1);?></div>
<?php
	}
?>
		</li>
		<li class="<?php echo $shipping_ready_active;?>">
			<a href="?page=kkcart_order&order_type=shipping-ready">배송준비</a>
<?php 
	if ( get_order_count(2) > 0 ){
?>
			<div class="numbering"><?php echo get_order_count(2);?></div>
<?php
	}
?>
		</li>
		<li class="<?php echo $shipping_complete_active;?>">
			<a href="?page=kkcart_order&order_type=shipping-complete">배송완료</a>
		</li>
		<li class="<?php echo $pending_active;?>">
			<a href="?page=kkcart_order&order_type=pending">입금대기</a>
<?php 
	if ( get_order_count(0) > 0 ){
?>
			<div class="numbering"><?php echo get_order_count(0);?></div>
<?php
	}
?>
		</li>
	</ul>
</div>
<div style="width:95%; clear:both; text-align:right">
	<form method="post" action="?page=kkcart_order&order_type=search">
	
	<select name="search_element">
		<option value="pid">주문번호</option>
		<option value="receive_name">결제자명</option>
		<option value="pname">제품명</option>
		<option value="order_id">결제자 아이디</option>
	</select>

	<select name="search_kind">
		<option value="-1">전체</option>
		<option value="1">신규주문</option>
		<option value="2">배송준비</option>
		<option value="3">배송완료</option>
		<option value="0">입금대기</option>
	</select>

	<input type="text" name="search_keyword">
	<input type="submit" class="button button-primary" value="검색">
	</form>
</div>
<div id="kkcart-admin-order-content">

<?php

	switch($order_type){

		case "new-order" :
			include dirname(__FILE__)."/admin-order-new.php";
		break;

		case "pending" :
			include dirname(__FILE__)."/admin-order-pending.php";
		break;

		case "shipping-ready" :
			include dirname(__FILE__)."/admin-order-ready.php";
		break;

		case "shipping-complete" :
			include dirname(__FILE__)."/admin-order-complete.php";
		break;

		case "search" :
			include dirname(__FILE__)."/admin-order-search.php";
		break;

		case "view" :
			include dirname(__FILE__)."/admin-order-view.php";
		break;

		default :
			include dirname(__FILE__)."/admin-order-new.php";
		break;

	}

?>

</div>

<?php

}

?>