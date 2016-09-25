<?php
	class database_connection{
		protected $dsn = 'mysql:host=localhost;dbname=my_backpack_database';
		protected $name = 'root';
		protected $password = '';
		protected $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		public $db = null;
		
		public function connect(){
			try{
				$this->db = new PDO($this->dsn,$this->name,$this->password,$this->option);
			}catch(PDOException $e){
				$error_message = $e->getMessage();
				echo $error_message;
			}
		}

	}
?>