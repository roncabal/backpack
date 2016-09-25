<?php
session_start();
require('database_connection.class.php');
require('../../model/get_directory_name.php');

$open_dir = $_POST['folder_dir'];
$folder_name = $_POST['folder_name'];
$db = new database_connection();
$db->connect();
$name_tag = $_POST['name_tag'];
$lock_code = $_POST['lock_code'];
$response = array("success"=>0,"error"=>0);

$folder_name = str_replace("%20", " ", $folder_name);

list($folder_name_error, $folder_name_check) = folderNameValidation($folder_name);

if($folder_name_check == false){
	$response['error'] = 1;
	$response['error_msg'] = $folder_name_error;
	echo json_encode($response);
	return;
}

$getDir = getDirectory($name_tag, $lock_code);
	
$myDir = '../../user_folders/' . $getDir .'/'. $open_dir . '/';

if(file_exists($myDir.$folder_name)){
	$response['error'] = 1;
	$response['error_msg'] = "Folder already exist.";
	echo json_encode($response);
	return;
}else{
	$dirName = $myDir.$folder_name;
	mkdir($dirName);
	$date = date("Y-m-d H:i:s");
	
	$dir_query = $db->db->query("INSERT INTO `$getDir`(file_name, file_size, file_type, file_dir, order_number, date_uploaded) VALUES ('$folder_name', '...', 'folder', '$open_dir', 0, '$date')");
	if($dir_query){
		$response['success'] = 1;
		$response['success_msg'] = 'Folder has been successfully created!';
		echo json_encode($response);
		return;
	}
}



function folderNameValidation($folder_name){
	$folder_name_e = null;
	if(preg_match('/[^A-Za-z0-9 ]/i',$folder_name)){
		$folder_name_e = 'Special charaters are not allowed.';
		$folder_name_c = false;
	}else if(strlen($folder_name) > 21){
		$folder_name_e = 'Folder name must contain less than 21 characters only.';
		$folder_name_c = false;
	}else if(strlen($folder_name) < 2){
		$folder_name_e = 'Folder name must contain more than 2 characters.';
		$folder_name_c = false;
	}else{
		$folder_name_c = true;
	}
	
	return Array($folder_name_e, $folder_name_c);
}
?>