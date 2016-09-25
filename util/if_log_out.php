<?php	
	if(isset($_GET['logout'])){
		if($_GET['logout'] == true){
			session_unset();
			session_destroy();
			$time = time() + 60*60*24*365;

			setcookie("name_tag_cookie", "", -$time);
			setcookie("lock_code_cookie", "", -$time);

		}
	}
?>