<?php
/*
Template Name: Login Page
*/

	if(!is_user_logged_in()){
?>
<?php get_header(); ?>
<?php 
	if(!$no_entry_content){
?>
<div class="entry-content" style="text-align:center">
<?php
	} else {
?>
	<div style="text-align:center">
<?php
	}
?>
<h2><?php //the_title(); ?></h2>

<form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
	<p>
		<label>아이디<br />
		<input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>

		<label>비밀번호<br />
		<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> 기억하기</label></p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php echo get_option('home'); ?>/wp-admin/" />

		<input type="hidden" name="testcookie" value="1" />
	</p>
</form>
<p id="nav">
<a href="<?php echo wp_login_url(); ?>?action=lostpassword" title="Password Lost and Found">비밀번호 찾기</a>
</p>
<?php 
	if($no_entry_content){
?>
	<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
		<input type="hidden" name="type" value="guest">
		<input type="submit" value="비회원 주문">
		<input type="button" value="회원가입" onclick="location.href='<?php echo get_the_permalink(KINGKONG_JOIN);?>';">
	</form>
<?php
	}
?>
</div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>

<?php
	}
?>