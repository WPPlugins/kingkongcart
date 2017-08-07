<?php

/* 상품등록 판매가격 설정 메타필드 html ***************************************************************/

function kkcart_product_meta_price(){

	global $post;
	$custom = get_post_custom($post->ID);
	$product_sell_price = $custom["product-sell-price"][0];
	$product_real_price = $custom["product_real_price"][0];

	$price = unserialize(get_post_meta($post->ID, 'kcommerce_price', true));

?>

<div style="height:40px">
	<ul>
		<li style="float:left"><b>공급가(VAT 포함)</b></li>
		<li style="float:left; margin-right:10px"><input type="text"></li>
	</ul>
	<ul>
		<li style="float:left"><b>소비자 판매가격</b></li>
		<li style="float:left; margin-right:10px"><input type="text" name="origin_price" onkeyup="input_complete_price();"></li>
		<li style="float:left"><b>할인 적용 판매가격</b></li>
		<li style="float:left; margin-right:10px">
<?php 
	if($price[1] !== ""){
?>
<input type="text" name="results_price"  value="<?echo $price[1];?>">
<?php
	}
	else {
?>
<input type="text" name="results_price" disabled="true" value="<?echo $price[1];?>">
<?php
	}
?>
		</li>	
	</ul>
</div>

<?php
}
?>