var background = false;
var tr_id = null;
var file_id = null;
var span = null;
var url = null;
var file_type = null;
var open_pocket = 'main';
var file_name = null;
var copytofolder = 'main';
var folder_details = null;
var folder_id = null;
var folder_name = 'main';
var folder_dir = 'main';

$('document').ready(function(){
	$("#setting").hide();
	$("#settingsBackground").hide();
	$("#rightPanel").hide();
	$("#modalBackground").hide();
	$("#errorLogHolder").hide();
	$("#modalHolder").hide();
	$("#uploadBox").hide();
	$("#showFileDiv").hide();
	$("#shareUrlBox").hide();
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
		window.location = './?open=main';
	}
	
	getFiles();
	
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
				$("#modalBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#showFileDiv").fadeIn("fast");
				
			view_file();
			
			}else if(file_type == 'folder'){
				location = "./?open=" + url;
			}else if(file_type == 'backbutton'){
				location = "./?open=" +url;
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
				$("#modalBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#showFileDiv").fadeIn("fast");
				
			view_file();
			
			}else if(file_type == 'folder'){
				location = "./?open=" + url;
			}else if(file_type == 'backbutton'){
				location = "./?open=" +url;
			}else{
				download();
			}
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
		
		//share button
		$("#shareFile").live("click", function(){
			if(tr_id == null){
				alert("Please select a file to share.");
			}else if(file_type == 'folder'){
				alert("folder");
			}else{
				$("#modalBackground").fadeIn("fast");
				$("#modalHolder").fadeIn("fast");
				$("#shareUrlBox").fadeIn("fast");
				generateDownload();
			}
		});
		
		$("#shareUrlClose").live("click", function(){
			document.getElementById("shareUrlText").value = "";
			$("#modalBackground").fadeOut("fast");
			$("#modalHolder").fadeOut("fast");
			$("#shareUrlBox").fadeOut("fast");
		});
		
		//close file
		$("#closeFile").click(function(){
			$("#modalBackground").fadeOut("fast");
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
		
		$('#trashFile').live("click", function(){
			if(tr_id == null){
				alert("Please select a file to delete.");
			}else{
				if(confirm("Delete selected file?")){
					trashFile();
				}
			}
		});
		
		$('#renameFile').live("click", function(){
			if(tr_id == null){
				alert("Please select a file.");
			}else if(file_type == 'folder'){
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

function getFiles(){
	document.getElementById("myFilePlace").innerHTML='<div id="loadingHolder" align="center"><div id="loadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	var request = {"sort_files":open_pocket};
	$.ajax({
		url:"../util/get_sorted_files.php",
		type:"POST",
		dataType: "json",
		data: request,
		success:function(response){
			if(response.success == 1){
				document.getElementById("myFilePlace").innerHTML=response.files;
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

function selectAll(id){
    document.getElementById(id).focus();
    document.getElementById(id).select();
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

function trashFile(){
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
					getFiles();
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
					getFiles();
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

