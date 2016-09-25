	var background = false;
	var tr_id = null;
	var file_id = null;
	var span = null;
	var url = null;
	var file_type = null;
	var open_pocket = 'main';
	var file_name = null;
	var tofolder = 'main';
	var folder_details = null;
	var folder_id = null;
	var folder_name = 'main';
	var folder_dir = 'main';
	
	$(document).ready(function(){
//hide all divs
		$("#setting").hide();
		$("#settingsBackground").hide();
		$("#rightPanel").hide();
		$("#uploadBackground").hide();
		$("#errorLogHolder").hide();
		$("#modalHolder").hide();
		$("#uploadBox").hide();
		$("#showFileDiv").hide();
		$("#addFolderBox").hide();
		$("#shareUrlBox").hide();
		$("#copyFileBox").hide();
		$("#moveFileBox").hide();
		$("#renameFileBox").hide();

	
		disableSelection(document.body);
		
			function getUrlVars() {
				var vars = {};
				var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
					vars[key] = value;
				});
				return vars;
			}
			
			open_pocket = getUrlVars()["open"];
			if(open_pocket == null){
				open_pocket = 'main';
			}
				
			loadXMLDoc();
			//end get pocket
		
			//show upload div
			$("#uploadButton").click(function() {
				$("#uploadBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#uploadBox").fadeIn("fast");			
			});
			
			//show upload div
			$("#uploadButtonSide").click(function() {
				$("#uploadBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#uploadBox").fadeIn("fast");			
			});
			
			//close upload div
			$('#close').click(function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#uploadBox").fadeOut("fast");	
			});
			
			//toggle settings
			$("#bottomCloud").click(function(){
				$("#setting").slideToggle("fast");
				if(background == false){
				$("#settingsBackground").fadeIn("fast");
				background = true;
				}else{
				$("#settingsBackground").fadeOut("fast");
				background = false;
				}
			});
			
			$("#settingsBackground").click(function(){
				$("#setting").slideToggle("fast");
				if(background == false){
				$("#settingsBackground").fadeIn("medium");
				background = true;
				}else{
				$("#settingsBackground").fadeOut("fast");
				background = false;
				}
			});
			//backpack settings end
			
			//right panel
			// $("#rightCloud").click(function(){
				// $("#rightPanel").slideToggle("fast");
				// $("#rightCloud").slideToggle("fast");
			// });
			
			//choose folder
			$(".folderRow").live("click", function(){
				if(folder_id!=null){
					$(folder_id).removeClass("folderSelect");
				}
				folder_details = $(this).find(".folderSpan").html().split(",");
				folder_id = folder_details[0];
				folder_name = folder_details[1];
				folder_dir = folder_details[2];
				$(folder_id).addClass("folderSelect");
			});
			
			//double click folder
			$(".folderRow").live("dblclick", function(){
				if(folder_id!=null){
					$(folder_id).removeClass("folderSelect");
				}
				folder_details = $(this).find(".folderSpan").html().split(",");
				folder_id = folder_details[0];
				folder_name = folder_details[1];
				folder_dir = folder_details[2];
				$(folder_id).addClass("folderSelect");
				
				tofolder = folder_dir;
				displayFolders();
				
			});
			
			//choose file
			$(".fileRow").live("click", function(){
				if(tr_id!=null){
					$(tr_id).removeClass("fileSelect");
				}
				span = $(this).find(".mySpan").html().split(",");
				tr_id = span[0];
				url = span[1];
				file_type = span[2];
				file_name = span[3];
				$(tr_id).addClass("fileSelect");
			});
			
			//open file double click
			$(".fileRow").live("dblclick", function(){
				if(tr_id!=null){
					$(tr_id).removeClass("fileSelect");
				}
				span = $(this).find(".mySpan").html().split(",");
				tr_id = span[0];
				url = span[1];
				file_type = span[2];
				file_name = span[3];
				$(tr_id).addClass("fileSelect");
				
				if(file_type.toLowerCase() == 'png' || file_type.toLowerCase() == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
				view_file();
				
				}else if(file_type == 'folder'){
					location = "./?open=" + url.replace(/ /g, "_");
				}else if(file_type == 'backbutton'){
					location = "./?open=" + url.replace(/ /g, "_");
				}else{
					download();
				}
			});
			
			//open file link click
			$(".filesInside").live("click",function(){
				if(tr_id!=null){
					$(tr_id).removeClass("fileSelect");
				}
				span = $(this).find(".mySpan").html().split(",");
				tr_id = span[0];
				url = span[1];
				file_type = span[2];
				file_name = span[3];
				$(tr_id).addClass("fileSelect");
				
				if(file_type.toLowerCase() == 'png' || file_type.toLowerCase() == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
				view_file();
				
				}else if(file_type == 'folder'){
					location = "./?open=" + url.replace(/ /g, "_");
				}else if(file_type == 'backbutton'){
					location = "./?open=" + url.replace(/ /g, "_");
				}else{
					download();
				}
			});
			
			//close file
			$("#closeFile").click(function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#showFileDiv").fadeOut("fast");
			});
			
			//right click file
			$(".fileRow").live("contextmenu", function(e) {
				if(tr_id!=null){
					$(tr_id).removeClass("fileSelect");
				}
				span = $(this).find(".mySpan").html().split(",");
				tr_id = span[0];
				url = span[1];
				file_type = span[2];
				file_name = span[3];
				$(tr_id).addClass("fileSelect");
				 $('#menu').css({
					top: e.pageY+'px',
					left: e.pageX+'px'
				}).show();
				return false;
					
			});
			
			//hide context menu
			$('#menu').click(function() {
				$('#menu').hide();
			});
			$(document).click(function() {
				$('#menu').hide();
			});
			
			//fileDownload
			$('#downloadFile').live("click", function(){
				if(tr_id == null){
					alert("Please select a file to download.");
				}else if(file_type == 'folder'){
					alert("Please select a file to download.");
				}else{
					download();
				}
			});
			
			//delete file
			$('#trashFile').live("click", function(){
				if(tr_id == null){
					alert("Please select a file to delete.");
				}else if(file_type == 'folder'){
					if(confirm("Delete selected folder? (The files contained on the selected folder will also be deleted) Continue?")){
						trashFile();
					}
				}else{
					if(confirm("Delete selected file?")){
						trashFile();
					}
				}
			});
			
			//share button
			$("#shareButton").live("click", function(){
				if(tr_id == null){
					alert("Please select a file to share.");
				}else if(file_type == 'folder'){
					alert("folder");
				}else{
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#shareUrlBox").fadeIn("fast");
					generateDownload();
				}
			});
			
			$("#shareUrlClose").live("click", function(){
				document.getElementById("shareUrlText").value = "";
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#shareUrlBox").fadeOut("fast");
			});
			
			//add folder
			$('#addFolderButton').live("click", function(){
				$("#uploadBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#addFolderBox").fadeIn("fast");
			});
			
			$("#addFolderClose").live("click", function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#addFolderBox").fadeOut("fast");
			});
			
			//copy file
			$('#copyFile').live("click", function(){
				if(tr_id == null){
					alert("Please select a file.");
				}else if(file_type == 'folder'){
					alert("Please select a file.");
				}else{
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#copyFileBox").fadeIn("fast");
					
					displayFolders();
				}
				
			});
			
			$('#copyFileClose').live("click", function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#copyFileBox").fadeOut("fast");
				folder_name = 'main';
				folder_dir = 'main';
			});
			
			//move file
			$('#moveFile').live("click", function(){
				if(tr_id == null){
					alert("Please select a file.");
				}else if(file_type == 'folder'){
					alert("Please select a file.");
				}else{
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#moveFileBox").fadeIn("fast");
					
					displayMoveFolders();
				}
				
			});
			
			$('#moveFileClose').live("click", function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#moveFileBox").fadeOut("fast");
				folder_name = 'main';
				folder_dir = 'main';
			});
			
			$('#renameFile').live("click", function(){
				if(tr_id == null){
					alert("Please select a file.");
				}else{
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#renameFileBox").fadeIn("fast");
				}
			});
			
			$('#renameFileClose').live("click", function(){
				$("#uploadBackground").fadeOut("fast");
				$("#modalHolder").fadeOut("fast");
				$("#renameFileBox").fadeOut("fast");
			});
			
	});
	
