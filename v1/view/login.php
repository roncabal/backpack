<?php include('view/header.php'); ?>
<link rel="stylesheet" type="text/css" href="styles/login.css" />
	<?php global $register; echo $register; ?>
	<div id="loginWrapper">
		<div id="backpackIconWrapper" align="center">
			<div id="iconPlace"><a href="#"><img src="backpack_images/backpack_icon.png" /></a></div>
		</div>

		<div id="loginFormWrapper">
			<table>
				<tr>
					<td>
						<div id="loginPlace">
							<div id="loginForm">
								<form action="." method="post">
									<input type="hidden" name="action" value="open_my_bag" />
									<input type="text" name="name_tag" placeholder="Name Tag" id="nameTag" /><br />
									<input type="password" name="lock_code" placeholder="Lock Code" id="lockCode" /><br />
									<input type="checkbox" name="stay_logged_in" value="true" id="checkKeep" class="onHover" />
									<label for="checkKeep" id="labelKeep" class="onHover" >Stay logged in</label>
									<input type="button" id="signUp" name="sign_up" value="" class="onHover" onClick="window.location='?action=register'" />
									<input type="submit" id="openBag" name="open_bag" value="" class="onHover" />
								</form>
							</div>
						</div>
					</td>
					<td width="300">
						<?php global $invalid; echo $invalid; ?>
					</td>
				</tr>
			</table>
		</div>

	</div>
	
<?php include('view/footer.php'); ?>
