<?php
	function validate($first_name, $last_name, $email_add, $lock_code, $accept_terms){
		$first_name_error = ''; 
		$last_name_error = '';
		$email_add_error = ''; 
		$lock_code_error = '';
		$accept_terms_error = '';
		$first_name_check = false; 
		$last_name_check = false;
		$email_add_check = false; 
		$lock_code_check = false;
		$accept_terms_check = false;
		
		if(empty($first_name)){
			$first_name_error = '*Please enter your first name.';
			$first_name_check = false; 
		}elseif(strlen($first_name) > 20){
			$first_name_error = '*First name must be less than 21 characters.';
			$first_name_check = false;
		}elseif(strlen($first_name) <= 2 ){
			$first_name_error = '*First must be greater than 2 characters.';
			$first_name_check = false;
		}else{
			$first_name_check = true;
		}
		
		if(empty($last_name)){
			$last_name_error = '*Please enter your last name.';
			$last_name_check = false;
		}elseif(strlen($last_name) > 20){
			$last_name_error = '*Last name must be less than 21 characters.';
			$last_name_check = false;
		}elseif(strlen($last_name) <= 2 ){
			$last_name_error = '*Last name must be greater than 2 characters.';
			$last_name_check = false;
		}else{
			$last_name_check = true;
		}
		
		if(empty($email_add)){
			$email_add_error = '*Please enter you email address.';
			$email_add_check = false; 
		}elseif(!preg_match('/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i', $email_add)){
			$email_add_error = "*Please enter a valid email.";
			$email_add_check = false;
		}else{
			$email_add_check = true;
		}
		
		if(empty($lock_code)){
			$lock_code_error = '*Please enter your lock code.';
			$lock_code_check = false;
		}elseif(strlen($lock_code) < 8){
			$lock_code_error = "*Password must be more than 8 characters.";
			$lock_code_check = false;
		}elseif(strlen($lock_code) > 18){
			$lock_code_error = "*Password must be less than 19 characters.";
			$lock_code_check = false;
		}else{
			$lock_code_check = true;
		}
		
		if($accept_terms == 0){
			$accept_terms_error = '*Accept check box must be ticked to process registration.';
			$accept_terms_check = false;
		}elseif($accept_terms == 1){
			$accept_terms_check = true;			
		}
		
		return array($first_name_error, $last_name_error, $email_add_error, $lock_code_error, $accept_terms_error, $first_name_check, $last_name_check, $email_add_check, $lock_code_check, $accept_terms_check);
	}
	
	function validateCheck($name_tag_check, $first_name_check, $last_name_check, $email_add_check, $lock_code_check, $accept_terms_check, $name_tag_error, $first_name_error, $last_name_error, $email_add_error, $lock_code_error, $accept_terms_error){
		$name_tag_message = '';
		$first_name_message = '';
		$last_name_message = '';
		$email_add_message = '' ;
		$lock_code_message = '' ;
		$accept_terms_message = '';
		
		if($name_tag_check == false){
			$name_tag_message = '<p class="validationTexts">'.$name_tag_error.'</p>';
		}
		
		if($first_name_check == false){
			$first_name_message = '<p class="validationTexts">'.$first_name_error.'</p>';
		}
		
		if($last_name_check == false){
			$last_name_message = '<p class="validationTexts">'.$last_name_error.'</p>';
		}
		
		if($email_add_check == false){
			$email_add_message = '<p class="validationTexts">'.$email_add_error.'</p>';
		}
		
		if($lock_code_check == false){
			$lock_code_message = '<p class="validationTexts">'.$lock_code_error.'</p>';
		}
		
		if($accept_terms_check == false){
			$accept_terms_message = '<p class="validationTexts">'.$accept_terms_error.'</p>';
		}
		
		return array($name_tag_message, $first_name_message, $last_name_message, $email_add_message, $lock_code_message, $accept_terms_message);
	
	}
	
	
	
?>