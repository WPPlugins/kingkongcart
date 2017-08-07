<?php
function kkcart_balance(){

	if(sanitize_text_field( $_POST['type'] ) == "this_month" or $_GET['type'] == "this_month"){
		$month_mktime_start = mktime(0,0,0,date("m"),1,date("Y"));
		$month_mktime_end	 = mktime(0,0,0,date("m", strtotime('next month')),1,date("Y"));
		$search_text = "이번달 검색";
	} elseif(sanitize_text_field( $_POST['type'] ) == "search_month" or $_GET['type'] == "search_month") {
		if($_POST['type']){
			$get_start_date = sanitize_text_field( $_POST['first_date'] );
			$get_last_date	= sanitize_text_field( $_POST['last_date'] );
		}

		if($_GET['type']){
			$get_start_date = sanitize_text_field( $_GET['first_date'] );
			$get_last_date	= sanitize_text_field( $_GET['last_date'] );
		}
		$search_text 	= $get_start_date."부터 ~ ".$get_last_date."까지";
		$get_start_date = explode("-",$get_start_date);
		$get_last_date	= explode("-",$get_last_date);

		$month_mktime_start = mktime(0,0,0,$get_start_date[1],$get_start_date[2],$get_start_date[0]);
		$month_mktime_end	= mktime(23,59,59,$get_last_date[1],$get_last_date[2],$get_last_date[0]);
	} else {
		$month_mktime_start = mktime(0,0,0,date("m"),1,date("Y"));
		$month_mktime_end	 = mktime(0,0,0,date("m", strtotime('next month')),1,date("Y"));		
	}

	if($_POST['first_date'] and $_POST['last_date']){
		$type = $_POST['type'];
		$first_date = $_POST['first_date'];
		$last_date = $_POST['last_date'];
	}

	if($_GET['first_date'] and $_GET['last_date']){
		$type = $_GET['type'];
		$first_date = $_GET['first_date'];
		$last_date = $_GET['last_date'];
	}
?>

<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">정산관리</span></h2>
<?php 
	if (sanitize_text_field( $_POST['type'] )){
?>
	<h3>검색조건: <?php echo $search_text;?></h3>
<?php
	}
?>

<form method="post" id="search_balance_form">
<input type="hidden" name="type" value="this_month">
</form>
<form method="post" style="width:95%;">
<input type="hidden" name="type" value="search_month">
<input type="button" class="button button-primary" value="이번달 검색" onclick="search_this_month_balance();"> 조건검색 : <input type="text" name="first_date" class="first_date" style="width:90px" value="<?php echo $first_date;?>">부터 ~ <input type="text" name="last_date" class="last_date" style="width:90px" value="<?php echo $last_date;?>">까지 <input type="submit" class="button button-primary" value="검색">
<input type="button" class="button" value="현재페이지 엑셀파일 다운로드" style="float:right" onclick="window.location='<?php echo admin_url('admin-post.php?action=download_balance.xls');?>&start=<?php echo $month_mktime_start;?>&end=<?php echo $month_mktime_end;?>';">
</form>

<?php
	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";
	switch($_POST['type']){
		case "this_month" :
			$query = "(SELECT * from ".$order_table." where status = '3' and mktime > '".$month_mktime_start."' and mktime < '".$month_mktime_end."')";
		break;

		case "search_month" :
			$query = "(SELECT * from ".$order_table." where status = '3' and mktime >= '".$month_mktime_start."' and mktime <= '".$month_mktime_end."')";
		break;

		default :
			$query = "(SELECT * from ".$order_table." where status = '3' and mktime >= '".$month_mktime_start."' and mktime < '".$month_mktime_end."')";
		break;
	}
	$total = $wpdb->get_var( "SELECT COUNT(1) FROM (${query}) AS combined_table" );
	$items_per_page = 20;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;
	$orders = $wpdb->get_results( $query . " ORDER BY mktime LIMIT ".$offset.", ".$items_per_page );
?>

<div id="kkcart-admin-order-content" style="margin-top:10px">
<table>
	<thead>
		<tr>
			<th>번호</th>
			<th>주문번호</th>
			<th>구분</th>
			<th>매출액(배송비포함)</th>
			<th>배송비</th>
			<th>매입가(VAT포함)</th>
			<th>매입원가</th>
			<th>매입가 부가세</th>
			<th>판매가(VAT포함)</th>
			<th>판매원가</th>
			<th>판매부가세</th>
			<th>실수익</th>
			<th>납부할부가세</th>
			<th>결제자명</th>
			<th>거래발생일</th>
		</tr>
	</thead>
	<tbody>
<?php
	if($orders){
		$cnt = 1;
		foreach($orders as $order){
		$order_product = unserialize(get_order_meta($order->ID, "buying_product"));	
		$shipping_cost = get_order_meta($order->ID, "shipping_cost");

			for ($i=0; $i < count($order_product); $i++) { 

				$product_id 		= $order_product[$i]['product_id'];
				$last_price 		= $order_product[$i]['price'];	// 판매가격
				$discount_price		= $order_product[$i]['discount_price'];	// 할인가격
				$mileage_price		= $order_product[$i]['mileage_price'];	// 적립금(마일리지) 원(점)
				$provide_price		= $order_product[$i]['provide_price'];	// 공급가(vat포함)	

				// 할인된 가격이 있다면 판매가격에서 제외시킨다.
				$last_price = $last_price - $discount_price;

				$gg = $provide_price;
				$gg_original = $gg / 1.1;
				$gg_vat = $gg - $gg_original;
				$gg_vat = round($gg_vat);
				$gg_original = $gg - $gg_vat;

				$sel_price = $last_price;
				$sel_original = $sel_price / 1.1;
				$sel_vat = $sel_price - $sel_original;
				$sel_vat = round($sel_vat);
				$sel_original = $sel_price - $sel_vat;

				$benefit = $sel_original - $gg_original;
				$real_vat = $sel_vat - $gg_vat;	
?>
		<tr>
<?php
	if($i == 0){
?>
			<td rowspan="<?php echo count($order_product);?>"><?php echo $cnt;?></td>
			<td rowspan="<?php echo count($order_product);?>"><a href="?page=kkcart_order&order_type=view&id=<?php echo $order->ID;?>"><?php echo $order->pid;?></a></td>
			<td rowspan="<?php echo count($order_product);?>"><?php echo $order->kind;?></td>
			<td rowspan="<?php echo count($order_product);?>"><?php echo number_format($order->order_price);?>원</td>
			<td rowspan="<?php echo count($order_product);?>"><?php echo number_format($shipping_cost);?>원</td>
<?php
	}
?>
			<td><?php echo number_format($gg);?>원</td>
			<td><?php echo number_format($gg_original);?>원</td>
			<td><?php echo number_format($gg_vat);?>원</td>
			<td><?php echo number_format($sel_price);?>원</td>
			<td><?php echo number_format($sel_original);?>원</td>
			<td><?php echo number_format($sel_vat);?>원</td>
			<td><?php echo number_format($benefit);?>원</td>
			<td><?php echo number_format($real_vat);?>원</td>
			<td><?php echo $order->receive_name;?></td>
			<td><?php echo $order->order_date;?></td>
		</tr>
<?php						
			}
			$cnt++;
		}
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
    'current' => $page,
    'add_args' => array(
    	'type' => $type,
    	'first_date' => $first_date,
    	'last_date' => $last_date
    	)
	));
?>
</div>
</div>

<script>
jQuery(document).ready(function() {
    jQuery('.first_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });

    jQuery('.last_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });

});
</script>

<?php
}
?>