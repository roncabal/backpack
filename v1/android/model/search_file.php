<?php
$response = array();

require_once('database_connection.class.php');
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
 
$name_tag = $_GET['name_tag'];
$lock_code = $_GET['lock_code'];
$search = $_GET['search'];

$search = str_replace("_", " ", $search);
$myDir = getDirectory($name_tag, $lock_code);
$query = $db->db->query("SELECT * FROM `$myDir` WHERE file_name like '%$search%' AND file_dir not like '%trash%' AND file_type not like 'folder' ORDER BY order_number ASC") or die(mysql_error());
 
$result = $query->rowCount();

$response["file_details"] = array();
	$searchFile = explode("/", $search);
	$folder_count = count($searchFile);
	if($folder_count > 1){
		$counter = 1;
		$back_dir = $searchFile[0];
		while($counter < $folder_count-1){
			$back_dir .= '/'.$searchFile[$counter];
			$counter++;
		}
		$file_details = array();
        $file_details["file_id"] = '0';
        $file_details["file_name"] = '../Up one directory';
        $file_details["file_size"] = '0';
        $file_details["file_type"] = 'back';
        $file_details["file_dir"] = $back_dir;
		$file_details["date_uploaded"] = '0';
		
		array_push($response["file_details"], $file_details);
	}
if ($result > 0) {
    
 
    while ($rows = $query->fetch(PDO::FETCH_NUM)) {
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
}
$response["success"] = 1;
echo json_encode($response);
?>