<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');
$db = new database_connection();
$db->connect();
$file_name = $_REQUEST['title'];
$content = $_REQUEST['content'];
$file_size = "...";
$ext = "txt";
$sort_type = "not supported.";
$name_tag = $_SESSION['name_tag'];
$lock_code = $_SESSION['lock_code'];
$myDir = getDirectory($name_tag, $lock_code);

$response = array("success"=>0, "error"=>0);

if($file_name == "" || $file_name == null){
	$response['error'] = 1;
	$response['error_msg'] = "Please enter the title for this note.";
	
	echo json_encode($response);
	exit();
}

$date = date("Y-m-d H:i:s");
$double_query = $db->db->query("SELECT * FROM `$myDir` WHERE file_name like binary '$file_name' AND file_type like binary '$ext' AND file_dir like binary 'notes' LIMIT 1");

$num_rows = $double_query->rowCount();
if($num_rows == 1){
	$update = $db->db->query("UPDATE `$myDir` SET file_size='$file_size', date_modified='$date' WHERE file_name like binary '$file_name' AND file_type like binary '$ext' AND file_dir like binary 'notes'");
	$response["success"] = 1;
	$response["success_msg"] = "Successfully updated note.";
	
	$noteFileName = "../user_folders/" . $myDir . "/notes/" . $file_name . ".txt";
	$noteFileHandle = fopen($noteFileName, 'w') or die("can't open file");
	fwrite($noteFileHandle, $content);
	fclose($noteFileHandle);
	
	echo json_encode($response);
	exit();
}else{
	$query = $db->db->prepare("INSERT INTO `$myDir`(file_name, file_size, file_type, file_dir, order_number, sort_type, date_uploaded, date_modified) VALUES ('$file_name','$file_size','$ext','notes', 1, '$sort_type', '$date', '$date')");
	try{
	$query->execute();
	}catch(PDOException $e){
		$error_message = $e->getMessage();
		echo $error_message;
	}
	$response["success"] = 1;
	$response["success_msg"] = "Successfully saved note.";
	
	$noteFileName = "../user_folders/" . $myDir . "/notes/" . $file_name . ".txt";
	$noteFileHandle = fopen($noteFileName, 'w') or die("can't open file");
	fwrite($noteFileHandle, $content);
	fclose($noteFileHandle);
	
	echo json_encode($response);
	exit();
}
$response["error"] = 1;
$response["error_msg"] = "Successfully saved note.";
echo json_encode($response);
exit();
?>