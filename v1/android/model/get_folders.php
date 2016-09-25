<?php
session_start();
require('database_connection.class.php');
require('../../model/get_directory_name.php');

$db = new database_connection();
$db->connect();
$name_tag = $_GET['name_tag'];
$lock_code = $_GET['lock_code'];

$getDir = getDirectory($name_tag, $lock_code);
$response = array("success"=>0, "error"=>0);

$query = $db->db->query("SELECT * FROM `$getDir` WHERE file_type like binary 'folder' AND file_dir not like 'trash%' ORDER BY order_number ASC");
$response["file_details"] = array();

while($rows = $query->fetch(PDO::FETCH_NUM)){
	$file_details["file_id"] = $rows[0];
	$file_details["file_name"] = $rows[1];
	$file_size = $rows[2];
	$file_details["file_type"] = $rows[3];
	$file_details["file_dir"] = $rows[4];
	$file_details["date_uploaded"] = $rows[6];
	
	if($file_size == '...'){
		$fileSize = '...';
		$fileBit = null;
	}else{
		list($fileSize, $fileBit) = getFileSize($file_size);
		$fileSize = sprintf("%.2f",$fileSize);
	}
	
	$file_details['file_size'] = $fileSize . ' ' .$fileBit;
	
	array_push($response["file_details"], $file_details);
}
$response["success"] = 1;
echo json_encode($response);
?>