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

	$board_config = unserialize(get_option("kingkongcart_board_config"));
	$vote_setup	= $board_config['vote'];
	$line_setup = $board_config['line'];

	$upload_dir 		= wp_upload_dir();
	$artDir 			= $upload_dir['baseurl']."/kingkongcart/";

	$board_table = $wpdb->prefix."kingkong_board";

	$page = sanitize_text_field( $_GET['page'] );

	if(!$page){
		$page = 1;
	}

	$line_end = $page * $line_setup;
	$line_start	= $line_end - $line_setup;

	// 전체 row 개수 //
	$board_count = $wpdb->get_var("SELECT COUNT(*) from $board_table where pid = $post->ID and kind = 'afternote' and type='normal' order by ID asc");




	$boards = $wpdb->get_results("SELECT * from $board_table where pid = $post->ID and kind = 'afternote' and type='normal' order by ID asc limit $line_start, $line_end");	


?>

<h2 class="modern_white_h2">Review</h2>
<table class="board-table">
	<thead>
		<tr>
			<th id="board-label-no">No</th>
			<th id="board-label-title">Title</th>
			<th id="board-label-writer">Writer</th>
			<th id="board-label-date">Date</th>
<?php 
	if($vote_setup == "T"){
?>
			<th id="board-label-vote">Vote</th>
<?php
	}
?>
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
			$thumbnail		= $board_info['thumb'];

			switch($board_info['vote']){
				case 1 :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					";
				break;

				case 2 :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png''>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					";
				break;

				case 3 :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					";
				break;

				case 4 :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					";
				break;

				case 5 :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-one.png'>
					";
				break;

				default :
					$vote = "
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					<img src='".KINGKONGCART_BOARD_PATH."modern_white/images/star-none.png'>
					";
				break;
			}

?>
		<tr id="board-title-<?php echo $board->ID;?>">
			<td id="board-label-no"><?php echo $cnt;?></td>
			<td style="text-align:left" id="board-label-title">

<?php

	if($thumbnail != "F"){

		$thumb_img = "<img src='".$artDir."board/".$thumbnail."' style='width:50px; height:50; position:relative; top:1px; margin-right:5px'>";
?>

				<ul class="board-content-title-area">
					<li class="thumb"><?php echo $thumb_img;?></li>
					<li class="title"><a onclick="board_view_content(<?php echo $board->ID;?>);" style="cursor:pointer; cursor:hand"><?php echo $board->title;?></a></li>
				</ul>

<?php
	} else {
		// 일반글 일때
		$thumb_img = "";
?>

				<ul class="board-content-title-area">
					<li><a onclick="board_view_content(<?php echo $board->ID;?>);" style="cursor:pointer; cursor:hand"><?php echo $board->title;?></a></li>
				</ul>

<?php
	}
?>

				

			</td>
			<td id="board-label-writer"><?php echo $board->writer;?></td>
			<td id="board-label-date"><?php echo substr($board->date,0,10);?></td>
<?php 
	if($vote_setup == "T"){
?>
			<td class="vote_display" id="board-label-vote"><?php echo $vote;?></td>
<?php
	}
?>
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

		if($thumbnail != "F"){
		echo "<img src='".$artDir."board/".$thumbnail."' class='board-insert-image'><br>";
		}
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
<input type="hidden" name="status_type" value="afternote">
<input type="submit" class="kingkongtheme_button" value="후기글 작성">
</form>
</div>
