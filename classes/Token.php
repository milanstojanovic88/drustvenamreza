<?php

	class Token {
		public static function generate($name){
			return Session::put($name, md5(uniqid()));
		}
		
		public static function check($token){
			
			if(Session::exists('reg_token') && $token === Session::get('reg_token')){
				Session::delete('reg_token');
				return true;
			}
			
			if(Session::exists('login_token') && $token === Session::get('login_token')){
				Session::delete('login_token');
				return true;
			}
			
			if(Session::exists('settings_token') && $token === Session::get('settings_token')){
				Session::delete('settings_token');
				return true;
			}
			
			return false;
		}
	}

?>