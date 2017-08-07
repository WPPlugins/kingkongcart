<?php
	
	global $wpdb;
	$order_table = $wpdb->prefix."kingkong_order";
	$query = "(SELECT * from $order_table where status = '2')";

	$total = $wpdb->get_var( "SELECT COUNT(1) FROM (${query}) AS combined_table" );
	$items_per_page = 20;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;
	$orders = $wpdb->get_results( $query . " ORDER BY mktime LIMIT ".$offset.", ".$items_per_page );

?>
<?php
	if($orders){
?>
<div class="list-view-style"><input type="button" class="button button-primary" value="일괄배송등록" style="margin-left:6px" onclick="open_modal_insert_csv();"> <input type="button" class="button button-large" value="배송리스트(csv) 다운로드" onclick="window.location='<?php echo admin_url('admin-post.php?action=download.csv');?>&kind=all&status=2';"> <input type="button" class="button button-large" value="배송리스트(csv) 다운로드(주문상품포함)" onclick="window.location='<?php echo admin_url('admin-post.php?action=downwithproduct.csv');?>&kind=all&status=2';"></div>
<?php
	}
?>

<table style="clear:both">
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

			$user_info = get_userdata($order->order_id);
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
			<td colspan="7" style="text-align:center">배송준비 물건이 없습니다.</td>
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
<div class="csv_modal">
	<div class="csv_modal_background"></div>
	<div class="csv_modal_content_area">
		<div class="csv_modal_content">
			<form id="order_csv_upload_form" target="csv_proc" method="POST" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php?action=upload.csv');?>">
				<iframe id="order_upload_process" name="csv_proc"></iframe>
				<div class="csv_modal_content_display">
					<div style="float:right"><a onclick="csv_modal_close();">[닫기]</a></div>
					<h3>일괄배송등록</h3>
					<p>반드시 첫 컬럼은 주문번호가 위치해야 하며 두번째 컬럼은 택배사, 세번째 컬럼은 송장번호가 위치해야 합니다. 일치 하지 않는다면 정상적으로 일괄배송등록이 이루어 지지 않습니다.</p>
					<p>타이틀 첫줄은 반드시 제거하시고 순수 주문 정보의 csv 파일이어야만 합니다.</p>
					<p style="color:red">구분자는 반드시 콤마(,)로 저장하셔야 합니다.</p>
					<table>
						<tr>
							<th>CSV 파일 업로드</th>
							<td><input type="file" name="csv_upfile"></td>
							<td><input type="submit" class="button" value="업로드"></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>



















