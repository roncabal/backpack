<?php
//database upload
$db = new database_connection();
$db->connect();
$file_name = $_FILES['file']['name'];
$file_size = $_FILES['file']['size'];
$file_type = $_FILES['file']['type'];
$ext = substr(strrchr($file_name, '.'), 1);
$file_name_array = explode(".", $file_name);
$file_name = $file_name_array[0];
global $sort_type;

$ext_type = strtolower($ext);
if($ext_type == 'jpg' || $ext_type == 'jpeg' || $ext_type == 'png' || $ext_type == 'gif'){
	$sort_type = "images";
}elseif($ext_type == 'doc' || $ext_type == 'docx' || $ext_type == 'dot' || $ext_type == 'pot' || $ext_type == 'ppt' || $ext_type == 'ppz' || $ext_type == 'word' || $ext_type == 'pps' || $ext_type == 'xl' || $ext_type == 'xla' || $ext_type == 'xlb' || $ext_type == 'xlc' || $ext_type == 'txt'){
	$sort_type = "documents";
}elseif($ext_type == 'mid' || $ext_type == 'midi' || $ext_type == 'mp3' || $ext_type == 'mpg' || $ext_type == 'mid' || $ext_type == 'wav'){
	$sort_type = "music";
}elseif($ext_type == 'avi' || $ext_type == 'flv' || $ext_type == 'mp4' || $ext_type == 'mpeg' || $ext_type == '3gp'){
	$sort_type = "videos";
}else{
	$sort_type = "not supported.";
}

$date = date("Y-m-d H:i:s");
$double_query = $db->db->query("SELECT * FROM `$myDir` WHERE file_name like binary '$file_name' AND file_type like binary '$ext' AND file_dir like binary '$pocket' LIMIT 1");

$num_rows = $double_query->rowCount();
if($num_rows == 1){
	$update = $db->db->query("UPDATE `$myDir` SET file_size='$file_size', date_modified='$date' WHERE file_name like binary '$file_name' AND file_type like binary '$file_type' AND file_dir like binary '$pocket'");
}else{
	$query = $db->db->prepare("INSERT INTO `$myDir`(file_name, file_size, file_type, file_dir, order_number, sort_type, date_uploaded, date_modified) VALUES ('$file_name','$file_size','$ext','$pocket', 1, '$sort_type', '$date', '$date')");
	try{
	$query->execute();
	}catch(PDOException $e){
		$error_message = $e->getMessage();
		echo $error_message;
	}
}
?>