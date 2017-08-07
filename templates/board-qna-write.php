<?php

get_header();

      if ( have_posts() ) :

      	$post_id = $post->ID;

       while ( have_posts() ) : the_post();

		$thumbnail_ids = unserialize(get_post_meta($post_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');

		if (is_user_logged_in()){

			global $current_user;
			get_currentuserinfo();

			$user_id 			= $current_user->ID;
			$user_display_name	= $current_user->display_name;
			$user_email			= $current_user->user_email;
			$user_email = explode("@",$user_email);
			$email = $user_email[0];
			$email_domain = $user_email[1];

		}

?>
<div class="entry-content">
<div class="board-write-product-summary">
	<ul>
		<li><img src="<?php echo $thumbnail_url[0];?>" style="width:100px; height:auto"></li>
		<li style="margin-left:10px; font-weight:bold"><?php echo get_the_title($post_id);?></li>
	</ul>
</div>
	<form method="post" id="board_write_form">
		<table>
			<tr>
				<th>제목</th>
				<td><input type="text" name="title" style="width:90%"></td>
			</tr>
			<tr>
				<th>작성자</th>
				<td><input type="text" name="writer" value="<?php echo $user_display_name;?>"></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td>
					<input type="text" name="email" value="<?php echo $email;?>">@<input type="text" name="email_domain" value="<?php echo $email_domain;?>">
					<select onchange="input_email_to_field(this.value);">
						<option>이메일선택</option>
<?php
	$email_domain = added_email_domain(); // function 에 등록되어 있는 리스트를 불러옴

	for ($i=0; $i < count($email_domain); $i++) { 
		echo "<option>".$email_domain[$i]."</option>";
	}

?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php wp_editor($content, 'board_content', array('textarea_name' => 'board_content'));?>
				</td>
			</tr>
			<tr>
				<th>비밀번호</th>
				<td><input type="password" name="pwd"></td>
			</tr>
<?php
	
	$board_config = get_option("kingkongcart_board_config");
	$board_config = unserialize($board_config);
	$pwd_setup	= $board_config['private'];
	if($pwd_setup == "T"){
?>
			<tr>
				<th>비밀글설정</th>
				<td><input type="radio" name="private" value="F">공개글 <input type="radio" name="private" value="T">비공개글</td>
			</tr>
<?php
	}
?>
		</table>
		<input type="hidden" name="kind" value="qna">
		<input type="hidden" name="type" value="normal">
	</form>
		<div class="board-button">
			<input type="button" class="kingkongtheme_button" value="취소하기" onclick="history.back();">
			<input type="button" class="kingkongtheme_button" value="등록하기" onclick="board_qna_write(<?php echo $post_id;?>);">
		</div>
</div>
<?php			
		endwhile;
      else :
        //echo "no";// If no content, include the "No posts found" template.
      endif;
get_footer();
?>