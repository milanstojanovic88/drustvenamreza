<?php

	class Validate {
		private $_db = null, $_passed = false, $_errors = array();
		
		public function __construct(){
			$this->_db = DB::getInstance();
		}
		
		public function check($source, $items = array()){
			foreach($items as $item => $rules){
				$name = $rules['name'];
				$value = trim($source[$item]);
				if(strpos($item, 'password') === false && strpos($item, 'mail') === false && !preg_match("/^[a-zA-Z\p{Latin}0-9\s]*$/", $value)){
					$this->addError("Only alphanumeric characters are allowed for {$name}!");
				}
				
				if($item == 'email' && !empty($source[$item]) && filter_var($value, FILTER_VALIDATE_EMAIL) === false){
					$this->addError('Invalid email!');
				}
				foreach($rules as $rule => $rule_value){
					
					if($rule === 'required' && empty($value)){
						$this->addError("{$name} is required.");
					} else if(!empty($value)){
						switch($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this->addError("{$name} must be at least {$rule_value} characters!");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$name} must be at most {$rule_value} characters!");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								$rule_value = ucfirst($rule_value);
								$this->addError("{$rule_value}s don't match!");
							}
						break;
						case 'unique':
							$user = new User();
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->counting() && !$user->isLoggedIn()){
								$this->addError("{$name} already exists!");
							} else if($check->counting() && $user->data()->$item !== $value && $user->isLoggedIn()) {
								$this->addError("{$name} already exists!");
							}
						break;
						}
					}
				}
			}
			
			if(empty($this->_errors)){
				$this->_passed = true;
			}
			
			return $this;
		}
		
		public function passed(){
			return $this->_passed;
		}
		
		public function errors(){
			return $this->_errors;
		}
		
		public function addError($error){
			$this->_errors[] = $error;
		}
	}
	
?>