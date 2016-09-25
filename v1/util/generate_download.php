<?php
	session_start();
	require('../model/database_connection.class.php');
	
	$db = new database_connection();
	$db->connect();
	
	if(isset($_REQUEST['url'])){
		if(!empty($_REQUEST['url'])){
			$url = $_REQUEST['url'];
			$file_type = $_REQUEST['file_type'];
		}
	}
	
	$url_download = $url . '.' . $file_type;
	
	$rows = 1;
	
	$currentDate = date("Y/m/d");
	
	$same_url = $db->db->query("SELECT * FROM share_file_download WHERE file_directory like binary '$url_download' AND expiry_date != '$currentDate' LIMIT 1");
	$same_url_rows = $same_url->rowCount();
	
	if($same_url_rows == 1){
		while($same_rows = $same_url->fetch(PDO::FETCH_NUM)){
			$unique_id = $same_rows[0];
		}
		$response = array('unique_id'=>$unique_id);
		echo json_encode($response);
		return;
	}
	
	
	while($rows == 1){
		$unique_id = generateRandomString();
	
		$search = $db->db->query("SELECT * FROM share_file_download WHERE uniq_id like binary '$unique_id' LIMIT 1");
		
		$rows = $search->rowCount();
	}
	
	$tomorrow = mktime(0,0,0,date("m"),date("d")+7,date("Y"));
	$date = date("Y/m/d", $tomorrow);
	
	$owner_name_tag = $_SESSION['name_tag'];

	$query = $db->db->query("INSERT INTO share_file_download VALUES ('$unique_id','$url_download','$owner_name_tag','$date')");
	
	$response = array('unique_id'=>$unique_id);
	echo json_encode($response);
	
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < 50; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

?>