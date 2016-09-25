<?php echo $this->Html->css('bp-s-out-1'); ?>

<?php if($online){ 
    echo $this->Html->script('backpack_js/bp-js-u', false);  
    echo $this->Html->script('backpack_js/bp-js-settings', false);  ?>
    <script type="text/javascript">
        var retrieve_url = '<?php echo $this->Html->url("/retrieve"); ?>';
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

<div id="headHolder" class="select-disable">
	<div id="titleHolder">
		<a href="<?php echo $this->Html->url('/login'); ?>"><h1>Backpack</h1></a>
	</div>

	<div id="userHolder" class="select-disable">
		<div id="nametagHolder" >
			<h4 class="fg-color-darken"><?php echo $this->Session->read('User.name_tag'); ?></h4>
		</div>
		<div id="imageHolder">
			<img src="<?php echo $image; ?>" width="50" height="50" />
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

<?php }else{ ?>

<div id="headHolder" class="select-disable">
    <div id="titleHolder">
        <a href="<?php echo $this->Html->url('/login'); ?>" ><h1>Backpack</h1></a>
    </div>
</div>

<?php } ?>
<!-- ''''Start here'''' -->


<div id="contactnHolder">

	<div id="contactTitle">
		<h2>Contact Us</h2>
	</div>
</div>	
<div id="nidoContact">
		<div id="nidoName"><h1>David Paolo Nido</h1></div>

	<div id="nidoDetails" class="fg-color-white">
		<p> Co-founder and CEO of BackPack, Backpack team leader, Analyst of Backpack, Designer, self employed
			partime ~ 2009 to present, Computer Programmer, Hardware servicing, Database administrator, Letran Computer Analysts Board of Directors 2012 to 2013 - Letran Computer Analysts President ~ Jun 2011 to 2012 Organization of Computer Science students under the School of Computer Studies and Technology of Letran</p>
	</div>

	<div id="nidoTile" class="tile double bg-color-orange fg-color-white">
		<h2>davidnido@gmail.com</h2>
		<h4>www.facebook.com/davidnido</h4>
		<h4>davidnido.blogspot.com</h4>
		<h4>follow me on twitter</h4>
		<h4>@Dnido</h4>
	</div>
</div>

<div id="ronContact">
		<div id="ronName"><h1>Ron Emerson Cabal</h1></div>

	<div id="ronDetails" class="fg-color-white">
		<p> Co-founder and CEO of BackPack, Programmer of Backpack, System Engineer, Web administrator of backpack, Took his OJT Training at Freshremix Software Corporation as a Quality Tester, Knowledgable in Java, PHP, Javascript, HTML5, CSS3, Android Programming, Visual Basic, MySQL database.  </p>
	</div>

	<div id="ronTile" class="tile double bg-color-orange fg-color-white">
		<h2>mrroncabal@gmail.com</h2>
		<h4>www.facebook.com/swith2omelons28</h4>

	</div>

</div>

<div id="ritzContact">
		<div id="ritzName"><h1>Ritz Kevin Ragel</h1></div>

	<div id="ritzDetails" class="fg-color-white">
		<p> Co-founder and CEO of BackPack, Researcher, File keeper, Associate System Engineer of Backpack, Landbank
			Intern ~ Siniloan, Laguna ~ In 2012 OJT - Trainee, Knowlegeable in Visual Basic, ASP and basic Java Programming, Managed the content of the documentation for BackPack</p>
	</div>

	<div id="ronTile" class="tile double bg-color-orange fg-color-white">
		<h2>ritzragel@gmail.com</h2>
		<h4>www.facebook.com/kevinragel24</h4>
		<h4>www.instagram.com/kevinragel24</h4>
		<h4>follow me on twitter</h4>
		<h4>@kevinragel24</h4>
	</div>

</div>

<div id="ftrcontactGroup">
    <div id="backpackTileHolder" onclick="window.location='<?php echo $this->Html->url("/users/about"); ?>'">
        <div class="tile bg-color-greenDark">
            <div class="tile-content">
                <h2>Backpack</h2>

                <div class="bottomContent">
                    <h5>Read about Backpack</h5>
                </div>
            </div>
        </div>
    </div>

    <div id="contactHolder" onclick="window.location='<?php echo $this->Html->url("/users/contact"); ?>'">
        <div class="tile bg-color-pink">
            <div class="tile-content">
                <h3>Contact Us</h3>

                <div class="bottomContent">

                </div>
            </div>
        </div>
    </div>

    <div id="supportHolder" onclick="window.location='<?php echo $this->Html->url("/users/customer"); ?>'">
        <div class="tile double bg-color-purple">
            <div class="tile-content">
                <h2>Customer support</h2>

                <div class="bottomContent">
                    We care about you
                </div>
            </div>
        </div>
    </div>

    <div id="creditsHolder" onclick="window.location='<?php echo $this->Html->url("/users/credits"); ?>'">
        <div class="tile double bg-color-darken">
            <div class="tile-content">
                <h2>Credits</h2><br />
                <p>People who contributed to the creation of Backpack</p>
                <div class="bottomContent">
                    We thank them
                </div>
            </div>
        </div>
    </div>  
</div>