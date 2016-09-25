<?php
	require('../model/database_connection.class.php');
	require('../model/get_directory_name.php');
	
	if(isset($_GET['download'])){
		if(!empty($_GET['download']) && strlen($_GET['download']) == 50){
			$unique_id = $_GET['download'];
		}
		
		$db = new database_connection();
		$db->connect();
		
		$query = $db->db->query("SELECT * FROM share_file_download WHERE uniq_id like binary '$unique_id' LIMIT 1");
		
		while($rows = $query->fetch(PDO::FETCH_NUM)){
		$url_download = $rows[1];
		$name_tag = $rows[2];
		}
		$url_array = explode("/", $url_download);
		$file_name = end($url_array);
		$file_name_array = explode(".", $file_name);
		$ext_name = end($file_name_array);
		
		$owner_query = $db->db->query("SELECT first_name, last_name FROM registered_user WHERE name_tag like binary '$name_tag'");
		
		while($rows = $owner_query->fetch(PDO::FETCH_NUM)){
		$first_name = $rows[0];
		$last_name = $rows[1];
		}
		
		$folder_name = getDirectoryGenerate($name_tag,$first_name, $last_name);
		$file_size = filesize("../user_folders/". $folder_name . '/' .$url_download);
		
		$ext_name = strtolower($ext_name);
		switch($ext_name){
			case 'mp4':
				header('Content-type: video/mp4');
				break;
			case 'mp3':
				header('Content-type: audio/mpeg3');
				break;
			case 'jpg':
				header('Content-type: image/jpg');
				break;
			case 'png':
				header('Content-type: image/png');
				break;
			case 'bmp':
				header('Content-type: image/x-windows-bmp');
				break;
			case 'txt':
				header('Content-type: text/plain');
				break;
			case 'docx':
				header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
				break;
			case 'xlsx':
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				break;
			case 'pptx':
				header('Content-type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
				break;
			case 'sldx':
				header('Content-type: application/vnd.openxmlformats-officedocument.presentationml.slide');
				break;
		}
		
		header("Content-Length: ".$file_size);
		header('Content-Disposition: attachment; filename="'.$file_name.'"');
		//read from server and write to buffer
		readfile("../user_folders/". $folder_name . '/' .$url_download);
	}
?>

<html>
<head>
</head>
<body>
</body>
</html>