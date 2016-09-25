<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');
	
$open_dir = "notes";
$db = new database_connection();
$db->connect();
$name_tag = $_SESSION['name_tag'];
$lock_code = $_SESSION['lock_code'];
global $my_files;

$getDir = getDirectory($name_tag, $lock_code);

$trId = 0;
$fileId = 0;

$myDir = '../user_folders/' . $getDir .'/'. $open_dir . '/';
if(file_exists($myDir)){
	$query = $db->db->prepare("SELECT file_name FROM `$getDir` WHERE file_dir like binary 'notes' ORDER BY order_number ASC");
}else{
	$response = array("response" => "Folder does not exist.");
	echo json_encode($response);
	return;
}

$query->execute();

while($rows = $query->fetch(PDO::FETCH_NUM)){
	$file_name = $rows[0];
	
	$my_files .= '<div class="myNotes" id="fileRow'.$trId.'" align="center">
					<div class="noteTitlePlace">'.$file_name.'<span class="mySpan">#fileRow'.$trId.','$file_name'</span></div>
				</div>';
	$fileId++;
	$trId++;	
}

$pocketfiles = array('files' => $my_files);

echo json_encode($pocketfiles);
	
?>