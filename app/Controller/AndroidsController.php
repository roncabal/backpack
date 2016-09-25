<?php

App::uses('Sanitize', 'Utility');
App::uses('Folder', 'Utility');
date_default_timezone_set('Asia/Manila');

/**
* 
*/
class AndroidsController extends AppController
{
	
	public $uses = array('Backpack_User', 'User_Detail', 'User_Activity', 'User_Verification', 'User_File');

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
		$nametag = $_REQUEST['name_tag'];
		$lockcode = $_REQUEST['lock_code'];
		$uid      = $this->UE->getUserId($nametag);

		if($this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.name_tag'=>$nametag))) == 1)
		{
			$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.name_tag'=>$nametag)));

			$elockcode = $this->UE->encryptLockcode($user_details['Backpack_User']['user_id'], $lockcode); 
			if($this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.name_tag'=>$nametag, 'Backpack_User.lock_code'=>$elockcode))) == 1)
			{

				$user_details = $this->Backpack_User->query("SELECT * FROM backpack__users AS Backpack_User LEFT JOIN user__details AS User_Detail ON Backpack_User.user_id = User_Detail.user_id WHERE Backpack_User.user_id = '$uid' ");

				$user = '';

				foreach ($user_details as $key => $detail) {
					$overwrite = 0;
					if($detail['Backpack_User']['file_overwrite'])
					{
						$overwrite = 1;
					}
					$user = '{"name_tag":"'. $detail['Backpack_User']['name_tag'] .'", "fname":"'. $detail['User_Detail']['first_name'] .'", "lname":"'. $detail['User_Detail']['last_name'] .'", "email":"'. addslashes($detail['Backpack_User']['email_address']) .'" , "file_overwrite":"'. $overwrite .'"}';
				}

				die('{"status":1, "user":'. $user .' }');
			}
			else
			{
				die('{"status":0 }');
			}
		}
		else
		{
			die('{"status":0 }');
		}

	}

	public function register()
	{
		
		if(isset($_POST['name_tag']) && isset($_POST['lock_code']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']))
		{
			$result = array();
			$nametag   = $_POST['name_tag'];
			$firstname = $_POST['first_name'];
			$lastname  = $_POST['last_name'];
			$email     = $_POST['email'];
			$lockcode  = $_POST['lock_code'];

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

			$agree = 'agree';

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
							die('{"status":1}');
						}

					}
				}
			}
			else
			{
				die('{"status":0, "name_tag":"'. $result['nametag']['r_response'] .'", "lock_code":"'. $result['lockcode']['r_response'].'", "first_name":"'. $result['firstname']['r_response'] .'", "last_name":"'. $result['lastname']['r_response'] .'", "email":"'. substr($result['email']['r_response'], 0, strpos($result['email']['r_response'], ".") + 1) .'" }');
			}
		}
	}

	public function get_everything()
	{
		$nametag = $_REQUEST['name_tag'];
		$uid     = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt   = $this->FH->getOuterFolder($nametag);
		$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name;

		$all_files   = $this->FH->getAllFiles($full_path, 'main');
		$all_files   = rtrim($all_files, ',');

		die('{"status":1, "files":['. $all_files .'] }');
	}

	public function download_file()
	{
		if(isset($_GET['file_name']) && isset($_GET['file_path']) && !empty($_GET['file_path']) && !empty($_GET['file_name']))
		{
			$download = $_GET['file_name'];
			$pocket   = $_GET['file_path'];
			$nametag  = $_GET['name_tag'];
			$uid = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt = $this->FH->getOuterFolder($nametag);
			$folder_path = 'u' . '/' . $folder_nt . '/' . $folder_name;
			$file_path = $folder_path . '/' . $pocket;
			$created = date('Y-m-d H:i:s');

			if(file_exists($file_path . '/' . $download))
			{
				if(is_dir($file_path . '/' . $download))
				{
					$file_download = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . 'download';
					$zip_name = preg_replace('/ /', '_', $download) . '.zip';
					$zip_path = $file_download . '/' . $zip_name;
					copy('files' . '/' . 'backpack-zip-create.zip', $zip_path);
					$zip = new ZipArchive;
					$create = $zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

					if($create == true)
					{
						$this->FH->addToZip($zip, $file_path . '/' . $download, $download);
						$zip->close();
					}					

					header('Content-type: application/zip');
					header('Content-disposition: filename="' . $zip_name . '"');
					header("Content-length: " . filesize($zip_path));
					if(readfile($zip_path))
					{
						unlink($zip_path);
						exit();
					}
				}
				else
				{
					if($this->FH->download_file($download, $file_path . '/' . $download))
					{
						$file_id = $this->FH->getFileId($download, rtrim($pocket, '/'));
						$file_details = $this->User_File->find('first', array('conditions'=>array('User_File.file_id'=>$file_id)));
						$downloads = $file_details['User_File']['file_download'] + 1;
						$this->User_File->updateAll(array('User_File.file_download'=>$downloads), array('User_File.file_id'=>$file_id));

						$a_desc   = "Downloaded " . $download;
						$a_type   = "download";
						$a_id     = $this->UE->getActivityId($uid, $nametag);
						$ip       = $this->UE->getRealIPAddress();
						$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
						"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

						$this->User_Activity->create();
						$this->User_Activity->save($activity);
					}
				}
			}
			else
			{
				die('{ "status" : "error", "error_msg" : "There was an error while downloading your file.'. $download .'" }');
			}
		}

		die('{ "status" : "error", "error_msg" : "There was an error while downloading your filesd." }');
	}

	public function file_upload()
	{
		if(isset($_GET['name_tag']) && isset($_GET['lock_code']))
		{
			$nametag = $_GET['name_tag'];
			$uid         = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			$user_path   = 'u/' . $folder_nt . '/' . $folder_name;
			$full_path   = rtrim($full_path, '/');
			$update      = false;

			$user_details = $this->Backpack_User->find('first', array(
				'conditions'=>array('Backpack_User.user_id'=>$uid),
				'fields'    =>array('Backpack_User.backpack_size'),
				'limit'     =>1));

			$backpack_size = $user_details['Backpack_User']['backpack_size'];
			$main_used     = $this->FH->getFolderSize($user_path, 'main');
			$bottom_used   = $this->FH->getFolderSize($user_path, 'bottom');
			if($main_used + $bottom_used + $_FILES['file']['size'] > $backpack_size)
			{
				die('{"status":"error", "error_msg":"Your bag is full." }');
			}

			$targetDir = 'u' . DS . $folder_nt . DS . $folder_name . DS . 'main';

			$file_name = $_FILES['file']['name'];

			if($this->UE->overwriteStatus($uid))
			{
				$update = true;
				$file_exists = true;
				unlink($targetDir . DS . $file_name);
			}
			else
			{
				$ext = strrpos($file_name, '.');
				$fileName_a = substr($file_name, 0, $ext);
				$fileName_b = substr($file_name, $ext);

				$count = 1;
				while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
					$count++;

				$file_name = $fileName_a . '_' . $count . $fileName_b;
			}

			$filePath = $targetDir . DIRECTORY_SEPARATOR . $file_name;

			if(move_uploaded_file($_FILES['file']['tmp_file'], $filePath))
			{
				$created = date("Y-m-d H:i:s");
				$file_id = null;
				if($update == false)
				{
					$a_desc = "Uploaded " . $file_name;
					$a_type = "upload";

					$file_id = $this->FH->getFid();
					$userfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$file_name, 'file_dir'=>$full_path, 
					'file_modified'=>$created));

					$this->User_File->save($userfile);
				}
				else
				{
					$a_desc = "Updated " . $file_name;
					$a_type = "update";

					$file_id = $this->FH->getFileId($file_name, $full_path);
					$this->User_File->updateAll(array("User_File.file_modified"=>`$created`),
						array("User_File.file_id"=>$file_id));
				}

				$a_id     = $this->UE->getActivityId($uid, $nametag);
				$ip       = $this->UE->getRealIPAddress();
				$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
					"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

				if($this->User_Activity->save($activity))
				{
					die('{"status" : "success", "success_msg" : "File was uploaded successfully!", "file_name":"'. $file_name .'"}');
				}
			}
		}
	}
}