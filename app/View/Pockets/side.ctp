<?php echo $this->Html->css('bp-s-in');
	  echo $this->Html->script('backpack_js/bp-js-u', false); 
	  echo $this->Html->script('backpack_js/bp-js-side', false); 

?>

<script type="text/javascript">
	var loader_url        = '<?php echo $this->Html->image("backpack/loading.gif"); ?>';
	var activity_url      = '<?php echo $this->Html->url("/get_activity"); ?>';
	var userspace_url     = '<?php echo $this->Html->url("/user_space"); ?>';
	var creategroup_url   = '<?php echo $this->Html->url("/create_group"); ?>';
	var retrieve_url      = '<?php echo $this->Html->url("/retrieve_side"); ?>';
	var opengroup_url     = '<?php echo $this->Html->url("/open_group"); ?>';
	var image_url         = '<?php echo $this->Html->url("/img/"); ?>';
	var nametag           = '<?php echo $this->Session->read("User.name_tag"); ?>';
	var id                = '<?php echo $this->Session->read("User.uid"); ?>';
	var groupmsg_url      = '<?php echo $this->Html->url("/group_message"); ?>';
	var joingroup_url     = '<?php echo $this->Html->url("/join_group"); ?>';
	var groupinfo_url     = '<?php echo $this->Html->url("/group_info"); ?>';
	var grouprequests_url = '<?php echo $this->Html->url("/group_requests"); ?>';
	var groupretrieve_url = '<?php echo $this->Html->url("/group_retrieve"); ?>';
	var acceptreq_url     = '<?php echo $this->Html->url("/accept_request"); ?>';
	var groupmembers_url  = '<?php echo $this->Html->url("/group_members"); ?>';
	var leavegroup_url    = '<?php echo $this->Html->url("/leave_group"); ?>';
	var addmember_url     = '<?php echo $this->Html->url("/add_member"); ?>';
	var denyrequest_url   = '<?php echo $this->Html->url("/deny_request"); ?>';
	var unsharefile_url   = '<?php echo $this->Html->url("/unshare_file"); ?>';
	var download_url      = '<?php echo $this->Html->url("/downloads/groups/"); ?>';
</script>

<div class="ajax-message bg-color-blue"><h5 id="ajaxMessage" class="fg-color-white"></h5></div>


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
	<div id="subExtra" class="bg-color-green cursor-pointer" onclick="window.location='<?php echo $this->Html->url('/extra'); ?>'">
		<div class="sub-menu-image">
			<?php echo $this->Html->image('icons/extrapocket.gif', array('width'=>'55', 'height'=>'55')); ?>
		</div>
		<div id="subDesc"><h2 class="fg-color-white ">Extra Pocket</h2></div>
	</div>

</div>

<div id="hoverHelper"><h5 id="hoverHelperTitle"></h5></div>

<div id="bodyContext" class="bg-color-darken">
	<ul class="dropdown-menu open" id="contextMenu">
		<li><a href="#" onclick="return false;" onmousedown="createGroup()">Create Group</a></li>
		<li><a href="#" onclick="return false;" onmousedown="joinGroup()">Join Group</a></li>
		<li><a href="#" onclick="return false;" onmousedown="getGroupRequests()">Group Requests</a></li>
		<li class="divider"></li>
		<li><a href="#" onclick="return false;" onmousedown="openGroupInfo()">Group Information</a></li>
		<li><a href="#" onclick="return false;" onmousedown="getGroupMembers()">Group Members</a></li>
		<li><a href="#" onclick="return false;" onmousedown="showAddMember()">Add Member</a></li>
		<li><a href="#" onclick="return false;" onmousedown="leaveCheck()">Leave Group</a></li>
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

