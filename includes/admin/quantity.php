<?php
function kkcart_quantity(){

if(sanitize_text_field( $_POST['submit'] )){
	
	$product_id 			= sanitize_text_field( $_POST['product_id'] );
	$first_option 			= sanitize_text_field( $_POST['option_first_name'] );
	$first_option_amount 	= sanitize_text_field( $_POST['option_first_amount'] );
	$first_option_price 	= sanitize_text_field( $_POST['option_first_price'] );
	$first_option_status 	= sanitize_text_field( $_POST['option_first_status'] );
	$second_option 			= sanitize_text_field( $_POST['option_second_name'] );
	$second_option_price 	= sanitize_text_field( $_POST['option_second_price'] );
	$second_option_amount 	= sanitize_text_field( $_POST['option_second_amount'] );
	$second_option_status 	= sanitize_text_field( $_POST['option_second_status'] );

	for ($i=0; $i < count($first_option); $i++) { 
		
		$update_option[$i]['main']['name'] = $first_option[$i];
		$update_option[$i]['main']['plus_price'] = $first_option_price[$i];
		$update_option[$i]['main']['total_amount'] = $first_option_amount[$i];
		$update_option[$i]['main']['option_status'] = $first_option_status[$i];
		$sub_quantity_total = 0;

		for ($s=0; $s < count($second_option[$first_option[$i]]); $s++) { 
			$update_option[$i]['sub'][$s]['name'] =  $second_option[$first_option[$i]][$s];
			$update_option[$i]['sub'][$s]['plus_price'] = $second_option_price[$first_option[$i]][$s];
			$update_option[$i]['sub'][$s]['total_amount'] = $second_option_amount[$first_option[$i]][$s];
			$update_option[$i]['sub'][$s]['option_status'] = $second_option_status[$first_option[$i]][$s];
			$sub_quantity_total += $second_option_amount[$first_option[$i]][$s];
		}
		if($second_option){
		$update_option[$i]['main']['total_amount'] = $sub_quantity_total;
		}

	}
	
	$update_option = serialize($update_option);
	update_post_meta($product_id, "kingkongcart-product-option", $update_option);
}


?>

<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">재고관리</span></h2>


<?php
	global $wpdb;
	$product_table = $wpdb->prefix."posts";
	switch(sanitize_text_field( $_POST['type'] )){

		case "search_productcode" :
			$args = array(
				'post_type' => 'kkcart_product',
				'meta_key' => 'kingkongcart-product-code',
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => 'kingkongcart-product-code',
						'value' => sanitize_text_field( $_POST['input_product_code'] ),
						'compare' => 'IN',
					)
				)
			);

			$products = new WP_Query($args);
		break;

		default :
			$args = array(
				'post_type' => 'kkcart_product',
				'post_status' => 'publish'
			);

			$products = new WP_Query($args);
		break;
	}
?>

<form method="post" style="width:95%;">
<input type="hidden" name="type" value="search_productcode">

상품번호 검색 : <input type="text" name="input_product_code"> <input type="submit" class="button button-primary" value="검색"> 

</form>

<div id="kkcart-admin-order-content" style="margin-top:10px">
	<table>
		<thead>
			<tr>
				<th>상품번호</th>
				<th>썸네일</th>
				<th>상품명</th>
				<th>종류</th>
				<th>옵션명</th>
				<th>옵션추가가격</th>
				<th>재고수량</th>
				<th>상태</th>
				<th>변경</th>
			</tr>
		</thead>
		<tbody>
