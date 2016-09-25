<?php
	session_start();
	require('../model/database_connection.class.php');
	require('../model/get_directory_name.php');
	
	$file_name = $_REQUEST['json']['file_name'];
	$file_type = $_REQUEST['json']['file_type'];
	$file_url = $_REQUEST['json']['file_url'];
	
	$name_tag = $_SESSION['name_tag'];
	$lock_code = $_SESSION['lock_code'];
	$getDir = getDirectory($name_tag, $lock_code);
	
	$full_path = "../user_folders/". $getDir . '/' . $file_url . '.' . $file_type;
	
	$response = array("file_dir"=>$full_path);
	
	echo json_encode($response);
?>