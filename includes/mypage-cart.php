<?php

	$cart = get_kingkong_cart(); // 장바구니 정보를 불러온다.
	@usort($cart);

	$original_mileage_text = MILEAGE_TEXT;
	$original_currency_text = CURRENCY_TEXT;

	// Apply Filter
	$mileage_text = apply_filters("change_mileage_text", $original_mileage_text);
	$currency_text = apply_filters("change_currency_text", $original_currency_text);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];

?>

<div class="mypage-cart mypage-div">
	<table>
		<thead>
			<tr>
				<th>상품명</th>
				<th>상품가격</th>
				<th>수량</th>
<?php
	if($mileage_status == "T"){
?>
				<th><?php echo $mileage_text;?></th>
<?php
	}
?>
				<th>합계</th>
				<th>선택</th>
			</tr>
		</thead>
		<tbody>
<?php

	if($cart){

		for ($i=0; $i < count($cart); $i++) { 

			$product_id 		= $cart[$i]['product_id'];
			$option1 			= $cart[$i]['first']['name'];
			$option1_plus_price = $cart[$i]['first']['plus_price'];
			$option2			= $cart[$i]['second']['name'];
			$option2_plus_price = $cart[$i]['second']['plus_price'];
			$quantity			= $cart[$i]['quantity'];

			$info = get_product_info($product_id); //상품 기본 정보 불러옴

			$each_total_mileage 	= $info->mileage_price * $quantity;
			$each_total_price			= ($info->final_price * $quantity) + ($option1_plus_price * $quantity) + ($option2_plus_price * $quantity);


			$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
			$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');

			$total_price 	+= $each_total_price;
			$total_mileage 	+= $each_total_mileage;

?>

			<tr>
				<td style="text-align:left;">
					<ul class="cart-thumbnail-display">
						<li><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></li>
						<li>
							<ul>
								<li>상품명 : <a href="<?php echo get_the_permalink($product_id);?>"><?php echo get_the_title($product_id);?></a></li>
								<li>옵션 1 : <?php echo $option1;?> (추가금액:<?php echo number_format($option1_plus_price).$currency_text;?>)</li>
<?php
	if($option2){
?>
								<li>옵션 2 : <?php echo $option2;?> (추가금액:<?php echo number_format($option2_plus_price).$currency_text;?>)</li>
<?php
}
?>
							</ul>
						</li>
					</ul>
				</td>
				<td><?php echo number_format($info->final_price).$currency_text;?></td>
				<td><?php echo $quantity;?></td>
<?php
	if($mileage_status == "T"){
?>
				<td><?php echo number_format($each_total_mileage);?></td>
<?php
	}
?>
				<td><?php echo number_format($each_total_price).$currency_text;?></td>
				<td class="cart-each-choice">
					<li onclick="kingkongcart_go_wish(<?php echo $i;?>);">위시리스트</li>
					<li onclick="kingkongcart_remove_cart(<?php echo $i;?>);">삭제</li>
				</td>
			</tr>

<?php
		} // end for
?>


		</tbody>
		<tfoot>
			<tr>
				<td colspan="6" class="cart-tfoot-td">총 구매금액 : <?php echo number_format($total_price).$currency_text;?> 
<?php
	if($mileage_status == "T"){
?>
					(적립금 <?php echo number_format($total_mileage);?>)
<?php
	}
?>
				</td>
			</tr>
		</tfoot>
	</table>
<?php
	$order_page_id = get_option("kingkongcart_order_page"); //구매하기 페이지 ID
?>
<div class="mypage-cart-buttons">
	<ul>
		<li><a onclick="remove_cart_all();" style="cursor:pointer; cursor:hand">장바구니비우기</a></li>
		<li>쇼핑계속</li>
		<li><a href="<?php echo get_the_permalink($order_page_id);?>">주문하기</a></li>
	</ul>
</div>

<?php 
	} // end if
	else {
?>
		<tr>
			<td colspan="6">장바구니가 비어있습니다.</td>
		</tr>
	</table>

<?php
	}
?>
</div>








