<?php 	echo $this->Html->css('bp-s-out');
		echo $this->Html->script('backpack_js/bp-js-register');
?>

<script type="text/javascript">
	var nametag_url = '<?php echo $this->Html->url("/nametag_valid"); ?>';
	var email_url   = '<?php echo $this->Html->url("/email_valid"); ?>';
</script>

<div id="oContext">
	<ul class="dropdown-menu open" id="contextMenu">
	  <li><a tabindex="-1" href="http://localhost/backpack/login">Login</a></li>
	  <li><a tabindex="-1" href="http://localhost/backpack/register">Register</a></li>
	  <li class="divider"></li>
	  <li><a tabindex="-1" href="#">Support</a></li>
	</ul>
</div>

<noscript>
	<div id="javaOff" align="center" class="bg-color-blue">
		<p>To continue registration to <strong>Backpack</strong>, please enable the <i>javascript</i> of your browser.</p>
	</div>
</noscript>

<div id="regTitleHolder">
	<h1 id="registrationWelcome" class="fg-color-darken">Create your Backpack now!</h1>
</div>

<div id="registrationHolder" class="tile bg-color-blue">
	<form method="POST" action="<?php echo $this->Html->url('/register'); ?>">
		<div class="input-control text reg-element">
			<input type="text" class="bp-element fg-color-darken" autocomplete="off" name="regNametag" id="regNametag" maxlength="20" value="<?php echo $nametagValue; ?>"/>
			<div class="placeholderPlace">
				<label class="placeholder" id="regNametagPlaceholder" for="regNametag">Name Tag</label>
			</div>
			<div class="validationHolder">
				<h5 class="regValidation" id="vNametag" ><?php echo $nametagValidator; ?></h5>
			</div><!--validation end -->
		</div>

		<div class="input-control text reg-element">
				<input type="password" class="bp-element fg-color-darken" name="regLockcode" id="regLockcode" maxlength="16"/>
			<div class="placeholderPlace">
				<label class="placeholder" id="regLockcodePlaceholder" for="regLockcode">Lock Code</label>
			</div>
			<div class="validationHolder">
				<h5 class="regValidation" id="vLockcode">Please enter your password.</h5>
			</div><!--validation end -->
		</div>

		<div class="input-control text reg-element">
				<input type="text" class="bp-element fg-color-darken" name="regFirstName" id="regFirstName" maxlength="20" value="<?php echo $firstnameValue; ?>" />
			<div class="placeholderPlace">
				<label class="placeholder" id="regFirstNamePlaceholder" for="regFirstName">First Name</label>
			</div>
			<div class="validationHolder">
				<h5 class="regValidation" id="vFirstName"><?php echo $firstnameValidator; ?></h5>
			</div><!--validation end -->
		</div><!--end form_element-->

		<div class="input-control text reg-element">
				<input type="text" class="bp-element fg-color-darken" name="regLastName" id="regLastName" maxlength="20" value="<?php echo $lastnameValue; ?>" />
			<div class="placeholderPlace">
				<label class="placeholder" id="regLastNamePlaceholder" for="regLastName">Last Name</label>
			</div>
			<div class="validationHolder">
				<h5 class="regValidation" id="vLastName"><?php echo $lastnameValidator; ?></h5>
			</div><!--validation end -->
		</div><!--end form_element-->

		<div class="input-control text reg-element">
				<input type="text" class="bp-element fg-color-darken" name="regEmail" id="regEmail" maxlength="50" value="<?php echo $emailValue; ?>" />
			<div class="placeholderPlace">
				<label class="placeholder" id="regEmailPlaceholder" for="regEmail">Email</label>
			</div>
			<div class="validationHolder">
				<h5 class="regValidation" id="vEmail"><?php echo $emailValidator; ?></h5>
			</div><!--validation end -->
		</div><!--end form_element-->
		<div class="reg-element" id="agreeHolder">
			<label id="agreeTerms" for="iAgree" class="fg-color-white mouse-pointer" >
				<input type="checkbox" name="iAgree" id="iAgree" value="agree" /> I Agree to the Backpack <a href="#" >Terms</a>
			</label>
			<div class="validationHolder" >
				<h5 class="regValidation" id="vAgree"><?php echo $agreeValidator; ?></h5>
			</div><!--validation end -->
		</div><!--end form_element-->
		
		<div class="form_element" id="getBackpackHolder">
			<button type="submit" class="bg-color-blueDark fg-color-white" id="getBackpack" name="getBackpack" >Get backpack</button>
		</div>
		<div id="loginHere">
			<a href="<?php echo $this->Html->url('/login'); ?>">I have an account already.</a>
		</div>
	</form><!--end register-->
</div>

<div id="regFirstGroup" class="tile-group">
	<div id="backpackTileHolder">
		<div class="tile bg-color-greenDark">
			<div class="tile-content">
				<h2>Backpack</h2>

				<div class="bottomContent">
					<h5>Read about Backpack</h5>
				</div>
			</div>
		</div>
	</div>

	<div id="contactHolder">
		<div class="tile bg-color-pinkDark">
			<div class="tile-content">
				<h3>Contact Us</h3>

				<div class="bottomContent">

				</div>
			</div>
		</div>
	</div>

	<div id="supportHolder">
		<div class="tile double bg-color-purple">
			<div class="tile-content">
				<h2>Customer support</h2>

				<div class="bottomContent">
					We care about you
				</div>
			</div>
		</div>
	</div>

	<div id="creditsHolder">
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