<?php

	class DB {
		private static $_instance;
		private $_pdo, $_query, $_error = false, $_results, $_count;
		
		private function __construct (){
			try{
				$this->_pdo = new PDO('mysql:host='. Config::get('mysql/host') .'; dbname='. Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
			} catch(PDOException $e) {
				echo "Problem connecting to database" . $e->getMessage();
			}
		}
		
		public static function getInstance(){
			if(!isset(self::$_instance)){
				self::$_instance = new DB();
			}
			return self::$_instance;
		}
		
		public function query($sql, $params = array()){
			$this->_error = false;
			
			if($this->_query = $this->_pdo->prepare($sql)){
				if(count($params)){
					foreach($params as $placeholder => $param){
						$placeholder = ':' . $placeholder;
						$this->_query->bindValue($placeholder, $param);
					}
				}
			
				if($this->_query->execute()){
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count = $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}
			return $this;
		}
		
		public function action($action, $table, $where = array()){
			$operators = array('=', '>', '<', '<=', '=>', 'LIKE');
			
			if(count($where) === 3){
				
				$field = $where[0];
				$operator = $where[1];
				$placeholder = ':' . $field;
				$value = $where[2];
				
				if(in_array($operator, $operators)){
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} {$placeholder}";
					
					if(!$this->query($sql, array($field => $value))->error()){
						return $this;
					}
				}
			}
			
			return false;
		}
		
		public function update($table, $id, $fields){
			
			$set = '';
			$x = 1;
			
			foreach($fields as $key => $value){
				$set .= "{$key} = :{$key}";
				if($x < count($fields)){
					$set .= ', ';
				}
				$x++;
			}
			
			
			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
			
			return false;
		}
		
		public function insert($table, $fields = array()){
			$keys = array_keys($fields);
			
			$values = '';
			$x = 1;
			
			foreach($fields as $placeholder => $field){
				$values .= ':' . $placeholder;
				if($x < count($fields)){
					$values .= ', ';
				}
				$x++;
			}
			
			$sql = "INSERT INTO {$table} (". implode(', ', $keys) .") VALUES ({$values})";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
			
			return false;
		}
		
		
		
		public function delete($table, $where){
			return $this->action('DELETE', $table, $where);
		}
		
		public function get($table, $where){
			return $this->action('SELECT *', $table, $where);
		}
		
		public function results(){
			return $this->_results;
		}
		
		public function first(){
			return $this->results()[0];
		}
		
		public function counting(){
			return $this->_count;
		}
		
		public function error(){
			return $this->_error;
		}
	}

?>