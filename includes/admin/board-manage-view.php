<?php

	$id = sanitize_text_field( $_GET['id'] );

	global $wpdb;
	$board_table = $wpdb->prefix."kingkong_board";

	$board = $wpdb->get_row("SELECT * from $board_table where ID = '".$id."' ");

	$thumbnail_ids = unserialize(get_post_meta($board->pid,"kingkongcart_added_thumbnail_id", true));
	$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
	$user_info = get_userdata($board->user);
	$board_reply = get_board_meta($board->ID, "board_reply");

	switch($board->kind){
		case "qna" :
			$result = "문의게시판";
		break;

		case "afternote" :
			$result = "이용후기";
		break;
	}

?>

<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">문의글 상세보기</span></h2>

<div id="kkcart-admin-order-content" style="margin-top:10px">
	<table>
		<tr>
			<td>상품 정보</td>
			<td colspan="3">
				<table>
					<tr>
						<td><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
						<td><?php echo get_the_title($board->pid);?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>구분</td>
			<td><?php echo $result;?></td>
			<td>작성일시</td>
			<td><?php echo $board->date;?></td>
		</tr>
		<tr>
			<td>작성자 아이디</td>
			<td><?php echo $user_info->user_login;?></td>
			<td>작성이름</td>
			<td><?php echo $board->writer;?></td>
		</tr>
		<tr>
			<td>제목</td>
			<td colspan="3"><?php echo $board->title;?></td>
		</tr>
		<tr>
			<td>내용</td>
			<td colspan="3"><?php echo $board->content;?></td>
		</tr>
		<tr>
			<td>답변</td>
			<td colspan="3" style="padding:10px 10px">
				<textarea style="width:100%; max-width:800px; height:100px; background:#f4f4f4" name="reply"><?php echo $board_reply;?></textarea>
				<input type="button" class="button button-primary" value="답변완료/수정" onclick="admin_board_reply(<?php echo $id;?>);">
			</td>
		</tr>
	</table>
	<input type="button" class="button" value="확인" onclick="history.back();"> <input type="button" class="button" value="삭제" onclick="remove_board_content(<?php echo $id;?>);">
</div>