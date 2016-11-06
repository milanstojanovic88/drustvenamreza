<aside>
<?php 
	$user = new User();
	if($user->isLoggedIn()){
		include_once 'widgets/logged_in.php';
	} else {
		include_once 'widgets/login.php';
	}
	
?>
	
	<hr width="80%"></hr>
</aside>