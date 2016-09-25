<?php echo $this->Html->css('bp-s-in-1'); 
	  echo $this->Html->script('backpack_js/bp-js-settings', false);
	  echo $this->Html->script('backpack_js/bp-js-u', false); ?>

<script type="text/javascript">
	var loader_url         = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var retrieve_url       = '<?php echo $this->Html->url("/retrieve"); ?>';
	var activity_url       = '<?php echo $this->Html->url("/get_activity"); ?>';
	var changebottom_url   = '<?php echo $this->Html->url("/change_bottom"); ?>';
	var org_status         = '<?php echo $org_status; ?>';
	var ovr_status         = '<?php echo $ovr_status; ?>';
	var changeorg_url      = '<?php echo $this->Html->url("/change_organizer"); ?>';
	var changeovr_url      = '<?php echo $this->Html->url("/change_overwrite"); ?>';
</script>

<?php if(isset($imageerror)) { ?>
	<script type="text/javascript">
		$(document).ready(function(){
			showToast('<?php echo $imageerror; ?>');
		});
	</script>
<?php } ?>

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
		<div id="myActivities" class="cursor-pointer notification">
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
		
	</div>
</div>

<div class="ajax-message bg-color-blue"><h5 id="ajaxMessage" class="fg-color-white"></h5></div>

<div id="rightPanelHolder">
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
			<img src="<?php echo $imagepath; ?>" width="50" height="50" />
		</div>
		<div id="optionTrigger" class="cursor-pointer" onclick="dropOptions()">
			<div id="optionArrow" class="arrow-down"></div>
		</div>
	</div>
</div>

<div id="userOptionHolder" class="select-disable">
	<div id="userOptionDrop">
		<div id="pocketTitle">
			<div class="option-title"><h5>Move to:</h5></div>
		</div>
		<div id="userPockets">
			<div id="topPocket" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/top'); ?>'"><h5>Top Pocket</h5></div>
			<div id="mainPocket" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/main'); ?>'"><h5>Main Pocket</h5></div>
			<div id="bottomPocket" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/bottom'); ?>'"><h5>Bottom Pocket</h5></div>
			<div id="sidePocket" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/side'); ?>'"><h5>Side Pocket</h5></div>
			<div id="extraPocket" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url('/extra'); ?>'"><h5>Extra Pocket</h5></div>
		</div>
		<div id="userSettings">
			<div id="accountSettings" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url("/account_settings"); ?>'"><h5>Account Settings</h5></div>
			<div id="backpackSettings" class="user-options cursor-pointer" onclick="window.location = '<?php echo $this->Html->url("/backpack_settings"); ?>'"><h5>Backpack Settings</h5></div>
		</div>
		<div id="logoutHolder">
			<div id="userLogout" class="user-options cursor-pointer select-disable" onclick="window.location = '<?php echo $this->Html->url('/log_out'); ?>'"><h5>Log out</h5></div>
		</div>
		<div id="userSpace">
			<div id="totalSpace" class="user-space select-disable"><h5>Total Space: 0</h5></div>
			<div id="spaceUsed" class="user-space select-disable"><h5>Space Used: 0</h5></div>
			<div id="progressHolder">
				<div class="progress-bar select-disable" id="spaceProgress" >
			    	<div id="percentage" class="bar bg-color-green" style="width: 0%"></div>
			    </div>
			</div>
		</div>
	</div>
</div>

<div id="modalBackground"></div>

<div id="settingsHolder" class="tile-group">
	<div id="settingsTitle">
		<h2>Backpack Settings</h2>
	</div>
</div>

