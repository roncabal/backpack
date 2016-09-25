<?php
session_start();
require('../model/database_connection.class.php');
require('../model/get_directory_name.php');
	
$open_dir = $_REQUEST['json']['open'];
$db = new database_connection();
$db->connect();
$name_tag = $_SESSION['name_tag'];
$lock_code = $_SESSION['lock_code'];
global $my_folders;

$getDir = getDirectory($name_tag, $lock_code);

$folderId = 0;

$folders = explode("/", $open_dir);
$folder_count = count($folders);
if($folder_count > 1){
	$counter = 1;
	$back_dir = $folders[0];
	while($counter < $folder_count-1){
		$back_dir .= '/'.$folders[$counter];
		$counter++;
	}
	$my_folders .= '<div class="myFoldersHolder" align="left"><table class="myFolderTable" border="0" cellpadding="0" cellspacing="0">
		<tr id="folderId'.$folderId.'" class="folderRow">
		<td class="foldertd"><span class="folderSpan">#folderId'.$folderId.',back,'.$back_dir.'</span>/Up one directory</td>
		</tr>
		</table></div>';
		$folderId++;
}

$myDir = '../user_folders/' . $getDir .'/'. $open_dir . '/';
if(file_exists($myDir)){
	$query = $db->db->prepare("SELECT file_name, file_dir FROM `$getDir` WHERE file_dir like binary '$open_dir' AND file_type like binary 'folder' ORDER BY order_number ASC");
}else{
	$response = array("response" => "Folder does not exist.");
	echo json_encode($response);
	return;
}

$query->execute();

while($rows = $query->fetch(PDO::FETCH_NUM)){
	$folder_name = $rows[0];
	$folder_dir = $rows[1];
	
		
	$my_folders .= '<div class="myFoldersHolder" align="left"><table class="myFolderTable" border="0" cellpadding="0" cellspacing="0">
	<tr id="folderId'.$folderId.'" class="folderRow">
	<td class="foldertd"><span class="folderSpan">#folderId'.$folderId.','.$folder_name.','.$folder_dir.'/'.$folder_name.'</span>'.$folder_name.' </td>
	</tr>
	</table></div>';
	$folderId++;
}

$tofolder = array('folders' => $my_folders);

echo json_encode($tofolder);
	
?>