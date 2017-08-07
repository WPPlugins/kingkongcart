<?php

add_shortcode("kingkong_product_loop","kingkong_product_loop");

function kingkong_product_loop($attr){

$cat_id = $attr['cat'];
$limit 	= $attr['limit'];
$type 	= $attr['type'];

if($cat_id){
$term = get_term($cat_id, "section");
$term_name = $term->name;
}

if($limit){
	$post_per_page = $limit;
} else {
	$post_per_page = -1;
}

if($term != ""){

	$args = array(
		'post_type' => 'kkcart_product',
		'post_status' => 'publish',
		'posts_per_page' => $post_per_page,
		'tax_query' => array(
			array(
				'taxonomy' => 'section',
				'field' => 'slug',
				'terms' => $term->slug
			)
		)

	);	

} else {
	$args = array(
		'post_type' => 'kkcart_product',
		'post_status' => 'publish',
		'posts_per_page' => $post_per_page
	);
}

$kkcart_products = new WP_Query( $args ); 

$options = get_kingkongcart_product_loop_options(); // 상품 loop 스타일링 옵션
$fonts_value = product_style_fonts($options->fonts);
$display_option = apply_filters("display_option_change", $options);
//$display_option = explode("-",$options->display);
$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
$mileage_status = $mileage_config['mileage_use'];

if($type){

$menu = wp_nav_menu( array(
	'menu_id'		  => 'nav-2',
	'theme_location'  => 'primary',
	'echo'			  => 0,
	'walker'          => new Kingkong_Walker_Nav_Sub_Menu(),
	'menu_class'      => '',
	s) );

$menu = preg_match_all('/(<ul class="sub-menu">.*<\/ul>)(.*<\/li>)/s',$menu,$matches);
$menu = $matches[1][0];
$menu = str_replace('class="sub-menu"','id="nav-2"',$menu);



}

if($term_name){

	if($type){
		$content = '
		<div style="margin-top:10px">
			<a href="'.home_url().'" style="color:gray">HOME</a> > <a href="'.get_category_link($cat_id).'" style="color:gray">'.$term_name.'</a>
		</div>
		<div style="max-width:'.SITEWIDTH.'px; text-align:center; font-size:28px; padding:20px 0px; margin-top:30px">'.$term_name.'</div>
		<div class="nav_sub_menu" style="max-width:'.SITEWIDTH.'px; text-align:center; font-size:14px; padding:10px 0px; margin-bottom:30px">
		'.$menu.'
		</div>
		<div id="kingkongcart-product-loop" style="'.$fonts_value.'"><ul>';
	} else {
		$content = '<div id="kingkongcart-product-loop-title">'.$term_name.'</div><div id="kingkongcart-product-loop" style="'.$fonts_value.'"><ul>';
	}
} else {
	$content ='<div id="kingkongcart-product-loop" style="'.$fonts_value.'"><ul>';
}

?>




<?php

if( count($kkcart_products) != 0 ){

	$original_mileage_text = MILEAGE_TEXT;
	$original_currency_text = CURRENCY_TEXT;

	// Apply Filter
	$mileage_text = apply_filters("change_mileage_text", $original_mileage_text);
	$currency_text = apply_filters("change_currency_text", $original_currency_text);

	while ( $kkcart_products -> have_posts() ) {
		$kkcart_products->the_post();
		$post_id = $kkcart_products->post->ID;
		$info = get_product_info($post_id);	// 해당상품 가격 정보

		$each_product_option = "";

		for ($i=0; $i < count($display_option); $i++) { 
			

			switch($display_option[$i]){

				case "thumbnail" :

					$each_product_option = apply_filters('product_loop_thumbnail', $options, $post_id);
					
				break;

				case "title" :

					$each_product_option .= apply_filters('product_loop_title', $options, $post_id);

				break;

				case "price" :

					$each_product_option .= apply_filters('product_loop_price', $options, $post_id);

				break;

				case "mileage" :

					$each_product_option .= apply_filters('product_loop_mileage', $options, $post_id);

				break;

				case "shortdesc" :

					$each_product_option .= apply_filters('product_loop_shortdesc', $options, $post_id);

				break;

				case "discount" :

					$each_product_option .= apply_filters('product_loop_discount', $options, $post_id);
			}

		}
		

		$content .= '<li><ul class="each_product_method" style="max-width:'.$options->grid_width.'; height:auto;">'.$each_product_option.'</ul></li>';

	} 	// end while

}		// end if	
else {

	//$content .= "<li>등록된 제품이 없습니다.</li>";
	$content .= apply_filters('product_loop_no_have');
}
	$content .= "</ul></div>";

	$content = wpautop(trim($content));
	
	return $content;
}


?>