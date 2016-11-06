<?php

	function loggedin_protection(){
		$user = new User();
		
		if($user->isLoggedIn() === true){
			Redirect::to('index.php');
		}
	}
	
	function loggedout_protection(){
		$user = new User();
		
		if($user->isLoggedIn() === false){
			Redirect::to('index.php');
		}
	}

?>