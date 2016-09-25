 $(function(){
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus',
			browse_button : 'pickfiles',
			drop_element : 'fileUploadHolder',
			/*required_features : 'chunks',
			chunk_size : '1mb',*/
			max_file_size : '150mb',
			container : 'fileUploadHolder',
			url : plupload_url,
			flash_swf_url : flash_url,
	        silverlight_xap_url : silverlight_url,
	        urlstream_upload: true,
	        multiple_queues : true
		});

		uploader.bind('Init', function(up, params) {
	        if(params.runtime == 'html5')
	        {
	        	$('#filePlace').html('<div id="dragdropHolder">' + 
	        		'<div id="dragdrop"><h2>Drag and drop files here</h2></div>' +
	        		'</div>');
	        }
	    });

	    $("#uploadfiles").click(function(e){
	    	uploader.start();
	    	e.preventDefault();
	    });

	    uploader.init();

	    uploader.bind('FilesAdded', function(up, files){
		    $.each(files, function(i, file) {
	            $('#filePlace').append(
	            	'<div id="' + file.id + '"" class="selected-files tile bg-color-darken">' +
						'<div class="upload-name">' +
							'<h5>' + file.name + ' ' + plupload.formatSize(file.size) + '</h5>' +
						'</div>' +
						'<div class="upload-process"><h5>On Queue</h5></div>' +
						'<div class="remove-file"><h5>X</h5></div>' +
					'</div>');

	            	$('#' + file.id + ' .remove-file').click(function(e){
				   		up.removeFile(file);
				   		$('#' + file.id).remove();
				   		$('#selectedFiles').tinyscrollbar();
			   		});
	        });

       		$('#selectedFiles').tinyscrollbar();
	       	up.refresh(); // Reposition Flash/Silverlight
	   	});

	   	uploader.bind('UploadProgress', function(up, file) {
	        $('#' + file.id + " .upload-process").html(
	        	    '<div class="progress-bar upload-progress bg-color-white">' +
				    	'<div class="bar bg-color-green" style="width: ' + file.percent + '%"></div>' +
				    '</div>'
	        	);
	    });

	   	uploader.bind('Error', function(up, err) {
	   		console.log(err);
            $('#filePlace').append(
            	'<div id="' + err.file.id + '"" class="selected-files tile bg-color-red">' +
					'<div class="upload-name">' +
						'<h5>' + err.file.name + ' ' + plupload.formatSize(err.file.size) + '</h5>' +
					'</div>' +
					'<div class="upload-process"><h5>' + err.message + '</h5></div>' +
					'<div class="remove-file"><h5>X</h5></div>' +
				'</div>');

            	$('#' + err.file.id + ' .remove-file').click(function(e){
			   		$('#' + err.file.id).remove();
		   		});
	 
	        up.refresh(); // Reposition Flash/Silverlight
	    });

	    uploader.bind('FileUploaded', function(up, file, response) {
	    	var json = $.parseJSON(response.response);
	    	if(json.status == "success")
	    	{
	    		$('#' + file.id + " .upload-name").html('<h5>' + file.name + ' ' + plupload.formatSize(file.size) + '</h5>')
		        $('#' + file.id + " .upload-process").html('<h5>Upload Successful</h5>');
		        $('#' + file.id).removeClass('bg-color-darken').addClass('bg-color-green');
		        if(pocket == "main")
		        {
		        	if(organizer == '1')
			        {
			        	uploadedfiles.push(json.file_name);
			        }
		        }
		        
		        showToast(json.success_msg);
	    	}
	    	else if(json.status == "error")
	    	{
	    		$('#' + file.id + " .upload-name").html('<h5>' + file.name + ' ' + plupload.formatSize(file.size) + '</h5>')
		        $('#' + file.id + " .upload-process").html('<h5>Upload Failed</h5>');
		        $('#' + file.id).removeClass('bg-color-darken').addClass('bg-color-red');
	    	}
	        reloadFileList();
	        
	        up.refresh();
	    });

	    uploader.bind('UploadComplete', function(up, file){

	    	if(pocket == "main")
	    	{
		    	if(organizer == "1" && uploadedfiles.length > 1)
		    	{
		    		if(!xGetSuggestions || xGetSuggestions.readyState == 4)
		    		{
		    			xGetSuggestions = $.ajax({
		    				url:organizer_url,
		    				data:{"files":uploadedfiles},
		    				dataType:"json",
		    				type:"POST",
		    				success:function(data)
		    				{
		    					if(data.status == "success")
		    					{
		    						if(data.suggestions.length > 0)
		    						{
		    							closeThisModal('fileupload');
							    		$("#blackModalBackground").show();
							    		$("#backpackOrganizerHolder").show();
							    		backpackorganizer = true;
							    		organizefiles = new Array();
						    			$("#backpackOrganizerHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
		    							var suggestions = '';

		    							for(var i=0;i<data.suggestions.length;i++)
		    							{
		    								var orgfiles = new Array();
		    								suggestions += '<div id="class_'+ i +'" class="classification"> <div class="class-head"> <div class="class-folder-holder"> <input type="text" name="folderName_'+ i +'" id="folderName_'+ i +'" class="bp-element organize-folder" value="'+ data.suggestions[i].folder_name +'" /> <div class="placeholderPlace"> <label for="folderName_'+ i +'" class="placeholder org-folder-names" id="folderName_'+ i +'Placeholder">Folder name...</label> </div> </div> <div class="organize-accept"> <button id="classAccept_'+ i +'" class="organize-accept-button bg-color-blue fg-color-white">Accept</button> </div> <div class="organize-deny"> <button id="classDeny_'+ i +'" class="organize-deny-button bg-color-red fg-color-white">Deny</button> </div> </div> <div class="class-files">';

											for(var ii=0;ii<data.suggestions[i].folder_files.length;ii++)
											{
												orgfiles.push(ii);
												suggestions += '<div id="file_'+ i +'_'+ ii +'" data-filename="'+ data.suggestions[i].folder_files[ii].file_name +'" class="organize-file"> <div id="remove_'+ i +'_'+ ii +'" class="remove-organize-file bg-color-red fg-color-white">x</div> <div class="organize-file-name"> <h5>'+ data.suggestions[i].folder_files[ii].file_name +'</h5> </div> </div>';
											}

											suggestions += '</div></div>';
											organizefiles.push(orgfiles);

		    							}

		    							$("#suggestionPlace").html(suggestions);
		    							console.log(organizefiles);

		    							$(".remove-organize-file").live("click", function(){
		    								var remove_id = $(this).attr("id");
		    								var class_number = remove_id.substring(remove_id.indexOf("_") + 1, remove_id.lastIndexOf("_"));
		    								var file_pos     = remove_id.substring(remove_id.lastIndexOf("_") + 1, remove_id.length);
		    								organizefiles[class_number][file_pos] = null;
		    								console.log(organizefiles);
		    								$("#file_"+class_number+"_"+file_pos).remove();

		    								for(var i=0;i<organizefiles.length;i++)
		    								{
		    									var is_null = true;
		    									for(var ii=0;ii<organizefiles[i].length;ii++)
		    									{
		    										if(organizefiles[i][ii] != null)
		    										{
		    											is_null = false;
		    										}
		    									}

		    									if(is_null == true)
		    									{
		    										$("#class_"+i).remove();
		    									}
		    								}
		    							});

		    							$(".organize-accept-button").live("click", function(){
		    								var toaccept = $(this).attr("id");
	    									var acceptid = toaccept.substring(toaccept.indexOf("_") + 1, toaccept.length);
	    									var createfolder = $("#folderName_" + acceptid).val();
		    								if((!xAcceptOrganize || xAcceptOrganize.readyState == 4) && createfolder != "")
		    								{
		    									var filetoorganize = new Array();
	    										$("#backpackOrganizerHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

	    										for(var i=0;i<organizefiles[acceptid].length;i++)
	    										{
	    											filetoorganize.push($("#file_"+ acceptid +"_"+ i).attr("data-filename"));
	    										}

		    									xAcceptOrganize = $.ajax({
		    										url:accept_url,
		    										data:{"files":filetoorganize, "fullpath":full_path, "foldername":createfolder} ,
		    										dataType:"json",
		    										type:"POST",
		    										success:function(data){
		    											if(data.status == "success")
		    											{
		    												$("#class_" + acceptid).remove();
		    												showToast(data.success_msg);
		    												reloadFileList();
		    											}
		    											else if(data.status == "error")
		    											{
		    												showToast(data.error_msg);
		    											}

		    											$("#backpackOrganizerHolder .divLoad").remove();
		    										}
		    									});

		    								}
		    								else
		    								{
		    									showToast("Please wait for the first request to finish.");
		    								}
		    							});

	 									$(".organize-deny-button").live("click", function(){
	 										var todeny = $(this).attr("id");
	    									var denyid = todeny.substring(todeny.indexOf("_") + 1, todeny.length);
	    									$("#class_" + denyid).remove();
	    									$('#backpackOrganizerHolder').tinyscrollbar();
	 									})


		    						}
		    						else
		    						{

		    						}

		    						$('#backpackOrganizerHolder').tinyscrollbar();
		    						$("#backpackOrganizerHolder .divLoad").remove();
		    					}
		    					uploadedfiles.splice(0, uploadedfiles.length);
		    				}
		    			});
		    		}

		    		if(uploadedfiles.length <= 1)
		    		{
		    			uploadedfiles.splice(0, uploadedfiles.length);
		    		}
		    		
		    	}
		    }
	    });
});