<div id="backpackSettingsHolder">
	<div id="bottomLockcodeHolder" class="tile triple bg-color-blue">
		<div class="tile-title">
			<h2>Bottom Lockcode</h2>
		</div>
		<div class="tile-details">
			<p>To make your backpack more secured, you change your default bottom lockcode. (Your default bottom lockcode is your backpack lockcode.)</p>
		</div>
		<div class="tile-button">
			<button id="bottomLockcode" class="bg-color-blueDark">Change</button>
		</div>
	</div>
	<div id="organizerExplanationHolder" class="tile double bg-color-pink">
		<div class="tile-title">
			<h2>Organizer</h2>
		</div>
		<div class="tile-details">
			<p>This feature will allow the backpack to suggest the best file management for different backpack user.</p>
		</div>
	</div>
	<div id="organizerStatusHolder" class="tile bg-color-purple">
		<div class="tile-title">
			<h3>Organizer is</h3>
		</div>
		<div id="organizerStatusPlace" class="center-div-h center-div-v">
			<label class="input-control switch">
				<input type="checkbox" id="organizerCheck" <?php if($organizer == true){ ?> checked="checked" <?php } ?> />
				<span id="orgStatus" class="helper"><?php echo $org_status; ?></span>
			</label>
		</div>
	</div>
	<div id="ownerImageHolder" class="tile bg-color-greenDark">
		<div id="ownerImagePlace">
			<img src="<?php echo $imagepath; ?>" id="ownerImage" />
		</div>
	</div>
	<div id="changeOwnerImageHolder" class="tile double bg-color-blueLight">
		<div class="tile-title">
			<h2>User Avatar</h2>
		</div>
		<div class="tile-details">
			<p>You can change your avatar here.</p>
		</div>
		<div class="tile-button">
			<button id="changeAvatar" class="bg-color-greenDark">Change Avatar</button>
		</div>
	</div>
</div>

<div id="backpackSettingsHolder2">
	<div id="fileOverwriteHolder" class="tile triple bg-color-green">
		<div class="tile-title">
			<h2>File Overwrite</h2>
		</div>
		<div class="tile-details">
			<p>If this option is on, all files uploaded, moved and copied which are already existing in the directory where it will be placed will be overwritten. If off, the files will be renamed with a suffix.</p>
		</div>
	</div>
	<div id="fileOverwriteStatusHolder" class="tile bg-color-blueDark">
		<div class="tile-title">
			<h3>Overwrite is</h3>
		</div>
		<div id="overwriteStatusPlace" class="center-div-h center-div-v">
			<label class="input-control switch">
				<input type="checkbox" id="overwriteCheck" <?php if($overwrite == true){ ?> checked="checked" <?php } ?> />
				<span id="ovrStatus" class="helper"><?php echo $ovr_status; ?></span>
			</label>
		</div>
	</div>
</div>

<div id="changeBottomLockcodeHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeBottomLockcodeTitle">
				<h2 class="fg-color-darken">Change Bottom Lockcode</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changebottomlockcode')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeBottomLockcodeContent" class="bp-modal-content">
		<div id="oldBottomLockcodeHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="password" name="oldBottomLockcode" id="oldBottomLockcode" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="oldBottomLockcode" class="placeholder" >Old Bottom Lockcode</label>
				</div>
		    </div>
		</div>
		<div id="newBottomLockcodeHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="password" name="newBottomLockcode" id="newBottomLockcode" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="newBottomLockcode" class="placeholder" >New Bottom Lockcode</label>
				</div>
		    </div>
		</div>
		<div id="buttonChangeBottomLockcodeHolder">
			<button id="buttonChangeBottomLockcode" class="bg-color-blueDark fg-color-white">Change</button>
		</div>
	</div>
</div>

<div id="changeOrganizerHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeOrganizerTitle">
				<h2 class="fg-color-darken">Change Organizer Status</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changeorganizer')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeOrgnizerContent" class="bp-modal-content">
		<div id="organizerTitle">
			
		</div>
		<div id="organizerButtonHolder">
			<button id="organizerButton" class="bg-color-green fg-color-white"></button>
		</div>
	</div>
</div>

<div id="changeOverwriteHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeOverwriteTitle">
				<h2 class="fg-color-darken">Change Overwrite Status</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changeoverwrite')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeOrgnizerContent" class="bp-modal-content">
		<div id="overwriteTitle">
			
		</div>
		<div id="overwriteButtonHolder">
			<button id="overwriteButton" class="bg-color-green fg-color-white"></button>
		</div>
	</div>
</div>

<div id="changeAvatarHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeAvatarTitle">
				<h2 class="fg-color-darken">Change Avatar</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changeavatar')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeAvatarContent" class="bp-modal-content">
		<form method="POST" action="./backpack_settings" enctype="multipart/form-data">
			<div id="getImageHolder" class="center-div-v">
				<input type="file" name="useravatar" id="useravatar" />
			</div>
			<div id="uploadImageButton">
				<input type="submit" id="uploadImage" class="bg-color-red fg-color-white" value="Upload Image" />
			</div>
		</form>
	</div>
</div>