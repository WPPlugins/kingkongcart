<?php

if ( is_user_logged_in() ){

	if($_POST['pwd']){

		$pwd = $_POST['pwd'];

		global $current_user;
		get_currentuserinfo();
		$user_login = $current_user->user_login;

		$user = get_user_by( 'login', $user_login );

		$result = wp_check_password($pwd, $user->user_pass, $user->ID);

		if($result){
			if(is_user_logged_in()) {
					require_once(ABSPATH.'wp-admin/includes/user.php' );
					$current_user = wp_get_current_user();
					wp_delete_user( $current_user->ID );
					echo "<script>alert('정상적으로 탈퇴 되었습니다.'); location.href='".home_url()."';</script>";
			}		
		} else {
			echo "<div class='mypage-broke-notice'>비밀번호가 일치하지 않습니다.</div>";
		}

	} 
?>
<div class="mypage-broke">
	<form method="post" id="mypage_broke_form">
	<table id="mypage_broke_table">
		<tr>
			<td colspan="2">
				회원탈퇴를 하기 위해서는 비밀번호를 재입력 하셔야 합니다.
			</td>
		</tr>
		<tr>
			<td>비밀번호 입력 :</td>
			<td><input type="password" name="pwd"> <input type="submit" value="회원탈퇴"></td>
		</tr>
	</table>
	</form>
</div>
<?php

} else {
?>
마이페이지는 로그인 후 이용하실 수 있습니다.
<?php
}
?>

