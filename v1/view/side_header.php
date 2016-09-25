<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Backpack</title>
<link rel="stylesheet" type="text/css" href="../styles/backpack_main.css" />
<script type="text/javascript" src="../jscripts/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../jscripts/side_javascript.js"></script>

<!--PLUpload javascripts -->
<script type="text/javascript" src="../pluploadjs/js/plupload.full.js"></script>
<script type="text/javascript" src="../pluploadjs/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="../pluploadjs/js/plupload.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.gears.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.flash.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.html4.js"></script>
<script type="text/javascript" src="../pluploadjs/js/plupload.html5.js"></script>
<!--PLUpload javascripts end-->

</head>
<body>
<div id="uploadBackground">
</div>
<div id="settingsBackground">
</div>
<div id="modalHolder" align="center">
<?php include("show_file_box.php"); ?>
<?php include("upload_file_box.php"); ?>
<?php include("add_folder_box.php"); ?>
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
			<li> <a href="#"><div id="doc_icon"></div></a> </li>
			<li> <a href="#"><div id="images_icon"></div> </a> </li>
			<li> <a href="#"><div id="video_icon"></div> </a> </li>
			<li> <a href="#"><div id="music_icon"></div> </a> </li>
			<li> <a href="#"><div id="pack_icon"></div> </a> </li>
		</ul>
	</div>
	
	<a href="?open=main_pocket"><div id="backpack_header"></div></a>
	
	<div id="rightCloud" class="onHover"></div>
	
	<div id="rightPanel">
		<div id="activityPanelWrapper">
		
		</div>
		
		<div id="bagMatesPanelWrapper">
		
		</div>
	</div>
	
	<div id="pageContent">