<?php

get_header();


/*
*  서브밋 하였을 경우 내용을 등록시킨다.
*/

      if ( have_posts() ) :

      	$post_id = $post->ID;

       while ( have_posts() ) : the_post();


if (sanitize_text_field( $_POST['kind'] )) {

	global $wpdb;

	$kind 				= sanitize_text_field( $_POST['kind'] );
	$type 				= sanitize_text_field( $_POST['type'] );
	$title 				= sanitize_text_field( $_POST['title'] );
	$writer 			= sanitize_text_field( $_POST['writer'] );
	$email 				= sanitize_text_field( $_POST['email'] )."@".sanitize_text_field( $_POST['email_domain'] );
	$vote 				= sanitize_text_field( $_POST['vote_point'] );
	$thumbnail_tmp 		= $_FILES['thumbnail']['tmp_name'];
	$thumbnail_real 	= $_FILES['thumbnail']['name'];
	$content 			= wpautop(sanitize_text_field( $_POST['board_content'] ));
	$password 			= sanitize_text_field( $_POST['pwd'] );
	$date 				= date("Y-m-d h:i:s");							// 작성일
	$content 			= stripslashes_deep( $content );
	$board_table 		= $wpdb->prefix . "kingkong_board";
	$board_meta_table 	= $wpdb->prefix . "kingkong_board_meta";


		$wpdb->insert( 
			$board_table, 
			array( 
				'pid' 			=> $post_id,
				'kind'			=> $kind,
				'type'			=> $type,
				'title'			=> $title,
				'content'		=> $content,
				'writer'		=> $writer,
				'user'			=> $current_user->ID,
				'date'			=> $date
			));

		$board_id = $wpdb->insert_id;


	$upload_dir 		= wp_upload_dir();
	$artDir 			= $upload_dir['baseurl']."/kingkongcart/board/";
	$artDir				= str_replace(home_url(),"",$artDir);

	if(!file_exists(ABSPATH.$artDir)) {
	    mkdir(ABSPATH.$artDir);
	}
		$extpop = explode(".", $thumbnail_real);
		$ext = array_pop($extpop);

		$new_filename = "kingkongcart-board-thumbnail-".$board_id.".".$ext; // 썸네일 파일 네임 재정의

	if (@fclose(@fopen($thumbnail_tmp, "r"))) { // 파일이 실제 존재하는지 확인

	    copy($thumbnail_tmp, ABSPATH.$artDir.$new_filename);

	    $siteurl = get_option('siteurl');
	    $file_info = getimagesize(ABSPATH.$artDir.$new_filename);
	}

		if($file_info){
			$thumb_status = $new_filename;
		} else {
			$thumb_status = "F";
		}


		$board_info = array(
			'email' 	=> $email,
			'password' 	=> $password,
			'pwd_setup' => 'F',
			'vote'		=> $vote,
			'thumb'		=> $thumb_status	
		);

		$board_info = serialize($board_info);
		
		$wpdb->insert( 
			$board_meta_table, 
			array( 
				'order_id' 		=> $board_id,
				'meta_key'		=> 'board_info',
				'meta_value'	=> $board_info
		));



	echo "<script>alert('등록되었습니다.'); location.href='".get_the_permalink($post_id)."';</script>";


}



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
		<li><img src="<?php echo $thumbnail_url[0];?>" style="width:50px; height:auto"></li>
		<li style="margin-left:10px; font-weight:bold">
			<?php echo get_the_title($post_id);?>
		</li>
	</ul>
</div>
	<form method="post" id="board_write_form" enctype="multipart/form-data">
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
				<th>평점</th>
				<td>
					<div class="vote-star">
						<img src="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/star-none.png" class="votestar-1">
						<img src="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/star-none.png" class="votestar-2">
						<img src="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/star-none.png" class="votestar-3">
						<img src="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/star-none.png" class="votestar-4">
						<img src="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/star-none.png" class="votestar-5">
					</div>
					<input type="hidden" name="vote_point" id="vote-point">
					<input type="hidden" id="vote-path" value="<?php echo KINGKONGCART_BOARD_PATH;?>modern_white/images/">
				</td>
			</tr>
			<tr>
				<th>인증샷</th>
				<td><input type="file" name="thumbnail"></td>
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
		</table>
		<input type="hidden" name="kind" value="afternote">
		<input type="hidden" name="type" value="normal">
		<input type="hidden" name="status_type" value="afternote">
		<div class="board-button">
			<input type="button" class="kingkongtheme_button" value="취소하기" onclick="history.back();">
			<input type="button" name="afternote_submit"  class="kingkongtheme_button" value="등록하기" onclick="board_afternote_write(<?php echo $post_id;?>);">
		</div>
		</form>
	</div>
<?php			
		endwhile;
      else :
        //echo "no";// If no content, include the "No posts found" template.
      endif;
get_footer();
?>