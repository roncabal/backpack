<?php
	session_start();
	require('../model/database_connection.class.php');
	require('../model/get_directory_name.php');
	
	$file_name = $_POST['file_name'];
	$file_type = $_POST['file_type'];
	$file_url = $_POST['file_url'];
	
	$name_tag = $_POST['name_tag'];
	$lock_code = $_POST['lock_code'];
	$getDir = getDirectory($name_tag, $lock_code);
	
	$full_path = "/user_folders/". $getDir . '/' . $file_url . '.' . $file_type;
	
	$response = array("file_dir"=>$full_path);
	
	echo json_encode($response);
?>