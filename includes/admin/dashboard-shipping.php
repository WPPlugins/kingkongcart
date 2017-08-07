<?php

	if(sanitize_text_field( $_POST['form_status'] )){

		$shipping = array();
		$shipping['basic'] 		= sanitize_text_field( $_POST['basic_shipping_cost'] ); 		// 기본 배송료
		$shipping['free']		= sanitize_text_field( $_POST['free_shipping_cost_limit'] );	// 무료배송 금액
		$shipping['company']	= sanitize_text_field( $_POST['shipping_company'] );			// 택배사

		$shipping = serialize($shipping);

		update_option("kingkong_shipping", $shipping);

	}

	$get_shipping = unserialize(get_option("kingkong_shipping"));

?>
<ul>
	<li><span class="dashboard-content-title">배송 설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		택배사, 기본배송료 및 도서산간 배송료 설정을 하실 수 있습니다.<br>
		금액 기입은 숫자만 입력하시기 바랍니다.
		</span>
	</li>
</ul>
<form method="post">
	<input type="hidden" name="form_status" value="1">
	<table style="padding-top:10px; padding-left:10px">
		<tr>
			<td>기본 배송료 설정</td>
			<td><input type="text" name="basic_shipping_cost" value="<?php echo $get_shipping['basic'];?>"> <span class="dashboard-content-subtitle">* 미입력시 기본 2,500원 으로 설정됩니다.</span></td>
		</tr>
		<tr>
			<td>무료배송 금액 설정</td>
			<td><input type="text" name="free_shipping_cost_limit" value="<?php echo $get_shipping['free'];?>"> <span class="dashboard-content-subtitle">* 금액을 입력하시면 총 주문금액이 해당 금액 이상이면 무료배송으로 적용됩니다.</span></td>
		</tr>
		<tr>
			<td>기본 택배사 선택</td>
			<td>
				<select name="shipping_company">
					<option value="-1">택배사를 선택하세요</option>
					<option value="PO">우체국택배</option>
					<option value="GD">경동택배</option>
					<option value="LG">로젠택배</option>
					<option value="DH">대한통운</option>
					<option value="CG">CJ GLS택배</option>
					<option value="HJ">한진택배</option>
					<option value="HD">현대택배</option>
					<option value="KB">KGB택배</option>
					<option value="YC">옐로우캡</option>
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" class="button button-primary" value="확인" style="margin-left:10px">
</form>
<script>
	jQuery("[name=shipping_company] > option").each(function(){
		if(jQuery(this).val() == "<?php echo $get_shipping['company'];?>"){
			jQuery("[name=shipping_company]").val(jQuery(this).val());
		}
	});
</script>