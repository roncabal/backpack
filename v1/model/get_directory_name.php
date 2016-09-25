<?php
	function getDirectory($name_tag, $lock_code){
		$db = new database_connection();
		$db->connect();
		$details = array();
		$crypt_lock_code = Crypt($lock_code, 'bp');
		$crypt_name_tag = Crypt($name_tag, 'bp');
		$crypt_lock_code = Crypt($crypt_lock_code . $crypt_name_tag, 'pb');
		$query = "SELECT * FROM registered_user WHERE name_tag like binary '$name_tag' AND lock_code like binary '$crypt_lock_code' LIMIT 1";
		try{
			$my_query = $db->db->query($query);
			$rows = $my_query->fetch();
			$details['first_name'] = $rows['first_name'];
			$details['last_name'] = $rows['last_name'];
			
			$name = $details['first_name'] . $details['last_name'];
		
			$my_dir_name = md5($name_tag) . Crypt($name, 'fd');
			
			$my_dir_name = str_replace("/", "slsh", $my_dir_name);
			$my_dir_name = str_replace(".","prd",$my_dir_name);
			
		}catch(PDOException $e){
			$error_message = $e->getMessage();
			echo $error_message;
		}
		
		return $my_dir_name;
	}
	
	function getDirectoryGenerate($name_tag, $first_name, $last_name){
		$name = $first_name . $last_name;
		$my_dir_name = md5($name_tag) . Crypt($name, 'fd');
		$my_dir_name = str_replace("/", "slsh", $my_dir_name);
		$my_dir_name = str_replace(".","prd",$my_dir_name);
		return $my_dir_name;
	}
?>