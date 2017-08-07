<?php

add_shortcode("kingkong_join_member","kingkong_join_member");

function kingkong_join_member($attr){

if (!is_user_logged_in()){

	if($_POST['uid'] && $_POST['pwd']){
		include "join-member-proc.php";
	} else {
		include "join-member-form.php";
		//include "zipcode.php";
	}

} else {


?>
	
	<script>
	location.href='<?php echo get_the_permalink(KINGKONG_MYPAGE);?>'; //회원일 경우 마이페이지로 리다이렉팅
	</script>
	
<?php
}

}

?>