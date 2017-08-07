<?php
function kkcart_board(){

	if(sanitize_text_field( $_GET['type'] ) == "view"){

		include_once "board-manage-view.php";

	} else {
?>

<h2><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:30px; height:auto"><span style="position:relative; top:-5px; left:10px">문의관리</span></h2>


<?php
	global $wpdb;
	$board_table = $wpdb->prefix."kingkong_board";

		$boards = $wpdb->get_results("SELECT * from $board_table where user > '0'");
?>
<div id="kkcart-admin-order-content" style="margin-top:10px">
	<table>
		<thead>
			<tr>
				<th>썸네일</th>
				<th>상품명</th>
				<th>제목</th>
				<th>작성자명</th>
				<th>아이디</th>
				<th>작성일</th>
				<th>답변여부</th>
				<th>적립금</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach($boards as $board){
		$thumbnail_ids = unserialize(get_post_meta($board->pid,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
		$user_info = get_userdata($board->user);
		$board_reply = get_board_meta($board->ID, "board_reply");
?>
			<tr>
				<td><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></td>
				<td><?php echo get_the_title($board->pid);?></td>
				<td><a href="admin.php?page=kkcart_board&type=view&id=<?php echo $board->ID;?>"><?php echo $board->title;?></a></td>
				<td><?php echo $board->writer;?></td>
				<td><?php echo $user_info->user_login;?></td>
				<td><?php echo $board->date;?></td>
				<td>
<?php
	if($board_reply){
		echo "답변완료";
	} else {
?>
					<a href="admin.php?page=kkcart_board&type=view&id=<?php echo $board->ID;?>">답변하기</a>
<?php
	}
?>
				</td>
				<td><input type="button" class="button button-primary" value="적립금주기"></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div>
<?php
	}

}

?>