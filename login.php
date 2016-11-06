<?php

	include_once 'core/init.php';
	
	
	if(Input::exists('post')){
		if(Token::check(Input::get('login_token'))){
			
			$remember = (Input::get('remember') === 'on') ? true : false;
			
			$user = new User();
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login === true){
				Redirect::to('index.php');
			} else {
				include_once 'includes/overalls/header.php';
				
?>
				<h3>There's been a problem...</h3>
				<div class="alert alert-danger" role="alert">
				<?php
				foreach($user->errors() as $error){
					echo $error . '<br/>';
				}
				?>
				</div>
				<?php
				include_once 'includes/overalls/footer.php';
			}
		}
	}
?>