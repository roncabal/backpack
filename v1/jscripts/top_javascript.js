	var background = false;
	var tr_id = null;
	var file_id = null;
	var file_name = null;
	var span = null;
	var url = null;
	var pocket = null;
	var file_type = null;
	var open_pocket = null;
	var folder;
	
	$(document).ready(function(){
		//hide all divs
		$("#setting").hide();
		$("#settingsBackground").hide();
		$("#rightPanel").hide();
		$("#uploadBackground").hide();
		$("#uploadBoxHolder").hide();
		$("#showFileHolder").hide();
		$("#addFolderHolder").hide();
		$("#errorLogHolder").hide();
		
			disableSelection(document.body);
				
			//get pocket
			function getUrlVars() {
				var vars = {};
				var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
					vars[key] = value;
				});
				return vars;
			}
			
			
			var open = getUrlVars()["open"];
			if(open == 'main_pocket'){
				open_pocket = 'main_pocket';
				pocket = 'main';
			}else if(open == 'top_pocket'){
				open_pocket = 'top_pocket';
				pocket = 'top';
			}else{
				open_pocket = 'main_pocket';
				pocket = 'main';
			}
			
			folder = getUrlVars()["folder"];
			
			loadXMLDoc();
			//end get pocket
		
			//show upload div
			$("#uploadButton").click(function() {
				$("#uploadBackground").fadeIn("fast");
				$("#uploadBoxHolder").fadeIn("fast");
				$("#uploadBox").fadeIn("fast");			
			});
			
			//close upload div
			$('#close').click(function(){
				$("#uploadBackground").fadeOut("fast");
				$("#uploadBoxHolder").fadeOut("fast");
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
				file_id = span[2];
				file_type = span[3];
				file_name = span[4];
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
				file_id = span[2];
				file_type = span[3];
				file_name = span[4];
				$(tr_id).addClass("fileSelect");
				
				if(file_type == 'png' || file_type == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#showFileHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
					document.getElementById('fileHolder').innerHTML = '<img src="' + url + '" id="filePic"/>';
				}else if(file_type == 'folder'){
					location = "./?open=" + open_pocket + '&folder=' + file_name;
				}else{
					alert('Download!');
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
				file_id = span[2];
				file_type = span[3];
				file_name = span[4];
				$(tr_id).addClass("fileSelect");
				
				if(file_type == 'png' || file_type == 'jpg'){
					$("#uploadBackground").fadeIn("fast");
					$("#showFileHolder").fadeIn("fast");
					$("#showFileDiv").fadeIn("fast");
					
					document.getElementById('fileHolder').innerHTML = '<img src="' + url + '" id="filePic"/>';
				}else{
					alert('Download!');
				}
			});
			
			//close file
			$("#closeFile").click(function(){
				$("#uploadBackground").fadeOut("fast");
				$("#showFileHolder").fadeOut("fast");
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
				file_id = span[2];
				file_type = span[3];
				file_name = span[4];
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
				}else{
					
				}
			});
			
			//delete file
			$('#delete').live("click", function(){
				
			});
			
			//add folder
			$('#addFolderButton').live("click", function(){
				$("#uploadBackground").fadeIn("fast");
				$("#addFolderHolder").fadeIn("fast");
			});
			
			$("#addFolderClose").live("click", function(){
				$("#uploadBackground").fadeOut("fast");
				$("#addFolderHolder").fadeOut("fast");
			});
			
	});
	
// function loadXMLDoc()
	// {
	// document.getElementById("myFilePlace").innerHTML='<div id="loadingHolder" align="center"><div id="loadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	// var xmlhttp;
	// if (window.XMLHttpRequest)
	  // {// code for IE7+, Firefox, Chrome, Opera, Safari
	  // xmlhttp=new XMLHttpRequest();
	  // }
	// else
	  // {// code for IE6, IE5
	  // xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  // }
	// xmlhttp.onreadystatechange=function()
	  // {
	  // if (xmlhttp.readyState==4 && xmlhttp.status==200){
		// document.getElementById("myFilePlace").innerHTML=xmlhttp.responseText;
			// if(xmlhttp.responseText == 'Folder does not exist.'){
				// window.location = "./?open=" + open_pocket;
			// }
		// }
	  // }
	  // if(folder != 'undefined' || folder != null){
			// xmlhttp.open("GET","../util/update.php?open=" + pocket + '&folder=' + folder,true);
		// }else{
			// xmlhttp.open("GET","../util/update.php?open=" + pocket,true);
		// }
	// xmlhttp.send();
// }

//addFolder
function addFolder(){
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
		document.getElementById("errorLog").innerHTML=xmlhttp.responseText;
		$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
			if(xmlhttp.responseText == 'Folder has been successfully created!'){
				loadXMLDoc();
			}
		}
	  }
	  
	var folderName=encodeURIComponent(document.getElementById("folderName").value)
	if(folder != 'undefined' || folder != null){
			xmlhttp.open("GET","../model/create_folder.php?folder_name=" + folderName + '&open=' + pocket + '&folder=' + folder,true);
		}else{
			xmlhttp.open("GET","../model/create_folder.php?folder_name=" + folderName + '&open=' + pocket,true);
		}
	xmlhttp.send();
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