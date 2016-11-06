<?php
//phpinfo(); die();
	session_start();
	
	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => 'localhost',
			'username' => '1253973',
			'password' => 'Strike12',
			'db' => '1253973'
		),
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800
		),
		'session' => array(
			'session_name' => 'user'
		)
	);

	spl_autoload_register(function($class){
		include_once 'classes/' . $class . '.php';
	});

//	include_once 'classes/Config.php';
//	include_once 'classes/Cookie.php';
//	include_once 'classes/DB.php';
//	include_once 'classes/Hash.php';
//	include_once 'classes/Input.php';
//	include_once 'classes/Redirect.php';
//	include_once 'classes/Session.php';
//	include_once 'classes/Token.php';
//	include_once 'classes/User.php';
//	include_once 'classes/Validate.php';
//
//	include_once 'functions/sanitize.php';
//	include_once 'functions/page_protect.php';

	if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

		if($hashCheck->counting()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}

?>