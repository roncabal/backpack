<?php echo $this->Html->css('bp-s-in-1'); 
	  echo $this->Html->script('backpack_js/bp-js-u', false);
	  echo $this->Html->script('backpack_js/bp-js-settings', false); ?>

<script type="text/javascript">
	var changename_url     = '<?php echo $this->Html->url("/change_name"); ?>';
	var changelockcode_url = '<?php echo $this->Html->url("/change_lockcode"); ?>';
	var loader_url         = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var retrieve_url       = '<?php echo $this->Html->url("/retrieve"); ?>';
	var activity_url    = '<?php echo $this->Html->url("/get_activity"); ?>';
</script>

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

<div id="accountSettingsTitleHolder" class="tile-group">
	<div id="accountSettingsTitle">
		<h2>Account Settings</h2>
	</div>
</div>

<div id="accountSettingsHolder" class="tile-group">
	<div id="nameTagHolder" class="account-settings tile bg-color-blueDark">
		<div class="account-settings-inside center-div-v center-div-h">
			<h3>Name Tag : <?php echo $nametag; ?><h3>
			<!-- <div class="account-settings-button">
				<button class="settings-button bg-color-blueDark">Change</button>
			</div> -->
		</div>
	</div>
	<div id="nameHolder" class="account-settings tile bg-color-greenDark">
		<div class="account-settings-inside center-div-v center-div-h">
			<h3 id="userName">Name : <?php echo $name; ?><h3>
				<div class="account-settings-button">
					<button class="settings-button bg-color-greenDark" onclick="showChangeName()">Change</button>
				</div>
		</div>
	</div>
	<div id="lockcodeHolder" class="account-settings tile bg-color-purple">
		<div class="account-settings-inside center-div-v center-div-h">
			<h3>Lockcode<h3>
				<div class="account-settings-button">
					<button class="settings-button bg-color-purple" onclick="showChangeLockcode()">Change</button>
				</div>
		</div>
	</div>
	<div id="emailHolder" class="account-settings tile bg-color-pink">
		<div class="account-settings-inside center-div-v center-div-h">
			<h3>Email : <?php echo $email; ?><h3>
				<div class="account-settings-button">
					<button class="settings-button bg-color-pink">Change</button>
				</div>
		</div>
	</div>
</div>

<div id="modalBackground"></div>

<div id="changeNameHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeNameTitle">
				<h2 class="fg-color-darken">Change Name</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changename')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeNameContent" class="bp-modal-content">
		<div id="firstNameHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="text" name="firstName" id="firstName" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="firstName" class="placeholder" >First Name</label>
				</div>
		    </div>
		</div>
		<div id="lastNameHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="text" name="lastName" id="lastName" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="lastName" class="placeholder" >Last Name</label>
				</div>
		    </div>
		</div>
		<div id="buttonChangeHolder" class="center-div-h">
			<button id="buttonChange" class="bg-color-blueDark fg-color-white" onclick="changeNameNow()">Change</button>
		</div>
	</div>
</div>

<div id="changeLockcodeHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="changeLockcodeTitle">
				<h2 class="fg-color-darken">Change Lockcode</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('changelockcode')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="changeLockcodeContent" class="bp-modal-content">
		<div id="oldLockcodeHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="password" name="oldLockcode" id="oldLockcode" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="oldLockcode" class="placeholder" >Old Lockcode</label>
				</div>
		    </div>
		</div>
		<div id="newLockcodeHolder" class="center-div-h">
			<div class="input-control text">
			    <input type="password" name="newLockcode" id="newLockcode" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="newLockcode" class="placeholder" >New Lockcode</label>
				</div>
		    </div>
		</div>
		<div id="buttonChangeLockcodeHolder" class="center-div-h">
			<button id="buttonChangeLockcode" class="bg-color-blueDark fg-color-white" onclick="changeLockcodeNow()">Change</button>
		</div>
	</div>
</div>