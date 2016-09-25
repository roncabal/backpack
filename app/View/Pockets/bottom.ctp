<?php echo $this->Html->css('bp-s-in');
	  echo $this->Html->script('backpack_js/bp-js-u', false); 
	  echo $this->Html->script('backpack_js/bp-js-bottom', false);
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
	var loader_url      = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var delete_url      = '<?php echo $this->Html->url("/delete_items"); ?>';
	var rename_url      = '<?php echo $this->Html->url("/rename_item"); ?>';
	var activity_url    = '<?php echo $this->Html->url("/get_activity"); ?>';
	var open_url        = '<?php echo $this->Html->url("/open_item"); ?>';
	var download_url    = '<?php echo $this->Html->url("/downloads/download_file/"); ?>';
	var full_path       = '<?php echo $full_path; ?>';
	var retrieve_url    = '<?php echo $this->Html->url("/retrieve_bottom"); ?>';
	var check_url       = '<?php echo $this->Html->url("/check_bottom"); ?>';

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
            type:"POST",
            data:{"pocket":full_path},
            dataType:"json",
            success: function(data) {
            	if(data.status == "success")
            	{
            		var items = '';
            		var loop = 1;
            		$("#pocket").css({"width" : data.pocket + 'px'});
            		$("#pocketHolder").css({"width" : data.pocket_holder + 'px'});
            		for(var i=0;i<data.files.length;i++)
            		{
            			if(loop == 1)
            			{
            				items +='<div class="file-place">';
            			}

            			items +='<div id="'+ data.files[i].file_id +'" data-fullname = "' + data.files[i].full_name + '" data-itemtype="'+ data.files[i].file_type +'" data-url="'+ data.files[i].file_url +'" class="tile select-item item bg-color-'+ data.files[i].color_code +'" data-role="tile-slider"><div class="item-name"><h3><?php echo htmlspecialchars("'+ data.files[i].file_name +'"); ?></h3></div><div class="item-size" ><h5>'+ data.files[i].file_size +'</h5></div><div class="item-type"><h5>'+ ucFirst(data.files[i].file_type) +'</h5></div><div class="item-date"><h6>'+ data.files[i].date_modified +'</h6></div></div>'; 

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

            		$("#pocketPlaceHolder").html(items);
        			$("#backFolder").removeClass("select-item");
            		itemLive();
	                $('#spotlightPlace').tinyscrollbar();
	                unselectItems();	                
            	}
            	else if(data.status == "error")
            	{
            		if(data.error_code == 0)
            		{
            			$("#pocketPlaceHolder").html('<div id="emptyMain"><h2>' + data.error_msg + '</h2></div>');
		                unselectItems();
            		}
            		
            	}
            	$("#pocketPlaceHolder .divLoad").remove();
            }
        });
	}
</script>

<div id="enterBottomLockcodeHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="enterBottomLockcodeIcon">
				<?php echo $this->Html->image('icons/bottompocket_darken.gif', array('width'=>50, 'height'=>50)); ?>
			</div>
			<div id="enterBottomLockcodeTitle">
				<h2 class="fg-color-darken">Bottom Password</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('bottomlockcode')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="enterBottomLockcodeContent" class="bp-modal-content">
		<div id="bottomLockcodeQuestion">
			<h4>To gain access to the bottom files, please enter your bottom lockcode.</h4>
		</div>
		<div id="bottomLockcodeForm">
			<div id="bottomLockcodeHolder">
				<div class="input-control text">
				    <input type="password" name="bottomLockcode" id="bottomLockcode" class="bp-element" />
				    <div class="placeholderPlace">
					    <label for="bottomLockcode" class="placeholder" id="bottomLockcodeNamePlaceholder">Bottom lockcode...</label>
					</div>
			    </div>
			</div>
			<button id="bottomLockcodeSubmit" name="bottomLockcodeSubmit" class="bg-color-pink fg-color-white">Open</button>
			<button id="bottomLockcodeCancel" name="bottomLockcodeCancel" class="bg-color-red fg-color-white" onclick="window.location='<?php echo $this->Html->url('/main'); ?>'">Cancel</button>
		</div>
	</div>
</div>


<div id="subMenuHolder2" class="bg-color-blueDark">
	<div id="subMain" class="bg-color-blueDark cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/main'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/mainpocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white">Main Pocket</h2></div>			
	</div>
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

</div>


<div id="hoverHelper"><h5 id="hoverHelperTitle"></h5></div>