function loadXMLDoc(){
	document.getElementById("myFilePlace").innerHTML='<div id="loadingHolder" align="center"><div id="loadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	var mydata = {"open":open_pocket};
	
	$.ajax({
		url: "../util/update.php",
		type: 'POST',
		dataType: 'json',
		data: {json: mydata},
		success: function(result){
			if(result.response == "Folder does not exist."){
				window.location = ".?open=main";
			}else{
			document.getElementById("myFilePlace").innerHTML=result.files;
			}
		}
	});
}

//display folder for copy
function displayFolders(){
	document.getElementById("copyStartLoad").innerHTML='<div id="copyFileLoading" align="center"><div id="copyFileLoadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	
	var mydata = {"open":tofolder};
	
	$.ajax({
		url: "../util/copy_update.php",
		type:"POST",
		dataType: "json",
		data: {json:mydata},
		success: function(result){
			document.getElementById("copyStartLoad").innerHTML='<div id="toBeCopiedPlace" align="left"><div id="toBeCopied"></div></div><div id="copyFileFolders"></div><div id="buttonCopyHolder"><div id="buttonCopyPlace"><input type="button" id="copyButton" value="Copy" onclick="copyFile()"/></div></div>';
			document.getElementById("toBeCopied").innerHTML='File to be copied: <font style="margin:0px;color:#F9B7FF;">' + file_name + '.' + file_type + '</font>';
			document.getElementById("folderDestination").innerHTML='Copy to folder: <font style="margin:0px;color:#7744AA;">' + folder_dir + '</font>';
			document.getElementById("copyFileFolders").innerHTML=result.folders;
		}
	});
}

