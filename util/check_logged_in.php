<?php
	if (isset($_SESSION['user_id']) OR isset($_SESSION['name_tag']) OR isset($_SESSION['lock_code'])){
		$user_id = preg_replace('#[^0-9]#i', '', $_SESSION['user_id']);
		$name_tag = preg_replace('#[^A-Za-z0-9_]#i', '', $_SESSION['name_tag']);
		$lock_code = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION['lock_code']);
		
		list($rows, $id) = login($name_tag, $lock_code);
		
		if($rows!= 0 AND $user_id == $id){
			header("location:./my_backpack/");
			exit();
		}else{
			session_unset();
			session_destroy();
			header("location:?action=login");
			exit();
		}
	}else{
		if(isset($_COOKIE['name_tag_cookie']) OR isset($_COOKIE['lock_code_cookie'])){
			$name_tag = preg_replace('#[^A-Za-z0-9_]#i', '', $_COOKIE['name_tag_cookie']);
			$lock_code = preg_replace('#[^A-Za-z0-9]#i', '', $_COOKIE['lock_code_cookie']);
			
			list($rows, $user_id) = login($name_tag, $lock_code);
			
			if($rows!= 0){
				
				$_SESSION['user_id'] = $user_id;
				$_SESSION['name_tag'] = $name_tag;
				$_SESSION['lock_code'] = $lock_code;
				
				header("location:./my_backpack/");
				exit();
			}else{
				setcookie("name_tag_cookie", "");
				setcookie("lock_code_cookie", "");
			
				header("location:?action=login");
				exit();
			}
		}
	}
?>