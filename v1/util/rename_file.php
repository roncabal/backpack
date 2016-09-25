<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');

$file_name = $_REQUEST['file_name'];
$file_type = $_REQUEST['file_type'];
$file_url = $_REQUEST['file_url'];
$new_name = $_REQUEST['new_file_name'];

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
if($file_type == 'folder'){
	$file_to_delete = "../user_folders/". $getDir .'/'.$file_url;
	$file_new_name = "../user_folders/". $getDir . '/' . $first_url . '/' . $new_name;
	$move_dir = $first_url . '/' . $file_name;
}else{
	$file_to_delete = "../user_folders/". $getDir .'/'.$file_url .'.'.$file_type;
	$file_new_name = "../user_folders/". $getDir . '/' . $first_url . '/' . $new_name .'.'.$file_type;
}

$response = array("success"=>0,"error"=>0);

if(rename($file_to_delete, $file_new_name)){
	if($file_type == 'folder'){
		$to_update = $db->db->query("SELECT * FROM `$getDir` WHERE file_dir like binary '$move_dir%'");
		
		while($rows = $to_update->fetch()){
			$file_id = $rows['file_id'];
			$get_file_dir = $rows['file_dir'];
			$update_dir = str_replace($file_name, $new_name, $get_file_dir);
			
			$file_update = $db->db->query("UPDATE `$getDir` SET file_dir='$update_dir' WHERE file_id like binary '$file_id'");
		}
	}
	$update = $db->db->query("UPDATE `$getDir` SET file_name='$new_name' WHERE file_name like binary '$file_name' AND file_dir like binary '$first_url'");
	
	if($update){
		$response['success'] = 1;
		$response['success_msg'] = "Successfully renamed file.";
		echo json_encode($response);
	}else{
		$response['error'] = 1;
		$response['error_msg'] = "Unable to update database.";
		echo json_encode($response);
	}
}else{
	$response['error'] = 1;
	$response['error_msg'] = "Unable to rename file.";
	echo json_encode($response);
}
?>