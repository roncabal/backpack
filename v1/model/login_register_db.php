<?php
	function login($name_tag, $lock_code){
		$db = new database_connection();
		$db->connect();
		$login = array();
		$crypt_lock_code = Crypt($lock_code, 'bp');
		$crypt_name_tag = Crypt($name_tag, 'bp');
		$crypt_lock_code = Crypt($crypt_lock_code . $crypt_name_tag, 'pb');
		$login_query = "SELECT user_id, name_tag, lock_code FROM registered_user WHERE name_tag like binary '$name_tag' AND lock_code like binary '$crypt_lock_code' LIMIT 1";
		try{
			$my_query = $db->db->query($login_query);
			$login['count'] = $my_query->rowCount();
			$rows = $my_query->fetch();
			$login['user_id'] = $rows['user_id'];

		}catch(PDOException $e){
			$error_message = $e->getMessage();
			echo $error_message;
		}
		
		return array($login['count'], $login['user_id']);
	}

	function checkNameTag($name_tag){
		$db = new database_connection();
		$db->connect();
		$query = "SELECT name_tag FROM registered_user WHERE name_tag = '$name_tag' LIMIT 1";
		try{
			$my_query = $db->db->query($query);
			$rows = $my_query->rowCount();
		}catch(PDOException $e){
			$error_message = $e->getMessage();
			echo $error_message;
		}
	
		return $rows;
	}
	
	function register($name_tag, $first_name, $last_name, $email_add, $lock_code, $accept_terms){
		$db = new database_connection();
		$db->connect();
		
		$crypt_lock_code = Crypt($lock_code, 'bp');
		$crypt_name_tag = Crypt($name_tag, 'bp');
		$crypt_lock_code = Crypt($crypt_lock_code . $crypt_name_tag, 'pb');
		$first_name = ucwords($first_name);
		$last_name = ucwords($last_name);
		
		$name = $first_name . $last_name;
		
		$my_dir_name = md5($name_tag) . Crypt($name, 'fd');
		
		$my_dir_name = str_replace("/", "slsh", $my_dir_name);
		$my_dir_name = str_replace(".","prd",$my_dir_name);
		
		mkdir("user_folders/". $my_dir_name);
		mkdir("user_folders/". $my_dir_name . "/main");
		mkdir("user_folders/". $my_dir_name . "/bottom");
		mkdir("user_folders/". $my_dir_name . "/notes");
		mkdir("user_folders/". $my_dir_name . "/trash");
		
		$query = $db->db->prepare("INSERT INTO registered_user(name_tag, first_name, last_name, email, lock_code, bag_size) VALUES ('$name_tag', '$first_name', '$last_name', '$email_add', '$crypt_lock_code', 2147483648)");
		$query2 = $db->db->prepare("CREATE TABLE IF NOT EXISTS `$my_dir_name` (`file_id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`file_id`), `file_name` VARCHAR(255) NOT NULL, `file_size` VARCHAR(10) NOT NULL, `file_type` VARCHAR(10) NOT NULL, `file_dir` VARCHAR(255), `order_number` INT NOT NULL, `sort_type` VARCHAR(12) NOT NULL, `date_uploaded` DATE NOT NULL, `date_modified` DATE) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		try{
			$query->execute();
			$query2->execute();
		}catch(PDOException $e){
			$error_message = $e->getMessage();
			echo $error_message;
		}
	}
?>