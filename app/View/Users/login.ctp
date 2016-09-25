<?php 	echo $this->Html->css('bp-s-out');
		echo $this->Html->script('backpack_js/bp-js-login', false);
?>

<script type="text/javascript">
	var download_url = '<?php echo $this->Html->url("/downloads/download_shared/"); ?>';
</script>
<div id="modalBackground"></div>
<div id="oContext" class="bg-color-darken">
	<ul class="dropdown-menu open" id="contextMenu">
		<li><a href="<?php echo $this->Html->url('/login') ?>">Login</a></li>
		<li><a href="<?php echo $this->Html->url('/register') ?>">Register</a></li>
		<li class="divider"></li>
		<li><a href="#">Support</a></li>
	</ul>
</div>

<noscript>
	<div id="javaOff" align="center" class="bg-color-blue">
		<p>For better experience and viewing of <strong>Backpack</strong>, please enable the <i>javascript</i> of your browser.</p>
	</div>
</noscript>

<div id="titleHolder">
	<div id="backpackHolder">
		<h1 id="title" class="fg-color-darken">Backpack</h1>
	</div>
</div>

<?php echo $this->Session->flash(); ?>

<button id="loginDropdown" class="image-button bg-color-darken fg-color-white">
	Login
	<?php echo $this->Html->image('icons/login.gif', array('class'=>'bg-color-green')); ?>
</button>

<div id="loginPlace">
	<div id="loginHolder" class="bg-color-darken">
		<div id="loginForm">
			<form action="<?php echo $this->Html->url('/login'); ?>" method="POST">
				<div class="input-control text" >
				    <input type="text" name="nametag" id="nametag" class="bp-element" />
					<div class="placeholderPlace">
					    <label for="nametag" class="placeholder" id="nametagPlaceholder" >Name Tag</label>
					</div>
			    </div>

			    <div class="input-control text">
				    <input type="password" name="lockcode" id="lockcode" class="bp-element" />
				    <div class="placeholderPlace">
					    <label for="lockcode" class="placeholder" id="lockcodePlaceholder">Lock Code</label>
					</div>
			    </div>

			     <label class="checkbox">
					<input type="checkbox" id="keepMeLoggedIn" name="keepMeLoggedIn" value="1">
					<span class="fg-color-white">Keep me logged in</span>
				</label>

				<div id="openBagHolder">
					<button type="submit" name="openBag" id="openBag" class="bg-color-green fg-color-white">Open bag</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="firstGroup" class="tile-group">

	<div id="signupHolder" onclick="window.location='<?php echo $this->Html->url('/register'); ?>'">
		<div class="tile double bg-color-blue" id="signup">
			<div class="tile-content">
				<div id="signupTitle">
					<h2>Get your own Backpack.</h2><br/>
					<p>Sign up now and get your own backpack with free 5GB space in the cloud!</p>
				</div>
			</div>
		</div>
	</div>

	<div id="whatBackpackHolder">
		<div class="tile bg-color-green" id="whatBackpack">
			<div class="tile-content">
				<div id="whatBackpackTitle">
					<h2>What is Backpack?</h2><br/>
					<p>Some backpack details will be placed here...</p>
				</div>
				<div class="bottomContent">
					<h5>Know more about Backpack</h5>
				</div>
			</div>
		</div>
	</div>

	<div id="claimFileHolder">
		<div class="tile bg-color-orange" id="claimFile">
			<div class="tile-content">
				<div id="claimTitle">
					<h2>Claim File</h2>
				</div>
				<div class="bottomContent">
					<h5>Claim a file here</h5>
				</div>
			</div>
		</div>
	</div>

	<div id="forgotHolder" onclick="window.location='<?php echo $this->Html->url("/users/forgot"); ?>'">
		<div class="tile bg-color-purple">
			<div class="tile-content">
				<h3>Forgot your password?</h3>

				<div class="bottomContent">
					<h5>Reset password</h5>
				</div>
			</div>
		</div>
	</div>

	<div id="imagePanel">
		<div class="tile triple image-slider" data-role="tile-slider" data-param-duration="500" data-param-period="3000" data-param-direction="up">
			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/gigfree.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/android.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/anytime.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/anywhere.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/bionic.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/bottom.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/simple.jpg'); ?>
			</div>

			<div class="tile-content">
			<?php echo $this->Html->image('backpack/slider/socialize.jpg'); ?>
			</div>
		</div>
	</div>
</div>

<div id="secondGroup" class="tile-group">
	<div id="bpCarousel" class="carousel" data-role="carousel" style="height:310px;" data-param-markers="off">
	    <div class="slides">
		    <div class="slide image" id="slide1">
		   		<?php echo $this->Html->image('backpack/backpack/access.gif'); ?>
			    <div class="description">
			    Access your files anywhere.
			    </div>
	    	</div>
	     
	   		<div class="slide image" id="slide2">
		    	<?php echo $this->Html->image('backpack/top/alive.gif'); ?>
			    <div class="description">
			    Your files are alive.
			    </div>
			</div>

			<div class="slide image" id="slide2">
		    	<?php echo $this->Html->image('backpack/main/fun.gif'); ?>
			    <div class="description">
			    A fun way to organize your files.
			    </div>
			</div>

			<div class="slide image" id="slide2">
		    	<?php echo $this->Html->image('backpack/side/controlside.gif'); ?>
			    <div class="description">
			    Share your files to your friends.
			    </div>
			</div>

			<div class="slide image" id="slide2">
		    	<?php echo $this->Html->image('backpack/bottom/bottom.gif'); ?>
			    <div class="description">
			    Your secured pocket.
			    </div>
			</div>

			<div class="slide image" id="slide2">
		    	<?php echo $this->Html->image('backpack/extra/managecontacts.gif'); ?>
			    <div class="description">
			    All group members.
			    </div>
			</div>
		</div>
	     
	    <span class="control left">‹</span>
	    <span class="control right">›</span>
	     
    </div>

    <div id="androidDownload" class="tile triple bg-color-red">
    	<div class="tile-content">
    		<h2>Backpack for android</h2>

    		<div class="bottomContent">
	    		<h5>Get Backpack for your android phone</h5>
	    	</div>
    	</div>
    </div>
</div>

<div id="thirdGroup" class="tile-group">
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

<div id="claimModalHolder" class="bp-modal">
	<div class="bp-modal-header select-disable">
		<div class="bp-modal-title">
			<div id="claimModalTitle">
				<h2 class="fg-color-darken">Claim File</h2>
			</div>
		</div>
		<div class="bp-modal-close cursor-pointer" onclick="closeThisModal('claimfile')"><h2 class="fg-color-darken">x</h2></div>
	</div>
	<div id="claimModalContent" class="bp-modal-content">
		<div id="claimCodeHolder">
			<div class="input-control text" >
			    <input type="text" name="claimCode" id="claimCode" class="bp-element" />
				<div class="placeholderPlace">
				    <label for="claimCode" class="placeholder">Enter claim code here...</label>
				</div>
		    </div>
		</div>
		<div id="claimCodeButton">
			<button id="claimFileButton" class="bg-color-orange fg-color-white">Claim File</button>
		</div>
	</div>
</div>