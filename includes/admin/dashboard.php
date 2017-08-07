<?php
function kkcart_dashboard(){

	$dashboard_type = sanitize_text_field( $_GET['dashboard_type'] );

	switch($dashboard_type){

		case "general" :
		$general_active = "active";
		break;

		case "basic" :
		$basic_active = "active";
		break;

		case "payment" :
		$payment_active = "active";
		break;

		case "shipping" :
		$shipping_active = "active";
		break;

		case "mileage" :
		$mileage_active = "active";
		break;

		case "coupon" :
		$coupon_active = "active";
		break;

		case "board" :
		$board_active = "active";
		break;

		case "coupon_modi" :
		$coupon_active = "active";
		break;

		default :
		$general_active = "active";
		break;

	}
?>
<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">킹콩카트</span></h2>
<div id="kkcart-admin-dashboard-btn">
	<ul>
		<li class="<?php echo $general_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=general">기본설정</a></li>
		<li class="<?php echo $basic_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=basic">리스트 설정</a></li>
		<li class="<?php echo $payment_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=payment">결제설정</a></li>
		<li class="<?php echo $shipping_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=shipping">배송설정</a></li>
		<li class="<?php echo $mileage_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=mileage">적립금설정</a></li>
		<li class="<?php echo $coupon_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=coupon">쿠폰설정</a></li>
		<li class="<?php echo $board_active;?>"><a href="?page=kkcart_dashboard&dashboard_type=board">상품 게시판 설정</a></li>
	</ul>
</div>
<div id="kkcart-admin-dashboard-content">

<?php

	switch($dashboard_type){

		case "general" :
			include dirname(__FILE__)."/dashboard-general.php";
		break;

		case "basic" :
			include dirname(__FILE__)."/dashboard-basic.php";
		break;

		case "payment" :
			include dirname(__FILE__)."/dashboard-payment.php";
		break;

		case "shipping" :
			include dirname(__FILE__)."/dashboard-shipping.php";
		break;

		case "mileage" :
			include dirname(__FILE__)."/dashboard-mileage.php";
		break;

		case "coupon" :
			include dirname(__FILE__)."/dashboard-coupon.php";
		break;

		case "coupon_modi" :
			include dirname(__FILE__)."/dashboard-coupon-modi.php";
		break;

		case "board" :
			include dirname(__FILE__)."/dashboard-board.php";
		break;

		default :
			include dirname(__FILE__)."/dashboard-general.php";
		break;

	}
	
?>
</div>
<?php
}
?>