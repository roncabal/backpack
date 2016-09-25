<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../backpack_images/favicon.ico" type="image/x-icon" /> 
<title>Backpack</title>
<link rel="stylesheet" type="text/css" href="../styles/backpack_main.css" />
<script type="text/javascript" src="../jscripts/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../jscripts/sort_javascript.js"></script>

</head>
<body>
<div id="modalBackground">
</div>
<div id="settingsBackground">
</div>
<div id="modalHolder" align="center">
<?php include("share_file_box.php"); ?>
<?php include("show_file_box.php"); ?>
<?php include("rename_file_box.php"); ?>
</div>
<?php include("error_log.php"); ?>

<div align="center" id="pageWrapper">
	<div id="backpackMenuWrapper">
		<ul id="nav">
			<li> <a href="?open=main"><div id="backpack_icon"></div> </a> 
				<!--<ul>
					<li><a href="?open=top"><div id="top"></div> </a>
					<li><a href="?open=main"><div id="main"></div> </a>
					<li><a href="?open=side"><div id="side"></div> </a>
					<li><a href="#"><div id="bottom"></div> </a>
					<li><a href="#"><div id="extra"></div> </a>
				</ul>-->
			</li>
			
			<li> <a href="?open=notebook"><div id="notebook_icon"></div> </a> </li>
			<li> <a href="?open=documents"><div id="doc_icon"></div></a> </li>
			<li> <a href="?open=images"><div id="images_icon"></div> </a> </li>
			<li> <a href="?open=videos"><div id="video_icon"></div> </a> </li>
			<li> <a href="?open=music"><div id="music_icon"></div> </a> </li>
			<!--<li> <a href="#"><div id="pack_icon"></div> </a> </li>-->
		</ul>
	</div>
	
	<a href="?open=main"><div id="backpack_header"></div></a>
	
	<div id="rightCloud"> <!--class="onHover"--></div>
	
	<div id="rightPanel">
		<div id="activityPanelWrapper">
		
		</div>
		
		<div id="bagMatesPanelWrapper">
		
		</div>
	</div>
	
	<div id="pageContent">