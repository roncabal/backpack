<?php echo $this->Html->css('bp-s-in-1'); 
	  echo $this->Html->script('backpack_js/bp-js-u', false);
	  echo $this->Html->script('backpack_js/bp-js-settings', false); ?>

<div id="headHolder" class="select-disable">
	<div id="titleHolder">
		<a href="<?php echo $this->Html->url('/main'); ?>" ><h1>Backpack</h1></a>
	</div>
</div>

<div id="newVerificationHolder">
	<?php if(isset($message)){?>
	<div id="newVerificationTitle">
		<h2><?php echo $message; ?></h2>
	</div>
	<?php }else{ ?>
	<div id="newVerificationTitle">
		<h2>New Verification Code</h2>
	</div>
	<div id="newVerificationLabel">
		<h4>Please enter your email address here.</h4>
	</div>
	<form action="./new_verification" method="POST">
		<div id="newVerificationEmailTextBox">
			<div class="input-control text" >
			    <input type="text" name="verifyEmail" id="verifyEmail" class="bp-element" />
				<div class="placeholderPlace">
				    <label for="verifyEmail" class="placeholder" >Email</label>
				</div>
		    </div>
		</div>
		<div id="newVerificationButton">
			<button id="verifyButton" name="verifyButton" class="bg-color-red fg-color-white">Get verification code</button>
		</div>
	</form>
	<?php } ?>
</div>