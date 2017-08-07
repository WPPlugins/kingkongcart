<?php

add_shortcode("kingkongcart_qna","kingkongcart_qna");

function kingkongcart_qna($attr){

	$board_config = unserialize(get_option("kingkongcart_board_config"));
	$current_skin = $board_config['skin'];

	include "skin/".$current_skin."/".$current_skin."-qna.php";

}

if( !function_exists(kkcart_board_style) ){

	add_action('wp_head', 'kkcart_board_style'); // Front-End 스타일 등록

	function kkcart_board_style() {

		$board_config = unserialize(get_option("kingkongcart_board_config"));
		$current_skin = $board_config['skin'];

	    wp_enqueue_style('kingkongcart-board-style', KINGKONGCART_PLUGINS_URL."/includes/board/skin/".$current_skin."/css/style.css");
	}

}

?>