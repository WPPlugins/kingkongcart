<?php

add_shortcode("my_page","my_page");

function my_page($attr){

// 전체 마이페이지를 숏코드로 호출하고 page 값을 인자로 받아서 장바구니,프로필,위시리스트 등을 처리

$mypage_id  	= get_option("kingkongcart_mypage_mypage");
$cart_id		= get_option("kingkongcart_mypage_cart");
$wish_id		= get_option("kingkongcart_mypage_wish");
$order_id		= get_option("kingkongcart_mypage_order");
$qna_id			= get_option("kingkongcart_mypage_qna");
$question_id	= get_option("kingkongcart_mypage_question");
$profile_id		= get_option("kingkongcart_mypage_profile");
$broke_id		= get_option("kingkongcart_mypage_broke");

?>

	<div id="kingkongcart-mypage">

		<div class="mypage-top-menu">

<?php 
	if (is_user_logged_in()){
?>
			<ul>
				<li><a href="<?php echo get_the_permalink($mypage_id);?>">마이페이지</a></li>
				<li><a href="<?php echo get_the_permalink($cart_id);?>">장바구니</a></li>
				<li><a href="<?php echo get_the_permalink($wish_id);?>">위시리스트</a></li>
				<li><a href="<?php echo get_the_permalink($order_id);?>">주문내역</a></li>
				<li><a href="<?php echo get_the_permalink($profile_id);?>">내정보수정</a></li>
				<li><a href="<?php echo get_the_permalink($broke_id);?>">회원탈퇴</a></li>
			</ul>
<?php
	}
?>
		</div>
		<div class="mypage-content" style='clear:both'>

<?php

	switch($attr['page']){

		case "mypage" :
			require_once("mypage-mypage.php");
		break;

		case "cart" :
			require_once("mypage-cart.php");
		break;

		case "wish" :
			require_once("mypage-wish.php");
		break;

		case "order" :
			require_once("mypage-order.php");
		break;

		case "profile" :
			require_once("mypage-profile.php");
		break;

		case "broke" :
			require_once("mypage-broke.php");
		break;

		default :
			if (is_user_logged_in()){
				require_once("mypage-mypage.php");
			} else {
				require_once("mypage-cart.php");
			}
		break;


	}
?>
		</div>
<?php

}

?>