//display folder for move
function displayMoveFolders(){
	document.getElementById("moveStartLoad").innerHTML='<div id="moveFileLoading" align="center"><div id="moveFileLoadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	var mydata = {"open":tofolder};
	
	$.ajax({
		url: "../util/move_update.php",
		type:"POST",
		dataType: "json",
		data: {json:mydata},
		success: function(result){
			document.getElementById("moveStartLoad").innerHTML='<div id="toBeMovedPlace" align="left"><div id="toBeMoved"></div></div><div id="moveFileFolders"></div><div id="buttonMoveHolder"><div id="buttonMovePlace"><input type="button" id="moveButton" value="Move" onclick="moveFile()"/></div></div>';
			document.getElementById("toBeMoved").innerHTML='File to be moved: <font style="margin:0px;color:#F9B7FF;">' + file_name + '.' + file_type + '</font>';
			document.getElementById("moveFolderDestination").innerHTML='Move to folder: <font style="margin:0px;color:#7744AA;">' + folder_dir + '</font>';
			document.getElementById("moveFileFolders").innerHTML=result.folders;
		}
	});
}

//addFolder
function addFolder(){
	var folderName=encodeURIComponent(document.getElementById("folderName").value)
	
	var add_folder = {"open":open_pocket, "folder_name": folderName};
	
	$.ajax({
		url: "../model/create_folder.php",
		type: 'POST',
		dataType: 'json',
		data: add_folder,
		success: function(result){
			document.getElementById("errorLog").innerHTML=result.response;
			$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
				if(result.response == 'Folder has been successfully created!'){
					loadXMLDoc();
					document.getElementById("folderName").value = "";
					$("#uploadBackground").fadeOut("fast");
					$("#modalHolder").fadeOut("fast");
					$("#addFolderBox").fadeOut("fast");
				}
			}
	});
}

//share
function generateDownload(){
	var pass_url = {"url":url, "file_type": file_type};
	
	$.ajax({
		url: "../util/generate_download.php",
		type: "POST",
		data: pass_url,
		dataType: "json",
		success: function(result){
			document.getElementById("shareUrlText").value = "www.skybackpack.com/download/" + result.unique_id;
		}
	});
}

//download
function download(){
	window.open("../download/user_download.php?url=" + url  + "." + file_type);
}

//disable select
function disableSelection(target){
	if (typeof target.onselectstart!="undefined") //IE route
		target.onselectstart=function(){return false}
	else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
		target.style.MozUserSelect="none"
	else //All other route (ie: Opera)
		target.onmousedown=function(){return false}
	target.style.cursor = "default"
}

function selectAll(id){
    document.getElementById(id).focus();
    document.getElementById(id).select();
}

