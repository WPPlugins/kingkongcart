<?php

if ( is_user_logged_in() ){ //로그인한 상태

	$wish = get_kingkong_wish(); // 위시리스트를 불러온다.
	@usort($wish);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];
?>

<div class="mypage-wish mypage-div">
	<table>
		<thead>
			<tr>
				<th>상품명</th>
				<th>상품가격</th>
				<th>수량</th>
<?php
	if($mileage_status == "T"){
?>
				<th>적립금</th>
<?php
	}
?>
				<th>합계</th>
				<th>선택</th>
			</tr>
		</thead>
		<tbody>
<?php

	if($wish){

		for ($i=0; $i < count($wish); $i++) { 

			$product_id 		= $wish[$i]['product_id'];
			$option1 			= $wish[$i]['first']['name'];
			$option1_plus_price = $wish[$i]['first']['plus_price'];
			$option2			= $wish[$i]['second']['name'];
			$option2_plus_price = $wish[$i]['second']['plus_price'];
			$quantity			= $wish[$i]['quantity'];

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
								<li>옵션 1 : <?php echo $option1;?> (추가금액:<?php echo number_format($option1_plus_price);?>)</li>
<?php
	if($option2){
?>
								<li>옵션 2 : <?php echo $option2;?> (추가금액:<?php echo number_format($option2_plus_price);?>)</li>
<?php
}
?>
							</ul>
						</li>
					</ul>
				</td>
				<td><?php echo $info->final_price;?></td>
				<td><?php echo $quantity;?></td>
<?php
	if($mileage_status == "T"){
?>
				<td><?php echo $each_total_mileage;?></td>
<?php
	}
?>
				<td><?php echo $each_total_price;?></td>
				<td class="cart-each-choice">
					<li onclick="kingkongcart_go_cart(<?php echo $i;?>);">장바구니로이동</li>
					<li onclick="kingkongcart_remove_wish(<?php echo $i;?>);">삭제</li>
				</td>
			</tr>

<?php
		} // end for
?>

		</tbody>
	</table>

<div class="mypage-cart-buttons">
	<ul>
		<li><a onclick="remove_wish_all();" style="cursor:pointer; cursor:hand">위시리스트비우기</a></li>
		<li>쇼핑계속</li>
	</ul>
</div>

<?php 
	} // end if
	else {
?>
		<tr>
			<td colspan="6">위시리스트가 비어있습니다.</td>
		</tr>
	</table>

<?php
	}
?>

</div>

<?php

} else {
	echo "위시리스트는 로그인 후 이용하실 수 있습니다.";
}


?>