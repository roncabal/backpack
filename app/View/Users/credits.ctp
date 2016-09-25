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
		<h2>Credits</h2>
	</div>
</div>	

<div id="creditfirstGroup">
		<div id="creditbackpackName"><h1>BackPack Credits</h1></div>

	<div id="creditaboutDetails" class="fg-color-white">
		<p> Creating a competitive website nowadays is a very difficult task especially there are so many good websites out there. The way the designer makes it look better and the programmer makes it work the way you want to, is really falling between arts and science. But Team CS4 would not be enough to do it all alone.</p>
		<br/>
		<br/>
		<p>
			Thanks to the masters who contributed a lot for the whole development of this site. Sharing ideas and knowledge that helped us realized what we think is not enough and to push this site beyond our single-minded ideas. This site reflects to the works of many individuals.
		</p>
	</div>
</div>

<div id="secondGroup">
		<div id="alexName"><h1>Sir Alexander</h1></div>

	<div id="alexDetails" class="fg-color-white">
		<p> Team adviser. Sir Alexander Hernandez sees all aspects of the documentation and technical view of the site development that helped the team improve the output. Boundless patience, genuine ideas and willingness to help, giving options and solutions makes him a great pleasure to work with.</p>
		
	</div>

</div>

<div id="thirdGroup">
		<div id="freedomName"><h1>Sir Freedom</h1></div>

	<div id="freedomDetails" class="fg-color-white">
		<p> Software Engineering Professor. Mr. Freedom John Ferrera is indeed an excellent professor. He taught the team what approach they should use on how the system would be developed way easier. Sir Ferrera’s way of teaching gives far more than his technical expertise. He never got tired of giving tips and pieces of inspirational advice that gave courage to his students. </p>
		
	</div>

</div>

<div id="fourthGroup">
		<div id="jbName"><h1>Sir Joell and Sir Barrun</h1></div>

	<div id="jbDetails" class="fg-color-white">
		<p> Joell Lapitan a Letran Professor and Mozilla Representative as well as Eusebio "Jun" Barrun also a Letran Professor, Mozilla Representative and Mozilla Community Manager of Mozilla Philippines. These two great people help the team published the website. They share brilliant ideas and concepts to the team. Since they are expert in web development they brought in new techniques on the development aspect of the site. </p>
		
	</div>

</div>

<div id="fifthGroup">
		<div id="metroName"><h1>Sir Sergey Pimenov</h1></div>

	<div id="metroDetails" class="fg-color-white">
		<p> Author of Metro UI CSS. The website’s design was completely derived on Metro UI which is in trend nowadays. The team absorbed the idea for because it is new and new is what people want. Metro UI CSS a set of styles to create a site with an interface similar to Windows 8 Metro UI. This set of styles was developed as a self-contained solution. http://metroui.org.ua/ </p>
		
	</div>

</div>

<div id="sixthGroup">
		<div id="jQueryName"><h1>Sir Maarten Baijs</h1></div>

	<div id="jQueryDetails" class="fg-color-white">
		<p> Author of Tiny Scroll JQuery. Tiny Scrollbar can be used for scrolling content. It was built using the javascript jQuery library. Tiny scrollbar was designed to be a dynamic lightweight utility that gives web designers a powerful way of enhancing a websites user interface. It was used by the team to support the navigation of the site. Thanks for the author for lending it free. http://baijs.nl/tinyscrollbar/

		</p>
		
	</div>

</div>

<div id="seventhGroup">
		<div id="plUploadName"><h1>Moxie Code Systems AB</h1></div>

	<div id="plUploadDetails" class="fg-color-white">
		<p> Developer of Plupload and Tiny MCE. Their free software, Plupload, was used for the multiple upload feature of the website. Plupload is a highly usable upload handler for your Content Management Systems or similar. Plupload is currently separated into a Core API and a jQuery upload queue widget this enables you to either use it out of the box or write your own custom implementation. http://www.plupload.com/index.php
		</p>
		
	</div>

</div>

<div id="eightFooter" class="tile-group">
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