<?php
	session_start();
	require('../model/database_connection.class.php');
	require('../model/login_register_db.php');
	require('../util/check_logged_out.php');
	
	if(isset($_POST['open'])){
		$open_folder = $_POST['open'];
		list($open) = explode("/", $open_folder);
		$check = end(explode("/", $open_folder));
		if($check == ""){
			header(".?open=main");
			exit();
		}
	}elseif(isset($_GET['open'])){
		$open_folder = $_GET['open'];
		list($open) = explode("/", $open_folder);
		$check = explode("/", $open_folder);
		$checker = end($check);
		if($checker == ""){
			header("location:.?open=main");
			exit();
		}
	}else{
		$open = 'main';
		$open_folder = 'main';
	}
	
	global $my_files, $will_upload, $is_uploading, $pocket_open, $open_dir;
	
	if($open == 'main'){
		$pocket_open = 'Main Pocket';
		$open_dir = $open_folder;
	}elseif($open == 'bottom'){
		$pocket_open = 'Bottom Pocket';
		$open_dir = $open_folder;
	}elseif ($open == 'top'){
		$pocket_open = 'Top Pocket';
	}elseif ($open == 'side'){
		$pocket_open = 'Side Pocket';
	}elseif ($open == 'extra'){
		$pocket_open = 'Extra Pocket';
	}elseif ($open == 'documents'){
		$pocket_open = 'Documents';
	}elseif ($open == 'images'){
		$pocket_open = 'Images';
	}elseif ($open == 'videos'){
		$pocket_open = 'Videos';
	}elseif ($open == 'music'){
		$pocket_open = 'Music';
	}
	
	$open = strtolower($open);
	switch($open){
		case 'main':
			include('../view/main_pocket.php');
			break;
		case 'notebook':
			include('../view/my_notebook.php');
			break;
		case 'top':
			include('../view/top_pocket.php');
			break;
		case 'side':
			include('../view/side_pocket.php');
			break;
		case 'download':
			include('../util/download.php');
			break;
		case 'documents':
			include('../view/show_sorted_files.php');
			break;
		case 'images':
			include('../view/show_sorted_files.php');
			break;
		case 'videos':
			include('../view/show_sorted_files.php');
			break;
		case 'music':
			include('../view/show_sorted_files.php');
			break;
		case 'backpack_page':
			include('../view/backpack_page_main.php');
			break;
		case 'about_page':
			include('../view/about_page_main.php');
			break;
		case 'read_page':
			include('../view/read_page_main.php');
			break;
		case 'team_page':
			include('../view/team_page_main.php');
			break;
		default:
			include('../view/error_404_main.php');
			break;
	}
	
?>

