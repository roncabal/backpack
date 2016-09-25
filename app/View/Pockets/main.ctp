<?php echo $this->Html->css('bp-s-in');
	  echo $this->Html->script('backpack_js/bp-js-main', false); 
	  echo $this->Html->script('backpack_js/bp-js-u', false); 
	  echo $this->Html->script('plupload/plupload.full', false);
	  echo $this->Html->script('plupload/jquery.plupload.queue/jquery.plupload.queue', false);
	  echo $this->Html->script('plupload/plupload', false);
	  echo $this->Html->script('plupload/plupload.gears', false);
	  echo $this->Html->script('plupload/plupload.silverlight', false);
	  echo $this->Html->script('plupload/plupload.flash', false);
	  echo $this->Html->script('plupload/plupload.browserplus', false);
	  echo $this->Html->script('plupload/plupload.html4', false);
	  echo $this->Html->script('plupload/plupload.html5', false);
	  echo $this->Html->script('backpack_js/bp-js-uploader', false); ?>

<script type="text/javascript">
	var plupload_url    = '<?php echo $this->Html->url("/main_upload/") . $full_path; ?>';
	var flash_url       = '<?php echo $this->Html->url("/js/plupload/plupload.flash.swf"); ?>';
	var silverlight_url = '<?php echo $this->Html->url("/js/plupload/plupload.silverlight.xap"); ?>';
	var file_list_path  = '<?php $url_path = preg_replace("#[ ]#i", "%20", $full_path); echo $this->Html->url("/get_files/") . $url_path; ?>';
	var folder_url      = '<?php echo $this->Html->url("/create_folder/") . $full_path; ?>';
	var loader_url      = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var delete_url      = '<?php echo $this->Html->url("/delete_items"); ?>';
	var rename_url      = '<?php echo $this->Html->url("/rename_item"); ?>';
	var activity_url    = '<?php echo $this->Html->url("/get_activity"); ?>';
	var open_url        = '<?php echo $this->Html->url("/open_item"); ?>';
	var copy_url        = '<?php echo $this->Html->url("/copy_items"); ?>';
	var move_url        = '<?php echo $this->Html->url("/move_items"); ?>';
	var paste_url       = '<?php echo $this->Html->url("/paste_items/") . $full_path; ?>';
	var userspace_url   = '<?php echo $this->Html->url("/user_space"); ?>';
	var showfolders_url = '<?php echo $this->Html->url("/show_folders"); ?>';
	var foldersopen_url = '<?php echo $this->Html->url("/folders_open/") . $full_path; ?>';
	var download_url    = '<?php echo $this->Html->url("/downloads/download_file"); ?>';
	var downloadm_url   = '<?php echo $this->Html->url("/downloads/download_files"); ?>';
	var recover_url     = '<?php echo $this->Html->url("/recover_bin"); ?>';
	var alive_url       = '<?php echo $this->Html->url("/file_alive"); ?>';
	var full_path       = '<?php echo $full_path; ?>';
	var view_path       = '<?php echo $viewpath; ?>';
	var retrieve_url    = '<?php echo $this->Html->url("/retrieve_main"); ?>';
	var getgroups_url   = '<?php echo $this->Html->url("/get_groups"); ?>';
	var groupshare_url  = '<?php echo $this->Html->url("/share_group"); ?>';
	var sharelink_url   = '<?php echo $this->Html->url("/get_links"); ?>';
	var organizer_url   = '<?php echo $this->Html->url("/organizer"); ?>';
	var organizer       = '<?php echo ($organizer) ? "1" : "0"; ?>';
	var accept_url      = '<?php echo $this->Html->url("accept_organize"); ?>';
	var tutorial_url    = '<?php echo $this->Html->url("tutorial"); ?>';

	function reloadFileList()
	{
		$("#pocketPlaceHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
		killItemLive();
	    if(xreloadfilelist && xreloadfilelist.readyState != 4)
	    {
	        xreloadfilelist.abort();
	    }

	    xreloadfilelist = $.ajax({
	            url: file_list_path,
	            data:{"pocket":"main"},
	            type:"POST",
	            dataType:"json",
	            success: function(data) {
	            	if(data.status == "success")
	            	{
	            		var items = '';
	            		var loop = 1;
	            		viewimages.splice(0, viewimages.length);
	            		// $("#pocket").css({"width" : data.pocket + 'px'});
	            		$("#pocketHolder").css({"width" : data.pocket_holder + 'px'});
	            		for(var i=0;i<data.files.length;i++)
	            		{
	            			if(loop == 1)
	            			{
	            				items +='<div class="file-place">';
	            			}

	            			items +='<div id="'+ data.files[i].file_id +'" data-fullname = "' + data.files[i].full_name + '" data-itemtype="'+ data.files[i].file_type +'" data-url="'+ data.files[i].file_url +'" class="tile select-item item bg-color-'+ data.files[i].color_code +'" data-role="tile-slider"><div class="item-name"><h3><?php echo htmlspecialchars("'+ data.files[i].file_name +'"); ?></h3></div><div class="item-size" ><h5>'+ data.files[i].file_size +'</h5></div><div class="item-type"><h3>'+ data.files[i].file_type.toUpperCase() +'</h3></div><div class="item-date"><h6>'+ data.files[i].date_modified +'</h6></div></div>'; 
	            			if(data.files[i].color_code == "blueDark")
	            			{
	            				viewimages.push(data.files[i].full_name);
	            			}

	            			if(loop == 5)
					         {
					         	items += '</div>';
					         	loop = 1;
					         }
					         else
					         {
					         	loop++;
					         }
	            			
	            		}
	            		$("#pocketPlaceHolder .divLoad").remove();
	            		$("#pocketPlaceHolder").html(items);
            			$("#backFolder").removeClass("select-item");
	            		itemLive();
		                $('#spotlightPlace').tinyscrollbar();
		                
	            	}
	            	else if(data.status == "error")
	            	{
	            		$("#pocketPlaceHolder").html('<div id="emptyMain"><h2>' + data.error_msg + '</h2></div>');
	            	}
	            	else
	            	{

	            	}
	            	aliveFiles();
	                unselectItems();
	               
	            }
	          });
	}
</script>

<?php if($newtomain == "1"){ ?>
	<script type="text/javascript">

	function mainDesc()
	{
		
		$("#previousButton").attr("onclick", "");
		$("#nextButton").attr("onclick", "uploadDesc()");
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head1.png'); ?>");
		$("#tutorialHolder").css({"left":"600px", "top":"120px"});
		$("#botlabel").css({"top":"300px"});
		$("#tutorialPlace h4").html("Here you can navigate and go to the different pockets of your bag, </br> </br> -The Main Pocket: Contains all your files and folders It's just like the main compartment of your bag </br> </br> -Top Pocket: Here you can easily keep track of your files </br> </br>-Side Pocket: Create Groups, Share Files </br></br> -Bottom Pocket: You wan't it private and secured? Got it! </br></br> -Extra Pocket: Find your friends here... We call them BagMates, Chat with them using the Backpack messenger"  );
		$("#tutorialPlace").css({"height":"300px"});
		$("#tutorialFinish").hide();

	}

	function uploadDesc()
	{
		
		$("#previousButton").attr("onclick", "mainDesc()");
		$("#nextButton").attr("onclick", "addfolderDesc()");
		$("#tutorialPlace").css({"height":"100px"});
		$("#botlabel").css({"top":"100px"});
		$("#tutorialHolder").css({"left":"50px", "top":"330px"})
		$("#tutorialPlace h4").html("Here's how you upload files, you can select multiple files or you could simply drag and drop your files on the upload window. It's easy!");
		$("#tutorialFinish").hide();
	}

	function addfolderDesc()
	{
		
		$("#previousButton").attr("onclick", "uploadDesc()");
		$("#nextButton").attr("onclick", "recoverDesc()");
		$("#tutorialPlace").css({"height":"150px"});
		$("#botlabel").css({"top":"150px"});
		$("#tutorialHolder").css({"left":"50px", "top":"370px"})
		$("#tutorialPlace h4").html("Add folders to make your bag more organized! And ohh! Did we mention that when you upload files, BackPack smartly suggest to put them on a folder to make your file management a lot easier.")
		$("#tutorialFinish").hide();
	}

	function recoverDesc()
	{
		$("#previousButton").attr("onclick", "addfolderDesc()");
		$("#nextButton").attr("onclick", "activityDesc()");
		$("#tutorialPlace").css({"height":"150px"});
		$("#botlabel").css({"top":"150px"});
		$("#tutorialHolder").css({"left":"50px", "top":"410px"})
		$("#tutorialPlace h4").html("The files you delete, we keep it! </br></br> Worry no more when you accidentally delete your files, we keep it on our own file cabinet and it would only consume 5% of your memory capacity. </br></br> You have a total of 30 days to try this service, after that you could still access it for only 1$/100mb. Great deal for your Great need!")
		$("#tutorialFinish").hide();
	}

	function activityDesc()
	{

		$("#previousButton").attr("onclick", "recoverDesc()");
		$("#nextButton").attr("onclick", "groupmainDesc()");
		$("#tutorialPlace").css({"height":"150px"});
		$("#botlabel").css({"top":"150px"});
		$("#tutorialHolder").css({"left":"50px", "top":"280px"})
		$("#tutorialPlace h4").html("Be updated, Keep track of your activities whenever someone accepted you on a group. You can also view your recent activities regarding your Upload, Download, Share, etc.")
		$("#tutorialFinish").hide();
	}

	function groupmainDesc()
	{
		$("#previousButton").attr("onclick", "activityDesc()");
		$("#nextButton").attr("onclick", "messagesDesc()");
		$("#tutorialPlace").css({"height":"150px"});
		$("#botlabel").css({"top":"150px"});
		$("#tutorialHolder").css({"left":"50px", "top":"250px"})
		$("#tutorialPlace h4").html("One thing that makes BackPack very useful is your ability to make groups and interact with your group members or what we quote as 'Pocket Members' share, download, and interact!")
		$("#tutorialFinish").hide();
	}


	function messagesDesc()

	{
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head1.png'); ?>");
		$("#previousButton").attr("onclick", "groupmainDesc()");
		$("#nextButton").attr("onclick", "spotlightDesc()");
		$("#tutorialPlace").css({"height":"150px"});
		$("#botlabel").css({"top":"150px"});
		$("#tutorialHolder").css({"left":"50px", "top":"210px"})
		$("#tutorialPlace h4").html("Get notified when someone left you a message, or go directly to the Extra Pocket, browse your bagmates and interact with them through Backpack Chat")
		$("#tutorialFinish").hide();

	}

	function spotlightDesc()

	{
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head6.png'); ?>");
		$("#tutorialImage").css({"left":"0px", "right":"auto"});
		$("#tutorialArrow").css({"left":"0px", "right":"auto"});
		$("#previousButton").attr("onclick", "messagesDesc()");
		$("#nextButton").attr("onclick", "useroptionDesc()");
		$("#tutorialPlace").css({"height":"250px", "right":"40px", "left":"auto"});
		$("#botlabel").css({"top":"250px", "right":"40px", "left":"auto"});
		$("#tutorialHolder").css({"left":"250px", "top":"140px"})
		$("#tutorialPlace h4").html("Have we mention to you that in BackPack, your files are alive! </br></br> YES! they are alive! Checkout your most active files here on your bag's spotlight and also take some time to manage the files that are decaying. </br> </br> YES your files do decay here... And you'll have an option to delete them")
		$("#tutorialFinish").hide();
	}

	function useroptionDesc()
	{
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head7.png'); ?>");
		$("#tutorialImage").css({"right":"0px", "left":"auto"});
		$("#tutorialArrow").css({"right":"0px", "left":"auto"});

		$("#previousButton").attr("onclick", "spotlightDesc()");
		$("#nextButton").attr("onclick", "panelDesc()");
		$("#tutorialPlace").css({"height":"150px", "left":"40px", "right":"auto"});
		$("#botlabel").css({"top":"150px", "left":"40px", "right":"auto"});
		$("#tutorialHolder").css({"left":"650px", "top":"60px"})
		$("#tutorialPlace h4").html("Want to set your preferences? Modify it here! </br></br>You can change your Bottom Pocket Password, Change Name, Pasword, and Email. You could also view the memory status of your bag here.")
		$("#tutorialFinish").hide();

	}

		function panelDesc()
	{
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head7.png'); ?>");
		$("#tutorialImage").css({"right":"0px", "left":"auto"});
		$("#tutorialArrow").css({"right":"0px", "left":"auto"});

		$("#previousButton").attr("onclick", "useroptionDesc()");
		$("#nextButton").attr("onclick", "enjoyBackpack()");
		$("#tutorialPlace").css({"height":"150px", "left":"40px", "right":"auto"});
		$("#botlabel").css({"top":"150px", "left":"40px", "right":"auto"});
		$("#tutorialHolder").css({"left":"758px", "top":"10px"})
		$("#tutorialPlace h4").html("Notice this tiny button up here? Click it to access your right panel. Your right panel contains shortcut to your recent activities and to your bagmates.")
		$("#tutorialFinish").hide();

	}

	function enjoyBackpack()
	{
		$("#imageTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/tutorial.gif'); ?>");
		$("#botlabel").hide();
		$("#tutorialPlace").hide();
		$("#arrowTutorial").hide();
		$("#tutorialHolder").css({"left":"10px", "top":"190px"});
		$("body").append('<div id="tutorialFinish" class="cursor-pointer"><h3 class="fg-color-white">Start Using BackPack</h3></div>');
		$("#tutorialFinish").live("click", function(){
			$("#tutorialHolder").remove();
			$("#tutorialFinish").die("click").remove();

			$.ajax({
				url:tutorial_url,
				data:{"pocket":"main"},
				dataType:"json",
				type:"POST",
				success:function(data){
					showToast(data.success_msg);
				}
			});
		});

	}


	$(document).ready(function(){
		$("body").append('<div id="tutorialHolder"> <div id="tutorialImage"> <img src="" id="imageTutorial" /> </div> <div id="tutorialArrow"> <img src="" id="arrowTutorial" /> </div> <div id="tutorialPlace"> <h4></br>  </br> </br> Hi! Welcome to Backpack, the most exciting cloud based file management. </br> </br> Here you can upload, download, share, and even socialize with other users to help you in managing your files. Please let us take you to a short tour around your bag. Let\'s go! </h4> </div> <div id="botlabel"> <div id="tutorlabelbot"> <h3 class="fg-color-white">Tutorial</h3> </div> <div id="buttonPrevious"> <button id="previousButton">Previous</button> </div> <div id="buttonNext"> <button id="nextButton" onclick="mainDesc()">Next</button> </div> </div> </div>');
		$("#imageTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/tutorial.png'); ?>");
		$("#arrowTutorial").attr("src", "<?php echo $this->Html->url('/img/backpack/tutorial/head6.png'); ?>");
	});
	</script>
<?php } ?>

<div id="subMenuHolder" class="bg-color-blueDark">
	<div id="subTop" class="bg-color-red cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/top'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/toppocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white">Top Pocket</h2></div>
	</div>
	<div id="subSide" class="bg-color-pinkDark cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/side'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/sidepocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white">Side Pocket</h2></div>
	</div>
	<div id="subExtra" class="bg-color-green cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/extra'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/extrapocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white ">Extra Pocket</h2></div>
	</div>
	<div id="subBottom" class="bg-color-purple cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/bottom'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/bottompocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white">Bottom Pocket</h2></div>			
	</div>
</div>



<div id="hoverHelper"><h5 id="hoverHelperTitle"></h5></div>

<div id="leftPanelHolder" class="bg-color-blueDark">
	<div id="userNotification">
		<div id="myMessages" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url("/extra"); ?>'">
			<?php echo $this->Html->image('icons/messages.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="messageHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					Messages
				</div>
			</div>
			<div id="myMessageNotification" class="user-noti bg-color-red">
				<h5 class="fg-color-white"></h5>
			</div>
		</div>
		<div id="myGroupReq" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url("/side"); ?>'">
			<?php echo $this->Html->image('icons/groups.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="memberHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					Groups
				</div>
			</div>
			<div id="myGroupReqNotification" class="user-noti bg-color-red">
				<h5 class="fg-color-white"></h5>
			</div>
		</div>
		<div id="myActivities" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url('/top'); ?>'">
			<?php echo $this->Html->image('icons/activities.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="activityHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					Activities
				</div>
			</div>
		</div>
	</div>
	<div id="separator"></div>
	<div id="userMenu">
		<div id="uploadFile" class="cursor-pointer notification" onclick="openFileUpload()">
			<?php echo $this->Html->image('icons/upload.gif', array('width'=>'40px', 'height'=>'40px', )); ?>
			<div id="uploadHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					Upload
				</div>
			</div>
		</div>
		<div id="addNewFolder" class="cursor-pointer notification" onclick="createFolder()">
			<?php echo $this->Html->image('icons/newfolder.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="addFolderHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					New Folder
				</div>
			</div>
		</div>
		<div id="recoverBin" class="cursor-pointer notification" onclick="window.location = recover_url">
			<?php echo $this->Html->image('icons/download.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="recoverHelper" class="menu-helper fg-color-white">
				<div class="menu-title">
					<div class="menu-arrow"></div>
					Recover
				</div>
			</div>
		</div>
	</div>
</div>

<div id="bodyContext" class="bg-color-darken">
	<ul class="dropdown-menu open" id="contextMenu">
		<li><a href="#" onclick="return false;" onmousedown="openFileUpload()">Upload</a></li>
		<li><a href="#" onclick="return false;" onmousedown="createFolder()">Add Folder</a></li>
		<li class="divider"></li>
		<li><a href="#" onclick="return false;" onmousedown="window.location = '<?php echo $this->Html->url('/top'); ?>'">Top Pocket</a></li>
		<li><a href="#" onclick="return false;" onmousedown="window.location = '<?php echo $this->Html->url('/main'); ?>'">Main Pocket</a></li>
		<li><a href="#" onclick="return false;" onmousedown="window.location = '<?php echo $this->Html->url('/side'); ?>'">Side Pocket</a></li>
		<li><a href="#" onclick="return false;" onmousedown="window.location = '<?php echo $this->Html->url('/bottom'); ?>'">Bottom Pocket</a></li>
		<li><a href="#" onclick="return false;" onmousedown="window.location = '<?php echo $this->Html->url('/extra'); ?>'">Extra Pocket</a></li>
	</ul>
</div>

<div id="fileContext" class="bg-color-darken">
	<ul class="dropdown-menu open" id="contextMenu">
		<li><a href="#" onclick="return false;" onmousedown="openItem()">Open</a></li>
		<li><a href="#" onclick="return false;" onmousedown="downloadNow()">Download</a></li>
		<li><a href="#" onclick="return false;" onmousedown="chooseShareType()">Share</a></li>
		<li><a href="#" onclick="return false;" onmousedown="renameItemSelected()">Rename</a></li>
		<li><a href="#" onclick="return false;" onmousedown="moveItems()">Move</a></li>
		<li><a href="#" onclick="return false;" onmousedown="copyItems()">Copy</a></li>
		<li><a href="#" onclick="return false;" onmousedown="confirmFileDelete()">Delete</a></li>
		<li class="divider"></li>
		<li><a href="#" onclick="return false;" onmousedown="openFileUpload()">Upload</a></li>
		<li><a href="#" onclick="return false;" onmousedown="createFolder()">Add Folder</a></li>
		<li class="divider"></li>
		<li><a href="#" onclick="return false;" onmousedown="deselectItems()">Deselect All</a></li>
	</ul>
</div>

<div class="ajax-message bg-color-blue"><h5 id="ajaxMessage" class="fg-color-white"></h5></div>

<div id="createFolderHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="createFolderIcon">
				<?php echo $this->Html->image('icons/newfolder_darken.gif', array('width'=>50, 'height'=>50)); ?>
			</div>
			<div id="createFolderTitle">
				<h2 class="fg-color-darken">Add Folder</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('createfolder')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="createFolderContent" class="bp-modal-content">
		<form method="POST" action="<?php echo $this->Html->url('/main'); ?>">
			<div id="folderNameHolder">
				<div class="input-control text">
				    <input type="text" name="createFolderName" id="createFolderName" class="bp-element" />
				    <div class="placeholderPlace">
					    <label for="createFolderName" class="placeholder" id="createFolderNamePlaceholder">Enter folder name here...</label>
					</div>
			    </div>
			</div>
			<div id="charsNotAllowed">
				<h6 class="fg-color-red">&bull; Characters \ / : ? < > | * " .. . are not allowed.</h6>
			</div>
			<div id="createFolderButton">
				 <button type="submit" id="createFolder" class="bg-color-blueDark fg-color-white" onclick="return false;" onmousedown="createFolderNow()">Create Folder</button>
			</div>
		</form>
	</div>
</div>

<div id="fileOptions" class="select-disable bg-color-blueDark">
	<div id="openFile" class="file-option cursor-pointer" onclick="openItem()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/open.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Open</h4>
		</div>
	</div>
	<div id="downloadFile" class="file-option cursor-pointer" onclick="downloadNow()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/download.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Download</h4>
		</div>
	</div>
	<div id="shareFile" class="file-option cursor-pointer" onclick="chooseShareType()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/share.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Share</h4>
		</div>
	</div>
	<div id="renameFile" class="file-option cursor-pointer" onclick="renameItemSelected()" >
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/rename.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Rename</h4>
		</div>
	</div>
	<div id="moveFile" class="file-option cursor-pointer" onclick="moveItems()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/move.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Move</h4>
		</div>
	</div>
	<div id="copyFile" class="file-option cursor-pointer" onclick="copyItems()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/copy.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Copy</h4>
		</div>
	</div>
	<div id="deleteFile" class="file-option cursor-pointer" onclick="confirmFileDelete()">
		<div class="file-image-action">
			<?php echo $this->Html->image('icons/delete.gif', array('width'=>30, 'height'=>30)); ?>
		</div>
		<div class="file-action">
			<h4 class="fg-color-white">Delete</h4>
		</div>
	</div>
</div>

<div id="userOptionHolder" class="select-disable">
	<div id="userOptionDrop">
		<div id="pocketTitle">
			<div class="option-title"><h5>Move to:</h5></div>
		</div>
		<div id="userPockets">
			<div id="topPocketMove" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/top'); ?>'"><h5>Top Pocket</h5></div>
			<div id="mainPocketMove" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/main'); ?>'"><h5>Main Pocket</h5></div>
			<div id="bottomPocketMove" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/bottom'); ?>'"><h5>Bottom Pocket</h5></div>
			<div id="sidePocketMove" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/side'); ?>'"><h5>Side Pocket</h5></div>
			<div id="extraPocketMove" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/extra'); ?>'"><h5>Extra Pocket</h5></div>
		</div>
		<div id="userSettings">
			<div id="accountSettings" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url("/account_settings"); ?>'"><h5>Account Settings</h5></div>
			<div id="backpackSettings" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url("/backpack_settings"); ?>'"><h5>Backpack Settings</h5></div>
		</div>
		<div id="logoutHolder">
			<div id="userLogout" class="user-options cursor-pointer select-disable" onclick="window.location = '<?php echo $this->Html->url('/log_out'); ?>'"><h5>Log out</h5></div>
		</div>
		<div id="userSpace">
			<div id="totalSpace" class="user-space select-disable"><h5>Total Space : 0</h5></div>
			<div id="spaceUsed" class="user-space select-disable"><h5>Space Used : 0</h5></div>
			<div id="progressHolder">
				<div class="progress-bar select-disable" id="spaceProgress" >
			    	<div id="percentage" class="bar bg-color-green"></div>
			    </div>
			</div>
		</div>
	</div>
</div>

<div id="modalBackground"></div>
<div id="blackModalBackground"></div>

<div id="fileUploadHolder" class="bp-modal select-disable">
	<div class="bp-modal-header">
		<a href="#">
			<div class="bp-modal-title">
				<div id="uploadIcon">
					<?php echo $this->Html->image('icons/upload_darken.gif', array('width'=>50, 'height'=>50)); ?>
				</div>
				<div id="uploadTitle">
					<h2 class="fg-color-darken">Upload Files</h2>
				</div>
			</div>
		</a>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('fileupload')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="fileUploadContent" class="bp-modal-content">
		<div id="selectedFiles">
			<div class="scrollbar" >
				<div class="track" >
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div id="filePlace" class="overview">
					
				</div>
			</div>
		</div>
		<div id="uploadMenu" class="select-disable">
			<div id="pickfiles" class="tile bg-color-darken">
				<div class="upload-button-title"><h3>Select Files</h3></div>
			</div>
			<div id="uploadfiles" class="tile bg-color-blue">
				<div class="upload-button-title"><h3>Upload Files</h3></div>
			</div>
		</div>
	</div>
</div>

<div id="rightPanelHolder">
	<div id="bagActivityTitle" class="rightpanel-title bg-color-blueDark">
		<h4 class="fg-color-white">Bag Activity</h4>
	</div>
	<div id="bagActivityHolder" class="select-disable">
		<div class="scrollbar" >
			<div class="track" >
				<div class="thumb">
					<div class="end"></div>
				</div>
			</div>
		</div>
		<div class="viewport">
			<div class="overview">
				<div id="activityPlace" style="overflow-x:visible;"></div>
			</div>
		</div>
	</div>

	<div id="chatBoxTitle" class="rightpanel-title bg-color-blueDark select-disable">
		<h4 class="fg-color-white">Bag Mates</h4>
	</div>
	<div id="chatBoxHolder" class="select-disable">
		<div class="scrollbar" >
			<div class="track" >
				<div class="thumb">
					<div class="end"></div>
				</div>
			</div>
		</div>
		<div class="viewport">
			<div class="overview">
				<a href="#">
					<div class="bag-mate bg-color-purple tile">
						<div class="bag-mate-image">
							<?php echo $this->Html->image('no-avatar.jpg', array('width'=>40, 'height'=>40)); ?>
						</div>
						<div class="bag-mate-name">
							<h4>Sample Name Here</h4>
						</div>
						<div class="group">
							<h6>Friends</h6>
						</div>
						<div class="user-online bg-color-blue"></div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div id="chatSearchHolder">
		<div class="input-control text">
		    <input type="text" name="chatBoxSearch" id="chatBoxSearch" class="bp-element" />
		    <div class="placeholderPlace">
			    <label for="chatBoxSearch" class="placeholder" >Search User</label>
			</div>
	    </div>
	</div> 
</div>

<div id="rightPanelToggler" class="cursor-pointer" onclick="toggleRightPanel()">
	<div id="rightPanelTogglerArrow" class="arrow-left"></div>
</div>

<div id="headHolder" class="select-disable">
	<div id="titleHolder">
		<a href="<?php echo $this->Html->url('/main'); ?>" ><h1>Backpack</h1></a>
	</div>

	<div id="userHolder" class="select-disable">
		<div id="nametagHolder" >
			<h4 class="fg-color-darken"><?php echo $this->Session->read('User.name_tag'); ?></h4>
		</div>
		<div id="imageHolder">
			<a href="#" onclick="return false;">
				<img src="<?php echo $imagepath; ?>" width="50" height="50" />
			</a>
		</div>
		<div id="optionTrigger" class="cursor-pointer" onclick="dropOptions()">
			<div id="optionArrow" class="arrow-down"></div>
		</div>
	</div>
</div>

<div id="pocketHolder" class="select-disable">
	<div id="leftPanel" >
		<div id="spotlightHolder" >
			<div id="spotlightTitleHolder" class="bg-color-red">
				<div id="spotlightTitle">
					<a><h2 class="fg-color-white">Spot Light</h2></a>
				</div>
			</div>
			<div id="searchHolder" align="center">
		<div id="searchBoxHolder" align="left">
			<div class="input-control text">
			    <input type="text" name="searchBox" id="searchBox" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="searchBox" class="placeholder" >Search File</label>
				</div>
		    </div>
		    <div id="rightPanelClose">

		    </div>
		</div>
	</div>
			<div id="spotlightPlace">
				<div class="scrollbar" >
					<div class="track" >
						<div class="thumb">
							<div class="end"></div>
						</div>
					</div>
				</div>
				<div class="viewport">
					<div class="overview">

						<!-- <a href="#">
							<div class="tile top-used bg-color-blue">
								<div class="tile-content">
									<div class="file-type">
									<?php echo $this->Html->image('icons/videofile.gif', array('width'=>45, 'height'=>45)); ?>
									</div>
									<div class="file-name">
										<h3>Borat</h3>
										<h5 style="">100 Downloads : 39 Shares</h5>
									</div>
								</div>
							</div>
						</a> -->
						<div id="geneticsSpotlight">
							
						</div>
						<div id="decaySpotlight">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="pocket">
		<div id="menuHolder" class="bg-color-blueDark">
			<div id="pocketNameHolder" class="cursor-pointer" onclick="subMenu()">
				<div id="pocketNamePlace">
					<?php echo $this->Html->image('icons/mainpocket.gif', array('width'=>55,'height'=>55)); ?>
					<div id="pocketName">
						<div id="currentFolder">
							<h2 class="fg-color-white">Main Pocket</h2>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="pocketPlaceHolder">
			
		</div>
	</div>
</div>

<div id="showFolderHolder" class="bp-modal select-disable"> 
    <div class="bp-modal-header select-disable"> 
        <div class="bp-modal-title"> 
            <div id="showFolderTitle"> 
                <h2 class="fg-color-darken"></h2> 
            </div> 
        </div> 
        <div class="bp-modal-close cursor-pointer" onclick="closeThisModal('showfolder')"><h2 class="fg-color-darken">x</h2></div> 
    </div> 
    <div id="showFolderContent" class="bp-modal-content"> 
        <div id="folderContainerHolder"> 
            <div id="mainFolder"> 
                <div id="showOpenFoldersTrigger" class="cursor-pointer" onclick="dropOpenFolders()"> 
                    <div class="arrow-down"></div> 
                </div> 
                <div id="showCurrentFolder"><div id="chosenFolder" class="select-folder cursor-pointer"><h3></h3></div></div> 
            </div> 
            <div id="folderContainer"> 
                <div class="showOpenFoldersTitle"><h4>Choose folder destination:</h4></div> 
                <div id="main" data-fullname="Main" data-fullpath="main" class="select-folder folder-list cursor-pointer"><h4>  Main  </h4></div> 
            </div> 
        </div> 
        <div id="showFolderInformation"><h5>&bull; Single click to choose a folder <br/>&bull; Double click to open the folder.</h5></div> 
        <div id="showFolderButton"> 
            <button id="doAction" name="doAction" class="bg-color-blue fg-color-white"></button> 
        </div> 
    </div> 
    <div id="showOpenFolders"> 
    </div> 
</div>

<div id="deleteFilesHolder" class="bp-modal"> 
    <div class="bp-modal-header select-disable"> 
        <div class="bp-modal-title"> 
            <div id="deleteFilesTitle"> 
                <h2 class="fg-color-darken">Delete Files?</h2> 
            </div> 
        </div> 
        <div class="bp-modal-close cursor-pointer" onclick="closeThisModal('deletefiles')"><h2 class="fg-color-darken">x</h2></div> 
    </div> 
    <div id="deleteFilesContent" class="bp-modal-content"> 
        <div id="deleteFilesMessage"> 
            <h4>Are you sure you want to move your files to the Recover Bin?</h4> 
        </div> 
        <div id="deleteFilesButtons"> 
            <button id="deleteFiles" class="bg-color-red fg-color-white" onclick="deleteSelectedFiles()">Delete</button> 
            <button id="cancelDeleteFiles" onmousedown="closeThisModal('deletefiles')">Cancel</button> 
        </div> 
    </div> 
</div>

<div id="renameItemHolder" class="bp-modal"> 
        <div class="bp-modal-header select-disable">
            <div class="bp-modal-title"> 
                <div id="renameItemTitle"> 
                    <h2 class="fg-color-darken">Rename</h2> 
                </div> 
            </div> 
            <div class="bp-modal-close cursor-pointer" onclick="closeThisModal('renameitem')"><h2 class="fg-color-darken">x</h2></div> 
        </div> 
        <div id="renameItemContent" class="bp-modal-content"> 
                <div id="renameNameHolder"> 
                    <div class="input-control text"> 
                        <input type="text" name="renameItemName" id="renameItemName" class="bp-element" /> 
                        <div class="placeholderPlace"> 
                            <label for="renameItemName" class="placeholder" id="renameItemPlaceholder">Enter new name here...</label> 
                        </div> 
                    </div> 
                </div> 
                <div id="charsNotAllowed"> 
                    <h6 class="fg-color-red">&bull; Characters \\ / : ? < > | * " are not allowed.</h6> 
                </div> 
                <div id="renameItemButton"> 
                    <button type="submit" id="renameItem" class="bg-color-blueDark fg-color-white" onclick="return false;" onmousedown="renameItemNow()">Rename</button> 
                </div> 
        </div> 
    </div>

<div id="shareTypeHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="shareTypeTitle">
				<h2 class="fg-color-darken">Share Files</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('sharetype')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="shareTypeContent" class="bp-modal-content">
		<div id="groupShare" class="tile double center-div-h bg-color-blue" onclick="shareToGroup()">
			<div class="type-title center-div-v">
				<h3>Share to group</h3>
			</div>
		</div>
		<div id="shareLink" class="tile double center-div-h bg-color-pink" onclick="shareLinkFile()">
			<div class="type-title center-div-v">
				<h3>Get share link</h3>
			</div>
		</div>
	</div>
</div>

<div id="groupShareHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupShareTitle">
				<h2 class="fg-color-darken">Group Share</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('groupshare')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupShareContent" class="bp-modal-content">
		<div id="groupSharePlace">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div class="overview">
					
				</div>
			</div>
		</div>
	</div>
</div>

<div id="showImageHolder" class="bp-modal">
	<div class="bg-color-darken bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="showImageTitle">
				<h2 class="fg-color-white">User Image</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('showimage')"><h2 class="fg-color-white">x</h2></div>
	</div>
	<div id="showImageContent" class="bp-modal-content">
		<div id="imagePlace" align="center">
			<img src="" id="image" alt="" class="center-div-h center-div-v"/>
		</div>
		<div id="imageMenuHolder">
			<div id="imageNameHolder">
				<h4 id="imageName" class="fg-color-white"></h4>
			</div>
			<div id="imageDownload" class="image-menu-holder">
				<h5 id="imageDownload" class="fg-color-white image-menu">Download</h5>
			</div>
			<div id="nextImage" class="image-menu-holder">
				<h5 id="nextImageToggle" class="fg-color-white image-menu">Next ></h5>
			</div>
			<div id="previousImage" class="image-menu-holder">
				<h5 id="previousImageToggle" class="fg-color-white image-menu">< Previous</h5>
			</div>
			<div id="imageCountHolder" class="image-menu-holder">
				<h5 id="imageCount" class="fg-color-white image-menu"></h5>
			</div>
		</div>
	</div>
</div>

<div id="shareLinkHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="shareLinkTitle">
				<h2 class="fg-color-darken">Share links below</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('sharelink')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="shareLinkContent" class="bp-modal-content">
		<div id="shareUrlLabel">
			<h3>Share url:</h3>
		</div>
		<div id="shareUrlPlace">
			<div class="input-control text">
			    <input type="text" name="shareUrl" id="shareUrl" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="shareUrl" class="placeholder" id="shareUrlPlaceholder">Url goes here...</label>
				</div>
		    </div>
		</div>
		<div id="claimFileLabel">
			<h3>Claim File:</h3>
		</div>
		<div id="claimFilePlace">
			<div class="input-control text">
			    <input type="text" name="claimFile" id="claimFile" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="claimFile" class="placeholder" id="claimFilePlaceholder">Claim file code goes here...</label>
				</div>
		    </div>
		</div>
	</div>
</div>

<div id="backpackOrganizerHolder" class="bp-modal select-disable">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="backpackOrganizerTitle">
				<h2 class="fg-color-darken">Backpack organizer</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('backpackorganizer')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="backpackOrganizerContent" class="bp-modal-content">
		<div class="scrollbar" >
			<div class="track" >
				<div class="thumb">
					<div class="end"></div>
				</div>
			</div>
		</div>
		<div class="viewport">
			<div class="overview">
				<div id="suggestionTitleHolder">
					<div id="suggestionTitle">
						<h4>Would you like to move these files to these folder(s)?</h4>
					</div>
				</div>
				<div id="suggestionPlace">

				</div>
				<!-- <div id="class_0" class="classification">
					<div class="class-head">
						<div class="class-folder-holder">
							<input type="text" name="folderName" id="folderName" class="bp-element organize-folder" />
						    <div class="placeholderPlace">
							    <label for="folderName" class="placeholder" id="folderNamePlaceholder">Folder name...</label>
							</div>
						</div>
						<div class="organize-accept">
							<button id="accept_0" class="organize-accept-button bg-color-blue fg-color-white">Accept</button>
						</div>
						<div class="organize-deny">
							<button id="deny_0" class="organize-deny-button bg-color-red fg-color-white">Deny</button>
						</div>
					</div>
					<div class="class-files">
						<div id="file_0_0" data-filename="Hello.txt" class="organize-file">
							<div class="remove-organize-file bg-color-red fg-color-white">x</div>
							<div class="organize-file-name">
								<h5>Hello.txt</h5>
							</div>
						</div>
					</div>
				</div> -->
				
			</div>
		</div>
	</div>
</div>