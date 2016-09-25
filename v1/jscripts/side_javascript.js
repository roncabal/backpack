	var background = false;
	var tr_id = null;
	var file_id = null;
	var span = null;
	var url = null;
	var file_type = null;
	var open_pocket = null;
	var folder;
	
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

	
		disableSelection(document.body);
				
			//get pocket
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
			//end get pocket
		
			//show upload div
			$("#uploadButton").click(function() {
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
			$("#rightCloud").click(function(){
				$("#rightPanel").slideToggle("fast");
				$("#rightCloud").slideToggle("fast");
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
				$(tr_id).addClass("fileSelect");
				
				if(file_type.toLowerCase() == 'png' || file_type.toLowerCase() == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
					document.getElementById('fileHolder').innerHTML = '<img src="' + url + '.' + file_type + '" id="filePic"/>';
				}else if(file_type == 'folder'){
					location = "./?open=" + url;
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
				$(tr_id).addClass("fileSelect");
				
				if(file_type.toLowerCase() == 'png' || file_type.toLowerCase() == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#modalHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
					document.getElementById('fileHolder').innerHTML = '<img src="' + url + '.' + file_type + '" id="filePic"/>';
				}else if(file_type == 'folder'){
					location = "./?open=" + url;
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
					alert("Please select a file.");
				}else if(file_type == 'folder'){
					alert("folder");
				}else{
					download();
				}
			});
			
			//delete file
			$('#delete').live("click", function(){
				
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
			document.getElementById("shareUrlText").value = "localhost/backpack/download/" + result.unique_id;
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