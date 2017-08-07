<?php

	$uid 				= sanitize_text_field( $_POST['uid'] );				// 아이디
	$pwd 				= sanitize_text_field( $_POST['pwd'] );				// 비밀번호
	$re_pwd 			= sanitize_text_field( $_POST['re-pwd'] );				// 비밀번호확인
	$uname 				= sanitize_text_field( $_POST['uname'] );				// 이름
	$zipcode1 			= sanitize_text_field( $_POST['postcode1'] );			// 우편번호1
	$zipcode2 			= sanitize_text_field( $_POST['postcode2'] );			// 우편번호2
	$address_jibun 		= sanitize_text_field( $_POST['address'] );			// 지번주소
	$address_else 		= sanitize_text_field( $_POST['else_address'] );		// 나머지 주소
	$address_doro		= sanitize_text_field( $_POST['doro_address'] );		// 도로명주소
	$tel1 				= sanitize_text_field( $_POST['tel1'] );				// 연락처1
	$tel2 				= sanitize_text_field( $_POST['tel2'] );				// 연락처2
	$tel3 				= sanitize_text_field( $_POST['tel3'] );				// 연락처3
	$phone1 			= sanitize_text_field( $_POST['phone1'] );				// 핸드폰1
	$phone2 			= sanitize_text_field( $_POST['phone2'] );				// 핸드폰2
	$phone3 			= sanitize_text_field( $_POST['phone3'] );				// 핸드폰3
	$send_agree 		= sanitize_text_field( $_POST['send-agree'] );			// 수신동의 (T or F)
	$email				= sanitize_text_field( $_POST['email'] );				// 이메일
	$email_domain 		= sanitize_text_field( $_POST['email_domain'] );		// 이메일 도메인
	$newsletter 		= sanitize_text_field( $_POST['newsletter'] );			// 뉴스레터 수신여부 (T or F)
	$policy_agree 		= sanitize_text_field( $_POST['agree-policy'] );		// 이용약관동의
	$privacy_agree 		= sanitize_text_field( $_POST['agree-privacy'] );		// 개인정보수집동의
	$birth_year			= sanitize_text_field( $_POST['birth_year'] );			// 생년
	$birth_month		= sanitize_text_field( $_POST['birth_month'] );		// 생월
	$birth_day			= sanitize_text_field( $_POST['birth_day'] );			// 생일
	$birth_kind			= sanitize_text_field( $_POST['birth_kind'] );			// 양력?음력? (양력:Y, 음력:E)

	$zipcode = $zipcode1."-".$zipcode2;
	$tel = $tel1.$tel2.$tel3;
	$phone = $phone1.$phone2.$phone3;
	$tel = str_replace("-", "", $tel);
	$phone = str_replace("-", "", $phone);
	$birthday = $birth_year."-".str_pad($birth_month,"2","0",STR_PAD_LEFT)."-".str_pad($birth_day,"2","0",STR_PAD_LEFT);
	$email = $email."@".$email_domain;

	$status = wp_create_user($uid, $pwd, $email);

	$kingkong_user_info = array(

		'zipcode' 		=> $zipcode,
		'address_doro' 	=> $address_doro,
		'address_jibun' => $address_jibun,
		'address_else'	=> $address_else,
		'tel'			=> $tel,
		'phone'			=> $phone,
		'send_agree'	=> $send_agree,
		'birthday'		=> $birthday,
		'birth_kind'	=> $birth_kind,
		'newsletter'	=> $newsletter
	);

	$kingkong_user_info = serialize($kingkong_user_info);

	$mileage_config = unserialize(get_option("mileage_config")); // 마일리지 설정 정보를 가져온다.
	$mileage_status = $mileage_config['mileage_use'];
	$join_mileage	= $mileage_config['join_mileage'];				// 회원 가입시 적립될 마일리지
	

	if (!$status || is_wp_error($status)){
		echo "회원등록에 실패 하였습니다. 관리자에게 문의 바랍니다.";
	} else {

		$userinfo = array(
			'ID' => $status,
			'display_name' => $uname
			);

		wp_update_user($userinfo);

		update_user_meta($status,"kingkong_user_info", $kingkong_user_info);

		if($mileage_status == "T"){
			update_user_meta($status, "kingkong_mileage", $join_mileage);
		}

		$user_data = array(
			'user_id'		=> $uid,
			'user_email'	=> $email,
			'zipcode'		=> $zipcode,
			'address_doro'	=> $address_doro,
			'address_jibun' => $address_jibun,
			'address_else'	=> $address_else,
			'user_tel'		=> $tel,
			'user_phone'	=> $phone,
			'birthday'		=> $birthday,
			'birth_kind'	=> $birth_kind,
		);

?>

	<div id="member_join_ok">

	<h2>회원가입에 감사드립니다.</h2>

	<input type="button" class="button button-primary" value="로그인하기" onclick="location.href='<?php echo get_the_permalink(KINGKONG_LOGIN);?>';">

	<input type="button" class="button button-primary" value="메인화면" onclick="location.href='<?php echo home_url();?>';">

	</div>

<?php
	do_action('join_member_after', $user_data);
	}
?>