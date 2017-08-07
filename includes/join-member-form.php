<?php


	$join_table = array(
	'id',
	'password',
	're_password',
	'name',
	'email',
	'address',
	'tel',
	'phone',
	'birth',
	'newsletter'
	);

	$join_table = apply_filters("join_table_array", $join_table);

?>
<div id="member-join">
<form method="post" id="member_join_form_pc">
	<h3>기본정보</h3>
	<table>
<?php
	$absolute_count = 0;
	foreach($join_table as $method){
		if($method == 'id' or $method == 'password' or $method == 're_password' or $method == 'name' or $method == 'email' or $method == 'address' or $method == 'phone'){
			$absolute_count++;
		}
		switch($method){
			case "id" :
				$join_contents .= "<tr><th>아이디</th><td><input type='text' name='uid'> <input type='button' value='중복확인' onclick=\"chk_uid_exist('pc');\"> (영문소문자/숫자, 4~16자) <input type='hidden' name='chk_duplicate' value='0'></tr>";
			break;

			case "password" :
				$join_contents .= "<tr><th>비밀번호</th><td><input type='password' name='pwd'> 영문 대소문자,숫자, 또는 특수문자 중 2가지 이상 조합하여 10~16자로 입력해 주세요.</td></tr>";
			break;

			case "re_password" :
				$join_contents .= "<tr><th>비밀번호 확인</th><td><input type='password' name='re-pwd'></td></tr>";
			break;

			case "name" :
				$join_contents .= "<tr><th>이름</th><td><input type='text' name='uname'></td></tr>";
			break;

			case "email" :

				$email_domain = added_email_domain(); // function 에 등록되어 있는 리스트를 불러옴

				for ($i=0; $i < count($email_domain); $i++) { 
					$email_options .= "<option>".$email_domain[$i]."</option>";
				}

				$join_contents .= "<tr><th>이메일</th><td><input type='text' name='email'> @ <input type='text' name='email_domain'> <select onchange='input_email_to_field(this.value);'><option value='-1'>이메일 선택</option>".$email_options."</select> <input type='button' value='이메일 중복확인' onclick=\"chk_email_exist('pc')\"><input type='hidden' name='chk_email_duplicate' value='0'></td></tr>";
			break;

			case "address" :
				$join_contents .= "<tr><th>주소</th><td><table><tr><td><input type='text' name='postcode1' class='kingkong_input_s'> - <input type='text' name='postcode2' class='kingkong_input_s'> <input type='button' value='우편번호' onclick=\"showDaumPostcode('pc');\">
					<div id='layer' style='display:none;border:5px solid;position:fixed;width:400px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden'><img src='//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png' id='btnCloseLayer' style='cursor:pointer;position:absolute;right:-3px;top:-3px' onclick='closeDaumPostcode()'>
					</div></td></tr><tr><td><input type='text' name='address' class='kingkong_input_l'></td></tr><tr><td><input type='text' name='else_address' class='kingkong_input_l'></td></tr></table></td></tr>";
			break;

			case "tel" :
				$join_contents .= "<tr><th>유선전화</th><td><input type='text' name='tel1'></td></tr>";
			break;

			case "phone" :
				$join_contents .= "<tr><th>휴대전화</th><td><input type='text' name='phone1'> <input type='radio' name='send-agree' value='T' checked> 수신함 <input type='radio' name='send-agree' value='F'> 수신안함</td></tr>";
			break;

			case "birth" :
				$join_contents .= "<tr><th>생년월일</th><td><input type='text' name='birth_year' class='kingkong_input_s'> 년 <input type='text' name='birth_month' class='kingkong_input_s'> 월 <input type='text' name='birth_day' class='kingkong_input_s'> 일 <input type='radio' name='birth_kind' value='Y' checked> 양력 <input type='radio' name='birth_kind' value='E'> 음력</td></tr>";
			break;

			case "newsletter" :
				$join_contents .= "<tr><th>뉴스메일</th><td>뉴스 메일을 받으시겠습니까? <input type='radio' name='newsletter' value='T' checked>수신함 <input type='radio' name='newsletter' value='F'>수신안함</td></tr>";
			break;
		}
	}
		if($absolute_count == 7){
			echo $join_contents;
		} else {
			echo "필수 표기 필드 미등록 오류";
		}