<div id="leftPanelHolder" class="bg-color-purple">
	<div id="userNotification">
		<div id="myMessages" class="cursor-pointer notification" onclick="window.location='<?php echo $this->Html->url("/extra"); ?>'">
			<?php echo $this->Html->image('icons/messages.gif', array('width'=>'40px', 'height'=>'40px')); ?>
			<div id="messageHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
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
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
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
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
					Activities
				</div>
			</div>
		</div>
	</div>
	<div id="separator"></div>
	<div id="userMenu">
		<div id="createGroup" class="cursor-pointer notification" onclick="createGroup()">
			<?php echo $this->Html->image('icons/creategroup.gif', array('width'=>'40px', 'height'=>'40px', )); ?>
			<div id="createGroupHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
					Create Group
				</div>
			</div>
		</div>
		<div id="joinGroup" class="cursor-pointer notification" onclick="joinGroup()">
			<?php echo $this->Html->image('icons/joingroup.gif', array('width'=>'40px', 'height'=>'40px', )); ?>
			<div id="joinHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
					Join Group
				</div>
			</div>
		</div>
		<div id="groupRequests" class="cursor-pointer notification" onclick="getGroupRequests()">
			<?php echo $this->Html->image('icons/requests.gif', array('width'=>'40px', 'height'=>'40px', )); ?>
			<div id="groupRequestsHelper" class="menu-helper fg-color-white">
				<div class="menu-title bg-color-purple">
					<div class="menu-arrow" style="border-right: 20px solid #603cba;"></div>
					Group Requests
				</div>
			</div>
			<div id="groupRequestsNotification" class="user-noti bg-color-red">
				<h5 class="fg-color-white"></h5>
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
		<div id="leftSideTitleHolder" class="bg-color-blue">
			<div id="leftSideTitle">
				<h2 class="fg-color-white">My Groups</h2>
			</div>
		</div>
		<div id="searchGroupHolder">
			<div id="searchGroup">
				<div class="input-control text">
				    <input type="text" name="searchGroups" id="searchGroups" class="bp-element" />
				    <div class="placeholderPlace">
					    <label for="searchGroups" class="placeholder" id="searchGroupsPlaceholder">Search Group...</label>
					</div>
			    </div>
			</div>
		</div>
		<div id="groupPlace">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div class="overview">
					<div id="groupsPlace">

						<!-- <div class="user-groups">
							<div class="group-name">
								<h4>Sample</h4>
							</div>
							<div class="messages-holder">
								<div class="group-messages">
									<?php echo $this->Html->image('icons/messages_darken.gif', array("width"=>25, "height"=>25)); ?>
								</div>
								<div class="messages-notification-holder">
									<div class="messages-notification bg-color-red">
										<h6 class="fg-color-white">1<h6>
									</div>
								</div>
							</div>
							<div class="new-share-holder">
								<div class="new-share-icon">
									<?php echo $this->Html->image('icons/share_darken.gif', array("width"=>25, "height"=>25)); ?>
								</div>
								<div class="new-share-notification-holder">
									<div class="new-share-notification bg-color-red">
										<h6 class="fg-color-white">1</h6>
									</div>
								</div>
							</div>
						</div> -->

					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="pocket">
		<div id="noGroupSelected">
			<h2>No Group Selected</h2>
		</div>
		<div id="menuHolder" class="bg-color-purple">
			<div id="pocketNameHolder" class="cursor-pointer" onclick="subMenu()">
				<div id="pocketNamePlace">
					<?php echo $this->Html->image('icons/sidepocket.gif', array('width'=>55,'height'=>55)); ?>
					<div id="pocketName">
						<div id="currentFolder">
							<h2 class="fg-color-white">Side Pocket</h2>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="pocketPlaceHolder">
			<div id="groupInformationAndChat">
				<div id="groupOwnerPlace">
					<div id="groupOwner">
						<h5 class="fg-color-darken">No Group Selected</h5>
					</div>
					<div id="groupSettingsTrigger" class="cursor-pointer">
						<?php echo $this->Html->image('icons/settings_darken.gif', array('width'=>20, 'height'=>20)); ?>
					</div>
				</div>
				<div id="groupChatHolderPlace" class="bg-color-white">
					<div class="scrollbar">
						<div class="track">
							<div class="thumb">
								<div class="end"></div>
							</div>
						</div>
					</div>
					<div class="viewport">
						<div class="overview">
							<div id="groupChatHolder">
								<!-- <div class="group-replies">
							    	<div class="sender">
							    		<h5 class="fg-color-darken">SomeoneElse</h5>
							    	</div>
							    	<div class="send-time">
							    		<h6 class="fg-color-darken">22 seconds ago</h6>
							    	</div>
							    	<p class="fg-color-darken">alskdjflkasjdlkfjaslkdfal;skjdfa;jslkdfjas;lkdmvnskjvnlkjsnfdlkjmnvaljksnda;slkdfja;slkdfj;lk</p>
							    </div>
							     <div class="group-replies">
							    	<div class="sender">
							    		<h5 class="fg-color-darken">RonCabal</h5>
							    	</div>
							    	<div class="send-time">
							    		<h6 class="fg-color-darken">15 seconds ago</h6>
							    	</div>
							    	<p class="fg-color-darken">Hello! My name is Ron Cabal. I am a backpack user since 2011. This is a very cool application!</p>
							    </div> -->
							</div>
						</div>
					</div>
				</div>
				<div id="userGroupMessageHolder">
					<div id="groupMessageHolder">
						<textarea id="groupMessage" name="groupMessage" class="bp-element"></textarea>
						<div class="placeholderPlace">
						    <label for="groupMessage" class="placeholder" id="groupMessagePlaceholder">Write message here...</label>
						</div>
					</div>
					<div id="sendGroupMessageHolder">
						<button type="submit" id="sendGroupMessage" name="sendGroupMessage" class="bg-color-blue fg-color-white" onclick="sendGroupMessage()">Send</button>
					</div>
				</div>
			</div>
			<div id="sharedFilePlace">
				<!-- <div class="shared-files-holder">
					<div class="shared-file tile double bg-color-orange">
						<div class="tile-content">
							<div class="shared-file-name">
								<h3>April 17, 2011</h3>
							</div>
							<div class="shared-file-size">
								<h5>5MB</h5>
							</div>
							<div class="shared-file-type">
								<h5>Docx</h5>
							</div>
							<div class="shared-file-date">
								<h5>Shared on : Jan. 1, 2013</h5>
							</div>
						</div>
					</div>

				</div> -->
			</div>
			
		</div>
	</div>