<div id="leftPanelHolder" class="bg-color-pink">
	<div id="userNotification">
		<div id="myMessages" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url("/extra"); ?>'">
			<?php echo $this->Html->image('icons/messages.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="messageHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-pink">
					<div class="menu-arrow" style="border-right: 20px solid #9f00a7;"></div>
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
				<div class="menu-title bg-color-pink">
					<div class="menu-arrow" style="border-right: 20px solid #9f00a7;"></div>
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
				<div class="menu-title bg-color-pink">
					<div class="menu-arrow" style="border-right: 20px solid #9f00a7;"></div>
					Activities
				</div>
			</div>
		</div>
	</div>
	<div id="separator"></div>
	<div id="userMenu">
		<div id="uploadFile" class="cursor-pointer" onclick="openFileUpload()">
			<?php echo $this->Html->image('icons/upload.gif', array('width'=>'40px', 'height'=>'40px', )); ?>
			<div id="uploadHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-pink">
					<div class="menu-arrow" style="border-right: 20px solid #9f00a7;"></div>
					Upload
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
		<li><a href="#" onclick="return false;">Share</a></li>
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

<div id="fileOptions" class="select-disable bg-color-pink">
	<a href="#" onclick="return false;" onmousedown="openItem()">
		<div id="openFile" class="file-option">
			<div class="file-image-action">
				<?php echo $this->Html->image('icons/open.gif', array('width'=>30, 'height'=>30)); ?>
			</div>
			<div class="file-action">
				<h4 class="fg-color-white">Open</h4>
			</div>
		</div>
	</a>
	<a href="#" onclick="return false;" onmousedown="downloadNow()">
		<div id="downloadFile" class="file-option">
			<div class="file-image-action">
				<?php echo $this->Html->image('icons/download.gif', array('width'=>30, 'height'=>30)); ?>
			</div>
			<div class="file-action">
				<h4 class="fg-color-white">Download</h4>
			</div>
		</div>
	</a>
	<a href="#"  onclick="return false;" onmousedown="renameItemSelected()">
		<div id="renameFile" class="file-option">
			<div class="file-image-action">
				<?php echo $this->Html->image('icons/rename.gif', array('width'=>30, 'height'=>30)); ?>
			</div>
			<div class="file-action">
				<h4 class="fg-color-white">Rename</h4>
			</div>
		</div>
	</a>
	<a href="#" onclick="return false;" onmousedown="confirmFileDelete()">
		<div id="deleteFile" class="file-option">
			<div class="file-image-action">
				<?php echo $this->Html->image('icons/delete.gif', array('width'=>30, 'height'=>30)); ?>
			</div>
			<div class="file-action">
				<h4 class="fg-color-white">Delete</h4>
			</div>
		</div>
	</a>
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
			    	<div id="percentage" class="bar bg-color-green" style="width: 0%"></div>
			    </div>
			</div>
		</div>
	</div>
</div>

<div id="modalBackground"></div>

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
	<div id="pocket" style="left:100px;">
		<div id="menuHolder" class="bg-color-pink">
				<div id="pocketNameHolder" class="cursor-pointer" onclick="subMenu()">
					<div id="pocketNamePlace">
						<?php echo $this->Html->image('icons/bottompocket.gif', array('width'=>55,'height'=>55)); ?>
						<div id="pocketName">
							<div id="currentFolder">
								<h2 class="fg-color-white">Bottom Pocket</h2>
							</div>
						</div>
					</div>
				</div>
		</div>

		<div id="pocketPlaceHolder">

		</div>
	</div>
</div>

<div id="renameItemHolder" class="bp-modal">
    <div class="bp-modal-header select-disable">
        <a href="#">
            <div class="bp-modal-title">
                <div id="renameItemTitle">
                    <h2 class="fg-color-darken">Rename</h2>
                </div>
            </div>
        </a> 
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

<div id="deleteFilesHolder" class="bp-modal"> 
    <div class="bp-modal-header select-disable"> 
        <a href="#" onclick="return false;"> 
            <div class="bp-modal-title"> 
                <div id="deleteFilesTitle"> 
                    <h2 class="fg-color-darken">Delete Files?</h2> 
                </div> 
            </div> 
        </a> 
        <div class="bp-modal-close cursor-pointer" onclick="closeThisModal('deletefiles')"><h2 class="fg-color-darken">x</h2></div> 
    </div> 
    <div id="deleteFilesContent" class="bp-modal-content"> 
        <div id="deleteFilesMessage"> 
            <h4>Are you sure you want to move your files to the Recover Bin?</h4> 
        </div> 
        <div id="deleteFilesButtons"> 
            <button id="deleteFiles" class="bg-color-red fg-color-white" onclick="deleteSelectedFiles()">Delete</button> 
            <button id="cancelDeleteFiles" onmousedown="closeThisModal()">Cancel</button> 
        </div> 
    </div> 
</div>