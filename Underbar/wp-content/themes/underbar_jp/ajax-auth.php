<?php
/**
 * Ajax auth template
 *
 */
?>	

<!-- Login Form -->
<form id="login" class="ajax-auth" action="login" method="post">
	<header>
		<h1>アンダバーログイン</h1>
	    <h3>アカウントを持っていませんか？<br /><a id="pop_signup" href="">新規特派員登録</a></h3>
	    <p class="status"></p> 
    </header> 
    <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>  
    <input id="username" type="text" class="required" name="username" placeholder="Username">
    <input id="password" type="password" class="required" name="password" placeholder="Password">
    <input class="submit_button" type="submit" value="Login">
    <a class="text-link" href="<?php
echo wp_lostpassword_url(); ?>">パスワードを忘れた</a>
	<a class="close" href=""><i class="fa fa-times-circle" aria-hidden="true"></i></a>    
</form>
<!-- /Login Form -->

<!-- Sign Up Form -->
<form id="register" class="ajax-auth"  action="register" method="post">
	<header>
		<h1>バーテンダーに成る</h1>
		<h3>すでにアカウントをお持ちですか？<br /><a id="pop_login"  href="">ログイン</a></h3>
	    <p class="status"></p>
    </header>
    <?php wp_nonce_field('ajax-register-nonce', 'signonsecurity'); ?>         
    <input id="signonname" type="text" name="signonname" class="required" placeholder="Username">
    <input id="email" type="text" class="required email" name="email" placeholder="Email">
    <input id="signonpassword" type="password" class="required" name="signonpassword" placeholder="Password">
    <input type="password" id="password2" class="required" name="password2" placeholder="Confirm password">
    <input class="submit_button" type="submit" value="Sign Up">
    <a class="text-link" href="#">プライバシーポリシー</a>
    <a class="close" href=""><i class="fa fa-times-circle" aria-hidden="true"></i></a>    
</form>
<!-- /Sign Up Form -->