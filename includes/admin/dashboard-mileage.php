<?php

if(sanitize_text_field( $_POST['form_status'] )){

	$mileage_use 		= sanitize_text_field( $_POST['mileage_use'] );
	$join_mileage 		= sanitize_text_field( $_POST['join_mileage'] );
	$afternote_mileage 	= sanitize_text_field( $_POST['afternote_mileage'] );
	$min_mileage		= sanitize_text_field( $_POST['min_mileage'] );
	$max_mileage		= sanitize_text_field( $_POST['max_mileage'] );

	$mileage_config = array(
		'mileage_use' 		=> $mileage_use,
		'join_mileage' 		=> $join_mileage,
		'afternote_mileage' => $afternote_mileage,
		'min_mileage'		=> $min_mileage,
		'max_mileage'		=> $max_mileage
	);

	$mileage_config = serialize($mileage_config);

	update_option("mileage_config",$mileage_config);

}

	$mileage_config = get_option("mileage_config");
	$mileage_config = unserialize($mileage_config);

	$mileage_use 		= $mileage_config['mileage_use'];
	$join_mileage 		= $mileage_config['join_mileage'];
	$afternote_mileage 	= $mileage_config['afternote_mileage'];
	$min_mileage 		= $mileage_config['min_mileage'];
	$max_mileage 		= $mileage_config['max_mileage'];

?>
<ul>
	<li><span class="dashboard-content-title">적립금 설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		적립금 설정 유무와 한도, 게시글 작성시 적립금 적립 여부등을 설정 하실 수 있습니다.
		</span>
	</li>
</ul>
<form method="POST">
	<input type="hidden" name="form_status" value="1">
	<table style="padding-top:10px; padding-left:10px">
		<tr>
			<td>적립금 사용유무</td>
			<td><input type="radio" name="mileage_use" value="T">사용 <input type="radio" name="mileage_use" value="F">미사용</td>
		</tr>
		<tr>
			<td colspan="2">미사용 체크시 쇼핑몰 전체에서 적립금 사용이 중지 됩니다.</td>
		</tr>
	</table>
	<table style="padding-top:10px; padding-left:10px">
		<tr>
			<td>회원 가입시 적립금</td>
			<td><input type="text" name="join_mileage" value="<?php echo $join_mileage;?>"></td>
		</tr>
		<tr>
			<td>후기 작성시 적립금</td>
			<td><input type="text" name="afternote_mileage" value="<?php echo $afternote_mileage;?>"></td>
		</tr>
		<tr>
			<td>사용가능 최소적립금</td>
			<td><input type="text" name="min_mileage" value="<?php echo $min_mileage;?>"></td>
		</tr>
		<tr>
			<td>사용가능 최대적립금</td>
			<td><input type="text" name="max_mileage" value="<?php echo $max_mileage;?>"></td>
		</tr>
	</table>
<input type="submit" class="button button-primary" value="확인" style="margin-left:10px">
</form>

<script>
	
	var mileage_use = "<?php echo $mileage_use;?>";

	if(mileage_use){
		if(mileage_use == "T"){
			jQuery("[name=mileage_use]:first").prop("checked", true);
		} else {
			jQuery("[name=mileage_use]:last").prop("checked",true);
		}
	} else {
		jQuery("[name=mileage_use]:first").prop("checked", true);
	}

</script>