function copyFile(){
	document.getElementById("copyStartLoad").innerHTML='<div id="copyFileLoading" align="center"><div id="copyFileLoadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	if(folder_name != null && folder_dir != null){
		if(folder_name != "back"){
			var folder_destination_details = {"folder_name": folder_name, "folder_dir": folder_dir, "file_name":file_name , "file_type":file_type, "file_url":url};
			
			$.ajax({
				url: "../util/copy_file.php",
				type: "POST",
				data: {json:folder_destination_details},
				dataType: "json",
				success: function(result){
					if(result.success == 1){
						document.getElementById("errorLog").innerHTML=result.success_msg;
						$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
						loadXMLDoc();
						$("#uploadBackground").fadeOut("fast");
						$("#modalHolder").fadeOut("fast");
						$("#copyFileBox").fadeOut("fast");
						folder_name = 'main';
						folder_dir = 'main';
					}else if(result.error == 1){
						document.getElementById("errorLog").innerHTML=result.error_msg;
						$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
						folder_name = 'main';
						folder_dir = 'main';
						tofolder = 'main';
						displayFolders();
					}
				}
			});
		}else{
			alert("Please select a folder!");
		}
	}else{
		alert("Please select a folder!");
	}
}

function view_file(){
	document.getElementById("fileHolder").innerHTML='<div id="viewFileLoading" align="center"><div id="viewFileLoadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	var file_details = {"file_name": file_name, "file_url": url, "file_type": file_type};
	$.ajax({
		url: "../util/view_file.php",
		type: "POST",
		data:{json:file_details},
		dataType:"json",
		success: function(result){
			document.getElementById('fileHolder').innerHTML = '<img src="' + result.file_dir + '" id="filePic"/>';
		}
	});
}

function moveFile(){
	document.getElementById("moveStartLoad").innerHTML='<div id="moveFileLoading" align="center"><div id="moveFileLoadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	if(folder_name != null && folder_dir != null){
		if(folder_name != "back"){
			var folder_destination_details = {"folder_name": folder_name, "folder_dir": folder_dir, "file_name":file_name , "file_type":file_type, "file_url":url};
			
			$.ajax({
				url: "../util/move_file.php",
				type: "POST",
				data: {json:folder_destination_details},
				dataType: "json",
				success: function(result){
					if(result.success == 1){
						document.getElementById("errorLog").innerHTML=result.success_msg;
						$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
						loadXMLDoc();
						$("#uploadBackground").fadeOut("fast");
						$("#modalHolder").fadeOut("fast");
						$("#moveFileBox").fadeOut("fast");
						folder_name = 'main';
						folder_dir = 'main';
					}else if(result.error == 1){
						document.getElementById("errorLog").innerHTML=result.error_msg;
						$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
						folder_name = 'main';
						folder_dir = 'main';
						tofolder = 'main';
						displayMoveFolders();
					}
				}
			});
		}else{
			alert("Please select a folder!");
		}
	}else{
		alert("Please select a folder!");
	}
}

function trashFile(){
	document.getElementById("myFilePlace").innerHTML='<div id="loadingHolder" align="center"><div id="loadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	var folder_destination_details = {"file_name":file_name , "file_type":file_type, "file_url":url};
		$.ajax({
			url: "../util/trash_file.php",
			type: "POST",
			data: {json:folder_destination_details},
			dataType: "json",
			success: function(result){
				if(result.success == 1){
					document.getElementById("errorLog").innerHTML=result.success_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
					loadXMLDoc();
				}else if(result.error == 1){
					document.getElementById("errorLog").innerHTML=result.error_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
				}
			}
		});
}

function renameFile(){
	
	var new_file_name = document.getElementById("newFileName").value
	var file_details = {"file_name":file_name , "file_type":file_type, "file_url":url, "new_file_name":new_file_name};
		$.ajax({
			url: "../util/rename_file.php",
			type:"POST",
			dataType:"json",
			data: file_details,
			success: function(result){
				if(result.success == 1){
					document.getElementById("errorLog").innerHTML=result.success_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
					loadXMLDoc();
					document.getElementById("newFileName").value = "";
					$("#uploadBackground").fadeOut("fast");
					$("#modalHolder").fadeOut("fast");
					$("#renameFileBox").fadeOut("fast");
				}else if(result.error == 1){
					document.getElementById("errorLog").innerHTML=result.error_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
				}
			}
		});
}
