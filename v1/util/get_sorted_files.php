<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');
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
$get_all = $_REQUEST['sort_files'];
$response = array("type"=>$get_all,"success"=>0, "error"=>0);

$name_tag = $_SESSION['name_tag'];
$lock_code = $_SESSION['lock_code'];
global $my_files;

$getDir = getDirectory($name_tag, $lock_code);

$trId = 0;
$fileId = 0;

$query = $db->db->query("SELECT file_name, file_size, file_type, file_dir FROM `$getDir` WHERE sort_type like binary '$get_all' AND file_dir not like 'trash%' ORDER BY order_number ASC");

while($rows = $query->fetch(PDO::FETCH_NUM)){
		$file_name = $rows[0];
		$file_size = $rows[1];
		$file_type = $rows[2];
		$file_dir = $rows[3];
		
		$file_type = strtolower($file_type);
		
		if($file_type == "txt"){
			$file_pic = '<img src="../backpack_images/file_types/txt.jpg" width="25" height="25" />';
		}elseif($file_type == "doc"){
			$file_pic = '<img src="../backpack_images/file_types/doc.jpg" width="25" height="25" />';
		}elseif($file_type == "docx"){
			$file_pic = '<img src="../backpack_images/file_types/docx.jpg" width="25" height="25" />';
		}elseif($file_type == "ppt"){
			$file_pic = '<img src="../backpack_images/file_types/ppt.jpg" width="25" height="25" />';
		}elseif($file_type == "png"){
			$file_pic = '<img src="../backpack_images/file_types/png.jpg" width="25" height="25" />';
		}elseif($file_type == "jpg"){
			$file_pic = '<img src="../backpack_images/file_types/jpg.jpg" width="25" height="25" />';
		}elseif($file_type == "gif"){
			$file_pic = '<img src="../backpack_images/file_types/gif.jpg" width="25" height="25" />';
		}elseif($file_type == "folder"){
			$file_pic = '<img src="../backpack_images/file_types/folder.jpg" width="25" height="25" />';
		}elseif($file_type == "mp4"){
			$file_pic = '<img src="../backpack_images/file_types/mp4.jpg" width="25" height="25" />';
		}elseif($file_type == "3gp"){
			$file_pic = '<img src="../backpack_images/file_types/3gp.jpg" width="25" height="25" />';
		}else{
			$file_pic = '?';
		}

		list($fileSize, $fileBit) = getFileSize($file_size);
		$fileSize = sprintf("%.2f",$fileSize);
	
		$my_files .= '<div class="myFile" align="center">
								<div class="clickFile">
								</div>
								<table class="myFileTable" border="0" cellpadding="0" cellspacing="0">
									<tr class="fileRow" id="fileRow'.$trId.'">
										<td align="center" width="40">'.$file_pic.'</td>
										<td width="530"><a id="fileNumber'.$fileId.'" href="javascript:;" class="filesInside" ><span class="mySpan">#fileRow'.$trId.','.$file_dir.'/'.$file_name.','.$file_type.','.$file_name.'</span>'.$file_name.'</a></td>
										 <td align="center" width="155">'.$file_type.'</td>
										 <td align="center">'.$fileSize.' '.$fileBit.'</td>
									 </tr>
								 </table>
							 </div>';
		$fileId++;
		$trId++;
	}
	
	$response['files'] = $my_files;
	$response['success'] = 1;
	echo json_encode($response);
?>