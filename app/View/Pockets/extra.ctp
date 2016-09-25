<?php echo $this->Html->css('bp-s-in');
	  echo $this->Html->script('backpack_js/bp-js-u', false); 
	  echo $this->Html->script('backpack_js/bp-js-extra', false); 

?>

<script type="text/javascript">
	var loader_url      = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var activity_url    = '<?php echo $this->Html->url("/get_activity"); ?>';
	var userspace_url   = '<?php echo $this->Html->url("/user_space"); ?>';
	var retrieve_url    = '<?php echo $this->Html->url("/retrieve_extra"); ?>';
	var getmsg_url      = '<?php echo $this->Html->url("/get_chat"); ?>';
	var sendmsg_url     = '<?php echo $this->Html->url("/send_message"); ?>';
</script>


<div id="subMenuHolder" class="bg-color-blueDark">

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
	
	<div id="subBottom" class="bg-color-purple cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/bottom'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/bottompocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white">Bottom Pocket</h2></div>			
	</div>
</div>


<div id="leftPanelHolder" class="bg-color-greenDark">
	<div id="userNotification">
		<div id="myMessages" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url("/extra"); ?>'">
			<?php echo $this->Html->image('icons/messages.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="messageHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-greenDark">
					<div class="menu-arrow" style="border-right: 20px solid #1e7145;"></div>
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
				<div class="menu-title bg-color-greenDark">
					<div class="menu-arrow" style="border-right: 20px solid #1e7145;"></div>
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
				<div class="menu-title bg-color-greenDark">
					<div class="menu-arrow" style="border-right: 20px solid #1e7145;"></div>
					Activities
				</div>
			</div>
		</div>
	</div>
	<div id="separator"></div>
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

<div id="pocketHolder">
	<div id="leftSideHolder">
		<div id="leftSideTitleHolder" class="bg-color-pink">
			<div id="leftSideTitle">
				<h2 class="fg-color-white">Bag Mates</h2>
			</div>
		</div>
		<div id="searchBagmateHolder">
			<div id="searchBagmate">
				<div class="input-control text">
				    <input type="text" name="searchBagmates" id="searchBagmates" class="bp-element" />
				    <div class="placeholderPlace">
					    <label for="searchBagmates" class="placeholder" id="searchBagmatesPlaceholder">Search Bagmate...</label>
					</div>
			    </div>
			</div>
		</div>
		<div id="bagmatesPlace">
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
	<div id="pocket">
		<div id="menuHolder" class="bg-color-greenDark">
			<div id="pocketNameHolder" class="cursor-pointer" onclick="subMenu()">
				<div id="pocketNamePlace">
					<?php echo $this->Html->image('icons/extrapocket.gif', array('width'=>55,'height'=>55)); ?>
					<div id="pocketName">
						<div id="currentFolder">
							<h2 class="fg-color-white">Extra Pocket</h2>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="pocketPlaceHolder">
			<div id="noSelectedBagmate" class="center-div-v">
				<h2>No bagmate is selected.</h2>
			</div>
			<div id="chatBagmateHolder">
				<div id="bagmateInfoHolder">
					<img src="" width="130" height="130" id="bagmateImage" />
					<div id="bagmateInfoPlace">
						<p id="bagmateName"></p><br/>
						<h5 id="bagmateStatus"></h5>
					</div>
				</div>
				<div id="chatHolder" class="center-div-h">
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
				<div id="userChatHolder" class="center-div-h">
					<textarea id="userChat" name="userChat" class="bp-element"></textarea>
					<div class="placeholderPlace">
					    <label for="userChat" class="placeholder" >Type your message here...</label>
					</div>
				</div>
				<div id="userInfoHolder">
					<img src="" width="130" height="130" id="userImage" />
					<div id="userInfoPlace">
						<p id="userName"><?php echo $this->Session->read('User.name_tag'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>