<?php

	class Cookie {
		public static function put($name, $hash, $expiry){
			if(setcookie($name, $hash, time() + $expiry, '/')){
				return true;
			}
			
			return false;
		}
		
		public static function delete($name){
			self::put($name, '', time() - 1);
		}
		
		public static function exists($name){
			return (isset($_COOKIE[$name])) ? true : false;
		}
		
		public static function get($name){
			return $_COOKIE[$name];
		}
	}

?>