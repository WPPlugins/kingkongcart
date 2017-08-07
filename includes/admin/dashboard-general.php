<?php

if(sanitize_text_field( $_POST['form_status'] )){

	$product_prefix = sanitize_text_field( $_POST['product_prefix'] );
	$order_prefix 	= sanitize_text_field( $_POST['order_prefix'] );

	if(!$product_prefix){
		$product_prefix = "KC";
	}

	if(!$order_prefix){
		$order_prefix = "ORDER";
	}

	update_option("kingkongcart_product_prefix", $product_prefix);
	update_option("kingkongcart_order_prefix", $order_prefix);

	if(sanitize_text_field( $_POST['id_main'] )){
		update_option("kingkongcart_main", sanitize_text_field( $_POST['id_main'] ));
	}

	if(sanitize_text_field( $_POST['id_mypage'] )){
		update_option("kingkongcart_mypage_mypage", sanitize_text_field( $_POST['id_mypage'] ));
	}

	if(sanitize_text_field( $_POST['id_cart'] )){
		update_option("kingkongcart_mypage_cart", sanitize_text_field( $_POST['id_cart'] ));
	}

	if(sanitize_text_field( $_POST['id_wish'] )){
		update_option("kingkongcart_mypage_wish", sanitize_text_field( $_POST['id_wish'] ));
	}

	if(sanitize_text_field( $_POST['id_broke'] )){
		update_option("kingkongcart_mypage_broke", sanitize_text_field( $_POST['id_broke'] ));
	}

	if(sanitize_text_field( $_POST['id_profile'] )){
		update_option("kingkongcart_mypage_profile", sanitize_text_field( $_POST['id_profile'] ));
	}

	if(sanitize_text_field( $_POST['id_order_list'] )){
		update_option("kingkongcart_mypage_order", sanitize_text_field( $_POST['id_order_list'] ));
	}

	if(sanitize_text_field( $_POST['id_order_result'] )){
		update_option("kingkongcart_order_result", sanitize_text_field( $_POST['id_order_result'] ));
	}

	if(sanitize_text_field( $_POST['id_join'] )){
		update_option("kingkongcart_join_member", sanitize_text_field( $_POST['id_join'] ));
	}

	if(sanitize_text_field( $_POST['id_buy'] )){
		update_option("kingkongcart_order_page", sanitize_text_field( $_POST['id_buy'] ));
	}

	if(sanitize_text_field( $_POST['id_policy'] )){
		update_option("kingkongcart_policy", sanitize_text_field( $_POST['id_policy'] ));
	}

	if(sanitize_text_field( $_POST['id_privacy'] )){
		update_option("kingkongcart_privacy", sanitize_text_field( $_POST['id_privacy'] ));
	}

	if(sanitize_text_field( $_POST['id_login'] )){
		update_option("kingkongcart_login", sanitize_text_field( $_POST['id_login'] ));
	}

	if(sanitize_text_field( $_POST['auto_quantity'] )){
		update_option("kingkongcart_auto_quantity", sanitize_text_field( $_POST['auto_quantity'] ));
	}

	if(sanitize_text_field( $_POST['safe_quantity'] )){
		update_option("kingkongcart_safe_quantity", sanitize_text_field( $_POST['safe_quantity'] ));
	}

	if(sanitize_text_field( $_POST['notuser_regist'] )){
		update_option("kingkongcart_notuser_regist", sanitize_text_field( $_POST['notuser_regist'] ));
	}


}

$product_prefix 	= get_option("kingkongcart_product_prefix");
$order_prefix 		= get_option("kingkongcart_order_prefix");
$id_main			= get_option("kingkongcart_main");
$id_mypage			= get_option("kingkongcart_mypage_mypage");
$id_cart			= get_option("kingkongcart_mypage_cart");
$id_wish			= get_option("kingkongcart_mypage_wish");
$id_broke			= get_option("kingkongcart_mypage_broke");
$id_profile			= get_option("kingkongcart_mypage_profile");
$id_order 			= get_option("kingkongcart_mypage_order");
$id_join			= get_option("kingkongcart_join_member");
$id_buy				= get_option("kingkongcart_order_page");
$id_order_result 	= get_option("kingkongcart_order_result");
$id_policy			= get_option("kingkongcart_policy");
$id_privacy 		= get_option("kingkongcart_privacy");
$id_login			= get_option("kingkongcart_login");
$auto_quantity 		= get_option("kingkongcart_auto_quantity");
$safe_quantity  	= get_option("kingkongcart_safe_quantity");
$secret_key			= get_option("kingkongcart_update_key");
$id_notuser_regist 	= get_option("kingkongcart_notuser_regist");

if(!$product_prefix){
	$product_prefix = "KC";
}

if(!$order_prefix){
	$order_prefix = "ORDER";
}

?>