<?php
	if ( $products->have_posts() ) {
		while ( $products->have_posts() ){
			$products->the_post();
		$post->ID = get_the_ID();
		$thumbnail_ids = unserialize(get_post_meta($post->ID,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
		$options = get_post_meta($post->ID, 'kingkongcart-product-option', true );
		$options = unserialize($options);
		$sub_count = 0;
		$total_count = 0;
		for ($i=0; $i < count($options); $i++) { 
			$sub_count += count($options[$i]['sub']);
		}

		// 1차옵션 수 + 2차옵션 수
		$total_count = count($options) + $sub_count;

		$product_code = get_post_meta($post->ID, "kingkongcart-product-code", true );
?>
		<form method="post">
			<tr>
				<td rowspan="<?php echo $total_count;?>" style="text-align:center"><a href="<?php echo get_the_permalink($post->ID);?>"><?php echo $product_code;?></a></td>
				<td rowspan="<?php echo $total_count;?>" style="padding:10px 0px; text-align:center"><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
				<td rowspan="<?php echo $total_count;?>"><?php echo get_the_title();?></td>
<?php	
	for($i=0; $i < $total_count; $i++){

		if($i == 0){

?>

				<td style="background:#e6cfc1">1차옵션</td>
				<td><?php echo $options[$i]['main']['name'];?></td>
				<td style="text-align:center">
					<input type="text" name="option_first_price[]" value="<?php echo $options[$i]['main']['plus_price'];?>" style="width:50px">
				</td>
				<td style="text-align:center">
					<input type="text" name="option_first_amount[]" value="<?php echo $options[$i]['main']['total_amount'];?>" style="width:50px">
					<input type="hidden" name="option_first_name[]" value="<?php echo $options[$i]['main']['name'];?>">
				</td>
				<td style="text-align:center">
					<select name="option_first_status[]">
						<option value="2" <?php if ($options[$i]['main']['option_status'] == 2){ echo "selected"; }?>>판매중</option>
						<option value="1" <?php if ($options[$i]['main']['option_status'] == 1){ echo "selected"; }?>>일시품절</option>
						<option value="0" <?php if ($options[$i]['main']['option_status'] == 0){ echo "selected"; }?>>품절</option>
					</select>
				</td>
				<td rowspan="<?php echo $total_count;?>" style="text-align:center">

					<input type="hidden" name="product_id" value="<?php echo $post->ID;?>">

					<input type="submit" name='submit' class="button button-primary" value="변경">

				</td>
			</tr>


<?php
			for($s=0; $s < count($options[$i]['sub']); $s++){
?>
				<tr>
					<td style="background:#fcebdb;">2차옵션</td>
					<td><?php echo $options[$i]['sub'][$s]['name'];?></td>
					<td style="text-align:center"><input type="text" name="option_second_price[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$s]['plus_price'];?>" style="width:50px"</td>
					<td style="text-align:center">
						<input type="text" name="option_second_amount[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$s]['total_amount'];?>" style="width:50px">
						<input type="hidden" name="option_second_name[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$s]['name'];?>">
					</td>
					<td style="text-align:center">
					<select name="option_second_status[<?php echo $options[$i]['main']['name'];?>][]">
						<option value="2" <?php if ($options[$i]['sub'][$s]['option_status'] == 2){ echo "selected"; }?>>판매중</option>
						<option value="1" <?php if ($options[$i]['sub'][$s]['option_status'] == 1){ echo "selected"; }?>>일시품절</option>
						<option value="0" <?php if ($options[$i]['sub'][$s]['option_status'] == 0){ echo "selected"; }?>>품절</option>
					</select>
					</td>
				</tr>
<?php
			}

		} else {
			if($options[$i]['main']['name'] != ""){

?>
			<tr>
				<td style="background:#e6cfc1">1차옵션</td>
				<td><?php echo $options[$i]['main']['name'];?></td>
				<td style="text-align:center"><input type="text" name="option_first_price[]" value="<?php echo $options[$i]['main']['plus_price'];?>" style="width:50px"></td>
				<td style="text-align:center">
					<input type="text" name="option_first_amount[]" value="<?php echo $options[$i]['main']['total_amount'];?>" style="width:50px">
					<input type="hidden" name="option_first_name[]" value="<?php echo $options[$i]['main']['name'];?>">
				</td>
				<td style="text-align:center">
					<select name="option_first_status[]">
						<option value="2" <?php if ($options[$i]['main']['option_status'] == 2){ echo "selected"; }?>>판매중</option>
						<option value="1" <?php if ($options[$i]['main']['option_status'] == 1){ echo "selected"; }?>>일시품절</option>
						<option value="0" <?php if ($options[$i]['main']['option_status'] == 0){ echo "selected"; }?>>품절</option>
					</select>
				</td>
			</tr>
<?php

			}
				for($t=0; $t < count($options[$i]['sub']); $t++){
?>
					<tr>
						<td style="background:#fcebdb;">2차옵션</td>
						<td><?php echo $options[$i]['sub'][$t]['name'];?></td>
						<td style="text-align:center"><input type="text" name="option_second_price[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$t]['plus_price'];?>" style="width:50px"></td>
						<td style="text-align:center">
							<input type="text" name="option_second_amount[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$t]['total_amount'];?>" style="width:50px">
							<input type="hidden" name="option_second_name[<?php echo $options[$i]['main']['name'];?>][]" value="<?php echo $options[$i]['sub'][$t]['name'];?>">
						</td>
						<td style="text-align:center">
							<select name="option_second_status[<?php echo $options[$i]['main']['name'];?>][]">
								<option value="2" <?php if ($options[$i]['sub'][$t]['option_status'] == 2){ echo "selected"; }?>>판매중</option>
								<option value="1" <?php if ($options[$i]['sub'][$t]['option_status'] == 1){ echo "selected"; }?>>일시품절</option>
								<option value="0" <?php if ($options[$i]['sub'][$t]['option_status'] == 0){ echo "selected"; }?>>품절</option>
							</select>
						</td>
					</tr>
<?php
				}
		}
	}
?>
		</form>
<?php
		}
	}
?>
		</tbody>
	</table>
</div>
<?php
}
?>