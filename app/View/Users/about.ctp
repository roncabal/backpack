<?php echo $this->Html->css('bp-s-out-1');?>

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

<div id="aboutTitleHolder">
	<div id="aboutTitle">
		<h2>What is it about?</h2>
	</div>
</div>

<div id="firstGroup">
	
	<a href="#secondGroup">
        <div id="about" class="tile bg-color-yellow">
    		<div class= "tile-content">
    			<div >
    				<h2>BackPack</h2>
    			</div>
    		</div>
    	</div>
    </a>

    <a href="#thirdGroup">
    	<div id="mainPocketTile" class="tile bg-color-orange">
    		<div class= "tile-content">
    			<div>
    				<h2>The Main Pocket</h2>
    			</div>
    		</div>
    	</div>
    </a>

   <a href="#fourthGroup">
    	<div id="sidePocketTile" class="tile bg-color-darken">
    		<div class= "tile-content">
    			<div >
    				<h2>Side Pocket</h2>
    			</div>
    		</div>
    	</div>
    </a>
	
    <a href="#fifthGroup">
    	<div id="topPocketTile" class="tile bg-color-purple">
    		<div class= "tile-content">
    			<div>
    				<h2>Top Pocket</h2>
    			</div>
    		</div>
    	</div>
    </a>

    <a href="#sixthGroup">
    	<div id="bottomPocketTile" class="tile bg-color-blueDark">
    		<div class= "tile-content">
    			<div>
    				<h2>Bottom Pocket</h2>
    			</div>
    		</div>
    	</div>
    </a>

	<a href="#seventhGroup">
    	<div id="extraPocketTile" class="tile bg-color-pinkdark">
    		<div class= "tile-content">
    			<div>
    				<h2>Extra Pocket</h2>
    			</div>
    		</div>
    	</div>
	</a>
</div>

<div id="secondGroup">
	<div id="aboutHeader" >
		<h1 class="fg-color-white">What is Backpack?</h1>	
	</div>


	<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/backpack/access.gif'); ?>
    	<div class="description">
   		Accessible as long as you have an Internet Connection
    	</div>
    	</div>
     
   		<div class="slide mixed" id="slide2">
   			<?php echo $this->Html->image('backpack/backpack/forall.gif'); ?>
    	<div class="description">
    	BackPack is for Everybody
   		</div>
    	</div>


    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/backpack/pack.gif'); ?>
    	<div class="description">
    	You can bring all your things with you with the help of Android Application
    	</div>
    	</div>

    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/backpack/share.gif'); ?>
    	<div class="description">
   	    A new way of File Management
    	</div>
    	</div>

    	...
    	</div>
    </div>
	     
</div>

<div id="thirdGroup">
	<div id="mainHeader" >
		<h1 class="fg-color-white">The Main Pocket</h1>
	</div>

	<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/main/bag.gif'); ?>
    	<div class="description">
   		Works like your bag
    	</div>
    	</div>
     
   		<div class="slide mixed" id="slide2">
   			<?php echo $this->Html->image('backpack/main/fun.gif'); ?>
    	<div class="description">
    	Genetic Algorithm
   		</div>
    	</div>


    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/main/home.gif'); ?>
    	<div class="description">
    	All uploaded files can be seen here
    	</div>
    	</div>

    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/main/simple.gif'); ?>
    	<div class="description">
   		User friendly
    	</div>
    	</div>

    	...
    	</div>
    </div>

</div>

<div id="fourthGroup">
<div id="sideHeader" >
		<h1 class="fg-color-white">Side Pocket</h1>
	</div>

<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/side/controlside.gif'); ?>
    	<div class="description">
   		Chose what to share and who to share with
    	</div>
    	</div>
     
   		<div class="slide mixed" id="slide2">
   			<?php echo $this->Html->image('backpack/side/groupside.gif'); ?>
    	<div class="description">
    	Share files on particular groups
   		</div>
    	</div>


    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/side/shareside.gif'); ?>
    	<div class="description">
    	Claim Files
    	</div>
    	</div>


    	...
    	</div>
    </div>

</div>

<div id="fifthGroup">
<div id="topHeader" >
		<h1 class="fg-color-white">Top Pocket</h1>
	</div>

<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/top/algo.gif'); ?>
    	<div class="description">
   		Classifies your files according to your usage
    	</div>
    	</div>
     
   		<div class="slide mixed" id="slide2">
   			<?php echo $this->Html->image('backpack/top/alive.gif'); ?>
    	<div class="description">
    	Genetics and Decay Rate
   		</div>
    	</div>


    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/top/smart.gif'); ?>
    	<div class="description">
    	Smart File Management
    	</div>
    	</div>

    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/top/spotlight.gif'); ?>
    	<div class="description">
   		The files you need and what you don't need.
    	</div>
    	</div>

    	...
    	</div>
    </div>

</div>

<div id="sixthGroup">
<div id="bottomHeader" >
		<h1 class="fg-color-white">Bottom Pocket</h1>
	</div>

	<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/bottom/bottom.gif'); ?>
    	<div class="description">
   		 We ensure that your files are secured
    	</div>
    	</div>


    	...
    	</div>
    </div>

</div>

<div id="seventhGroup">
<div id="extraHeader" >
		<h1 class="fg-color-white">Extra Pocket</h1>
	</div>

<div id="kahon" class="carousel" data-role="carousel" >
    	<div class="slides">
    	<div class="slide image" id="slide1">
    		<?php echo $this->Html->image('backpack/extra/communicate.gif'); ?>
    	<div class="description">
   		Chat/PMs among users.
    	</div>
    	</div>
     
   		<div class="slide mixed" id="slide2">
   			<?php echo $this->Html->image('backpack/extra/managecontacts.gif'); ?>
    	<div class="description">
    	Manage your connections
   		</div>
    	</div>


    	<div class="slide mixed" id="slide2">
    		<?php echo $this->Html->image('backpack/extra/people.gif'); ?>
    	<div class="description">
    	Find your bagmates here
    	</div>
    	</div>



    	...
    	</div>
    </div>

</div>



<div id="ftraboutGroup">
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