<ul>
	<li><span class="dashboard-content-title">기본설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		제품번호 프리픽스(prefix) 설정과 운영 전반에 대한 옵션을 설정하실 수 있습니다.<br>
		(프리픽스는 상품번호/주문번호 앞에 붙는 단어로, 가급적 영문 대문자 2자리수를 사용하시면 됩니다.)<br>
		프리픽스는 최초 설정 후 가급적 변경하시지 않는편이 좋습니다.<br><br>
		페이지의 ID 는 POST ID 이며 다른 페이지로 변경하시거나 올바르게 들어가지 않았다면 우측 숏코드를 내용에 붙여넣기 하시고<br>
		해당 글 또는 페이지의 POST ID 값을 입력하시면 됩니다.
		</span>
	</li>
</ul>
<form method="POST">
	<input type="hidden" name="form_status" value="1">
	<table style="padding-top:10px; padding-left:10px">

		<tr style="height:40px">
			<td>상품번호 프리픽스(Prefix) 설정</td>
			<td><input type="text" name="product_prefix" value="<?php echo $product_prefix;?>"><span class="dashboard-content-subtitle"> * 미입력시 KC 로 설정됩니다.</span></td>
		</tr>
		<tr style="height:40px">
			<td>주문번호 프리픽스(Prefix) 설정</td>
			<td><input type="text" name="order_prefix" value="<?php echo $order_prefix;?>"><span class="dashboard-content-subtitle"> * 미입력시 ORDER 로 설정됩니다.</span></td>
		</tr>
		<tr style="height:40px">
			<td>자동 재고관리 설정</td>
			<td><input type="radio" name="auto_quantity" value="T">사용 <input type="radio" name="auto_quantity" value="F">미사용</td>
		</tr>
		<tr style="height:40px">
			<td>안전 재고수량</td>
			<td><input type="text" name="safe_quantity" value="<?php echo $safe_quantity;?>">개 <span class="dashboard-content-subtitle">* 안전 재고수량은 기입하신 수량에 도달하면 자동으로 일시품절 표시가 되는 기능입니다.</span></td>
		</tr>
		<tr style="height:40px">
			<td>메인페이지 ID</td>
			<td>
				<input type="text" name="id_main" value="<?php echo $id_main;?>">
				<span class="dashboard-content-subtitle"> [kingkong_product_loop]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>마이페이지 ID</td>
			<td>
				<input type="text" name="id_mypage" value="<?php echo $id_mypage;?>">
				<span class="dashboard-content-subtitle"> [my_page]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>장바구니 ID</td>
			<td>
				<input type="text" name="id_cart" value="<?php echo $id_cart;?>">
				<span class="dashboard-content-subtitle"> [my_page page=cart]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>위시리스트 ID</td>
			<td>
				<input type="text" name="id_wish" value="<?php echo $id_wish;?>">
				<span class="dashboard-content-subtitle"> [my_page page=wish]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>회원탈퇴 ID</td>
			<td>
				<input type="text" name="id_broke" value="<?php echo $id_broke;?>">
				<span class="dashboard-content-subtitle"> [my_page page=broke]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>내정보수정 ID</td>
			<td>
				<input type="text" name="id_profile" value="<?php echo $id_profile;?>">
				<span class="dashboard-content-subtitle"> [my_page page=profile]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>주문내역 ID</td>
			<td>
				<input type="text" name="id_order_list" value="<?php echo $id_order;?>">
				<span class="dashboard-content-subtitle"> [my_page page=order]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>회원가입 ID</td>
			<td>
				<input type="text" name="id_join" value="<?php echo $id_join;?>">
				<span class="dashboard-content-subtitle"> [kingkong_join_member]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>로그인 ID</td>
			<td>
				<input type="text" name="id_login" value="<?php echo $id_login;?>">
				<span class="dashboard-content-subtitle"> [kingkong_login]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>구매하기 ID</td>
			<td>
				<input type="text" name="id_buy" value="<?php echo $id_buy;?>">
				<span class="dashboard-content-subtitle"> [kingkongcart_order]</span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>주문완료 ID</td>
			<td>
				<input type="text" name="id_order_result" value="<?php echo $id_order_result;?>">
				<span class="dashboard-content-subtitle"></span>
			</td>
		</tr>
		<tr style="height:40px">
			<td>이용약관 ID</td>
			<td><input type="text" name="id_policy" value="<?php echo $id_policy;?>"></td>
		</tr>
		<tr style="height:40px">
			<td>개인정보취급방침 ID</td>
			<td><input type="text" name="id_privacy" value="<?php echo $id_privacy;?>"></td>
		</tr>
		<tr stye="height:40px">
			<td>비회원 주문확인</td>
			<td><input type="text" name="id_notuser_regist" value="<?php echo $id_notuser_regist;?>"></td>
		</tr>
	</table>

	<input type="submit" class="button button-primary" value="확인" style="margin-left:10px">
</form>

<script>

	var auto_quantity = "<?php echo $auto_quantity;?>";

	switch(auto_quantity){
		case "T" :
			jQuery("[name=auto_quantity]:first").prop("checked", true);
		break;

		case "F" :
			jQuery("[name=auto_quantity]:last").prop("checked", true);
		break;

		default :
			jQuery("[name=auto_quantity]:first").prop("checked", true);
		break;
	}

</script>






