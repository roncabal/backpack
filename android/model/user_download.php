<?php
	session_start();
	require('database_connection.class.php');
	require('../../model/get_directory_name.php');
	
	if(isset($_GET['url'])){
		if(!empty($_GET['url'])){
			$url_download = $_GET['url'];
		}
		$url_array = explode("/", $url_download);
		$file_name = end($url_array);
		$file_name_array = explode(".", $file_name);
		$ext_name = end($file_name_array);
	
		$db = new database_connection();
		$db->connect();
		
		$name_tag = $_GET['name_tag'];
		$lock_code = $_GET['lock_code'];
		$folder_name = getDirectory($name_tag, $lock_code);
		
		$file_size = filesize("../user_folders/". $folder_name . '/' .$url_download);

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