<?php

if(sanitize_text_field( $_POST['form_status'] )){

	$afternote  = sanitize_text_field( $_POST['afternote'] ); 	// 이용후기 사용여부
	$qna		= sanitize_text_field( $_POST['qna'] );		// 상품문의 사용여부
	$vote		= sanitize_text_field( $_POST['vote'] );		// 평점기능 사용여부
	$private	= sanitize_text_field( $_POST['private'] );	// 비밀글 사용여부
	$line		= sanitize_text_field( $_POST['line'] );		// 페이지 표시 라인 수
	$skin 		= sanitize_text_field( $_POST['skin'] );		// 게시판 스킨

	$board_config = array(
		'afternote' => $afternote,
		'qna'		=> $qna,
		'vote'		=> $vote,
		'private'	=> $private,
		'line'		=> $line,
		'skin'		=> $skin
	);

	$board_config = serialize($board_config);

	update_option("kingkongcart_board_config",$board_config);
}

	$board_config = unserialize(get_option("kingkongcart_board_config"));
	$current_skin = $board_config['skin'];


	$plugins_url = KINGKONGCART_PLUGINS_URL;
	$plugins_url = explode("plugins",$plugins_url);
	$plugins_url[1] = str_replace("/", "", $plugins_url[1]);
	$absolute_url = str_replace(site_url('/'), "", $plugins_url[0]);
	$dir = ABSPATH.$absolute_url."plugins/".$plugins_url[1]."/includes/board/skin";

	$skins = scandir($dir, 1);

	for ($i=0; $i < count($skins); $i++) { 
	 	if($skins[$i] != "." and $skins[$i] != ".." and strpos($skins[$i],".") === false ){
	 		if($skins[$i] == $current_skin){
	 			$skin_option .= "<option selected>".$skins[$i]."</option>";
	 		}
	 		else {
	 			$skin_option .= "<option>".$skins[$i]."</option>";
	 		}
	 	}
	 }

?>
<ul>
	<li><span class="dashboard-content-title">상품 게시판 설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		상문 문의 / 이용후기 게시판관련 설정 페이지 입니다.<br>
		게시판 숏코드를 원하는 페이지나 포스트에 붙여넣기 하시면 해당 페이지의 게시판이 생성됩니다.
		</span>
	</li>
</ul>
<form method="POST">
	<input type="hidden" name="form_status" value="1">
	<table style="padding-top:10px; padding-left:10px">
		<tr>
			<td>스킨설정</td>
			<td><select name="skin"><?php echo $skin_option;?></select></td>
		</tr>
		<tr style="height:40px">
			<td>이용후기 사용여부</td>
			<td><input type="radio" name="afternote" value="T">사용 <input type="radio" name="afternote" value="F">미사용</td>
		</tr>
		<tr style="height:40px">
			<td>상품문의 사용여부</td>
			<td><input type="radio" name="qna" value="T">사용 <input type="radio" name="qna" value="F">미사용</td>
		</tr>
		<tr style="height:40px">
			<td>평점기능 사용여부</td>
			<td><input type="radio" name="vote" value="T">사용 <input type="radio" name="vote" value="F">미사용</td>
		</tr>
		<tr style="height:40px">
			<td>비밀글 사용여부</td>
			<td><input type="radio" name="private" value="T">사용 <input type="radio" name="private" value="F">미사용</td>
		</tr>
		<tr style="height:40px">
			<td>표시할 게시글 수</td>
			<td><input type="text" name="line" value="<?php echo $board_config['line'];?>">개</td>
		</tr>

		<tr>
			<td>이용후기 게시판 숏코드</td>
			<td>[kingkongcart_afternote]</td>
		</tr>

		<tr>
			<td>상품문의 게시판 숏코드</td>
			<td>[kingkongcart_qna]</td>
		</tr>

	</table>
	<input type="submit" class="button button-primary" value="확인" style="margin-left:10px">
</form>

<script>

var afternote 	= "<?php echo $board_config['afternote'];?>";
var qna			= "<?php echo $board_config['qna'];?>";
var vote		= "<?php echo $board_config['vote'];?>";
var security	= "<?php echo $board_config['private'];?>";

switch(afternote){
	case "T" :
		jQuery("[name=afternote]:first").prop("checked", true);
	break;

	case "F" :
		jQuery("[name=afternote]:last").prop("checked", true);
	break;

	default :
		jQuery("[name=afternote]:first").prop("checked", true);
	break;
}

switch(qna){
	case "T" :
		jQuery("[name=qna]:first").prop("checked", true);
	break;

	case "F" :
		jQuery("[name=qna]:last").prop("checked", true);
	break;

	default :
		jQuery("[name=qna]:first").prop("checked", true);
	break;
}

switch(vote){
	case "T" :
		jQuery("[name=vote]:first").prop("checked", true);
	break;

	case "F" :
		jQuery("[name=vote]:last").prop("checked", true);
	break;

	default :
		jQuery("[name=vote]:first").prop("checked", true);
	break;
}

switch(security){
	case "T" :
		jQuery("[name=private]:first").prop("checked", true);
	break;

	case "F" :
		jQuery("[name=private]:last").prop("checked", true);
	break;

	default :
		jQuery("[name=private]:first").prop("checked", true);
	break;
}
</script>
