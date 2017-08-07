<?php
if ( is_user_logged_in() ){

	global $current_user;
	get_currentuserinfo();
	$user_id 			= $current_user->ID;

	if($_POST['submit']){
		$new_pwd 		= $_POST['pwd'];
		$new_repwd 		= $_POST['re-pwd'];
		$user_name 		= $_POST['uname'];
		$postcode1 		= $_POST['postcode1'];
		$postcode2 		= $_POST['postcode2'];
		$zipcode 		= $postcode1."-".$postcode2;
		$address 		= $_POST['address'];
		$address_else 	= $_POST['else_address'];
		$tel 			= $_POST['tel1']."-".$_POST['tel2']."-".$_POST['tel3'];
		$phone 			= $_POST['phone1']."-".$_POST['phone2']."-".$_POST['phone3'];
		$send_agree 	= $_POST['send-agree'];
		$birthday 		= $_POST['birth_year']."-".str_pad($_POST['birth_month'],"2","0",STR_PAD_LEFT)."-".str_pad($_POST['birth_day'],"2","0",STR_PAD_LEFT);
		$birth_kind 	= $_POST['birth_kind'];
		$newsletter 	= $_POST['newsletter'];

	if($new_pwd != $new_repwd){
		echo "<script>alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.');</script>";
	} else {

		$kingkong_user_info = array(

			'zipcode' 		=> $zipcode,
			'address_doro' 	=> '',
			'address_jibun' => $address,
			'address_else'	=> $address_else,
			'tel'			=> $tel,
			'phone'			=> $phone,
			'send_agree'	=> $send_agree,
			'birthday'		=> $birthday,
			'birth_kind'	=> $birth_kind,
			'newsletter'	=> $newsletter
		);

		$userinfo = array(
			'ID' => $user_id,
			'display_name' => $user_name
			);

		wp_update_user($userinfo);

		$kingkong_user_info = serialize($kingkong_user_info);

		update_user_meta($user_id,"kingkong_user_info", $kingkong_user_info);
		if($new_pwd){
			wp_set_password($new_pwd, $user_id);
		}
		echo "<script>alert('수정되었습니다.'); location.href='/wp-admin/';</script>";
	}

	}

	global $current_user;
	get_currentuserinfo();
	$user_id 			= $current_user->ID;

	$user_name 		 	= $current_user->user_login;
	$user_display_name	= $current_user->display_name;
	$user_email			= $current_user->user_email;

	$kingkong_user_info	= get_user_meta($user_id, "kingkong_user_info", true);
	$kingkong_user_info = unserialize($kingkong_user_info);

	$zipcode 		= $kingkong_user_info['zipcode'];
	$address 		= $kingkong_user_info['address_jibun'];
	$address_else 	= $kingkong_user_info['address_else'];
	$tel 			= $kingkong_user_info['tel'];
	$phone 			= $kingkong_user_info['phone'];
	$birthday 		= $kingkong_user_info['birthday'];
	$birth_kind 	= $kingkong_user_info['birth_kind']; 
	$send_agree 	= $kingkong_user_info['send_agree'];
	$newsletter 	= $kingkong_user_info['newsletter'];

	$tel 			= explode("-", $tel);
	$phone 			= explode("-", $phone);
	$zipcode 		= explode("-", $zipcode);
	$birthday 		= explode("-", $birthday);
	$zipcode1 		= $zipcode[0];
	$zipcode2 		= $zipcode[1];
	$tel1 			= $tel[0];
	$tel2 			= $tel[1];
	$tel3 			= $tel[2];
	$phone1			= $phone[0];
	$phone2 		= $phone[1];
	$phone3			= $phone[2];
	$birth_year 	= $birthday[0];
	$birth_month 	= $birthday[1];
	$birth_day 		= $birthday[2];

?>
<div id="member-join-mobile" style="display:none">
<form method="post" id="member_join_form_mobile">
		<ul>
			<li>
				<h3>아이디</h3>
				<?php echo $user_name;?>
			</li>
			<li>
				<h3>비밀번호</h3>
				<input type="password" name="pwd" style="width:100%">
			</li>
			<li>
				<h3>비밀번호 확인</h3>
				<input type="password" name="re-pwd" style="width:100%">
			</li>
			<li>
				<h3>이름</h3>
				<input type="text" name="uname" value="<?php echo $user_display_name;?>" style="width:100%">
			</li>
			<li>
				<h3>이메일</h3>
				<?php echo $user_email;?>
			</li>
			<li>
				<h3>우편번호</h3>
				<input type="text" style="width:70px" name="postcode1" value="<?php echo $zipcode1;?>"> - <input type="text" style="width:70px" name="postcode2" value="<?php echo $zipcode2;?>">
				<input type="button" value="우편번호" class="kingkongtheme_button" onclick="showDaumPostcode('mobile');">
				<div id="layer_mobile" style="display:none;border:5px solid;position:fixed;width:310px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode('mobile')">
			</li>
			<li>
				<h3>기본주소</h3>
				<input type="text" name="address" style="width:100%" value="<?php echo $address;?>">
			</li>
			<li>
				<h3>나머지 주소</h3>
				<input type="text" name="else_address" style="width:100%" value="<?php echo $address_else;?>">
			</li>
			<li>
				<h3>유선전화</h3>
				<input type="text" name="tel1" style="width:70px" value="<?php echo $tel1;?>"> - <input type="text" name="tel2" style="width:70px" value="<?php echo $tel2;?>"> - <input type="text" name="tel3" style="width:70px" value="<?php echo $tel3;?>">
			</li>
			<li>
				<h3>휴대전화</h3>
				<input type="text" name="phone1" style="width:70px" value="<?php echo $phone1;?>"> - <input type="text" name="phone2" style="width:70px" value="<?php echo $phone2;?>"> - <input type="text" name="phone3" style="width:70px" value="<?php echo $phone3;?>"><div style="margin-top:10px"><input type="radio" style="width:30px; height:30px" name="send-agree" value="T" class="send_agree_true" checked> <span style="font-size:14px; position:relative; top:-10px">수신함</span> <input type="radio" style="width:30px; height:30px; margin-left:20px" name="send-agree" class="send_agree_false" value="F"> <span style="font-size:14px; position:relative; top:-10px">수신안함</span></div>
			</li>
			<li>
				<h3>생년월일</h3>
				<input type="text" name="birth_year" style="width:70px" value="<?php echo $birth_year;?>"> 년 <input type="text" name="birth_month" style="width:70px" value="<?php echo $birth_month;?>"> 월 <input type="text" name="birth_day" style="width:70px" value="<?php echo $birth_day;?>"> 일
				<div style="margin-top:10px"><input type="radio" style="width:30px; height:30px" name="birth_kind" value="Y" class="birth_kind_true" checked> <span style="font-size:14px; position:relative; top:-10px">양력</span> <input type="radio" style="width:30px; height:30px; margin-left:20px" name="birth_kind" class="birth_kind_false" value="E"> <span style="font-size:14px; position:relative; top:-10px">음력</span></div>
			</li>
			<li>
				<h3>뉴스메일 수신</h3>
				<span style="font-size:14px; position:relative; top:-10px">뉴스 메일을 받으시겠습니까?</span><input type="radio" style="width:30px; height:30px; margin-left:10px" name="newsletter" value="T" class="newsletter_true" checked> <span style="font-size:14px; position:relative; top:-10px">예</span> <input type="radio" style="width:30px; height:30px; margin-left:20px" name="newsletter" class="newsletter_false" value="F"> <span style="font-size:14px; position:relative; top:-10px">아니요
			</li>
		</ul>
		<div style="margin-top:20px"><input type="button" class="kingkongtheme_button" value="취소" onclick="history.back();"> <input type="submit" class="kingkongtheme_button" name="submit" value="정보수정"></div>
</form>
</div>

<div id="member-join">
	<form method="post" id="member_join_form">
	<h3>기본정보</h3>
	<table>
		<tr>
			<th>아이디</th>
			<td><?php echo $user_name;?></td>
		</tr>
		<tr>
			<th>비밀번호</th>
			<td>
				<input type="password" name="pwd"> 영문 대소문자,숫자, 또는 특수문자 중 2가지 이상 조합하여 10~16자로 입력해 주세요.
			</td>
		</tr>
		<tr>
			<th>비밀번호 확인</th>
			<td><input type="password" name="re-pwd"></td>
		</tr>
		<tr>
			<th>이름</th>
			<td><input type="text" name="uname" value="<?php echo $user_display_name;?>"></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td>
				<?php echo $user_email;?>
			</td>
		</tr>
		<tr>
			<th>주소</th>
			<td>
				<table>
					<tr>
						<td>
							<input type="text" name="postcode1" class="kingkong_input_s" value="<?php echo $zipcode1;?>"> - <input type="text" name="postcode2" class="kingkong_input_s" value="<?php echo $zipcode2;?>"> <input type="button" class="kingkongtheme_button" value="우편번호" onclick="showDaumPostcode();">
							<div id="layer" style="display:none;border:5px solid;position:fixed;width:400px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()">
							</div>
						</td>
					</tr>
					<tr>
						<td><input type="text" name="address" class="kingkong_input_l" value="<?php echo $address;?>"></td>
					</tr>
					<tr>
						<td><input type="text" name="else_address" class="kingkong_input_l" value="<?php echo $address_else;?>"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>유선전화</th>
			<td>
				<input type="text" name="tel1" class="kingkong_input_l" value="<?php echo $tel1;?>">
			</td>
		</tr>
		<tr>
			<th>휴대전화</th>
			<td>
				<input type="text" name="phone1" class="kingkong_input_l" value="<?php echo $phone1;?>"> <input type="radio" name="send-agree" class="send_agree_true" value="T" checked> 수신함 <input type="radio" name="send-agree" class="send_agree_false" value="F"> 수신안함
			</td>
		</tr>
		<tr>
			<th>생년월일</th>
			<td><input type="text" name="birth_year" class="kingkong_input_s" value="<?php echo $birth_year;?>"> 년 <input type="text" name="birth_month" class="kingkong_input_s" value="<?php echo $birth_month;?>"> 월 <input type="text" name="birth_day" class="kingkong_input_s" value="<?php echo $birth_day;?>"> 일 <input type="radio" name="birth_kind" value="Y" class="birth_kind_true" checked> 양력 <input type="radio" name="birth_kind" class="birth_kind_false" value="E"> 음력</td>
		</tr>
		<tr>
			<th>뉴스메일</th>
			<td>뉴스 메일을 받으시겠습니까? <input type="radio" name="newsletter" class="newsletter_true" value="T" checked>수신함 <input type="radio" class="newsletter_false" name="newsletter" value="F">수신안함</td>
		</tr>
	</table>

	<input type="button" class="kingkongtheme_button" value="취소"> <input type="submit" class="kingkongtheme_button" name="submit" value="정보수정">

	</form>
</div>

<script>
	var send_agree = "<?php echo $send_agree;?>";
	var newsletter = "<?php echo $newsletter;?>";
	var birth_kind = "<?php echo $birth_kind;?>";
	if(send_agree == "T"){
		jQuery(".send_agree_true").prop("checked", true);
	} else {
		jQuery(".send_agree_false").prop("checked", true);
	}

	if(newsletter == "T"){
		jQuery(".newsletter_true").prop("checked", true);
	} else {
		jQuery(".newsletter_false").prop("checked", true);
	}

	if(birth_kind == "Y"){
		jQuery(".birth_kind_true").prop("checked", true);
	} else {
		jQuery(".birth_kind_false").prop("checked", true);
	}
</script>

<?php

} else {
	echo "내정보수정은 로그인 후 이용하실 수 있습니다.";
}
?>