<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');

$folder_name = $_REQUEST['json']['folder_name'];
$folder_dir = $_REQUEST['json']['folder_dir'];
$file_name = $_REQUEST['json']['file_name'];
$file_type = $_REQUEST['json']['file_type'];
$file_url = $_REQUEST['json']['file_url'];
$db = new database_connection();
$db->connect();
$name_tag = $_SESSION['name_tag'];
$lock_code = $_SESSION['lock_code'];

$getDir = getDirectory($name_tag, $lock_code);

$chop_url = explode("/", $file_url);
$chop_url_size = count($chop_url);
$chop_counter = 0;
$first_url = "";
if($chop_url_size > 1){
	while($chop_counter < $chop_url_size - 1){
		if($chop_counter == $chop_url_size - 2){
			$first_url .= $chop_url[$chop_counter];
		}else{
			$first_url .= $chop_url[$chop_counter] .'/';
		}
		$chop_counter++;
	}
}else{
	$first_url = $chop_url[0];
}
$file_to_move = "../user_folders/". $getDir .'/'.$file_url .'.'.$file_type;
$file_destination = "../user_folders/". $getDir . '/' . $folder_dir . '/' . $file_name .'.'.$file_type;

$response = array("success"=>0,"error"=>0);

if($first_url == $folder_dir){
	$response['error'] = 1;
	$response['error_msg'] = "File exist in the target directory.";
	echo json_encode($response);
	return;
}


if(copy($file_to_move, $file_destination)){
	unlink($file_to_move);
	$update = $db->db->query("UPDATE `$getDir` SET file_dir='$folder_dir' WHERE file_name like binary '$file_name' AND file_dir like binary '$first_url'");
	
	if($update){
		$response['success'] = 1;
		$response['success_msg'] = "Successfully moved file.";
		echo json_encode($response);
	}else{
		$response['error'] = 1;
		$response['error_msg'] = "Unable to update database.";
		echo json_encode($response);
	}
}else{
	$response['error'] = 1;
	$response['error_msg'] = "Unable to move file.";
	echo json_encode($response);
}
?>