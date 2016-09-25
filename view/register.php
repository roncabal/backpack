<?php include('view/header.php'); ?>
<link rel="stylesheet" type="text/css" href="styles/register.css" />
		<div id="errorHolder">
			<?php echo $incorrect; ?>
		</div>
		<div id="registerFormWrapper">
			<div id="registerPlace">
				<div id="registerForm" align="left">
					<form action="." method="post">
						<input type="hidden" value="submit_register" name="action" />
						<input type="hidden" value="true" name="register_check" />
						<input type="text" name="name_tag" placeholder="Name Tag" id="nameTag" value="<?php echo $name_tag; ?>" /><br />
						<input type="text" name="first_name" placeholder="First Name" id="firstName" value="<?php echo $first_name; ?>" />
						<input type="text" name="last_name" placeholder="Last Name" id="lastName" value="<?php echo $last_name; ?>" /><br />
						<input type="text" name="email_add" placeholder="Email Address" id="emailAdd" value="<?php echo $email_add; ?>" /><br />
						<input type="password" name="lock_code" placeholder="Lock Code" id="lockCode"  /><br />
						<input type="hidden" name="accept_terms" value="0" />
						<input type="checkbox" name="accept_terms" value="1" id="acceptTerms" class="onHover" />
							<label for="acceptTerms" id="labelAccept" class="onHover" >I Accept the <a href="#" id="termsAndConditions">Terms and Conditions</a> of Backpack</label><br />
						<div id="inputHolder" align="center">
							<input type="submit" id="register" name="register" value="" class="onHover" />
							<input type="button" id="cancel" name="cancel" value="" class="onHover" onClick="window.location='.?action=login'" /><br />
						</div>
					</form>
				</div>
			</div>
		</div>
<?php include('view/footer.php'); ?>