$(document).ready(function(){
	$("#setting").hide();
	$("#settingsBackground").hide();
	$("#uploadBackground").hide();
	$("#errorLogHolder").hide();
	$("#modalHolder").hide();
	$("#rightPanel").hide();
	$("#renameFileBox").hide();
	
	loadNotes();
	
	$("#saveNote").live("click", function(){
		var title = document.getElementById("noteTitleBox").value;
		var content = document.getElementById("noteContent").value;
		var to_save = {"title":title, "content":content};
		//alert(to_save.title + "\n" + to_save.content);
		
		$.ajax({
			url:"../util/save_note.php",
			type:"POST",
			data:to_save,
			dataType:"json",
			success:function(result){
				if(result.success == 1){
					document.getElementById("errorLog").innerHTML=result.success_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
					loadNotes();
				}else if(result.error == 1){
					document.getElementById("errorLog").innerHTML=result.error_msg;
					$("#errorLogHolder").fadeIn().delay(800).fadeOut("slow");
				}
			}
		});
	});
});

function loadNotes(){
	document.getElementById("noteListHolder").innerHTML='<div id="loadingHolder" align="center"><div id="loadingPlace" ><img src="../backpack_images/loading.gif" /></div></div>';
	$.ajax({
		url: "../util/update_note_list.php",
		type: 'POST',
		dataType: 'json',
		success: function(result){
			document.getElementById("noteListHolder").innerHTML=result.files;
		}
	});
}