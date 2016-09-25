<?php echo $this->Html->css('bp-s-in-1'); 
	  echo $this->Html->script('backpack_js/bp-js-u', false);
	  echo $this->Html->script('backpack_js/bp-js-settings', false); ?>

<div id="headHolder" class="select-disable">
	<div id="titleHolder">
		<a href="<?php echo $this->Html->url('/main'); ?>" ><h1>Backpack</h1></a>
	</div>
</div>

<div id="verificationMessageHolder">
	<p id="verMessage">
	<?php 
	if($verified)
	{
		if($this->Session->read('Users'))
		{
			echo 'You have successfully verified your account. <a href="'. $this->Html->url("/main") .'">Click here</a> to go back to your main pocket.';
		}
		else
		{
			echo 'You have successfully verified your account. <a href="'. $this->Html->url("/login") .'">Click here</a> to login.';
		}
		
	}
	else
	{
		echo 'This link is either expired or does not exist. <a href="'. $this->Html->url("/users/new_verification") .'">Click here</a> to get a new verification code.';
	} 
?>
</p>
</div>
