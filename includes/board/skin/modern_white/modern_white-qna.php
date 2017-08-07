<?php
/*
 Page Name: Modern White Q&A
 Skin URI: http://kingkongcart.com
 Description: 모던 화이트
 Author: Bryan Lee
*/


/*
* - 이미지 경로 잡을 때 -
* KINGKONGCART_BOARD_PATH : 스킨 경로
* 스킨명이 basic 이고 images 폴더에 이미지가 있다면 KINGKONGCART_BOARD_PATH."/basic/images/이미지명"
*/


	global $wpdb, $post;
	$board_table = $wpdb->prefix."kingkong_board";
	$boards = $wpdb->get_results("SELECT * from $board_table where pid = $post->ID and kind = 'qna' and type='normal' order by ID asc");	

?>

<h2 class="modern_white_h2">Q&A</h2>
<table class="board-table">
	<thead>
		<tr>
			<th id="board-label-no">No</th>
			<th id="board-label-title">Title</th>
			<th id="board-label-writer">Writer</th>
			<th id="board-label-date">Date</th>
			<th id="board-label-status">Status</th>
		</tr>
	</thead>
	<tbody>
<?php

	if ($boards){
		$cnt = count($boards);
		foreach ($boards as $board) {

			$board_reply 	= get_board_meta($board->ID, "board_reply");
			$board_info 	= get_board_meta($board->ID, "board_info");
			$board_info		= unserialize($board_info);
			$pwd_setup		= $board_info['pwd_setup'];

			if($board_reply){
				$board_reply_text = "<div style='background:#4dcebc; color:#fff; border-radius:10px'>답변완료</div>";
			} else {
				$board_reply_text = "<div style='background:#cecece; color:#fff; border-radius:10px'>답변대기</div>";
			}

?>
		<tr id="board-title-<?php echo $board->ID;?>">
			<td id="board-label-no"><?php echo $cnt;?></td>
			<td id="board-label-title" style="text-align:left">

<?php

	if($pwd_setup == "T"){
		// 비밀글 일때
		echo "<img src='".KINGKONGCART_BOARD_PATH."/modern_white/images/lock.png' style='width:10px; height:auto; position:relative; top:1px; margin-right:5px'>";
	} else {
		// 일반글 일때
		echo "";
	}
?>
			<a onclick="board_view_content(<?php echo $board->ID;?>);" style="cursor:pointer; cursor:hand"><?php echo $board->title;?></a>

			</td>
			<td id="board-label-writer"><?php echo $board->writer;?></td>
			<td id="board-label-date"><?php echo substr($board->date,0,10);?></td>
			<td id="board-label-status"><?php echo $board_reply_text;?></td>
		</tr>
		<tr id="board-content-<?php echo $board->ID;?>" class="board-content-tr">
			<td colspan="5" style="text-align:left; width:100%">
				<div class="board-content-div" id="board-content-div-<?php echo $board->ID;?>">

<?php
	if($pwd_setup == "T" && !current_user_can('administrator')){
?>
				<form method="post" id="board-pwd-form-<?php echo $board->ID;?>">
					비공개 글입니다. 확인하시려면 하단에 작성시 입력하신 비밀번호를 입력 해 주세요.<br>
				<input type="password" name="pwd"><input type="button" value="확인" onclick="board_pwd_check(<?php echo $board->ID;?>);">
				</form>

<?php
	} else {
		echo $board->content;
		if($board_reply){
?>
				

				<div class="board-content-answer">
					<ul>
						<li><b>관리자 답변</b></li>
						<li><?php echo $board_reply;?></li>
					</ul>
				</div>

				</div>
<?php
		}
	}
?>

<?php 
	if ( current_user_can('administrator') ) {
?>
				<div class="board-content-reply">
					<form method="post" id="board_reply_form_<?php echo $board->ID;?>">
						<table class="reply_table">
							<tr>
								<td style="text-align:center; vertical-align:middle">관리자 답변</td>
								<td><textarea name="reply_content"></textarea></td>
								<td><input type="button" value="답변완료" onclick="board_reply_proc(<?php echo $board->ID;?>);"></td>
							</tr>
						</table>
					</form>
				</div>
<?php
	}
?>
			</td>
		</tr>
<?php
			$cnt--;
		}

	} else {
?>
		<tr>
			<td colspan="5" style="color:gray">등록된 글이 없습니다.</td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<div class="board-button">
<form method="post">
<input type="hidden" name="status_type" value="qna">
<input type="submit" class="kingkongtheme_button" value="문의글 작성">
</form>
</div>
