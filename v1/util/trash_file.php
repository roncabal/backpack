<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');

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
if($file_type == 'folder'){
	$file_to_delete = "../user_folders/". $getDir .'/'.$file_url;
	$file_destination = "../user_folders/". $getDir . '/trash/' . $file_name;
	$trash_dir = $first_url . '/' . $file_name;
}else{
	$file_to_delete = "../user_folders/". $getDir .'/'.$file_url .'.'.$file_type;
	$file_destination = "../user_folders/". $getDir . '/trash/' . $file_name .'.'.$file_type;
}

$response = array("success"=>0,"error"=>0);

if(file_exists($file_destination)){
	unlink($file_destination);
}

if(rename($file_to_delete, $file_destination)){
	if($file_type == 'folder'){
		$to_update = $db->db->query("SELECT * FROM `$getDir` WHERE file_dir like binary '$trash_dir%'");
		
		while($rows = $to_update->fetch()){
			$file_id = $rows['file_id'];
			$get_file_dir = $rows['file_dir'];
			$update_dir = str_replace('main', 'trash', $get_file_dir);

			$file_update = $db->db->query("UPDATE `$getDir` SET file_dir='$update_dir' WHERE file_id like binary '$file_id'");
		}
	}
	$update = $db->db->query("UPDATE `$getDir` SET file_dir='trash' WHERE file_name like binary '$file_name' AND file_dir like binary '$first_url'");
	
	if($update){
		$response['success'] = 1;
		$response['success_msg'] = "Successfully deleted file.";
		echo json_encode($response);
	}else{
		$response['error'] = 1;
		$response['error_msg'] = "Unable to update database.";
		echo json_encode($response);
	}
}else{
	$response['error'] = 1;
	$response['error_msg'] = "Unable to delete file.";
	echo json_encode($response);
}
?>