</div>

<div id="createGroupHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="createGroupTitle">
				<h2 class="fg-color-darken">Create Group</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('creategroup')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="createGroupContent" class="bp-modal-content">
		<div id="createGroupPlace">
			<div class="input-control text">
			    <input type="text" name="addGroup" id="addGroup" class="bp-element" maxlength="255"/>
			    <div class="placeholderPlace">
				    <label for="addGroup" class="placeholder" id="addGroupPlaceholder">Group name...</label>
				</div>
		    </div>
		</div>
		<div id="submitGroupButton">
			<button id="submitGroup" name="submitGroup" class="bg-color-purple fg-color-white" onclick="createGroupNow()">Create</button>
		</div>
		<div id="cancelAddGroupButton">
			<button id="cancelGroup" name="cancelGroup" onclick="closeThisModal('creategroup')">Cancel</button>
		</div>
	</div>
</div>

<div id="joinGroupHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="joinGroupTitle">
				<h2 class="fg-color-darken">Join Group</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('joingroup')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="joinGroupContent" class="bp-modal-content">
		<div id="ownerNametagHolder">
			<div class="input-control text">
			    <input type="text" name="ownerNametag" id="ownerNametag" class="bp-element"/>
			    <div class="placeholderPlace">
				    <label for="ownerNametag" class="placeholder" id="ownerNametagPlaceholder">Owner Name Tag or Email...</label>
				</div>
		    </div>
		</div>
		<div id="groupZiplockHolder">
			<div class="input-control text">
			    <input type="text" name="groupZiplock" id="groupZiplock" class="bp-element"/>
			    <div class="placeholderPlace">
				    <label for="groupZiplock" class="placeholder" id="groupZiplockPlaceholder">Group Ziplock...</label>
				</div>
		    </div>
		</div>
		<div id="joinButtonHolder">
			<button id="joinButton" name="joinButton" class="bg-color-purple fg-color-white" onclick="joinGroup()">Join Group</button>
		</div>
	</div>
</div>

<div id="groupSettings">
	<div id="groupSettingsTitle" class="bg-color-purple">
		<h4 class="fg-color-white">Group Settings:</h4>
	</div>
	<div class="group-settings" onclick="openGroupInfo()">
		<h5>Group Information</h5>
	</div>
	<div class="group-settings" onclick="getGroupMembers()">
		<h5>Group Members</h5>
	</div>
	<div class="group-settings" onclick="leaveCheck()">
		<h5>Leave Group</h5>
	</div>
</div>

