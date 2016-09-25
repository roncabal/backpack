<?php
session_start();
require('database_connection.class.php');
require('../../model/get_directory_name.php');

$folder_name = $_POST['folder_name'];
$folder_dir = $_POST['folder_dir'];
$file_name = $_POST['file_name'];
$file_type = $_POST['file_type'];
$file_url = $_POST['file_url'];
$db = new database_connection();
$db->connect();
$name_tag = $_POST['name_tag'];
$lock_code = $_POST['lock_code'];

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
$file_to_copy = "../../user_folders/". $getDir .'/'.$file_url .'/' . $file_name . '.' .$file_type;
$file_destination = "../../user_folders/". $getDir . '/' . $folder_dir . '/' . $file_name .'.'.$file_type;

$response = array("success"=>0,"error"=>0);

if($first_url == $folder_dir){
	$response['error'] = 1;
	$response['error_msg'] = "File exist in the target directory.";
	echo json_encode($response);
	return;
}


if(copy($file_to_copy, $file_destination)){
	$get_row = $db->db->query("SELECT * FROM `$getDir` WHERE file_name like binary '$file_name' AND file_dir like binary '$first_url' LIMIT 1");
	
	while($rows = $get_row->fetch(PDO::FETCH_NUM)){
		$insert_file_name = $rows[1];
		$insert_file_size = $rows[2];
		$insert_file_type = $rows[3];
		$compare_file_dir = $rows[4];
		$sort_type = $rows[6];
	}
	if($compare_file_dir != $folder_dir){
		$date = date("Y-m-d H:i:s");
		$add_copied_file = $db->db->query("INSERT INTO `$getDir`(file_name, file_size, file_type, file_dir, order_number, sort_type, date_uploaded, date_modified) VALUES ('$insert_file_name','$insert_file_size','$insert_file_type','$folder_dir', 1, '$sort_type', '$date', '$date')");
	}
	
	$response['success'] = 1;
	$response['success_msg'] = "Successfully copied file.";
	echo json_encode($response);
}else{
	$response['error'] = 1;
	$response['error_msg'] = "Unable to copy file.";
	echo json_encode($response);
}
?>