?>
	</table>
	<h3>이용약관 동의</h3>
	<table>
		<tr>
			<td>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_POLICY);?></textarea>
			</td>
		</tr>
		<tr>
			<td>이용약관에 동의하십니까? <input type="checkbox" name="agree-policy">동의함</td>
		</tr>
	</table>

	<h3>개인정보 수집 및 이용 동의</h3>
	<table>
		<tr>
			<td>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_PRIVACY);?></textarea>
			</td>
		</tr>
		<tr>
			<td>개인정보 수집 및 이용에 동의하십니까? <input type="checkbox" name="agree-privacy">동의함</td>
		</tr>
	</table>

	<input type="button" value="취소" onclick="history.back();"> <input type="button" value="회원가입" onclick="kingkong_member_join('pc');">
</form>
</div>
<div id="member-join-mobile" style="display:none">
<form method="post" id="member_join_form_mobile">
		<ul>
			<li>
				<h3>아이디</h3>
				<input type="text" name="uid"> <input type="button" value="중복확인" class="kingkongtheme_button" onclick="chk_uid_exist('mobile');">
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
				<input type="text" name="uname" style="width:100%">
			</li>
			<li>
				<h3>이메일</h3>
				<input type="text" name="email" style="width:80px"> @ <input type="text" name="email_domain">
				<input type="button" class="kingkongtheme_button" style="margin-top:5px" value="이메일 중복확인" onclick="chk_email_exist('mobile');">
			</li>
			<li>
				<h3>우편번호</h3>
				<input type="text" style="width:70px" name="postcode1"> - <input type="text" style="width:70px" name="postcode2">
				<input type="button" value="우편번호" class="kingkongtheme_button" onclick="showDaumPostcode('mobile');">
				<div id="layer_mobile" style="display:none;border:5px solid;position:fixed;width:310px;height:530px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode('mobile')">
			</li>
			<li>
				<h3>기본주소</h3>
				<input type="text" name="address" style="width:100%">
			</li>
			<li>
				<h3>나머지 주소</h3>
				<input type="text" name="else_address" style="width:100%">
			</li>
			<li>
				<h3>유선전화</h3>
				<input type="text" name="tel1" style="width:70px"> - <input type="text" name="tel2" style="width:70px"> - <input type="text" name="tel3" style="width:70px">
			</li>
			<li>
				<h3>휴대전화</h3>
				<input type="text" name="phone1" style="width:70px"> - <input type="text" name="phone2" style="width:70px"> - <input type="text" name="phone3" style="width:70px">
			</li>
			<li>
				<h3>생년월일</h3>
				<input type="text" name="birth_year" style="width:70px"> 년 <input type="text" name="birth_month" style="width:70px"> 월 <input type="text" name="birth_day" style="width:70px"> 일
				<div style="margin-top:10px"><input type="radio" style="width:30px; height:30px" name="birth_kind" value="Y" checked> <span style="font-size:14px; position:relative; top:-10px">양력</span> <input type="radio" style="width:30px; height:30px; margin-left:20px" name="birth_kind" value="E"> <span style="font-size:14px; position:relative; top:-10px">음력</span></div>
			</li>
			<li>
				<h3>뉴스메일 수신</h3>
				<span style="font-size:14px; position:relative; top:-10px">뉴스 메일을 받으시겠습니까?</span><input type="radio" style="width:30px; height:30px; margin-left:10px" name="newsletter" value="T" checked> <span style="font-size:14px; position:relative; top:-10px">예</span> <input type="radio" style="width:30px; height:30px; margin-left:20px" name="newsletter" value="F"> <span style="font-size:14px; position:relative; top:-10px">아니요
			</li>
			<li>
				<h3>이용약관 동의</h3>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_POLICY);?></textarea>
				<div style="margin-top:10px;"><span style="font-size:14px; position:relative; top:-10px">이용약관에 동의 하십니까?</span> <input type="checkbox" style="width:30px; height:30px; margin-left:10px" name="agree-policy"> <span style="font-size:14px; position:relative; top:-10px">동의함</span></div>
			</li>
			<li>
				<h3>개인정보 수집 및 이용 동의</h3>
				<textarea style="height:100px; background:#f1f1f1"><?php echo get_post_field('post_content',KINGKONG_PRIVACY);?></textarea>
				<div style="margin-top:10px;"><span style="font-size:14px; position:relative; top:-10px">개인정보 수집 및 이용에 동의 하십니까?</span> <input type="checkbox" style="width:30px; height:30px; margin-left:10px" name="agree-privacy"> <span style="font-size:14px; position:relative; top:-10px">동의함</span></div>
			</li>
		</ul>
		<div style="margin-top:20px"><input type="button" class="kingkongtheme_button" value="취소" onclick="history.back();"> <input type="button" class="kingkongtheme_button" value="회원가입" onclick="kingkong_member_join('mobile');"></div>
</form>
</div>










