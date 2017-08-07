<?php

	class kingkong_create_page {

		function __construct(){
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkongcart_create_page') );
		}

		public function kingkongcart_create_page() {
		    global $wpdb; // Not sure if you need this, maybe

		    $page_notuser_regist = array(
		      'post_title'    => '비회원 주문확인',
		      'post_content'  => '',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );

		    $page_exists = get_page_by_title( $page_notuser_regist['post_title'] );

		    if(!get_option("kingkongcart_notuser_regist")){
		      if( $page_exists == null ){
		        $insert = wp_insert_post( $page_notuser_regist );
		        if($insert){
		          update_option("kingkongcart_notuser_regist", $insert);
		        }
		      }
		    }

		    $page_order_result = array(
		      'post_title'    => '킹콩카트-주문완료',
		      'post_content'  => '',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );

		    $page_exists = get_page_by_title( $page_order_result['post_title'] );

		    if(!get_option("kingkongcart_order_result")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_order_result );
		            if($insert){  
		                update_option("kingkongcart_order_result",$insert);
		            }
		        }
		    }

		    $page_mypage = array(
		      'post_title'    => '킹콩카트-마이페이지',
		      'post_content'  => '[my_page]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_mypage['post_title'] );

		    if(!get_option("kingkongcart_mypage_mypage")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_mypage );
		            if($insert){ 
		                update_option("kingkongcart_mypage_mypage",$insert);
		            }
		        }
		    }

		    $page_cart = array(
		      'post_title'    => '킹콩카트-장바구니',
		      'post_content'  => '[my_page page=cart]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_cart['post_title'] );

		    if(!get_option("kingkongcart_mypage_cart")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_cart );
		            if($insert){  
		                update_option("kingkongcart_mypage_cart",$insert);
		            }
		        }
		    }

		    $page_main = array(
		      'post_title'    => '킹콩카트-메인페이지',
		      'post_content'  => '[kingkong_product_loop]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_main['post_title'] );

		    if(!get_option("kingkongcart_main")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_main );
		            if($insert){
		                update_option("kingkongcart_main",$insert);
		            }
		        }
		    }

		    $page_wish = array(
		      'post_title'    => '킹콩카트-위시리스트',
		      'post_content'  => '[my_page page=wish]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_wish['post_title'] );

		    if(!get_option("kingkongcart_mypage_wish")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_wish );
		            if($insert){    
		                update_option("kingkongcart_mypage_wish",$insert);
		            }
		        }
		    }

		    $page_order = array(
		      'post_title'    => '킹콩카트-주문내역',
		      'post_content'  => '[my_page page=order]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_order['post_title'] );

		    if(!get_option("kingkongcart_mypage_order")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_order );
		            if($insert){
		                update_option("kingkongcart_mypage_order",$insert);
		            }
		        }
		    }



		    $page_join = array(
		      'post_title'    => '킹콩카트-회원가입',
		      'post_content'  => '[kingkong_join_member]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_join['post_title'] );

		    if(!get_option("kingkongcart_join_member")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_join );
		            if($insert){
		                update_option("kingkongcart_join_member",$insert);
		            }
		        }
		    }



		    $page_profile = array(
		      'post_title'    => '킹콩카트-내정보수정',
		      'post_content'  => '[my_page page=profile]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_profile['post_title'] );

		    if(!get_option("kingkongcart_mypage_profile")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_profile );
		            if($insert){
		                update_option("kingkongcart_mypage_profile",$insert);       
		            }
		        }
		    }

		    $page_broke = array(
		      'post_title'    => '킹콩카트-회원탈퇴',
		      'post_content'  => '[my_page page=broke]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_broke['post_title'] );

		    if(!get_option("kingkongcart_mypage_broke")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_broke );
		            if($insert){
		                update_option("kingkongcart_mypage_broke",$insert);
		            }
		        }
		    }

		    $page_all_order = array(
		      'post_title'    => '킹콩카트-구매하기',
		      'post_content'  => '[kingkongcart_order]',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_all_order['post_title'] );

		    if(!get_option("kingkongcart_order_page")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_all_order );
		            if($insert){
		                update_option("kingkongcart_order_page",$insert);
		            }
		        }
		    }

		    $page_login = array(
		      'post_title'    => '킹콩카트-로그인',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_login['post_title'] );

		    if(!get_option("kingkongcart_login")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_login );
		            if($insert){
		                update_option("kingkongcart_login",$insert);
		            }
		        }
		    }

		    $page_policy = array(
		      'post_title'    => '킹콩카트-이용약관',
		      'post_content'  => '',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_policy['post_title'] );

		    if(!get_option("kingkongcart_policy")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_policy );
		            if($insert){   
		                update_option("kingkongcart_policy",$insert);
		            }
		        }
		    }

		    $page_privacy = array(
		      'post_title'    => '킹콩카트-개인정보취급방침',
		      'post_content'  => '',
		      'post_status'   => 'publish',
		      'post_type'     => 'page',
		      'post_author'   => 1
		    );


		    $page_exists = get_page_by_title( $page_privacy['post_title'] );
		    if(!get_option("kingkongcart_privacy")){
		        if( $page_exists == null ) {
		            $insert = wp_insert_post( $page_privacy );
		            if($insert){  
		                update_option("kingkongcart_privacy",$insert);   
		            }
		        }
		    }
		}
	}

	if(is_admin()){
		new kingkong_create_page();
	}	

?>