<div id="groupInfoHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupInfoTitle">
				<h2 class="fg-color-darken">Group Information</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('groupinfo')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupInfoContent" class="bp-modal-content">
		<div id="groupInfoName">
			<h3></h3>
		</div>
		<div id="groupInfoOwner">
			<h3></h3>
		</div>
		<div id="groupInfoZiplock">
			<h3></h3>
		</div>
		<div id="closeInfoHolder">
			<button id="closeInfo" name="closeInfo" onclick="closeThisModal('groupinfo')" class="bg-color-purple fg-color-white">Close</button>
		</div>
	</div>
</div>

<div id="groupRequestsHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupRequestsTitle">
				<h2 class="fg-color-darken">Group Requests</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('grouprequests')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupRequestsContent" class="bp-modal-content">
		<div id="groupRequestsPlace">
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

<div id="groupMemberHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupMemberTitle">
				<h2 class="fg-color-darken">Group Members</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('groupmembers')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupMemberContent" class="bp-modal-content">
		<div id="searchMemberHolder">
			<div class="input-control text">
			    <input type="text" name="searchMember" id="searchMember" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="searchMember" class="placeholder" >Search Member</label>
				</div>
		    </div>
		</div>
		<div id="groupMemberPlace">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div class="overview">
					<!-- <div class="member-place">
						<div class="member-image">

						</div>
						<div class="member-name-holder">
							<p class="member-name">Ron Cabal</p>
						</div>
						<div class="remove-member">
							<button class="bg-color-red fg-color-white">Remove</button>
						</div>
					</div> -->
				</div>
			</div>
		</div>
		<div id="addMemberHolder">
			<button id="addMember" class="bg-color-purple fg-color-white" onclick="showAddMember()" >Add Member</button>
		</div>
	</div>
</div>

<div id="groupRemoveHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupRemoveTitle">
				<h2 class="fg-color-darken">Remove Member</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('groupremove')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupRemoveContent" class="bp-modal-content">
		<div id="groupRemoveQuestion">
			<h4>Do you want to remove this member?</h4>
		</div>
		<div id="acceptRemoveHolder">
			<button id="removeNow" class="bg-color-red fg-color-white">Yes</button>
		</div>
		<div id="cancelRemoveHolder">
			<button id="cancelRemove" onclick="closeThisModal('groupremove')">Cancel</button>
		</div>
	</div>
</div>

<div id="groupAddHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="groupAddTitle">
				<h2 class="fg-color-darken">Add Member</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('addmember')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="groupAddContent" class="bp-modal-content">
		<div id="groupAdd">
			<div class="input-control text">
			    <input type="text" name="addNametag" id="addNametag" class="bp-element" />
			    <div class="placeholderPlace">
				    <label for="addNametag" class="placeholder" >Name Tag or Email Address</label>
				</div>
		    </div>
		</div>
		<div id="buttonAddHolder">
			<button id="addGroupMember" class="bg-color-purple fg-color-white" onclick="addGroupMember()">Add</button>
		</div>
	</div>
</div>

<div id="ownerPanel" class="bg-color-purple">
	
</div>

<div id="leaveGroupHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="leaveGroupTitle">
				<h2 class="fg-color-darken">Leave Group</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('leavegroup')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="leaveGroupContent" class="bp-modal-content">
		<div id="leaveGroupCheck">
			<h4>Are you sure you want to leave this group?</h4>
		</div>
		<div id="buttonLeaveGroup">
			<button id="leaveGroup" class="bg-color-red fg-color-white" onclick="removeFromGroup('leave', '')">Leave</button>
		</div>
		<div id="buttonCancelLeave">
			<button id="cancelLeave" onclick="closeThisModal('leavegroup')">Cancel</button>
		</div>
	</div>
</div>

<div id="unshareFilesHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="unshareFilesTitle">
				<h2 class="fg-color-darken">Unshare Files</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('unsharefiles')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="unshareFilesContent" class="bp-modal-content">
		<div id="unshareFilesCheck">
			<h4>Are you sure you want to unshare file(s)?</h4>
		</div>
		<div id="buttonUnshareFiles">
			<button id="unshareFiles" class="bg-color-red fg-color-white" onclick="unshareFilesNow()">Unshare</button>
		</div>
		<div id="buttonCancelUnshare">
			<button id="cancelUnshare" onclick="closeThisModal('leavegroup')">Cancel</button>
		</div>
	</div>
</div>