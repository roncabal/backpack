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
	
	$open_dir = $_REQUEST['json']['open'];
	$db = new database_connection();
	$db->connect();
	$name_tag = $_SESSION['name_tag'];
	$lock_code = $_SESSION['lock_code'];
	global $my_files;
	
	$getDir = getDirectory($name_tag, $lock_code);
	
	$trId = 0;
	$fileId = 0;
	
	$open_dir = str_replace("_", " ", $open_dir);
	
	$folders = explode("/", $open_dir);
	$folder_count = count($folders);
	if($folder_count > 1){
		$counter = 1;
		$back_dir = $folders[0];
		while($counter < $folder_count-1){
			$back_dir .= '/'.$folders[$counter];
			$counter++;
		}
		$my_files .= '<div class="myFile" align="center">
									<div class="clickFile">
									</div>
									<table class="myFileTable" border="0" cellpadding="0" cellspacing="0">
										<tr class="fileRow" id="fileRow'.$trId.'">
											<td align="center" width="40"><img src="../backpack_images/file_types/folder.jpg" width="25" height="25" /></td>
											<td width="530"><a id="fileNumber'.$fileId.'" href="javascript:;" class="filesInside" ><span class="mySpan">#fileRow'.$trId.','.$back_dir.',backbutton</span>/Up one directory</a></td>
											 <td align="center" width="155">back</td>
											 <td align="center">...</td>
										 </tr>
									 </table>
								 </div>';
		$trId++;
		$fileId++;
	}

	$myDir = '../user_folders/' . $getDir .'/'. $open_dir . '/';
	if(file_exists($myDir)){
		$query = $db->db->prepare("SELECT file_name, file_size, file_type, file_dir FROM `$getDir` WHERE file_dir like binary '$open_dir' ORDER BY order_number ASC");
	}else{
		$response = array("response" => "Folder does not exist.");
		echo json_encode($response);
		return;
	}

	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_NUM)){
		$file_name = $rows[0];
		$file_size = $rows[1];
		$file_type = $rows[2];
		$file_dir = $rows[3];
		
		$file_type_comp = strtolower($file_type);
		
		if($file_type_comp == "txt"){
			$file_pic = '<img src="../backpack_images/file_types/txt.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "doc"){
			$file_pic = '<img src="../backpack_images/file_types/doc.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "docx"){
			$file_pic = '<img src="../backpack_images/file_types/docx.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "ppt"){
			$file_pic = '<img src="../backpack_images/file_types/ppt.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "png"){
			$file_pic = '<img src="../backpack_images/file_types/png.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "jpg"){
			$file_pic = '<img src="../backpack_images/file_types/jpg.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "gif"){
			$file_pic = '<img src="../backpack_images/file_types/gif.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "folder"){
			$file_pic = '<img src="../backpack_images/file_types/folder.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "mp4"){
			$file_pic = '<img src="../backpack_images/file_types/mp4.jpg" width="25" height="25" />';
		}elseif($file_type_comp == "3gp"){
			$file_pic = '<img src="../backpack_images/file_types/3gp.jpg" width="25" height="25" />';
		}else{
			$file_pic = '?';
		}
		
		if($file_size == '...'){
			$fileSize = '...';
			$fileBit = null;
		}else{
			list($fileSize, $fileBit) = getFileSize($file_size);
			$fileSize = sprintf("%.2f",$fileSize);
		}
		
		if($file_type == 'folder'){
			$file_folder = $file_name;
			$full_dir = $file_dir . '/' . $file_name;
			
			$my_files .= '<div class="myFile" align="center">
									<div class="clickFile">
									</div>
									<table class="myFileTable" border="0" cellpadding="0" cellspacing="0">
										<tr class="fileRow" id="fileRow'.$trId.'">
											<td align="center" width="40">'.$file_pic.'</td>
											<td width="530"><a id="fileNumber'.$fileId.'" href="javascript:;" class="filesInside" ><span class="mySpan">#fileRow'.$trId.','.$full_dir.','.$file_type.','.$file_name.'</span>'.$file_name.'</a></td>
											 <td align="center" width="155">'.strtolower($file_type).'</td>
											 <td align="center">'.$fileSize.'</td>
										 </tr>
									 </table>
								 </div>';
			$fileId++;
			$trId++;
		}else{
			$my_files .= '<div class="myFile" align="center">
									<div class="clickFile">
									</div>
									<table class="myFileTable" border="0" cellpadding="0" cellspacing="0">
										<tr class="fileRow" id="fileRow'.$trId.'">
											<td align="center" width="40">'.$file_pic.'</td>
											<td width="530"><a id="fileNumber'.$fileId.'" href="javascript:;" class="filesInside" ><span class="mySpan">#fileRow'.$trId.','.$file_dir.'/'.$file_name.','.$file_type.','.$file_name.'</span>'.$file_name.'</a></td>
											 <td align="center" width="155">'.strtolower($file_type).'</td>
											 <td align="center">'.$fileSize.' '.$fileBit.'</td>
										 </tr>
									 </table>
								 </div>';
			$fileId++;
			$trId++;
		}	
	}
	
	$pocketfiles = array('files' => $my_files);

	echo json_encode($pocketfiles);
	
?>