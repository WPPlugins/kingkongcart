<?php

add_action('save_post', 'kkcart_save_details');


function kkcart_save_details(){

global $post;

$post_id = sanitize_text_field( $_POST['ID'] );

if(!$post_id) {
    return false;
}

if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
	return $post_id;
}

// 상품코드 포스트메타 입력 /////////////////////////////////////////////////////

$kingkongcart_prefix = KINGKONGCART_PRODUCT_PREFIX;
$rand_number = rand(1000,9999);
$product_code = $kingkongcart_prefix.$post_id.$rand_number;

if(!get_post_meta($post_id, "kingkongcart-product-code", true)){
	update_post_meta($post_id, "kingkongcart-product-code", $product_code);
}


// 상품 정보 포스트메타에 배열로 입력 /////////////////////////////////////////////
$data = array();
$data[0] = sanitize_text_field( $_POST['origin_price'] ); 		// 소비자 판매가격
$data[1] = sanitize_text_field( $_POST['results_price'] ); 		// 할인 적용 판매가격
$data[2] = sanitize_text_field( $_POST['discount'] );			// 할인율 %
$data[3] = sanitize_text_field( $_POST['discount_price'] );		// 할인가격
$data[4] = sanitize_text_field( $_POST['mileage'] );			// 적립금(마일리지) %
$data[5] = sanitize_text_field( $_POST['mileage_price'] );		// 적립금(마일리지) 원(점)
$data[6] = sanitize_text_field( $_POST['short_desc'] );			// 짤막소개
$data[7] = sanitize_text_field( $_POST['provide_price'] );		// 공급가(vat 포함)
$data[8] = sanitize_text_field( $_POST['product_kind'] );		// 상품종류(배송상품 or 다운로드상품)
$data[9] = sanitize_text_field( $_POST['demo_site'] );			// 데모사이트 URL

$data = serialize($data);
update_post_meta($post_id, 'kingkongcart-product-info', $data);

// 옵션정보 포스트메타에 배열로 입력 /////////////////////////////////////////////
$option         = array();

$option_length 	= sanitize_text_field( $_POST['kkcart_total_number'] ); // 등록된 전체 옵션의 길이
$option_name 	= sanitize_text_field( $_POST['input_option'] );
for ($o=0; $o < $option_length; $o++) { 


	$option[$o]['main'] = array(
		'name' 			=> sanitize_text_field( $_POST['kkcart_option_name_'.$o] ), 	// 옵션명
		'plus_price' 	=> sanitize_text_field( $_POST['kkcart_plus_price_'.$o] ),
		'total_amount'	=> sanitize_text_field( $_POST['kkcart_total_amount_'.$o] ),
		'option_status' => sanitize_text_field( $_POST['kkcart_option_status_'.$o] )
	);

    // 2nd 옵션 설정값 저장
    $option_second_length[$o] = sanitize_text_field( $_POST['second_option_'.$o.'_length'] );
    $sub_total_amount = 0;
    for ($i=0; $i < $option_second_length[$o]; $i++){

    $option[$o]['sub'][$i] = array(
    	'name' 			=> sanitize_text_field( $_POST['kkcart_second_option_'.$o.'_'.($i+1)] ),
    	'plus_price'	=> sanitize_text_field( $_POST['kkcart_second_price_'.$o.'_'.($i+1)] ),
    	'total_amount'	=> sanitize_text_field( $_POST['kkcart_second_amount_'.$o.'_'.($i+1)] ),
    	'option_status' => sanitize_text_field( $_POST['kkcart_second_option_status_'.$o.'_'.($i+1)] )
    );

    $sub_total_amount += sanitize_text_field( $_POST['kkcart_second_amount_'.$o.'_'.($i+1)] );

    }
    if($option_second_length[$o] > 0){
    	$option[$o]['main']['total_amount'] = $sub_total_amount;
	}

}

$options = serialize($option);
update_post_meta($post_id, 'kingkongcart-product-option', $options);

$thumb_id = $_POST['added_thumb_id'];
$thumb_id = serialize($thumb_id);

update_post_meta($post_id, "kingkongcart_added_thumbnail_id", $thumb_id);

require_once(ABSPATH.'wp-load.php');
require_once(ABSPATH.'wp-admin/includes/image.php');

global $wpdb;


$download_file_tmp_name 	= $_FILES['download_file']['tmp_name'];
$download_file_real_name 	= $_FILES['download_file']['name'];
$upload_dir 				= wp_upload_dir();
$download_dir				= $upload_dir['baseurl']."/kingkong_files/";
$download_dir 				= str_replace(site_url(), "", $download_dir);

if(!file_exists(ABSPATH.$download_dir)){
	mkdir(ABSPATH.$download_dir);
}

$download_dir 				= $download_dir.$post_id."/";

/////////////////////////////////////////////////////////

if(!file_exists(ABSPATH.$download_dir)){
	mkdir(ABSPATH.$download_dir);
}

if (@fclose(@fopen($download_file_tmp_name, "r"))) { // 파일이 실제 존재하는지 확인

    copy($download_file_tmp_name, ABSPATH.$download_dir.$download_file_real_name);

    update_post_meta($post_id,"kingkong_download",$download_file_real_name);

}

} // function end


?>