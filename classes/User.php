<?php

	class User {
		private $_isLoggedIn = false, $_db, $_sessionName, $_cookieName, $_data, $_errors = array();
		
		public function __construct($user = null){
			$this->_db = DB::getInstance();
			$this->_sessionName = Config::get('session/session_name');
			$this->_cookieName = Config::get('remember/cookie_name');
			
			if(!$user){
				if(Session::exists($this->_sessionName)){
					
					$user = Session::get($this->_sessionName);
					
					if($this->find($user)){
						$this->_isLoggedIn = true;
					} else {
						$this->logout();
					}
				}
			} else {
				$this->find($user);
			}
		}
		
		public function find($user = null){
			if($user){
				
				$field = '';
				switch($user){
					case is_numeric($user):
						$field = 'id';
					break;
					case filter_var($user, FILTER_VALIDATE_EMAIL):
						$field = 'email';
					break;
					default:
						$field = 'username';
					break;
				}
				
				$data = $this->_db->get('users', array($field, '=', $user));
				
				if($data->counting() === 1){
					$this->_data = $data->first();
					return true;
				}
			}
			
			return false;
		}
		
		public function login($username = null, $password = null, $remember = false){
			
			if(!$username && !$password && $this->exists()){
				Session::put($this->_sessionName, $this->data()->id);
			} else {
				$user = $this->find($username);
				
				if($user === false){
					$this->addError('User can not be found!');
					return false;
				} else if($this->data()->password !== Hash::make($password, $this->data()->salt)){
					$this->addError('Incorrect password!');
					return false;
				} else if($this->data()->activated === '0'){
					$this->addError('Account not activated! Please check your mail to activate your account.');
					return false;
				} else {
					Session::put($this->_sessionName, $this->data()->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
						
						if(!$hashCheck->counting()){
							$this->_db->insert('users_session',array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}
						
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
						
					}
					return true;
				}
			}
			return false;
		}
		
		public function profile_image($file_temp, $file_extn){
			
			$file_dir = "images/profile images/{$this->data()->id}/";
			
			if(!file_exists($file_dir)){
				mkdir($file_dir);
			}
			
			$file_path = $file_dir . substr(md5(time()),0 , 10) . '.' . $file_extn;
			
			if(!move_uploaded_file($file_temp, $file_path)){
				throw new Exception('Couldn\'t upload the image... Try again later.');
			}
			
			$this->update(array(
				'profile_picture_path' => $file_path
			));
			
		}
		
		public function update($fields = array(), $id = null){
			if(!$id && $this->isLoggedIn()){
				$id = $this->data()->id;
			}
			
			if(!$this->_db->update('users', $id, $fields)){
				throw new Exception('Could not update your user data... Please try again later.');
			}
		}
		
		public function create($data = array()){
			if(!$this->_db->insert('users', $data)){
				throw new Exception('Problem creating user... Try again later.');
			}
			
			mail($data['email'], 'Activate your account.', 'Hello, ' . $data['name'] . 'Your activation code is: http://milantest2.freetzi.com/activate.php?email='. $data['email'] .'&email_code='. $data['email_code'] .' Your MLG admin!', 'admin@mlg.com');
		}
		
		public function logout(){
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
			
			$this->_db->delete('users_session', array('user_id', '=', $this->data->id));
		}
		
		public function activate($mail, $code){
			$user = $this->find($mail);
			
			if(!$user){
				$this->addError('User doesn\'t exists!');
				return false;
			} else if($this->data()->email === $mail && $this->data()->email_code === $code){
				if(!$this->_db->update('users', $this->data()->id, array('activated' => '1'))){
					throw new Exception('Problem activating your account... Try again later.');
				}
				return true;
			} else {
				$this->addError('Activation code isn\'t valid or it has been expired!');
			}
			
			return false;
		}
		
		public function data(){
			return $this->_data;
		}
		
		public function exists(){
			return (!empty($this->_data)) ? true : false;
		}
		
		public function addError($error){
			$this->_errors[] = $error;
		}
		
		public function errors(){
			return $this->_errors;
		}
		
		public function isLoggedIn(){
			return $this->_isLoggedIn;
		}
	}

?>