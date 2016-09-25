<?php

App::uses('Sanitize', 'Utility');
App::uses('Folder', 'Utility');
date_default_timezone_set('Asia/Manila');
/**
* 
*/
class UsersController extends AppController
{

	public $uses = array('Backpack_User', 'User_Detail', 'User_Activity', 'User_Verification');

	private $UE;

	private $FH;

	function beforeFilter()
	{
		parent::beforeFilter();

		App::import('Lib/Validate', 'UsersExtension');
		App::import('Lib/Validate', 'FilesHandler');
		$this->UE = new UsersExtension();
		$this->FH = new FilesHandler();

	}

	public function login()
	{
		if($this->Session->check('User') == true)
		{
			$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
		}

		$this->set('bpinfo', '');
		if(isset($_POST['openBag']))
		{
			$login = $_POST['nametag'];
			$lockcode = $_POST['lockcode'];
			$keep = (isset($_POST['keepMeLoggedIn'])) ? $_POST['keepMeLoggedIn'] : 0;

			$b_uid = $this->Backpack_User->find('first', array(
				'conditions' => array('OR'=>array('Backpack_User.name_tag LIKE binary' => addslashes($login), 'Backpack_User.email_address LIKE binary' => addslashes($login))),
				'field'     => array('Backpack_User.user_id', 'Backpack_User.name_tag'),
				'limit'     => 1));

			$user_id = $b_uid['Backpack_User']['user_id'];
			$nametag = $b_uid['Backpack_User']['name_tag'];
			$elockcode = $this->UE->encryptlockcode($user_id, $lockcode);

			$user_exists = $this->Backpack_User->find('count', array(
				'conditions' => array('Backpack_User.user_id'   => $user_id,
									 'Backpack_User.name_tag LIKE binary'  => $nametag,
									 'Backpack_User.lock_code' => $elockcode)));

			if($user_exists==1)
			{
				$this->Session->write('User.c', $user_id);
				$this->Session->write('User.name_tag', $nametag);
				$this->Session->write('User.ip', $this->request->clientIp());
				$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
			}
			else
			{
				$user_exists = $this->Backpack_User->find('count', array(
				'conditions' => array('Backpack_User.user_id'   => $user_id,
									 'Backpack_User.email_address like binary'  => $nametag,
									 'Backpack_User.lock_code' => $elockcode)));

				if($user_exists==1)
				{
					$this->Session->write('User.c', $user_id);
					$this->Session->write('User.name_tag', $nametag);
					$this->Session->write('User.ip', $this->request->clientIp());
					$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
				}
				else
				{
					$this->Session->setFlash('<div id="loginFailed" class="bg-color-red">
															<div id="loginLabel">
																<h4 style="margin:0px" class="fg-color-white">Username or password is invalid!</h4>
															</div>
														</div>');
				}
			}
		}
	}

	public function register()
	{
		if($this->Session->check('User') == true)
		{
			$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
		}

		if(isset($_POST['getBackpack']))
		{
			/* Get Post */
			$nametag = $_POST['regNametag'];
			$lockcode = $_POST['regLockcode'];
			$email = $_POST['regEmail'];
			$firstname = $_POST['regFirstName'];
			$lastname = $_POST['regLastName'];
			$agree = '';
			$result = array();

			/*Check inputs*/
			list($result['nametag']['r_title'], $result['nametag']['r_desc'], $result['nametag']['r_valid'],
			$result['nametag']['r_response']) = $this->UE->check_nametag($nametag);

			list($result['lockcode']['r_title'], $result['lockcode']['r_desc'], $result['lockcode']['r_valid'],
			$result['lockcode']['r_response']) = $this->UE->check_lockcode($lockcode);

			list($result['firstname']['r_title'], $result['firstname']['r_desc'], $result['firstname']['r_valid'],
			$result['firstname']['r_response']) = $this->UE->check_name($firstname, 'First');

			list($result['lastname']['r_title'], $result['lastname']['r_desc'], $result['lastname']['r_valid'],
			$result['lastname']['r_response']) = $this->UE->check_name($lastname, 'Last');

			list($result['email']['r_title'], $result['email']['r_desc'], $result['email']['r_valid'],
			$result['email']['r_response']) = $this->UE->check_email($email);

			if(isset($_POST['iAgree'])){
				$agree = $_POST['iAgree'];
			}

			if($agree == 'agree')
			{
				$agreeMsg = '';
			}
			else
			{
				$agreeMsg = 'To continue, please agree to our terms.';
			}

			/*Check validity */
			if($result['nametag']['r_valid'] == true && $result['lockcode']['r_valid'] == true && $result['firstname']['r_valid'] == true &&
				$result['lastname']['r_valid'] == true && $result['email']['r_valid'] == true && $agree == 'agree')
			{
				$uid = $this->UE->getUid();
				$elockcode = $this->UE->encryptlockcode($uid, $lockcode);
				$nametag = Sanitize::paranoid($nametag, array('_'));
				$firstname = Sanitize::paranoid($firstname, array(' '));
				$lastname = Sanitize::paranoid($lastname, array(' '));
				$joined = date("Y-m-d H:i:s");
				$register = array('Backpack_User'=>array('user_id'=>$uid, 'name_tag'=>$nametag, 
					'lock_code'=>$elockcode, 'bottom_code'=>$elockcode, 'email_address'=>$email, 'is_verified'=>false, 
					'backpack_size'=>5368709120, 'space_used'=>0));
				$information = array('User_Detail'=>array('user_id'=>$uid, 'first_name'=>$firstname, 
					'last_name'=>$lastname, 'date_joined'=>$joined, 'img_set'=>false));
				if($this->Backpack_User->save($register) && $this->User_Detail->save($information))
				{
					$folder = new Folder();
					$folder_name = $this->FH->getFolderName($uid, $nametag);
					$folder_nt = $this->FH->getOuterFolder($nametag);
					if($folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt))
					{

						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . $folder_name);
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . $folder_name . DS . 'main');
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . $folder_name . DS . 'bottom');
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . $folder_name . DS . 'trash');
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . $folder_name . DS . 'download');
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . 'v');
						$folder->create(WWW_ROOT . DS . 'u' . DS . $folder_nt . DS . 'v' . DS . 'profile-pic');
						copy('img/backpack/no-avatar.jpg', 'u/' . $folder_nt . '/v/profile-pic/user_avatar.jpg');
						$this->Session->setFlash('<div id="regSuccess" class="bg-color-green">
														<div class="close fg-color-white">x</div>
														<div id="loginLabel">
															<h4 style="margin:0px" class="fg-color-white">Registration successful!</h4>
														</div>
													</div>');
						$v_code = $this->UE->getVerificationId();
						$expiry = strtotime($joined) + (60 * 60 * 60 * 24);
						$verify = array('User_Verification'=>array('user_id'=>$uid, 'verification_code'=>$v_code, 'expiration_date'=>$expiry));
						$this->User_Verification->save($verify);
						$to = $email;
						$subject = "Verify your account";
						$message = "Greetings " . $nametag .", \r\n\r\nWelcome to Backpack!\r\nWe are very happy to welcome you in backpack. \r\n\r\nIn able for you to fully enjoy your backpack, please verify you account by clicking the link below:\r\n\r\nhttp://" . $_SERVER['SERVER_NAME'] . Router::url('/users/verify/') . $v_code . "\r\n\r\nThank you for joining us and we are hoping that you will enjoy your backpack. \r\n\r\n\r\nSincerely, \r\n\r\nThe Backpack Team";
						$from = "noreply@skybackpack.com";
						$headers = "From: " . $from;
						if(mail($to,$subject,$message,$headers))
						{
							$this->redirect(array('action' => 'login'));
						}

						$this->redirect(array('action' => 'login'));
					}
				}
			}
			else
			{
				$this->set('nametagValue', $nametag);
				$this->set('firstnameValue', $firstname);
				$this->set('lastnameValue', $lastname);
				$this->set('emailValue', $email);
				$this->set('nametagValidator',  $result['nametag']['r_response']);
				$this->set('firstnameValidator',  $result['firstname']['r_response']);
				$this->set('lastnameValidator',  $result['lastname']['r_response']);
				$this->set('emailValidator',  $result['email']['r_response']);
				$this->set('agreeValidator', $agreeMsg);
			}
		}
		else
		{
			$this->set('nametagValue', '');
			$this->set('firstnameValue', '');
			$this->set('lastnameValue', '');
			$this->set('emailValue', '');
			$this->set('nametagValidator', 'Name tag for your backpack.');
			$this->set('firstnameValidator', 'First name please?');
			$this->set('lastnameValidator', 'Your last name here.');
			$this->set('emailValidator', 'Enter your email here.');
			$this->set('agreeValidator', '');
		}
	}


	public function nametag_valid()
	{
		if($this->Session->check('User') == true)
		{
			$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
		}

		$nametag = $_POST['check_nametag'];
		$result = array();

		list($result['r_title'], $result['r_desc'], $result['r_valid'], $result['r_response']) = $this->UE->check_nametag($nametag);
		echo json_encode($result);
		$this->autoRender = false;

	}

	public function email_valid()
	{
		if($this->Session->check('User') == true)
		{
			$this->redirect(array('controller'=>'pockets', 'action'=>'main'));
		}

		$email = $_POST['check_email'];
		$result = array();

		list($result['r_title'], $result['r_desc'], $result['r_valid'], $result['r_response']) = $this->UE->check_email($email);
		echo json_encode($result);
		$this->autoRender = false;
	}

	public function account_settings()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}
		$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
		$user_details = $this->Backpack_User->query("SELECT * FROM backpack__users AS Backpack_User LEFT JOIN user__details AS User_Detail ON Backpack_User.user_id = User_Detail.user_id WHERE Backpack_User.user_id = '$uid' LIMIT 1");

		$user_detail = array_shift($user_details);
		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$this->set('imagepath', $imagepath);
		$this->set('nametag', $user_detail['Backpack_User']['name_tag']);
		$this->set('name', $user_detail['User_Detail']['first_name'] . ' ' . $user_detail['User_Detail']['last_name']);
		$this->set('lockcode', preg_replace('#[^\*]#i', '*', $user_detail['Backpack_User']['lock_code']));
		$this->set('email', $user_detail['Backpack_User']['email_address']);
	}

	public function user_activity()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}
		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);

		/*$posted_act = $_POST['activities'];*/
		$limit      = $_POST['number'];

		$activities = $this->User_Activity->find('all', array(
			'conditions'=>array('User_Activity.user_id'=>$uid),
			'fields'    =>array('User_Activity.activity_id', 'User_Activity.activity_desc', 'User_Activity.activity_type', 
				'User_Activity.activity_created'),
			'order'     =>array('User_Activity.activity_created DESC'),
			'offset' => $limit,
			'limit'     => 30));
		if(count($activities) >= 1)
		{
			$post_act = '';
			foreach ($activities as $key => $act) {
				$act_id    = $act['User_Activity']['activity_id'];
				$act_desc  = $act['User_Activity']['activity_desc'];
				$act_type  = $act['User_Activity']['activity_type'];
				$act_date  = date($act['User_Activity']['activity_created']);
				$act_title = (strlen($act_desc) >= 30) ? substr($act_desc, 0, 30) . '...' : $act_desc;
				$now       = date("Y-m-d H:i:s");
				$date      = strtotime($now) - strtotime($act_date);
				$time      = '';
				$color     = $this->FH->activityColorCode($act_type);
				$img       = Router::url("/") . 'img/icons/' . $act_type . '.gif';

				if(floor($date / 3.15569e7) > 0)
				{
					$time = (floor($date / 3.15569e7) > 1) ? floor($date / 3.15569e7) . ' years ago' : floor($date / 3.15569e7) . ' year ago';
				}
				else if(floor($date / 2.63e+6) > 0)
				{
					$time = (floor($date / 2.63e+6) > 1) ? floor($date / 2.63e+6) . ' months ago' : floor($date / 2.63e+6) . ' month ago';
				}
				else if(floor($date / 86400) > 0)
				{
					$time = (floor($date / 86400) > 1) ? floor($date / 86400) . ' days ago' : floor($date / 86400) . ' day ago';
				}
				else if(floor($date / 3600) > 0)
				{
					$time = (floor($date / 3600) > 1) ? floor($date / 3600) . ' hours ago' : floor($date / 3600) . ' hour ago';
				}
				else if(floor($date / 60) > 0)
				{
					$time = (floor($date / 60) > 1) ? floor($date / 60) . ' minutes ago' : floor($date / 60) . ' minute ago';
				}
				else if($date > 0)
				{
					$time = ($date > 1) ? $date . ' seconds ago' : $date->s . ' second ago';
				}

				$post_act .= '{"act_id" : "'. $act_id .'", 
				"act_title" : "'. $act_title .'",
				"act_desc" : "'. $act_desc .'",  
				"act_time" : "'. $time .'",
				"color_code" : "'. $color .'",
				"img_path" : "'. $img .'" },';
			}

			$post_act = rtrim($post_act, ',');
			die('{"status" : "success", "activities" : [ '. $post_act .' ] }');
		}
		else
		{
			die('{"status" : "error", "error_msg" : "No activities yet."}');
		}
	}

	public function backpack()
	{
		
	}

	public function change_name()
	{
		if(isset($_POST['firstname']) && isset($_POST['lastname']))
		{
			$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
			$first_name = $_POST['firstname'];
			$last_name  = $_POST['lastname'];
			$result = array();

			if($first_name != '')
			{
				list($result['firstname']['r_title'], $result['firstname']['r_desc'], $result['firstname']['r_valid'],
				$result['firstname']['r_response']) = $this->UE->check_name($first_name, 'First');

				if($result['firstname']['r_valid'] == false)
				{
					die('{"status":"error", "error_msg":"'. $result['firstname']['r_response'] .'" }');
				}
			}
			
			if($last_name != '')
			{
				list($result['lastname']['r_title'], $result['lastname']['r_desc'], $result['lastname']['r_valid'],
				$result['lastname']['r_response']) = $this->UE->check_name($last_name, 'Last');

				if($result['lastname']['r_valid'] == false)
				{
					die('{"status":"error", "error_msg":"'. $result['lastname']['r_response'] .'" }');
				}

			}

			$update = false;
			if($first_name != '' && $last_name == '')
			{
				$update = $this->User_Detail->updateAll(array('User_Detail.first_name'=>"'$first_name'"), array('User_Detail.user_id'=>$uid));
			}
			else if($first_name == '' && $lastname != '')
			{
				$update = $this->User_Detail->updateAll(array('User_Detail.last_name'=>"'$last_name'"), array('User_Detail.user_id'=>$uid));
			}
			else if($first_name != '' && $last_name != '')
			{
				$update = $this->User_Detail->updateAll(array('User_Detail.first_name'=>"'$first_name'", 'User_Detail.last_name'=>"'$last_name'"), array('User_Detail.user_id'=>$uid));
			}

			if($update)
			{
				$new_name = $this->User_Detail->find('first', array('conditions'=>array('User_Detail.user_id'=>$uid)));
				die('{"status":"success", "success_msg":"Your name has been successfully updated!", "first_name":"'. $new_name['User_Detail']['first_name'] .'", "last_name":"'. $new_name['User_Detail']['last_name'] .'" }');
			}
			else
			{
				die('{"status":"error", "error_msg":"An error occurred while updating your name." }');
			}
			
		}
	}

	function change_lockcode()
	{
		if(isset($_POST['oldlockcode']) && $_POST['oldlockcode'] != '' && isset($_POST['newlockcode']) && $_POST['newlockcode'] != '')
		{
			$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
			$oldlockcode = $_POST['oldlockcode'];
			$newlockcode = $_POST['newlockcode'];

			$eoldlockcode = $this->UE->encryptLockcode($uid, $oldlockcode);
			$is_true = $this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.user_id'=>$uid, 'Backpack_User.lock_code'=>$eoldlockcode)));

			if($is_true == 0)
			{
				die('{"status":"error", "error_msg":"Old password is incorrect." }');
			}
			$result = array();

			list($result['lockcode']['r_title'], $result['lockcode']['r_desc'], $result['lockcode']['r_valid'],
			$result['lockcode']['r_response']) = $this->UE->check_lockcode($newlockcode);

			if($result['lockcode']['r_valid'] == false)
			{
				die('{"status":"error", "error_msg":"'. $result['lockcode']['r_response'] .'" }');
			}

			$enewlockcode = $this->UE->encryptLockcode($uid, $newlockcode);
			if($this->Backpack_User->updateAll(array('Backpack_User.lock_code'=>"'$enewlockcode'"), array('Backpack_User.user_id'=>$uid)))
			{
				die('{"status":"success", "success_msg":"The lock code has been changed successfully." }');
			}

			die('{"status":"error", "error_msg":"An error occured while changing your lockcode" }');
		}
		die('{"status":"error", "error_msg":"Please complete the fields" }');
	}

	public function backpack_settings()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		if(isset($_FILES['useravatar']['name']) && !empty($_FILES['useravatar']['tmp_name']) && $_FILES["useravatar"]["error"] == UPLOAD_ERR_OK)
		{
			$file_name = $_FILES['useravatar']['name'];
			$file_type = substr($file_name, strrpos($file_name, '.') + 1);
			$ext = strtolower($file_type);

			if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "bmp")
			{
				$this->FH->delFiles('u/' . $folder_nt . '/v/profile-pic');
				move_uploaded_file($_FILES['useravatar']['tmp_name'], 'u/' . $folder_nt . '/v/profile-pic/user_avatar.' . $file_type);
			}
			else
			{
				$this->set('imageerror', 'Please upload an image.');
			}
		}

		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$organizer = $this->UE->organizerStatus($uid);
		$overwrite = $this->UE->overwriteStatus($uid);
		($organizer == true) ? $this->set('org_status', 'On') : $this->set('org_status', 'Off');
		($overwrite == true) ? $this->set('ovr_status', 'On') : $this->set('ovr_status', 'Off');
		$this->set('imagepath', $imagepath);
		$this->set('organizer', $organizer);
		$this->set('overwrite', $overwrite);
	}

	public function organizer()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}

		$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
		$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$uid)));
		$organizer = true;
		if($user_details['Backpack_User']['organizer'])
		{
			$organizer = false;
		}

		if($this->Backpack_User->updateAll(array('Backpack_User.organizer'=>$organizer), array('Backpack_User.user_id'=>$uid)))
		{
			$status = ($organizer) ? "On" : "Off";
			die('{"status":"success", "success_msg":"Organizer has been turned '. $status .'", "org_status":"'. $status .'" }');
		}

		die('{"status":"error", "error_msg":"An error occured while updating your organizer." }');
	}

	public function overwrite()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}

		$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
		$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$uid)));
		$overwrite = true;
		if($user_details['Backpack_User']['file_overwrite'])
		{
			$overwrite = false;
		}

		if($this->Backpack_User->updateAll(array('Backpack_User.file_overwrite'=>$overwrite), array('Backpack_User.user_id'=>$uid)))
		{
			$status = ($overwrite) ? "On" : "Off";
			die('{"status":"success", "success_msg":"File overwrite has been turned '. $status .'", "ovr_status":"'. $status .'" }');
		}

		die('{"status":"error", "error_msg":"An error occured while updating your file overwrite." }');
	}

	public function change_bottom()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller'=>'users', 'action'=>'login'));
		}

		$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
		if($this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.user_id'=>$uid))) == 1 && isset($_POST['oldbottomcode']) && isset($_POST['newbottomcode']))
		{
			$old = $_POST['oldbottomcode'];
			$new = $_POST['newbottomcode'];

			if(empty($old) || empty($new))
			{
				die('{"status":"error", "error_code":105, "error_msg":"Please complete the fields." }');
			}

			$eold = $this->UE->encryptLockcode($uid, $old);
			$result = array();

			if($this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.user_id'=>$uid, 'Backpack_User.bottom_code'=>$eold))) == 0)
			{
				die('{"status":"error", "error_code":102, "error_msg":"Old lock code is incorrect, please try again." }');
			}

			list($result['lockcode']['r_title'], $result['lockcode']['r_desc'], $result['lockcode']['r_valid'],
			$result['lockcode']['r_response']) = $this->UE->check_bottomlockcode($new);

			if($result['lockcode']['r_valid'] == false)
			{
				die('{"status":"error", "error_code":103, "error_msg":"'. $result['lockcode']['r_response'] .'" }');
			}

			$enew = $this->UE->encryptLockcode($uid, $new);
			if($this->Backpack_User->updateAll(array('Backpack_User.bottom_code'=>"'$enew'"), array('Backpack_User.user_id'=>$uid)))
			{
				die('{"status":"success", "success_msg":"The bottom lock code has been changed successfully." }');
			}

			die('{"status":"error", "error_code":103, "error_msg":"An error occured while changing your bottom lockcode" }');			
		}

		die('{"status":"error", "error_code":101 }');
	}

	function retrieve()
	{
		if(isset($_GET['timestamp']) && isset($_GET['usedspace']))
		{
			$nametag = $this->Session->read('User.name_tag');
			$uid     = $this->UE->getUserId($nametag);
			$last_visit = preg_replace('#[^0-9]#i', '', $_GET['timestamp']);
			$user_space = preg_replace('#[^A-Z0-9. ]#i', '', $_GET['usedspace']);
			$time = time();
			App::uses('CometHandler', 'Comet');
			$comet = new CometHandler();

			session_write_close();
			while(time() - $time < 25)
			{
				list($data, $submit) = $comet->otherLatest($uid, $nametag, $last_visit, $user_space);

				if($submit == true)
				{
					die($data);
					break;
				}

				usleep(25000);
			}

			die('{"status":"renew"}');
		}

		die('{"status":"error"}');
	}

	public function verify($verification_code)
	{
		$time = strtotime(date('Y-m-d H:i:s'));
		$verify_exist = $this->User_Verification->find('count', array('conditions'=>array('User_Verification.verification_code'=>$verification_code, 'User_Verification.expiration_date >'=>$time)));
		if($verify_exist == 1)
		{
			$verify_details = $this->User_Verification->find('first', array('conditions'=>array('User_Verification.verification_code'=>$verification_code, 'User_Verification.expiration_date >'=>$time)));

			if($this->Backpack_User->updateAll(array('Backpack_User.is_verified'=>true), array('Backpack_User.user_id'=>$verify_details['User_Verification']['user_id'])))
			{
				$this->User_Verification->updateAll(array('User_Verification.expiration_date'=>$time), array('User_Verification.verification_code'=>$verification_code));
				$this->set("verified", true);
			}
		}
		else
		{
			$this->set("verified", false);
		}
	}

	public function new_verification()
	{
		/*$exist = $this->User_Verification->find('count', array('conditions'=>array('User_Verification.verification_code'=>$verification_code, 'User_Verification.expiration_date >'=>$time)));

		if($exist == 1)
		{
			$this->set("message", "The verification code has been resent.");
		}
		else
		{
			$v_code = $this->UE->getVerificationId();
			$expiry = strtotime($joined) + (60 * 60 * 60 * 24);
			$verify = array('User_Verification'=>array('user_id'=>$uid, 'verification_code'=>$v_code, 'expiration_date'=>$expiry));
			$this->User_Verification->save($verify);
			$to = $email;
			$subject = "Verify your account";
			$message = "Greetings " . $nametag .", \r\n\r\nWelcome to Backpack!\r\nWe are very happy to welcome you in backpack. \r\n\r\nIn able for you to fully enjoy your backpack, please verify you account by clicking the link below:\r\n\r\nhttp://" . $_SERVER['SERVER_NAME'] . Router::url('/users/verify/') . $v_code . "\r\n\r\nThank you for joining us and we are hoping that you will enjoy your backpack. \r\n\r\n\r\nSincerely, \r\n\r\nThe Backpack Team";
			$from = "noreply@skybackpack.com";
			$headers = "From: " . $from;
			if(mail($to,$subject,$message,$headers))
			{
				$this->redirect(array('action' => 'login'));
			}
		}*/
		if(isset($_POST['verifyEmail']))
		{
			$email = $_POST['verifyEmail'];
			$result = array();

			list($result['email']['r_title'], $result['email']['r_desc'], $result['email']['r_valid'],
			$result['email']['r_response']) = $this->UE->verify_email($email);

			if($result['email']['r_valid'])
			{
				$is_verified = $this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.email_address like binary'=>$email, 'Backpack_User.is_verified'=>true)));

				if($is_verified == 1)
				{
					$this->set('message', 'This account is already verified. <a href="'. Router::url("/login") .'">Click here</a> to login or go back to your main pocket.');
				}
				else
				{
					$time = strtotime(date('Y-m-d H:i:s'));
					$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.email_address'=>$email)));
					$exist = $this->User_Verification->find('count', array('conditions'=>array('User_Verification.user_id'=>$user_details['Backpack_User']['user_id'] , 'User_Verification.expiration_date >'=>$time)));
					$v_code = '';
					if($exist == 1)
					{
						$verification_details = $this->User_Verification->find('first', array('conditions'=>array('User_Verification.user_id'=>$user_details['Backpack_User']['user_id'], 'User_Verification.expiration_date >'=>$time)));
						$v_code = $verification_details['User_Verification']['verification_code'];
					}
					else
					{
						$v_code = $this->UE->getVerificationId();
						$expiry = strtotime(date('Y-m-d H:i:s')) + (60 * 60 * 60 * 24);
						$verify = array('User_Verification'=>array('user_id'=>$user_details['Backpack_User']['user_id'] , 'verification_code'=>$v_code, 'expiration_date'=>$expiry));
						$this->User_Verification->save($verify);
					}

					$to = $email;
					$subject = "Verify your account";
					$message = "Greetings " . $user_details['Backpack_User']['name_tag'] .", \r\n\r\nWelcome to Backpack!\r\nWe are very happy to welcome you in backpack. \r\n\r\nIn able for you to fully enjoy your backpack, please verify you account by clicking the link below:\r\n\r\nhttp://" . $_SERVER['SERVER_NAME'] . Router::url('/users/verify/') . $v_code . "\r\n\r\nThank you for joining us and we are hoping that you will enjoy your backpack. \r\n\r\n\r\nSincerely, \r\n\r\nThe Backpack Team";
					$headers = "From: noreply@skybackpack.com";
					if(mail($to,$subject,$message,$headers))
					{
						$this->set("message", 'The verification code has been sent successfully. <a href="'. Router::url("/login") .'">Click here</a> to go back to login page or main pocket.');
					}
				}
			}
			else
			{
				$this->set("message", $result['email']['r_response']);
			}
		}
	}

	public function contact()
	{
		if($this->Session->check('User'))
		{
			$this->set('online', true);
			$folder_nt = $this->FH->getOuterFolder($this->Session->read('User.name_tag'));
			$this->set('image', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/' . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic')));
		}
		else
		{
			$this->set('online', false);
		}
	}

	public function credits()
	{
		if($this->Session->check('User'))
		{
			$this->set('online', true);
			$folder_nt = $this->FH->getOuterFolder($this->Session->read('User.name_tag'));
			$this->set('image', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/' . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic')));
		}
		else
		{
			$this->set('online', false);
		}
	}

	public function about()
	{
		if($this->Session->check('User'))
		{
			$this->set('online', true);
			$folder_nt = $this->FH->getOuterFolder($this->Session->read('User.name_tag'));
			$this->set('image', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/' . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic')));
		}
		else
		{
			$this->set('online', false);
		}
	}

	public function customer()
	{
		if($this->Session->check('User'))
		{
			$this->set('online', true);
			$folder_nt = $this->FH->getOuterFolder($this->Session->read('User.name_tag'));
			$this->set('image', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/' . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic')));
		}
		else
		{
			$this->set('online', false);
		}
	}

	public function forgot()
	{
		if($this->Session->check('User'))
		{
			$this->set('online', true);
			$folder_nt = $this->FH->getOuterFolder($this->Session->read('User.name_tag'));
			$this->set('image', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/' . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic')));
		}
		else
		{
			$this->set('online', false);
		}
	}

}