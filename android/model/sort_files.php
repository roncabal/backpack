<?php
session_start();
require('database_connection.class.php');
require('../../model/get_directory_name.php');
function getFileSize($file_size){
		$size = $file_size;
		$count = 0;
		while($size > 1024){
			$size /= 1024;
			$count++;
		}
		
		if($count == 0){
			$bit = 'Bytes';
		}elseif($count == 1){
			$bit = 'KB';
		}elseif($count == 2){
			$bit = 'MB';
		}elseif($count == 3){
			$bit = 'GB';
		}
		
		return Array($size, $bit);
	}
	
$db = new database_connection();
$db->connect();
$get_all = $_GET['sort_files'];
$response = array("type"=>$get_all,"success"=>0, "error"=>0);

$name_tag = $_GET['name_tag'];
$lock_code = $_GET['lock_code'];

$getDir = getDirectory($name_tag, $lock_code);

$query = $db->db->query("SELECT * FROM `$getDir` WHERE sort_type like binary '$get_all' AND file_dir NOT LIKE 'trash%' AND file_type != 'folder' ORDER BY order_number ASC");

$num_rows = $query->rowCount();
$response["file_details"] = array();
if($num_rows > 0){
while($rows = $query->fetch(PDO::FETCH_NUM)){
		$file_details["file_id"] = $rows[0];
        $file_details["file_name"] = $rows[1];
        $file_size = $rows[2];
        $file_details["file_type"] = $rows[3];
        $file_details["file_dir"] = $rows[4];
		$file_details["date_uploaded"] = $rows[6];

		list($fileSize, $fileBit) = getFileSize($file_size);
		$fileSize = sprintf("%.2f",$fileSize);
		
		$file_details['file_size'] = $fileSize . ' ' .$fileBit;
		
        array_push($response["file_details"], $file_details);
	}
}else{
	$file_details["file_id"] = 0;
	$file_details["file_name"] = 'No files of this category.';
	$file_details["file_size"] = '...';
	$file_details["file_type"] = 'main';
	$file_details["file_dir"] = 'main';
	$file_details["date_uploaded"] = '';
	
	array_push($response["file_details"], $file_details);
}
	$response['success'] = 1;
	echo json_encode($response);
?>