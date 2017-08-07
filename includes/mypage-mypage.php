<?php
if ( is_user_logged_in() ){

	global $current_user;
	get_currentuserinfo();

	$user_id 			= $current_user->ID;
	$user_name 		 	= $current_user->user_login;
	$user_display_name	= $current_user->display_name;
	$user_email			= $current_user->user_email;

	$registered = ($current_user->user_registered . "\n");

	$user_info = get_user_meta($user_id, "kingkong_user_info", true);
	$user_info = unserialize($user_info);
	$tel = $user_info['tel'];
	$phone = $user_info['phone'];
	$address_doro = $user_info['address_doro'];
	$address_jibun = $user_info['address_jibun'];
	$address_else = $user_info['address_else'];
	$zipcode = $user_info['zipcode'];
	$zipcode = explode("-",$zipcode);
	$zipcode1 = $zipcode[0];
	$zipcode2 = $zipcode[1];

	$tel = explode("-",$tel);
	$tel1 = $tel[0];
	$tel2 = $tel[1];
	$tel3 = $tel[2];
	$phone = explode("-",$phone);
	$phone1 = $phone[0];
	$phone2 = $phone[1];
	$phone3 = $phone[2];

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];
	$user_current_mileage = get_user_meta($user_id, "kingkong_mileage", true);
 	$user_coupons = unserialize(get_user_meta($user_id, "added_coupon", true));
 	$count_coupons = count($user_coupons);

?>
<div class="mypage-profile">
	<ul>
		<li>

			<table>
				<tr>
					<th>이름 :</th>
					<td><?php echo $user_display_name;?>님</td>
				</tr>
				<tr>
					<th>총구매금액 :</th>
					<td><?php echo number_format(get_user_buying_total_price($user_id));?>원 (배송비 제외)</td>
				</tr>
				<tr>
					<th>회원가입일 :</th>
					<td><?php echo $registered;?></td>
				</tr>
				<tr>
					<th>최근로그인 :</th>
					<td><?php echo get_user_meta($user_id, "last_login", true);?></td>
				</tr>
			</table>

		</li>
		<li>

			<table>
<?php 
	if($mileage_status == "T"){
?>
				<tr>
					<th>적립금 :</th>
					<td>
						<?php 
							if($user_current_mileage){
								echo number_format($user_current_mileage);
							} else {
								echo "없음";
							}
						?>
					</td>
				</tr>
<?php
	}
?>
				<tr>
					<th>예치금 :</th>
					<td>0 원</td>
				</tr>
				<tr>
					<th>할인쿠폰 :</th>
					<td><?php echo $count_coupons;?> 매</td>
				</tr>
			</table>

		</li>
	</ul>
</div>
<?php
} else {
?>
마이페이지는 로그인 후 이용하실 수 있습니다.
<?php
}
?>

