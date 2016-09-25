<?php

if(isset($_POST['action']) && $_POST['action'] != ""){
	$action = $_POST['action'];

	require_once('model/database_connection.class.php');
	require('model/register_validation.php');
	
	global $name_tag_error, $first_name_error, $last_name_error, $email_add_error, $lock_code_error, $accept_terms_error, $name_tag, $first_name, $last_name, $email_add, $lock_code, $accept_terms, $name_tag_check, $first_name_check, $last_name_check, $email_add_check, $lock_code_check, $accept_terms_check;
	global $name_tag_message, $first_name_message, $last_name_message, $email_add_message, $lock_code_message, $accept_terms_message;
	
	$db = new database_connection();
	$db->connect();
	
	$action = strtolower($action);
	$response = array("action" => $action, "success" => 0, "error" => 0);
	
	switch($action){
		case 'login':
			$name_tag = $_POST['name_tag'];
			$lock_code = $_POST['lock_code'];
			
			$login = $db->login($name_tag, $lock_code);
			if($login != false){
				$response["success"] = 1;
				$response["uid"] = $login["user_id"];
				$response["user"]["name_tag"] = $login["name_tag"];
				$response["user"]["lock_code"] = $lock_code;
				
				echo json_encode($response);
			}else{
				$response["error"] = 1;
				$response["error_msg"] = "Incorrect name tag or lock code!";
				echo json_encode($response);
			}
			break;
		case 'register':
			$name_tag = $_POST['name_tag'];
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email_add = $_POST['email'];
			$lock_code = $_POST['lock_code'];
			$accept_terms = 1;
			
			//validations for name_tag
			if(empty($name_tag)){
				$name_tag_error = '*Please enter your name tag.';
				$name_tag_check = false;
			}elseif(preg_match('#[^A-Za-z0-9_]#i', $name_tag)){
				$name_tag_error = '*Only letters, numbers, and underscores are allowed.';
				$name_tag_check = false;
			}elseif(strlen($name_tag) > 20){
				$name_tag_error = '*Name tag must be less than 21 characters.';
				$name_tag_check = false;
			}elseif(strlen($name_tag) <= 3 ){
				$name_tag_error = '*Name tag must be greater than 3 characters.';
				$name_tag_check = false;
			}else{
				$check_name_rows = $db->checkNameTag($name_tag);
				if($check_name_rows == 0){
					$name_tag_check = true;
				}else{
					$name_tag_error = '*Name tag is already taken.';
					$name_tag_check = false;
				}
			}
			
			list($first_name_error, $last_name_error, $email_add_error, $lock_code_error, $accept_terms_error, $first_name_check, $last_name_check, $email_add_check, $lock_code_check, $accept_terms_check) = validate($first_name, $last_name, $email_add, $lock_code, $accept_terms);
			
			list($name_tag_message, $first_name_message, $last_name_message, $email_add_message, $lock_code_message, $accept_terms_message) = validateCheck($name_tag_check, $first_name_check, $last_name_check, $email_add_check, $lock_code_check, $accept_terms_check, $name_tag_error, $first_name_error, $last_name_error, $email_add_error, $lock_code_error, $accept_terms_error);
			
			if($name_tag_check == true AND $first_name_check == true AND $last_name_check == true AND $email_add_check == true AND $lock_code_check == true AND $accept_terms_check == true){
				$db->register($name_tag, $first_name, $last_name, $email_add, $lock_code, $accept_terms);
				$response['success'] = 1;
				echo json_encode($response);
			}else{
				$response['error_msg']['name_tag'] = "";
				$response['error_msg']['last_name'] = "";
				$response['error_msg']['first_name'] = "";
				$response['error_msg']['email'] = "";
				$response['error_msg']['lock_code'] = "";
				$response['error'] = 1;
				if($name_tag_check == false){
					$response['error_msg']['name_tag'] = $name_tag_error;
				}
				if($last_name_check == false){
					$response['error_msg']['last_name'] = $last_name_error;
				}
				if($first_name_check == false){
					$response['error_msg']['first_name'] = $first_name_error;
				}
				if($email_add_check == false){
					$response['error_msg']['email'] = $email_add_error;
				}
				if($lock_code_check == false){
					$response['error_msg']['lock_code'] = $lock_code_error;
				}
				
				echo json_encode($response);
			}
			break;
		case 'upload':
			require('../model/get_directory_name.php');
			
			$to_file = "../user_folders/085e5987917016661b4739d9792f7e13fdlpp9rqxntdw/" . basename($_FILES['uploadedfile']['name']);
			$from_file = $_FILES['uploadedfile']['tmp_name'];

			if (move_uploaded_file($from_file, $to_file)) {
				$response = array("success"=>1);
				echo json_encode($response);
			} else {
				$response = array("error"=>1);
				echo json_encode($response);
			} 
			
			break;
		default:
			echo 'Invalid Request';
	}
}else{
	echo 'Access denied';
}
?>