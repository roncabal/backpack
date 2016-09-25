<?php

App::uses('Sanitize', 'Utility');
App::uses('Folder', 'Utility');
App::uses('BackpackHelper', 'Helper');
App::uses('File', 'Utility');
date_default_timezone_set('Asia/Manila');

/**
* 
*/
class PocketsController extends AppController
{
	
	public $uses = array('User_Sharefile', 'User_Message', 'Backpack_User', 'User_Detail', 'User_Activity', 'User_File', 'Backpack_Group', 'Group_Member', 'Group_File', 'Group_Message', 'Group_Request', 'Backpack_Genetic', 'Backpack_Word');

	private $UE;

	private $FH;

	function beforeFilter()
	{
		parent::beforeFilter();

		App::uses('UsersExtension', 'Validate');
		App::uses('FilesHandler', 'Validate');
		$this->UE = new UsersExtension();
		$this->FH = new FilesHandler();

	}

	public function get_files($folder_path = null)
	{
		if($folder_path != null)
		{
			$nametag     = $this->Session->read('User.name_tag');
			$uid         = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . rtrim($folder_path, '/');
			$pocket      = $_POST['pocket'];
			$image_view_path = 'u' . '/' . $folder_nt . '/' . 'v';	

			if(file_exists($full_path) && $this->FH->delFiles($image_view_path))
			{
				$items = scandir($full_path);
				$file_count = count($items) - 2;

				$main_pocket = 800;
				$columns = $file_count / 5;
				$columns = (BackpackHelper::is_decimal($columns)) ? floor($columns) + 1 : $columns;
				$file_holder = 310 * $columns;
				if($columns == 3)
				{
					$main_pocket += 200;
				}
				else if($columns > 3)
				{
					$add_width = $columns - 3;
					$main_pocket += 200 + ((310 * $add_width) + (30 * $add_width));
				}

				$result = '';

				if($file_count != 0)
				{
					$count = 1;
					foreach($items as $item)
					{
						if($item != '.' && $item != '..')
						{
							$item_name = '';
							$item_type  = '';

							if(is_dir($full_path . '/' . $item))
							{
								$item_name = $item;
								$item_type = 'folder';
								$item_size = '';
							}
							else
							{
								$item_name = substr($item, 0, strrpos($item, '.'));
								$item_type  = substr($item, strrpos($item, '.') + 1);
								$item_size = $this->FH->getFileSize(filesize($full_path . '/' . $item));
							}

							if($this->FH->isImage($item_type))
							{
								copy($full_path . '/' . $item, $image_view_path . '/' . $item);
							}
							$color     = $this->FH->fileColorCode($item_type);
							$date      = date("M. j, Y", filemtime($full_path . '/' . $item)); 
							clearstatcache();
							$item_name = (strlen($item_name) >= 15) ? substr($item_name, 0, 15) . '...' : $item_name;
							$file_url  = Router::url('/downloads/download_file/') . $item;

							if(is_dir($full_path . '/' . $item))
							{
								$result = '{ "file_id" : "f_id_'. $count .'", 
								"file_name" : "'. $item_name .'",
								"full_name" : "'. $item .'", 
								"file_size" : "'. $item_size .'", 
								"file_type" : "'. $item_type .'",
								"file_url" :  "'. $file_url .'",
								"date_modified" : "'. $date .'",
								"color_code" : "'. $color .'" },' . $result;
							}
							else
							{
								$result .= '{ "file_id" : "f_id_'. $count .'", 
								"file_name" : "'. $item_name .'", 
								"full_name" : "'. $item .'", 
								"file_size" : "'. $item_size .'", 
								"file_type" : "'. $item_type .'", 
								"file_url" :  "'. $file_url .'", 
								"date_modified" : "'. $date .'",
								"color_code" : "'. $color .'" },';
							}
							
							$count++;
						}
					}

					if($folder_path != 'main/' && $folder_path != 'bottom/')
					{
						$back_url = rtrim($folder_path, '/');
						$back_url = Router::url('/', true) . substr($back_url, 0, strrpos($back_url, '/'));
						$result = '{ "file_id" : "backFolder", 
						"file_name" : "Back",
						"full_name" : "Back to previous folder.", 
						"file_size" : "", 
						"file_type" : "Back",
						"file_url" :  "'. $back_url .'",
						"date_modified" : "" },' . $result;
					}

					$result = rtrim($result, ',');
					die('{ "status" : "success", "files" : [ ' . $result . ' ],
					 "pocket" : ' . $main_pocket . ', "pocket_holder" : ' . ($main_pocket + 450) . ' }');

				}
				else
				{
					if($folder_path == 'main/' || $folder_path == 'bottom/')
					{
						die('{ "status" : "error", "error_code":0, "error_msg" : "No files added yet." }');
					}
					else
					{
						$back_url = rtrim($folder_path, '/');
						$back_url = Router::url('/', true) . substr($back_url, 0, strrpos($back_url, '/'));
						$result = '{ "file_id" : "backFolder", 
						"file_name" : "Back",
						"full_name" : "Back to previous folder.", 
						"file_size" : "", 
						"file_type" : "Back",
						"file_url" :  "'. $back_url .'",
						"date_modified" : "" },' . $result;

						$result = rtrim($result, ',');
						die('{ "status" : "success", "files" : [ ' . $result . ' ],
						 "pocket" : ' . $main_pocket . ', "pocket_holder" : ' . ($main_pocket + 450) . ' }');
					}
					
				}

			}
			else
			{
				die('{ "status" : "error", "error_msg" : "This folder does not exist" }');
			}

		}

		die('{ "status" : "error", "error_msg" : "This folder does not exist" }');

	}

	public function main($folder = null)
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		$full_path = 'main/';
		if($folder != null)
		{
			$folder = rtrim($folder, '/');
			$full_path .= $folder . '/';
		}

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$imagepath = "http://" . $_SERVER['SERVER_NAME'] . Router::url("/" . $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic'));
		if($this->UE->organizerStatus($uid))
		{
			$this->set('organizer', "1");
		}
		else
		{
			$this->set('organizer', "0");
		}

		if($this->UE->newToMain($uid))
		{
			$this->set('newtomain', '1');
		}
		else
		{
			$this->set('newtomain', '0');
		}

		$this->set('viewpath', 'http://' . $_SERVER['SERVER_NAME'] . Router::url('/u/' . $folder_nt . '/v/'));
		$this->set('imagepath', $imagepath);
		$this->set('full_path', $full_path); 
	}

	public function genetics()
	{
		if(isset($_POST['files']))
		{
			$files = $_POST['files'];
			App::uses('GeneticAlgorithm', 'GeneticAlgorithm');
			$GA = new GeneticAlgorithm();
			$temp_class = $GA->genetics($files);
			$class = array_shift($temp_class);

			$folders = '';
			foreach ($class as $folder => $org_files) {
				if(count($class[$folder]) > 1)
				{
					$folder_files = '';
					foreach ($org_files as $key => $value) {
						$folder_files .= '{"file_name":"'. $value .'" },';
					}

					$folder_files = rtrim($folder_files, ',');
					$folders .= '{"folder_name": "'. $folder.'", "folder_files": ['. $folder_files .'] },';
				}
			}

			$folders = rtrim($folders, ',');
			die('{"status":"success", "suggestions":['. $folders .'] }');
		}

		die('{"status":"error", "error_msg":"Please upload a file." }');
	}

	public function accept_organize()
	{
		if(isset($_POST['files']) && isset($_POST['fullpath']) && !preg_match('#[\\\<\>\'\|\?:\*\"\.]#i', $_POST['fullpath'] ) && isset($_POST['foldername']))
		{
			if(empty($_POST['foldername']))
			{
				die('{"status" : "error", "error_msg":"Folder name must not be blank."}');
			}
			else if(preg_match('#[\\\<\>\'/\|\?:\*\"\.]#i', $_POST['foldername']))
			{
				die('{"status" : "error", "error_msg":"Some characters are not allowed."}');
			}
			else if(strlen($_POST['foldername']) > 20)
			{
				die('{"status" : "error", "error_msg":"Folder name must be less than 21 characters."}');
			}
			else
			{
				$nametag     = $this->Session->read('User.name_tag');
				$uid         = $this->UE->getUserId($nametag);
				$folder      = $_POST['foldername'];
				$path        = rtrim($_POST['fullpath'], '/');
				$file_path   = $path . '/' . $folder;
				$folder_nt   = $this->FH->getOuterFolder($nametag);
				$folder_name = $this->FH->getFolderName($uid, $nametag);
				$full_path   = 'u/' . $folder_nt . '/' . $folder_name;;
				$files       = $_POST['files'];
				$created     = date("Y-m-d H:i:s");
				$mutate_files = array();

				if(file_exists($full_path . '/' . $folder))
				{
					$message = "All files have been moved to " . $folder;
				}
				else
				{
					$message = $folder . " folder has been created and all files have been moved.";
				}

				if(!file_exists($full_path . '/' . $file_path))
				{
					$cfolder = new Folder();
					if($cfolder->create(WWW_ROOT . $full_path . '/' . $file_path))
					{
						$file_id     = $this->FH->getFid();
						$userfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$folder, 'file_dir'=>$path, 
						'file_modified'=>$created));

						$a_desc   = "Created the folder " . $folder;
						$a_type   = "newfolder";
						$a_id     = $this->UE->getActivityId($uid, $nametag);
						$ip       = $this->UE->getRealIPAddress();
						$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
						"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

						$this->User_File->save($userfile);
						$this->User_Activity->save($activity);
					}
				}

				if(file_exists($full_path . '/' . $file_path))
				{
					foreach ($files as $key => $file) {
						if(file_exists($full_path . '/' . $path . '/' . $file) && !empty($file))
						{
							if(file_exists($full_path . '/' . $file_path . '/' . $file))
							{
								if($this->UE->overwriteStatus($uid))
								{
									$dfile_id = $this->FH->getFileId($file, $file_path);
									if(rename($full_path . '/' . $path . '/' . $file, $full_path . '/' . $file_path . '/' . $file))
									{
										$this->User_File->deleteAll(array('User_File.file_id'=>$dfile_id));
										$file_id = $this->FH->getFileId($file, $path);
										$this->User_File->updateAll(array('User_File.file_dir'=>"'$file_path'"), array('User_File.file_id'=>$file_id));
										$a_desc   = "Moved and Updated " . $file . ' to ' . $folder;
										$a_type   = "move";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

										$this->User_Activity->create();
										$this->User_Activity->save($activity);
										array_push($mutate_files, $file);
									}
								}
								else
								{
									$count = 1;
									$item_name = substr($file, 0, strrpos($file, '.'));
									$item_type = substr($file, strrpos($file, '.') + 1);
									while(file_exists($full_path . '/' . $file_path . '/' . $item_name . '(' . $count . ').' . $item_name))
										$count++;

									$new_file = $item_name . '(' . $count . ').' . $item_type;

									if(rename($full_path . '/' . $path . '/' . $file, $full_path . '/' . $file_path . '/' . $new_file))
									{
										$file_id = $this->FH->getFileId($file, $path);
										$this->User_File->updateAll(array('User_File.file_name'=>"'$new_file'", 'User_File.file_dir'=>"'$file_path'"), array('User_File.file_id'=>$file_id));
										$a_desc   = "Moved " . $file . ' to ' . $folder .' and was renamed to ' . $new_file;
										$a_type   = "move";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

										$this->User_Activity->create();
										$this->User_Activity->save($activity);
										array_push($mutate_files, $file);
									}
								}
								
							}
							else
							{
								if(rename($full_path . '/' . $path . '/' . $file, $full_path . '/' . $file_path . '/' . $file))
								{
									$file_id = $this->FH->getFileId($file, $path);
									$this->User_File->updateAll(array('User_File.file_dir'=>"'$file_path'"), array('User_File.file_id'=>$file_id));
									$a_desc   = "Moved " . $file . ' to ' . $folder;
									$a_type   = "move";
									$a_id     = $this->UE->getActivityId($uid, $nametag);
									$ip       = $this->UE->getRealIPAddress();
									$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
									"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

									$this->User_Activity->create();
									$this->User_Activity->save($activity);
									array_push($mutate_files, $file);
								}
							}
							
						}
					}

					App::uses('GeneticAlgorithm', 'GeneticAlgorithm');
					$GA = new GeneticAlgorithm();
					$GA->mutation($folder, $mutate_files);
					die('{"status":"success", "success_msg":"'. $message .'" }');
				}
				else
				{
					die('{"status":"error", " error_msg":"An error occurred while arranging your files." }');
				}
				
			}
		
		}
		else
		{
			die('{"status":"error", " error_msg":"An error occurred while arranging your files." }');
		}
	}

	public function get_links()
	{
		if(isset($_POST['file']) && !empty($_POST['file']) && isset($_POST['fullpath']) && !empty($_POST['fullpath']))
		{
			$nametag     = $this->Session->read('User.name_tag');
			$uid         = $this->UE->getUserId($nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$file_path   = rtrim($_POST['fullpath'], '/');
			$file        = $_POST['file'];
			$time        = strtotime(date('Y-m-d H:i:s'));
			$created     = date('Y-m-d H:i:s');

			if(preg_match('#[\\\<\>\'\|\?:\*\"\.]#i', $file_path))
			{
				die('{"status":"error", "error_msg":"This folder does not exist." }');
			}

			$fullpath = 'u/' . $folder_nt . '/' . $folder_name . '/' . $file_path;

			if(!file_exists($fullpath . '/' . $file))
			{
				die('{"status":"error", "error_msg":"This file does not exist." }');
			}

			$share_exist = $this->User_Sharefile->find('count', array('conditions'=>array('User_Sharefile.user_id'=>$uid, 'User_Sharefile.file_name'=>$file, 'User_Sharefile.file_dir'=>$file_path, 'User_Sharefile.expiry_date >'=> $time)));

			if($share_exist == 1)
			{
				$share_info = $this->User_Sharefile->find('first', array('conditions'=>array('User_Sharefile.user_id'=>$uid, 'User_Sharefile.file_name'=>$file, 'User_Sharefile.file_dir'=>$file_path, 'User_Sharefile.expiry_date >'=> $time)));
				$claim      = $share_info['User_Sharefile']['share_id'];
				$share_url  = $_SERVER['SERVER_NAME'] . Router::url("/downloads/download_shared/" . $claim);
				die('{"status":"success", "claim_code":"'. $claim .'", "share_url":"'. $share_url .'" }');
			}

			$share_id = $this->FH->getShareId();
			$expiry_date = (60 * 60 * 60 * 24) + $time;
			$share = array('User_Sharefile'=>array('share_id'=>$share_id, 'user_id'=>$uid, 'file_name'=>$file, 'file_dir'=>$file_path, 'expiry_date'=>$expiry_date));
			if($this->User_Sharefile->save($share))
			{
				$file_id = $this->FH->getFileId($file, $file_path);
				$file_details = $this->User_File->find('first', array('conditions'=>array('User_File.file_id'=>$file_id)));
				$shares = $file_details['User_File']['file_share'] + 1;
				$this->User_File->updateAll(array('User_File.file_share'=>$shares), array('User_File.file_id'=>$file_id));

				$a_desc   = "Shared a link of " . $file;
				$a_type   = "share";
				$a_id     = $this->UE->getActivityId($uid, $nametag);
				$ip       = $this->UE->getRealIPAddress();
				$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
				"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

				$this->User_Activity->create();
				$this->User_Activity->save($activity);

				$claim      = $share_id;
				$share_url  = $_SERVER['SERVER_NAME'] . Router::url("/downloads/download_shared/" . $claim);
				die('{"status":"success", "claim_code":"'. $claim .'", "share_url":"'. $share_url .'" }');
			}

			die('{"status":"error", "error_msg":"An error occurred while getting file\'s link." }');
		}

		die('{"status":"error", "error_msg":"An error occurred while getting file\'s link." }');
	}

	public function main_upload($full_path = null)
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		$nametag     = $this->Session->read('User.name_tag');
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


		/**
		 * upload.php
		 *
		 * Copyright 2009, Moxiecode Systems AB
		 * Released under GPL License.
		 *
		 * License: http://www.plupload.com/license
		 * Contributing: http://www.plupload.com/contributing
		 */

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");



		// Settings
		//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";

		$targetDir = 'u' . DS . $folder_nt . DS . $folder_name . DS . $full_path;

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$file_name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the fileName for security reasons
		//$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
		$file_name = preg_replace('#[\<\>\'\\/\|\?\:\*\"]#i', '', $file_name);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $file_name)) {

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
			
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file_name;

		// Create target dir
		if (!file_exists($targetDir))
			//@mkdir($targetDir);
			die('{"jsonrpc":"2.0", "result":"Folder does not exist.", "id":"id"}');

		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

				fclose($in);
				fclose($out);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}

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

	public function create_folder($folder = null)
	{
		$c_folder = $_REQUEST['folder_name'];
		if(empty($c_folder))
		{
			die('{"jsonrpc" : "2.0", "result" : {"message":"Folder name must not be blank."}, "id" : "id"}');
		}
		else if(preg_match('#[\\\<\>\'/\|\?:\*\"\.]#i', $c_folder))
		{
			die('{"jsonrpc" : "2.0", "result" : {"message":"Some characters are not allowed."}, "id" : "id"}');
		}
		else if(strlen($c_folder) > 20)
		{
			die('{"jsonrpc" : "2.0", "result" : {"message":"Folder name must be less than 21 characters."}, "id" : "id"}');
		}
		else
		{
			$nametag     = $this->Session->read('User.name_tag');
			$uid         = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			
			$file_dir = ltrim(rtrim($folder, '/'), '/');
			$full_path = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $file_dir . '/' . $c_folder;
			
			if(!file_exists($full_path))
			{
				$folder = new Folder();
				if($folder->create(WWW_ROOT . '/' . $full_path))
				{
					$created     = date("Y-m-d H:i:s");
					$file_id     = $this->FH->getFid();
					$userfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$c_folder, 'file_dir'=>$file_dir, 
					'file_modified'=>$created));

					$a_desc   = "Created the folder " . $c_folder;
					$a_type   = "newfolder";
					$a_id     = $this->UE->getActivityId($uid, $nametag);
					$ip       = $this->UE->getRealIPAddress();
					$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
					"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

					$this->User_File->save($userfile);
					$this->User_Activity->save($activity);

					die('{"jsonrpc" : "2.0", "result" : {"message":"Folder has been created successfully."}, "id" : "id"}');
				}
			}
			else
			{
				die('{"jsonrpc" : "2.0", "result" : {"message":"Folder already exists."}, "id" : "id"}');
			}
		}

		die('{"jsonrpc":"2.0", "result":{"message":"A problem has occurred while deleting the item."}, "id":"id"}');
		
	}

	public function delete_items()
	{
		$items = (isset($_POST['items'])) ? $_POST['items'] : "";
		$file_path = rtrim($_POST['full_path'], '/');

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$trash_folder = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . 'trash';
		$full_path = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $file_path ;
		foreach($items as $item)
		{
			if(file_exists($full_path . '/' . $item))
			{
				$created = date("Y-m-d H:i:s");
				$file_id = $this->FH->getFileId($item, $file_path);
				if(is_dir($full_path . '/' . $item))
				{
					if($this->FH->delTree($full_path . '/' . $item))
					{
						$this->User_File->deleteAll(array("User_File.file_id"=>$file_id));
						$this->User_File->deleteAll(array('User_File.file_dir'=>$file_path));

						$a_desc   = "Deleted the file " . $item;
						$a_type   = "delete";
						$a_id     = $this->UE->getActivityId($uid, $nametag);
						$ip       = $this->UE->getRealIPAddress();
						$activity = array("User_Activity"=>array("activity_id"=>$a_id, "activity_desc"=>$a_desc, "activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

						$this->User_Activity->save($activity);
					}
				}
				else
				{
					if(rename($full_path . '/' . $item, $trash_folder . '/' . $item))
					{
						$this->User_File->deleteAll(array("User_File.file_id"=>$file_id));

						$a_desc   = "Deleted the file " . $item;
						$a_type   = "delete";
						$a_id     = $this->UE->getActivityId($uid, $nametag);
						$ip       = $this->UE->getRealIPAddress();
						$activity = array("User_Activity"=>array("activity_id"=>$a_id, "activity_desc"=>$a_desc, "activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

						$this->User_Activity->save($activity);
					}
				}
				
			}
			
		}

		die('{"status" : "success", "success_msg" : "File(s) has been deleted." }');

	}

	public function rename_item()
	{
		$item_name   = $_REQUEST['name'];
		$newname     = $_REQUEST['newname'];
		$item_path   = rtrim($_REQUEST['full_path'], '/');
		$nametag     = $this->Session->read('User.name_tag');
		$uid         = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt   = $this->FH->getOuterFolder($nametag);
		$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $item_path;

		if(empty($item_name))
		{
			die('{"status":"error", "error_msg":"Please select an item."}');
		}
		elseif(empty($newname))
		{
			die('{"status":"error", "error_msg":"Please enter your desired name."}');
		}
		elseif(preg_match('#[\\\<\>\'/\|\?:\*\"]#i', $newname))
		{
			die('{"status":"error", "error_msg":"Some characters are not allowed."}');
		}
		else
		{
			if(file_exists($full_path . '/' . $item_name))
			{
				if(is_dir($full_path . '/' . $item_name))
				{
					if(preg_match("/\./", $newname))
					{
						die('{ "status" : "error", "error_msg" : "Some chatacters are not allowedas '.$newname.'." }');
					}
					else
					{
						if(rename($full_path . '/' . $item_name, $full_path . '/' . $newname))
						{
							$file_id = $this->FH->getFileId($item_name, $item_path);
							if(is_dir($full_path . '/' . $newname))
							{
								$file_details = $this->User_File->find('first', array('conditions'=>array('User_File.file_id'=>$file_id)));
								
								$old_destination = $file_details['User_File']['file_dir'] . '/' . $item_name;
								$update_files = $this->User_File->find('all', array('conditions'=>array('User_File.file_dir like'=>$old_destination . '%')));

								foreach ($update_files as $key => $update_file) {
									$new_dest = $file_details['User_File']['file_dir'] . '/' . $newname;
									$old_dest = preg_replace("/\//", '\\\/', $old_destination);
									$new_destination = preg_replace('/' . $old_dest . '/', $new_dest, $update_file['User_File']['file_dir'], 1);
									$this->User_File->updateAll(array('User_File.file_dir'=>"'$new_destination'"), array('User_File.file_dir'=>$update_file['User_File']['file_dir']));
								}
							}

							$created = date("Y-m-d H:i:s");
							$this->User_File->updateAll(array("User_File.file_name"=>"'$newname'"), array("User_File.file_id"=>$file_id));

							$a_desc   = "Rename the file " . $item_name;
							$a_type   = "rename";
							$a_id     = $this->UE->getActivityId($uid, $nametag);
							$ip       = $this->UE->getRealIPAddress();
							$activity = array("User_Activity"=>array("activity_id"=>$a_id, "activity_desc"=>$a_desc,
							"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

							$this->User_Activity->save($activity);
							die('{ "status" : "success", "success_msg" : "File has been renamed." }');

						}
						else
						{
							die('{ "status" : "error", "error_msg" : "There was an error while renaming your file." }');
						}
					}
					
				}
				else
				{
					$file_type = substr($item_name, strrpos($item_name, '.') + 1);
					$newname   = $newname . '.' . $file_type;

					if(rename($full_path . '/' . $item_name, $full_path . '/' . $newname))
					{
						$created = date("Y-m-d H:i:s");
						$file_id = $this->FH->getFileId($item_name, $item_path);
						$this->User_File->updateAll(array("User_File.file_name"=>"'$newname'"),
						array("User_File.file_id"=>$file_id));

						$a_desc   = "Rename the file " . $item_name;
						$a_type   = "rename";
						$a_id     = $this->UE->getActivityId($uid, $nametag);
						$ip       = $this->UE->getRealIPAddress();
						$activity = array("User_Activity"=>array("activity_id"=>$a_id, "file_id"=>$file_id, "activity_desc"=>$a_desc,
						"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

						$this->User_Activity->save($activity);
						die('{ "status" : "success", "success_msg" : "File has been renamed." }');
					}
					else
					{
						die('{ "status" : "error", "error_msg" : "There was an error while renaming your file." }');
					}
				}
			}
			else
			{
				die('{ "status" : "error", "error_msg" : "There was an error while renaming your file." }');
			}
		}

		die('{"status":"error", "error_msg":"A problem has occurred while renaming the item."}');

	}

	public function open_item()
	{
		if(isset($_REQUEST['item']))
		{
			$nametag   = $this->Session->read('User.name_tag');
			$folder_nt = $this->FH->getOuterFolder($nametag);
			$item_id   = preg_replace('#[^A-Za-z0-9_]#i', '', array_shift($_REQUEST['item']));
			$item_details = $this->File_Handler->find('first', array(
				'conditions'=>array('File_Handler.file_id'=>$item_id),
				'fields'    =>array('File_Handler.file_name', 'File_Handler.file_size', 'File_Handler.file_type',
					                'File_Handler.sort_type', 'File_Handler.file_dir'),
				'limit'     =>1));

			$item_name = $item_details['File_Handler']['file_name'];
			$item_size = $item_details['File_Handler']['file_size'];
			$item_type = $item_details['File_Handler']['file_type'];
			$item_sort = $item_details['File_Handler']['sort_type'];
			$item_dir  = $item_details['File_Handler']['file_dir'];
			$item_path = 'u' . '/' . $folder_nt . '/' . 'v' . '/' . $item_name . '.' . $item_type;
			if($item_sort == 'image')
			{
				die('{"jsonrpc" : "2.0", "result" : { "item_sort" : "' . $item_sort . '", "item_type" : "' . $item_type . '",
					"item_size" : "' . $item_size . '", "item_name" : "' . $item_name . '", "item_path" : "' . $item_path . '"}, 
					"id":"id"}');
			}
			elseif($item_sort == 'folder')
			{
				$url = $item_dir . $item_name;
				$go_to = Router::url('/', true) . $url;
				die('{"jsonrpc" : "2.0", "result" : { "item_sort" : "' . $item_sort . '", "item_path" : "' . $go_to . '"}, "id" : "id"}');
			}
		}
	}

	public function show_folders()
	{
		if(isset($_POST['open_folder']) && isset($_POST['open_path']))
		{
			$f_name = preg_replace("#[\\\<\>\'\/\|\?:\*\"\.]#i", '', urldecode($_POST['open_folder']));
			$folder_path = trim(urldecode($_POST['open_path']), '/');

			$nametag     = $this->Session->read('User.name_tag');
			$uid         = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $folder_path;

			if(file_exists($full_path) && is_dir($full_path))
			{
				$f_details = '';
				$folders = scandir($full_path);
				$count = 1;
				foreach ($folders as $folder) {
					if($folder != '.' && $folder != '..' && is_dir($full_path . '/' . $folder))
					{
						$f_details .= '{"id" : "f_open_'. $count .'", 
						                "name" : "'. $folder .'", 
						                "path" : "'. $folder_path . '/' . $folder .'"},';
					}
					$count++;
				}
				$f_details = rtrim($f_details, ',');

				die('{"status" : "success", "files" : [ ' . $f_details . ' ]}');
			}
			else
			{
				die('{"status" : "error", "error_msg" : "A problem has occurred while opening your folder."}');
			}
		
		}
		die('{"status" : "error", "error_msg" : "A problem has occurred while opening your folder."}');
	}

	public function copy_items()
	{
		$nametag     = $this->Session->read('User.name_tag');
		$uid         = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt   = $this->FH->getOuterFolder($nametag);
		$created     = date('Y-m-d H:i:s');

		if(isset($_POST['items']) && isset($_POST['destination']) && isset($_POST['from']))
		{
			if(is_array($_POST['items']))
			{
				$items          = $_POST['items'];
				$destination    = trim(preg_replace('#[\\\<\>\'\|\?:\*\"]#i', '', $_POST['destination']), '/');
				$edestination   = explode('/', $destination);
				$tofolder       = (count($edestination) > 1) ? end($edestination) : $destination;
				$mutate_files   = array();
				$from           = rtrim($_POST['from'], '/');
				$full_path      = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $from;
				$full_path_dest = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $destination;

				if(file_exists($full_path) && is_dir($full_path))
				{
					foreach($items as $item)
					{
						$item_name = $item;
						$copyfile;
						$activity;
						$user_details = $this->Backpack_User->find('first', array(
							'conditions'=>array('Backpack_User.user_id'=>$uid),
							'fields'    =>array('Backpack_User.backpack_size'),
							'limit'     =>1));

						$backpack_size = $user_details['Backpack_User']['backpack_size'];
						$main_used     = $this->FH->getFolderSize($user_path, 'main');
						$bottom_used   = $this->FH->getFolderSize($user_path, 'bottom');
						if($main_used + $bottom_used + filesize($full_path . '/' . $item) > $backpack_size)
						{
							die('{"status":"error", "error_msg":"Your bag is full." }');
						}

						if(is_dir($full_path . '/' . $item))
						{
							if(file_exists($full_path_dest . '/' . $item))
							{
								if($this->UE->overwriteStatus($uid) && $from != $to)
								{
									$dfile_id = $this->FH->getFileId($item, $destination);
									$file_id = $this->FH->getFid();
									if($this->FH->recCopy($uid, $destination, $from, $full_path . '/' . $item, $full_path_dest . '/' . $new_file))
									{
										$this->User_File->deleteAll(array('User_File.file_id'=>$dfile_id));
										$this->User_File->deleteAll(array('User_File.file_dir'=>$destination . '/' . $item));
										$copyfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$new_file, 'file_dir'=>$destination, 'file_modified'=>$created));
										$a_desc   = "Copied and Updated " . $item . ' from folder ' . $tofolder;
										$a_type   = "copy";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

									}
								}
								else
								{
									$count = 1;
									while(file_exists($full_path_dest . '/' . $item . '(' . $count . ')'))
										$count++;

									$new_file = $item . '(' . $count . ')';

									$file_id = $this->FH->getFid();
									if($this->FH->recCopy($uid, $destination, $from, $full_path . '/' . $item, $full_path_dest . '/' . $new_file))
									{
										$copyfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$new_file, 'file_dir'=>$destination, 'file_modified'=>$created));
										$a_desc   = "Copied " . $item . ' to ' . $tofolder .' and was renamed to ' . $new_file;
										$a_type   = "copy";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
									}
								}
							}
							else
							{
								$file_id = $this->FH->getFid();
								if($this->FH->recCopy($uid, $destination, $folder, $full_path . '/' . $item, $full_path_dest . '/' . $item_name))
								{
									$copyfile = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$item, 'file_dir'=>$destination, 'file_modified'=>$created));
									$a_desc   = "Copied " . $item . ' to folder ' . $tofolder;
									$a_type   = "copy";
									$a_id     = $this->UE->getActivityId($uid, $nametag);
									$ip       = $this->UE->getRealIPAddress();
									$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
									"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
								}
							}
							$this->User_Activity->create();
							$this->User_File->create();
							$this->User_File->save($copyfile);
							$this->User_Activity->save($activity);
							array_push($mutate_files, $item);
						}
						else
						{
							if(file_exists($full_path_dest . '/' . $item))
								{
									if($this->UE->overwriteStatus($uid) && $from != $to)
									{
										$dfile_id = $this->FH->getFileId($item, $destination);
										$file_id = $this->FH->getFid();
										if(copy($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
										{
											$this->User_File->deleteAll(array('User_File.file_id'=>$dfile_id));
											$copy = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$item, 'file_dir'=>$destination, 'file_modified'=>$created));
											$a_desc   = "Copied and Updated " . $item . ' from folder ' . $tofolder;
											$a_type   = "copy";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

										}
									}
									else
									{
										$count = 1;
										$item_name = substr($item, 0, strrpos($item, '.'));
										$item_type = substr($item, strrpos($item, '.') + 1);
										while(file_exists($full_path_dest . '/' . $item_name . '(' . $count . ').' . $item_type))
											$count++;

										$new_file = $item_name . '(' . $count . ').' . $item_type;

										$file_id = $this->FH->getFid();
										if(copy($full_path . '/' . $item, $full_path_dest . '/' . $new_file))
										{
											$copy = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$new_file, 'file_dir'=>$destination, 'file_modified'=>$created));
											$a_desc   = "Copied " . $item . ' to ' . $tofolder .' and was renamed to ' . $new_file;
											$a_type   = "copy";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
										}
									}

								}
								else
								{
									$file_id = $this->FH->getFid();
									if(copy($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
									{
										$copy = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$item, 'file_dir'=>$destination, 'file_modified'=>$created));
										$a_desc   = "Copied " . $item . ' to folder ' . $tofolder;
										$a_type   = "copy";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
									}
								}

								$this->User_Activity->create();
								$this->User_File->create();
								$this->User_File->save($copy);
								$this->User_Activity->save($activity);
								array_push($mutate_files, $item);
						}

					}

					App::uses('GeneticAlgorithm', 'GeneticAlgorithm');
					$GA = new GeneticAlgorithm();
					$GA->mutation($tofolder, $mutate_files);
					die('{ "status" : "success", "success_msg" : "File(s) has been copied successfully!" }');
				}

				die('{"status" : "error", "error_msg" : "A problem has occurred while copying your file(s). not dir"}');
			}
			else
			{
				die('{"status" : "error", "error_msg" : "A problem has occurred while copying your file(s). not array"}');
			}
		}
		else
		{
			die('{"status" : "error", "error_msg" : "A problem has occurred while copying your file(s) no post."}');
		}
	}

	public function move_items()
	{
		$nametag     = $this->Session->read('User.name_tag');
		$uid         = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt   = $this->FH->getOuterFolder($nametag);
		$created     = date('Y-m-d H:i:s');

		if(isset($_POST['items']) && isset($_POST['destination']) && isset($_POST['from']))
		{
			if(is_array($_POST['items']))
			{
				$items          = $_POST['items'];
				$destination    = trim(preg_replace('#[\\\<\>\'\|\?:\*\"]#i', '', $_POST['destination']), '/');
				$edestination   = explode('/', $destination);
				$tofolder       = (count($edestination) > 1) ? end($edestination) : $destination;
				$mutate_files   = array();
				$from           = rtrim($_POST['from'], '/');
				$full_path      = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $from;
				$full_path_dest = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $destination;

				if(file_exists($full_path) && is_dir($full_path) && file_exists($full_path_dest))
				{
					if($destination != $from)
					{
						foreach($items as $item)
						{
							$item_name = $item;
							$activity;
							$user_details = $this->Backpack_User->find('first', array(
							'conditions'=>array('Backpack_User.user_id'=>$uid),
							'fields'    =>array('Backpack_User.backpack_size'),
							'limit'     =>1));

							$backpack_size = $user_details['Backpack_User']['backpack_size'];
							$main_used     = $this->FH->getFolderSize($user_path, 'main');
							$bottom_used   = $this->FH->getFolderSize($user_path, 'bottom');
							if($main_used + $bottom_used + filesize($full_path . '/' . $item) > $backpack_size)
							{
								die('{"status":"error", "error_msg":"Your bag is full." }');
							}
							
							if(is_dir($full_path . '/' . $item))
							{
								if(file_exists($full_path_dest . '/' . $item))
								{
									if($this->UE->overwriteStatus($uid))
									{
										$dfile_id = $this->FH->getFileId($item, $destination);
										$file_id  = $this->FH->getFileId($item, $from);
										if(rename($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
										{
											$this->User_File->deleteAll(array('User_File.file_id'=>$dfile_id));
											$this->User_File->deleteAll(array('User_File.file_dir'=>$destination . '/' . $item));
											$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
											$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination/$item'"), array('User_File.file_dir'=>$from . '/' . $item));
											$a_desc   = "Moved and Updated " . $item . ' from folder ' . $tofolder;
											$a_type   = "move";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

										}
									}
									else
									{
										$count = 1;
										while(file_exists($full_path_dest . '/' . $item . '(' . $count . ')'))
											$count++;

										$new_file = $item . '(' . $count . ')';

										$file_id = $this->FH->getFileId($item, $from);
										if(rename($full_path . '/' . $item, $full_path_dest . '/' . $new_file))
										{
											$this->User_File->updateAll(array('User_File.file_name'=>"'$new_file'", 'User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
											$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination/$item'"), array('User_File.file_dir'=>$from . '/' . $item));
											$a_desc   = "Moved " . $item . ' to ' . $tofolder .' and was renamed to ' . $new_file;
											$a_type   = "move";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
										}
									}
								}
								else
								{
									$file_id = $this->FH->getFileId($item, $from);
									if(rename($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
									{
										$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
										$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination/$item'"), array('User_File.file_dir'=>$from . '/' . $item));
										$a_desc   = "Moved " . $item . ' to folder ' . $tofolder;
										$a_type   = "move";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
									}
								}
								$this->User_Activity->create();
								$this->User_Activity->save($activity);
								array_push($mutate_files, $item);

							}
							else
							{
								if(file_exists($full_path_dest . '/' . $item))
								{
									if($this->UE->overwriteStatus($uid))
									{
										$dfile_id = $this->FH->getFileId($item, $destination);
										$file_id  = $this->FH->getFileId($item, $from);
										if(rename($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
										{
											$this->User_File->deleteAll(array('User_File.file_id'=>$dfile_id));
											$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
											$a_desc   = "Moved and Updated " . $item . ' from folder ' . $tofolder;
											$a_type   = "move";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

										}
									}
									else
									{
										$count = 1;
										$item_name = substr($item, 0, strrpos($item, '.'));
										$item_type = substr($item, strrpos($item, '.') + 1);
										while(file_exists($full_path_dest . '/' . $item_name . '(' . $count . ').' . $item_type))
											$count++;

										$new_file = $item_name . '(' . $count . ').' . $item_type;

										$file_id = $this->FH->getFileId($item, $from);
										if(rename($full_path . '/' . $item, $full_path_dest . '/' . $new_file))
										{
											$this->User_File->updateAll(array('User_File.file_name'=>"'$new_file'", 'User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
											$a_desc   = "Moved " . $item . ' to ' . $tofolder .' and was renamed to ' . $new_file;
											$a_type   = "move";
											$a_id     = $this->UE->getActivityId($uid, $nametag);
											$ip       = $this->UE->getRealIPAddress();
											$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
											"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
										}
									}

								}
								else
								{
									$file_id = $this->FH->getFileId($item, $from);
									if(rename($full_path . '/' . $item, $full_path_dest . '/' . $item_name))
									{
										$this->User_File->updateAll(array('User_File.file_dir'=>"'$destination'"), array('User_File.file_id'=>$file_id));
										$a_desc   = "Moved " . $item . ' to folder ' . $tofolder;
										$a_type   = "move";
										$a_id     = $this->UE->getActivityId($uid, $nametag);
										$ip       = $this->UE->getRealIPAddress();
										$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
										"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));
									}
								}

								$this->User_Activity->create();
								$this->User_Activity->save($activity);
								array_push($mutate_files, $item);

							}
						}

						App::uses('GeneticAlgorithm', 'GeneticAlgorithm');
						$GA = new GeneticAlgorithm();
						$GA->mutation($tofolder, $mutate_files);

						die('{ "status" : "success", "success_msg" : "File(s) has been moved successfully!" }');
					}
					else
					{
						die('{"status":"error", "error_msg":"Files cannot be moved to the same directory." }');
					}

					
				}

				die('{"status" : "error", "error_msg" : "This directory does not exist. Please try again."}');
			}
			else
			{
				die('{"status" : "error", "error_msg" : "A problem has occurred while moving your file(s). not array"}');
			}
		}
		else
		{
			die('{"status" : "error", "error_msg" : "A problem has occurred while moving your file(s) no post."}');
		}
	}

	public function log_out()
	{
		if($this->Session->check('User') == true)
		{
			$this->Session->delete('User');
		}
		
		$this->redirect(array('controller'=>'users', 'action'=>'login'));
	}

	public function file_alive()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		$nametag     = $this->Session->read('User.name_tag');
		$uid         = $this->UE->getUserId($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$folder_nt   = $this->FH->getOuterFolder($nametag);
		$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name;

		$files  = $this->FH->scanAllFiles('main', $full_path);
		if(count($files) > 0)
		{
			$pivot  = floor(count($files) / 2);
			$result = $this->FH->sortFiles($pivot, $files);
			$decay  = '';
			$alive  = '';

			$items  = (count($result) > 5) ? 5 : count($result);

			for($i=0;$i<$items;$i++)
			{
				if($result[$i]['decay'] < $result[$i]['date_expiry'] * 0.0000001)
				{
					$decay .= '{"file_name" : "'. substr($result[$i]['file_name'], 0, strrpos($result[$i]['file_name'], ".")) .'", "file_dir" : "'. $result[$i]['file_dir'] .'", 
					"file_ext" : "'. strtolower($result[$i]['file_ext']) .'" },';
				}
			}
			$decay = rtrim($decay, ',');

			for($i=count($result) - 1;$i>(count($result) - 1) - $items;$i--)
			{
				$alive .= '{"file_name" : "'. substr($result[$i]['file_name'], 0, strrpos($result[$i]['file_name'], ".")) .'", "file_dir" : "'. $result[$i]['file_dir'] .'", 
					"file_ext" : "'. strtolower($result[$i]['file_ext']) .'", "file_share":"'. $result[$i]['file_share'] .'", "file_download":"'. $result[$i]['file_download'] .'" },';
			}
			$alive = rtrim($alive, ',');

			die('{ "status" : "success", "alive":['. $alive .'], "decaying" : [ '. $decay . ' ]}');
		}
		else
		{
			die('{"status":"success", "success_msg":"No files.", "alive":[], "decaying":[] }');
		}
	}

	public function retrieve_main()
	{
		if(isset($_GET['timestamp']))
		{
			$nametag   = $this->Session->read('User.name_tag');
			$uid       = $this->UE->getUserId($nametag);
			$last_visit = preg_replace("#[^0-9]#i", '', $_GET['timestamp']);
			$used_space = preg_replace("#[^A-Za-z0-9 \.]#i", '', $_GET['used']);
			$files_present = preg_replace("#[0-9]#i", "", $_GET['filespresent']);
			$pocket_open = preg_replace("#[\\\<\>\'\|\?:\*\"\.]#i", "", $_GET['pocket']);
			$organizer = $_GET['organizer'];
			$time      = time();
			App::uses('CometHandler', 'Comet');
			$comet = new CometHandler();

			session_write_close();
			while(time() - $time <= 25)
			{
				list($data, $submit) = $comet->mainLatest($uid, $nametag, $last_visit, $used_space, $files_present, $pocket_open, $organizer);
				if($submit == true)
				{
					die($data);
					break;
				}

				usleep(25000);
			}
			die('{ "status":"renew" }');
		}
		else
		{
			die('{ "status":"error", "error_code":"100"}');
		}
	}

	public function share_group()
	{
		$nametag  = $this->Session->read('User.name_tag');
		$uid      = $this->UE->getUserId($nametag);
		$group_id = substr(preg_replace("#[^A-Za-z0-9_]#i", '', $_POST['share_id']), 2);
		$files    = $_POST['files_selected'];
		$path     = rtrim(preg_replace('#[\\\<\>\'\|\?\:\*\"]#i', '', $_POST['path']), '/');
		$group_details = $this->Backpack_Group->find('first', array('Backpack_Group.group_id'=>$group_id));
		$created = date('Y-m-d H:i:s');

		$date     = date('Y-m-d H:i:s');
		foreach ($files as $key => $value) {
			$share_id = $this->FH->getGroupShareId();
			$share_exists = $this->Group_File->find('count', array('conditions'=>array('Group_File.group_id'=>$group_id, 'Group_File.user_id'=>$uid, 'Group_File.file_name'=>$value, 'Group_File.file_dir'=>$path)));
			if($share_exists == 0)
			{
				$share_file = array('Group_File'=>array('share_id'=>$share_id, 'group_id'=>$group_id, 'user_id'=>$uid, 'file_name'=>$value, 'file_dir'=>$path, 'date_shared'=>$date));
				$this->Group_File->create();
				$this->Group_File->save($share_file);
				$file_id = $this->FH->getFileId($value, $path);
				$file_details = $this->User_File->find('first', array('conditions'=>array('User_File.file_id'=>$file_id)));
				$shares = $file_details['User_File']['file_share'] + 1;
				$this->User_File->updateAll(array('User_File.file_share'=>$shares), array('User_File.file_id'=>$file_id));

				$a_desc   = "Shared " . $value . ' to group ' . $group_details['Backpack_Group']['group_name'];
				$a_type   = "share";
				$a_id     = $this->UE->getActivityId($uid, $nametag);
				$ip       = $this->UE->getRealIPAddress();
				$activity = array("User_Activity"=>array("activity_id"=>$a_id, "user_id"=>$uid, "activity_desc"=>$a_desc,
				"activity_type"=>$a_type, "activity_created"=>$created, "user_ip"=>$ip));

				$this->User_Activity->create();
				$this->User_Activity->save($activity);

			}
		}

		die('{ "status":"success", "success_msg":"All files has been shared." }');
	}

	public function top()
	{
		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$folder_name = $this->FH->getFolderName($uid, $nametag);
		$full_path = 'u/' . $folder_nt . '/' . $folder_name;
		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$this->set('imagepath', $imagepath);

		$files  = $this->FH->scanAllFiles('main', $full_path);
		if(count($files) > 0)
		{
			$pivot  = floor(count($files) / 2);
			$result = $this->FH->sortFiles($pivot, $files);
			$decay  = array();
			$alive  = array();
			$activity = array();

			for($i=0;$i<count($result);$i++)
			{
				if($result[$i]['decay'] < $result[$i]['date_expiry'] * 0.0000001)
				{
					$tempdecay['file_name'] = substr($result[$i]['file_name'], 0, strrpos($result[$i]['file_name'], "."));
					$tempdecay['file_type'] = strtolower($result[$i]['file_ext']);
					array_push($decay, $tempdecay);
				}
			}

			for($i=count($result) - 1;$i>=0;$i--)
			{
				if($result[$i]['decay'] > $result[$i]['date_expiry'] / 10)
				{
					$tempalive['file_name'] = substr($result[$i]['file_name'], 0, strrpos($result[$i]['file_name'], "."));
					$tempalive['file_type'] = strtolower($result[$i]['file_ext']);
					$tempalive['file_share'] = $result[$i]['file_share'];
					$tempalive['file_download'] = $result[$i]['file_download'];
					array_push($alive, $tempalive);
				}
			}

			$activity_detail = $this->User_Activity->find('all', array('conditions'=>array('User_Activity.user_id'=>$uid), 'order'=>array('User_Activity.activity_created DESC'), 'limit'=>30));

			foreach ($activity_detail as $key => $detail) {
				$tempactivity['activity'] = $detail['User_Activity']['activity_desc'];
				$tempactivity['date']     = date('M d Y', strtotime($detail['User_Activity']['activity_created']));
				array_push($activity, $tempactivity);
			}
			$this->set('useractivity', $activity);
			$this->set('alivefiles', $alive);
			$this->set('decayfiles', $decay);			
		}
	}

	public function retrieve_top()
	{
		if(isset($_GET['timestamp']))
		{
			$nametag   = $this->Session->read('User.name_tag');
			$uid       = $this->UE->getUserId($nametag);
			$last_visit = preg_replace("#[^0-9]#i", '', $_GET['timestamp']);
			$used_space = preg_replace("#[^A-Za-z0-9 \.]#i", '', $_GET['used']);
			$time      = time();
			App::uses('CometHandler', 'Comet');
			$comet = new CometHandler();

			session_write_close();
			while(time() - $time <= 25)
			{
				list($data, $submit) = $comet->topLatest($uid, $nametag, $last_visit, $used_space);
				if($submit == true)
				{
					die($data);
					break;
				}

				usleep(25000);
			}
			die('{ "status":"renew" }');
		}
		else
		{
			die('{ "status":"error", "error_code":"100"}');
		}
	}

	public function get_groups()
	{
		$nametag = $this->Session->read('User.name_tag');
		$uid     = $this->UE->getUserId($nametag);

		$user_groups = $this->Backpack_Group->find('all', array('conditions'=>array('Backpack_Group.group_owner'=>$uid)));

		$group_share = '';
		foreach ($user_groups as $key => $group) {
			$group_name = $group['Backpack_Group']['group_name'];
			$group_id   = $group['Backpack_Group']['group_id'];

			$group_share .= '{"group_name":"'. $group_name .'", "group_id":"'. $group_id .'" },';
		}

		$group_share = rtrim($group_share, ',');
		die('{ "status":"success", "group_share":['. $group_share .'] }');
	}

	public function bottom($folder = null)
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		$full_path = 'bottom/';
		if($folder != null)
		{
			$folder = rtrim($folder, '/');
			$full_path .= $folder . '/';
		}

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$this->set('imagepath', $imagepath);
		$this->set('full_path', $full_path); 
	}

	public function check_bottom()
	{
		$uid         = $this->UE->getUserId($this->Session->read('User.name_tag'));
		$bottomcode  = $_POST['bottomcode'];
		$ebottomcode = $this->UE->encryptLockcode($uid, $bottomcode);
		$check       = $this->Backpack_User->find("count", array(
			"conditions"=>array("Backpack_User.user_id"=>$uid, "Backpack_User.bottom_code"=>addslashes($ebottomcode)),
			"limit"     =>1));

		if($check != 1)
		{
			die('{ "status":"error", "error_code":1, "error_msg":"The lockcode that was given is incorrect. Please try again." }');
		}

		die('{"status":"success" }');
	}

	public function retrieve_bottom()
	{
		if(isset($_GET['timestamp']))
		{
			$nametag   = $this->Session->read('User.name_tag');
			$uid       = $this->UE->getUserId($nametag);
			$last_visit = preg_replace("#[^0-9]#i", '', $_GET['timestamp']);
			$used_space = preg_replace("#[^A-Za-z0-9 \.]#i", '', $_GET['used']);
			$time      = time();
			App::uses('CometHandler', 'Comet');
			$comet = new CometHandler();

			session_write_close();
			while(time() - $time <= 25)
			{
				list($data, $submit) = $comet->bottomLatest($uid, $nametag, $last_visit, $used_space);
				if($submit == true)
				{
					die($data);
					break;
				}

				usleep(25000);
			}

			die('{ "status":"renew" }');
		}
		else
		{
			die('{ "status":"error", "error_code":"100"}');
		}
	}

	public function extra()
	{

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$this->set('imagepath', $imagepath);
	}

	public function retrieve_extra()
	{
		if(isset($_GET['timestamp']))
		{
			$nametag   = $this->Session->read('User.name_tag');
			$uid       = $this->UE->getUserId($nametag);
			$last_visit = preg_replace("#[^0-9]#i", '', $_GET['timestamp']);
			$online    = preg_replace("#[^0-9]#i", '', $_GET['online']);
			$offline   = preg_replace("#[^0-9]#i", '', $_GET['offline']);
			$all_bagmates = preg_replace("#[^0-9]#i", '', $_GET['allbagmates']);
			$selected_bagmate = substr(preg_replace("#[^A-Za-z0-9_]#i", '', $_GET['selectedbagmate']), 2);
			$used_space = preg_replace("#[^A-Za-z0-9 \.]#i", '', $_GET['used']);
			$time      = time();
			App::uses('CometHandler', 'Comet');
			$comet = new CometHandler();

			session_write_close();
			while(time() - $time <= 25)
			{
				list($data, $submit) = $comet->extraLatest($uid, $nametag, $last_visit, $online, $offline, $all_bagmates, $selected_bagmate, $used_space);
				if($submit == true)
				{
					die($data);
					break;
				}

				usleep(25000);
			}

			die('{ "status":"renew" }');
		}
		else
		{
			die('{ "status":"error", "error_code":"100"}');
		}
	}

	public function get_chat()
	{
		if(isset($_POST['selectedbagmate']) && isset($_POST['allmessages']))
		{
			$bid = substr(preg_replace("#[^A-Za-z0-9_]#i", '', $_POST['selectedbagmate']), 2);
			$limit = preg_replace("#[^0-9]#i", '', $_POST['allmessages']);
			$nametag = $this->Session->read('User.name_tag');
			$uid = $this->UE->getUserId($nametag);
			$bagmate_exists = $this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.user_id'=>$bid)));
			$max_messages = "false";
			$count_past = 0;

			if(count($bagmate_exists)==1)
			{
				$bagmate_info = $this->Backpack_User->query("SELECT * FROM backpack__users AS Backpack_User LEFT JOIN user__details AS User_Detail ON Backpack_User.user_id = User_Detail.user_id WHERE Backpack_User.user_id = '$bid' limit 1");
				$bagmate = array_shift($bagmate_info);
				$bagmate_nt = $this->FH->getOuterFolder($bagmate['Backpack_User']['name_tag']);
				$bagmate_img = scandir("u/". $bagmate_nt ."/v/profile-pic");
				$bagmateimg_src = '';
				foreach ($bagmate_img as $key => $img) {
					if($img != "." && $img != "..")
					{
						$bagmateimg_src = Router::url("/u/". $bagmate_nt ."/v/profile-pic/". $img);
					}
				}
				$folder_nt = $this->FH->getOuterFolder($nametag);
				$files = scandir("u/". $folder_nt ."/v/profile-pic");
				$userimg_src = '';
				foreach ($files as $key => $image) {
					if($image != "." && $image != "..")
					{
						$userimg_src = Router::url("/u/". $folder_nt ."/v/profile-pic/". $image);
					}
				}
				
				$past_chat = $this->User_Message->query("SELECT * FROM user__messages AS User_Message WHERE (User_Message.reciever_id = '$uid' OR User_Message.sender_id = '$uid') AND (User_Message.reciever_id = '$bid' OR User_Message.sender_id = '$bid') ORDER BY User_Message.date_sent DESC LIMIT $limit, 10");
				$messages = '';
				foreach ($past_chat as $key => $chat) {
					$message = $chat['User_Message']['message'];
					if($chat['User_Message']['sender_id'] == $uid)
					{
						$sender = "true";
					}
					else
					{
						$sender = "false";
					}

					$date_sent = date("M j, Y, g:i a", strtotime($chat['User_Message']['date_sent']));
					$count_past++;
					$messages .= '{"message":"'. stripslashes(htmlspecialchars($message)) .'", "sender":'. $sender .', "date_sent":"'. $date_sent .'" },';
				}
				$count_message = $this->User_Message->query("SELECT COUNT(*) FROM user__messages AS User_Message WHERE (User_Message.reciever_id = '$uid' OR User_Message.sender_id = '$uid') AND (User_Message.reciever_id = '$bid' OR User_Message.sender_id = '$bid')");

				if($count_message[0][0]['COUNT(*)'] == $limit + $count_past)
				{
					$max_messages = "true";
				}
				$this->User_Message->updateAll(array('User_Message.message_seen'=>true), array('User_Message.reciever_id'=>$uid, 'User_Message.sender_id'=>$bid));
				$messages = rtrim($messages, ',');
				die('{ "status":"success", "bagmate_first":"'. $bagmate['User_Detail']['first_name'] .'", "bagmate_last":"'. $bagmate['User_Detail']['last_name'] .'", "bagmate_nametag":"'. $bagmate['Backpack_User']['name_tag'] .'", "messages":['. $messages .'], "bagmate_img":"'. $bagmateimg_src .'", "user_img":"'. $userimg_src .'", "max_messages":'. $max_messages .'  }');
			}
			else
			{
				die('{"status":"error", "error_msg":"This user is not a member of your side pocket." }');
			}

		}
		die();
	}

	public function send_message()
	{
		if(isset($_POST['messages']))
		{
			$nametag = $this->Session->read('User.name_tag');
			$uid     = $this->UE->getUserId($nametag);
			$messages = $_POST['messages'];
			$date = date('Y-m-d H:i:s');
			$sent_count = 0;
			foreach ($messages as $key => $message) {
				$bid = substr($message['bagmate'], 2);
				$send_message = array('User_Message'=>array('sender_id'=>$uid, 'reciever_id'=>$bid, 'message'=>$message['message'], 'date_sent'=>$date));
				$this->User_Message->create();
				if($this->User_Message->save($send_message))
				{
					$sent_count++;
				}
			}

			die('{ "status":"success", "sent_count":'. $sent_count .' }');
		}
		else
		{
			die('{ "status":"error", "error_code":100, "error_msg":"An error occurred while sending your message." }');
		}
	}

	public function side()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->UE->getUserId($nametag);
		$folder_nt = $this->FH->getOuterFolder($nametag);
		$imagepath = $this->FH->getUserImage('u/' . $folder_nt . '/v/profile-pic');
		$this->set('imagepath', $imagepath);
	}

	public function unshare_file()
	{
		if(isset($_POST['file']))
		{
			$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
			$file = $_POST['file'];
			if(preg_match('#[^A-Za-z0-9]#i', $file))
			{
				die('{"status":"error", "error_msg":"An error occurred while unsharing the file(s)." }');
			}
			$is_owner = $this->Group_File->find('count', array('conditions'=>array('Group_File.share_id'=>$file, 'Group_File.user_id'=>$uid)));

			if($is_owner == 1)
			{
				if($this->Group_File->deleteAll(array('Group_File.share_id'=>$file)))
				{
					die('{"status":"success", "success_msg":"File(s) has been unshared successfully" }');
				}
				else
				{
					die('{"status":"error", "error_msg":"An error occurred while unsharing the file(s)." }');
				}
			}
			else
			{
				die('{"status":"error", "error_msg":"An error occurred while unsharing the file(s)." }');
			}
		}

		die('{"status":"error", "error_msg":"An error occurred while unsharing the file(s)." }');
	}

	public function deny_request()
	{
		if(isset($_POST['request_id']))
		{
			$request_id = substr(preg_replace('#[^A-Za-z0-9_]#i', '', $_POST['request_id']), 5);
			$uid        = $this->UE->getUserId($this->Session->read('User.name_tag'));
			$request_exists = $this->Group_Request->find('count', array('conditions'=>array('Group_Request.request_id'=>$request_id, 'Group_Request.request_status'=>false, 'OR'=>array('Group_Request.owner_id'=>$uid, 'Group_Request.user_id'=>$uid))));

			if($request_exists == 0)
			{
				die('{"status":"error", "error_msg":"This request does not exist." }');
			}

			if($this->Group_Request->query("DELETE FROM group__requests WHERE group__requests.request_id = $request_id"));
			{
				die('{"status":"success", "success_msg":"The request was denied successfully." }');
			}

			die('{"status":"error", "error_msg":"There was an error while denying the request." }');

		}
		else
		{
			die('{"status":"error", "error_msg":"There was an error while denying the request." }');
		}
	}

	public function group_members()
	{
		$nametag  = $this->Session->read('User.name_tag');
		$uid      = $this->UE->getUserId($nametag);
		$group_id = preg_replace("#[^A-Za-z0-9]#i", '', $_POST['chosengroup']);

		$is_member = $this->Group_Member->find('count', array('conditions'=>array('Group_Member.group_id'=>$group_id, 'Group_Member.user_id'=>$uid)));

		if($is_member == 1)
		{
			$owner = "false";
			$members = '';
			$is_owner = $this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$uid, 'Backpack_Group.group_id'=>$group_id)));
			if($is_owner == 1)
			{
				$owner = "true";
			}

			$group_members = $this->Group_Member->query("SELECT Group_Member.*, User_Detail.first_name, User_Detail.last_name, Backpack_User.name_tag FROM group__members AS Group_Member LEFT JOIN user__details AS User_Detail ON Group_Member.user_id = User_Detail.user_id LEFT JOIN backpack__users AS Backpack_User ON Group_Member.user_id = Backpack_User.user_id WHERE Group_Member.group_id = '$group_id' AND Group_Member.user_id != '$uid'");

			foreach ($group_members as $key => $member) {
				$member_nt = $this->FH->getOuterFolder($member['Backpack_User']['name_tag']);
				$img_src   = $this->FH->getUserImage('u/' . $member_nt . '/v/profile-pic/');
				$members .= '{ "bagmate_fname":"'. $member['User_Detail']['first_name'] .'", "bagmate_lname":"'. $member['User_Detail']['last_name'] .'", "bagmate_id":"'. $member['Group_Member']['user_id'] .'", "img_path":"'. $img_src .'", "bagmate_nametag":"'. Router::url('/user/') . $member['Backpack_User']['name_tag'] .'" },';
			}
			$members = rtrim($members, ',');
			die('{ "status":"success", "members":['. $members .'], "group_owner":'. $owner .' }');
		}
		else
		{
			die('{ "status":"error", "error_msg":"You are not part of this group." }');
		}
	}

	public function add_member()
	{
		if(isset($_POST['nametag']) && isset($_POST['selectedgroup']))
		{
			$nametag = $this->Session->read('User.name_tag');
			$uid     = $this->UE->getUserId($nametag);
			$group_id = $_POST['selectedgroup'];
			if(preg_match("#[^A-Za-z0-9_]#i", $nametag))
			{
				die('{ "status":"error", "success_msg":"This group does not exist." }');
			}

			$group_owner = $this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$uid, 'Backpack_Group.group_id'=>$group_id)));
			if($group_owner == 0)
			{
				die('{ "status":"error", "error_msg":"You do not own this group." }');
			}

			$user = $_POST['nametag'];
			if(preg_match("#[^A-Za-z0-9_]#i", $nametag))
			{
				die('{ "status":"success", "success_msg":"This user does not exist." }');
			}

			$user_exists = $this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.name_tag like binary'=>$user)));

			if($user_exists == 0)
			{
				die('{ "status":"error", "error_msg":"This user does not exist." }');
			}

			$user_id = $this->UE->getUserId($user);

			$is_member = $this->Group_Member->find('count', array('conditions'=>array('Group_Member.user_id'=>$user_id, 'Group_Member.group_id'=>$group_id)));

			if($is_member == 1)
			{
				die('{"status":"error", "error_msg":"This user is already a member of this group." }');
			}

			$request_exists = $this->Group_Request->find('count', array('conditions'=>array('Group_Request.user_id'=>$user_id, 'Group_Request.owner_id'=>$uid, 'Group_Request.group_id'=>$group_id, 'Group_Request.request_status'=>false)));

			if($request_exists == 1)
			{
				die('{"status":"error", "error_msg":"You have a pending request from or to this user. Please check your group requests." }');
			}

			$request = array('Group_Request'=>array('user_id'=>$user_id, 'owner_id'=>$uid, 'group_id'=>$group_id, 'request_type'=>'add', 'request_status'=>false, 'request_created'=>date('Y-m-d H:i:s'), 'request_seen'=>false));

			if($this->Group_Request->save($request))
			{
				die('{ "status":"success", "success_msg":"A request has been sent successfully!" }');
			}

		}
		else
		{
			die('{ "status":"error", "error_msg":"Please enter a nametag." }');
		}
	}

	public function accept_request()
	{
		if(isset($_POST['request_id']))
		{
			$request_id = substr(preg_replace("#[^a-z0-9_]#i", '', $_POST['request_id']), 7);
			$request_exists = $this->Group_Request->find('count', array(
				'conditions'=>array('Group_Request.request_id'=>$request_id)));

			if($request_exists == 1)
			{
				$request_details = $this->Group_Request->find('first', array(
					'conditions'=>array('Group_Request.request_id'=>$request_id)));

				$request = array_shift($request_details);
				$group_id = $request['group_id'];
				$user_id  = $request['user_id'];
				$date     = date('Y-m-d H:i:s');

				$add_member = array('Group_Member'=>array('group_id'=>$group_id, 'user_id'=>$user_id, 'owner'=>false, 'member_since'=>$date, 'last_visit'=>$date));

				if($this->Group_Member->save($add_member))
				{
					$this->Group_Request->updateAll(array('Group_Request.request_status'=>true), array('Group_Request.request_id'=>$request_id));
					die('{ "status":"success", "success_msg":"Request was accepted successfully!" }');
				}
			}
		}
		
		die('{ "status":"error", "error_msg":"An error occurred while accepting the request." }');
	}

	public function group_requests()
	{
		if($this->Session->check('User.name_tag'))
		{
			$nametag = $this->Session->read('User.name_tag');
			$uid     = $this->UE->getUserId($nametag);
			$date    = date('Y-m-d H:i:s');

			$group_requests = $this->Group_Request->query("SELECT Group_Request.*, Backpack_User.name_tag, Backpack_Group.group_name FROM group__requests AS Group_Request LEFT JOIN backpack__users AS Backpack_User ON Backpack_User.user_id = Group_Request.user_id LEFT JOIN backpack__groups AS Backpack_Group ON Backpack_Group.group_id = Group_Request.group_id WHERE ((Group_Request.user_id = '$uid' AND Group_Request.request_type = 'add') OR (Group_Request.owner_id = '$uid' AND Group_Request.request_type = 'join')) AND Group_Request.request_status = false");
			$user_requests = '';
			foreach ($group_requests as $key => $request) {
				$request_id = $request['Group_Request']['request_id'];
				$nametag    = $request['Backpack_User']['name_tag'];
				$group_name = $request['Backpack_Group']['group_name'];
				$request_type = $request['Group_Request']['request_type'];
				$owner_info = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$request['Group_Request']['owner_id'])));
				$owner_name = $owner_info['Backpack_User']['name_tag'];

				$user_requests .= '{"request_id":"'. $request_id .'", "nametag":"'. $nametag .'", "group_name":"'. $group_name .'", "request_type":"'. $request_type .'", "owner_name":"'. $owner_name .'" },';
			}

			$this->Group_Request->updateAll(array('Group_Request.request_seen'=>true), array('OR'=>array('Group_Request.owner_id'=>$uid, 'Group_Request.user_id'=>$uid), 'Group_Request.request_status'=>false));
			$user_requests = rtrim($user_requests, ',');
			die('{ "status":"success", "requests":['. $user_requests .'] }');
		}

		die('{ "status":"error", "error_msg":"Please login first." }');
	}

	public function create_group()
	{
		if(isset($_POST['groupname']) && !empty($_POST['groupname']))
		{
			$group_name = $_POST['groupname'];
			$nametag      = $this->Session->read('User.name_tag');
			$g_owner      = $this->UE->getUserId($nametag);
			if(preg_match('#[^A-Za-z0-9 \!\?]#i', $group_name))
			{
				die('{"status":"error", "error_msg":"Some characters are not allowed." }');
			}
			else if($this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$g_owner, 'Backpack_Group.group_name'=>$group_name))) > 0)
			{
				die('{"status":"error", "error_msg":"You already have this group." }');
			}

			$g_id         = $this->UE->getGroupId();
			$g_ziplock    = $this->UE->getGroupToken();
			$date_created = date("Y-m-d H:i:s");
			$create_group = array('Backpack_Group'=>array('group_id'=>$g_id, 'group_name'=>$group_name, 'group_owner'=>$g_owner, 'group_ziplock'=>$g_ziplock, 'group_created'=>$date_created));	
			$group_member = array('Group_Member'=>array('group_id'=>$g_id, 'user_id'=>$g_owner, 'owner'=>true,'member_since'=>$date_created, 'last_visit'=>$date_created, 'last_message_update'=>$date_created));
			
			if($this->Backpack_Group->save($create_group))
			{
				$this->Group_Member->save($group_member);
			}

			die('{"status":"success", "success_msg":"The group has been created successfully!"}');
		}
		else
		{
			die('{"status":"error", "error_msg":"The group name is not defined!"}');
		}
	}

	public function retrieve_side()
	{
		$nametag     = $this->Session->read('User.name_tag');
		$uid         = $this->UE->getUserId($nametag);
		$last_visit  = preg_replace('#[^0-9]#i', '', $_GET['last_visit']);
		$group_open  = preg_replace('#[^A-Za-z0-9]#i', '', $_GET['group_open']);
		$group_count = preg_replace('#[^0-9]#i', '',	$_GET['group_count']);
		$used_space = preg_replace("#[^A-Za-z0-9 \.]#i", '', $_GET['used']);
		App::uses('CometHandler', 'Comet');
		$comet = new CometHandler();

		if($uid != false)
		{
			$data = '';
			$submit = false;
			$time_now = time();

			session_write_close();
			while(time() - $time_now <= 25)
			{
				list($data, $submit) = $comet->sideLatest($uid, $nametag, $last_visit, $group_open, $group_count, $used_space);
				usleep(25000);

				if($submit == true)
				{
					die($data);
					break;
				}
			}

			die('{ "status":"renew" }');
		}

		die();
	}

	public function leave_group()
	{
		if(isset($_POST['method']))
		{
			$nametag = $this->Session->read('User.name_tag');
			$uid     = $this->UE->getUserId($nametag);
			$group_id  = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['groupid']);
			$remove_id = '';
			if($_POST['method'] == "remove")
			{
				$remove_id = substr(preg_replace("#[^A-Za-z0-9_]#i", '', $_POST['id']), 2);
				$is_owner  = $this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$uid, 'Backpack_Group.group_id'=>$group_id)));
				if($is_owner == 0)
				{
					die('{ "status":"error", "error_msg":"An error has occurred while removing your member." }');
				}
				else if($is_owner == 1)
				{
					$delete_member = $this->Group_Member->deleteAll(array('Group_Member.user_id'=>$remove_id, 'Group_Member.group_id'=>$group_id));
					if($delete_member)
					{
						die('{"status":"success", "success_msg":"The user was successfully removed from group." }');
					}
				}
			}
			else if($_POST['method'] == "leave")
			{

				$is_member = $this->Group_Member->find('count', array('conditions'=>array('Group_Member.user_id'=>$uid, 'Group_Member.group_id'=>$group_id)));
				if($is_member == 0)
				{
					die('{ "status":"error", "error_msg":"An error has occurred while removing your member." }');
				}

				$is_owner = $this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$uid, 'Backpack_Group.group_id'=>$group_id)));
				if($is_owner == 1)
				{
					$delete_files    = $this->Group_File->deleteAll(array('Group_File.group_id'=>$group_id));
					$delete_members  = $this->Group_Member->deleteAll(array('Group_Member.group_id'=>$group_id));
					$delete_messages = $this->Group_Message->query("DELETE FROM group__messages WHERE group__messages.group_id = '$group_id'");
					$delete_requests = $this->Group_Request->query("DELETE FROM group__requests WHERE group__requests.group_id = '$group_id'");
					$this->Backpack_Group->deleteAll(array('Backpack_Group.group_id'=>$group_id));

					die('{ "status":"success", "success_msg":"The group has been deleted." }');
				}
				else
				{
					$delete_member = $this->Group_Member->deleteAll(array('Group_Member.user_id'=>$uid, 'Group_Member.group_id'=>$group_id));
					if($delete_member)
					{
						die('{"status":"success", "success_msg":"You have left the group successfully." }');
					}
				}

			}
		}
		else
		{
			die('{ "status":"error", "error_msg":"An error has occurred." }');
		}
	}

	public function open_group()
	{
		$nametag    = $this->Session->read('User.name_tag');
		$uid        = $this->UE->getUserId($nametag);
		$group_open = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['group_id']);
		$datetime   = date('Y-m-d H:i:s');

		$is_member = $this->Group_Member->find('count', array(
			'conditions'=>array('Group_Member.group_id'=>$group_open, 'Group_Member.user_id'=>$uid)));

		if($is_member > 0)
		{
			$this->Group_Member->updateAll(array('Group_Member.last_visit'=>"'$datetime'"),
				array('Group_Member.group_id'=>$group_open, 'Group_Member.user_id'=>$uid));

			$group_details = $this->Backpack_Group->find('first', array(
				'conditions'=>array('Backpack_Group.group_id'=>$group_open)));

			$group_owner   = $this->Backpack_User->find('first', array(
				'conditions'=>array('Backpack_User.user_id'=>$group_details['Backpack_Group']['group_owner'])));
			$folder_nt   = $this->FH->getOuterFolder($group_owner['Backpack_User']['name_tag']);
			$folder_name = $this->FH->getFolderName($group_owner['Backpack_User']['user_id'] , $group_owner['Backpack_User']['name_tag']);
			$folder_path = 'u' . '/' . $folder_nt . '/' . $folder_name;

			$files_shared   = $this->Group_File->find('all', array(
				'conditions'=>array('Group_File.group_id'=>$group_open)));

			$shared = '';
			$c_shared = 0;
			foreach ($files_shared as $key => $file_shared) {
				$share_id    = $file_shared['Group_File']['share_id'];
				$full_name   = $file_shared['Group_File']['file_name'];
				$file_name   = substr($full_name, 0, strrpos($full_name, '.'));
				$file_type   = substr($full_name, strrpos($full_name, '.') + 1);
				$file_dir    = rtrim($file_shared['Group_File']['file_dir'], '/');
				$date_shared = date('M j, Y, g:i a', strtotime($file_shared['Group_File']['date_shared']));

				if(file_exists($folder_path . '/' . $file_dir . '/' . $full_name))
				{
					if(is_dir($folder_path . '/' . $file_dir . '/' . $full_name))
					{
						$file_type = "folder";
						$file_name = $full_name;
					}
					$file_size = $this->FH->getFileSize(filesize($folder_path . '/' . $file_dir . '/' . $full_name));
					$color     = $this->FH->fileColorCode($file_type);

					$shared   .= '{ "share_id":"'. $share_id .'", "full_name":"'. $full_name .'", "file_name":"'. $file_name .'", "file_type":"'. $file_type .'", "file_dir":"'. $file_dir .'", "date_shared":"'. $date_shared .'", "file_size":"'. $file_size .'", "color":"'. $color .'" },';
					$c_shared++;
				}
				else
				{
					continue;
				}
			}

			$group_chat = $this->Group_Message->find('all', array(
				'conditions'=>array('Group_Message.group_id'=>$group_open),
				'order'  =>array('Group_Message.date_sent DESC'),
				'limit'     =>10));

			$groupchat = '';

			foreach ($group_chat as $key => $chat) {
				$sender_id = $chat['Group_Message']['user_id'];
				$message   = $chat['Group_Message']['message'];
				$date_sent = date("M j, Y, g:i a", strtotime($chat['Group_Message']['date_sent']));

				$sender_detail = $this->Backpack_User->find('first', array(
					'conditions'=>array('Backpack_User.user_id'=>$sender_id),
					'fields'    =>array('Backpack_User.name_tag')));

				$sender_nametag = $sender_detail['Backpack_User']['name_tag'];

				$groupchat .= '{"sender_id":"'. $sender_id .'", "message":"'. stripslashes(htmlspecialchars($message)) .'", "date_sent":"'. $date_sent .'", "sender_nametag":"'. $sender_nametag .'" },';
			}

			$is_owner = $this->Backpack_Group->find('count', array('conditions'=>array('Backpack_Group.group_owner'=>$uid, 'Backpack_Group.group_id'=>$group_open)));
			$owner = "false";
			if($is_owner == 1)
			{
				$owner = "true";
			}

			$shared = rtrim($shared, ',');
			$groupchat = rtrim($groupchat, ',');
			die('{"status":"success", "is_owner":'. $owner .', "group_name":"' . $group_details['Backpack_Group']['group_name'] . '", "owner_name":"' . $group_owner['Backpack_User']['name_tag'] . '", "files_shared":[' . $shared . '], "group_chat":['. $groupchat .']}');
		}

		die();
	}

	public function group_retrieve_message()
	{
		if(isset($_POST['selectedgroup']) && isset($_POST['messagepresent']))
		{
			$nametag  = $this->Session->read('User.name_tag');
			$uid      = $this->UE->getUserId($nametag);
			$group_id = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['selectedgroup']);
			$limit    = preg_replace('#[^0-9]#i', '', $_POST['messagepresent']); 

			$user_member = $this->Group_Member->find('count', array('conditions'=>array('Group_Member.group_id'=>$group_id, 'Group_Member.user_id'=>$uid)));
			if($user_member == 1)
			{
				$groupchat = '';
				$group_message = $this->Group_Message->query("SELECT Group_Message.*, Backpack_User.name_tag FROM group__messages AS Group_Message LEFT JOIN backpack__users AS Backpack_User ON Group_Message.user_id = Backpack_User.user_id WHERE Group_Message.group_id = '$group_id' ORDER BY Group_Message.date_sent DESC LIMIT $limit, 10");
				
				foreach ($group_message as $key => $chat) {
					$sender_id = $chat['Group_Message']['user_id'];
					$message   = $chat['Group_Message']['message'];
					$date_sent = date("M j, Y, g:i a", strtotime($chat['Group_Message']['date_sent']));

					$sender_detail = $this->Backpack_User->find('first', array(
						'conditions'=>array('Backpack_User.user_id'=>$sender_id),
						'fields'    =>array('Backpack_User.name_tag')));

					$sender_nametag = $sender_detail['Backpack_User']['name_tag'];

					$groupchat .= '{"sender_id":"'. $sender_id .'", "message":"'. $message .'", "date_sent":"'. $date_sent .'", "sender_nametag":"'. $sender_nametag .'" },';
				}
				$groupchat = rtrim($groupchat, ',');
				die('{"status":"success", "group_chat":['. $groupchat .']}');
			}

			die();
		}

	}

	public function group_message()
	{
		if(isset($_POST['user_messages']))
		{
			$nametag  = $this->Session->read('User.name_tag');
			$uid      = $this->UE->getUserId($nametag);
			$messages = $_POST['user_messages'];
			$datetime = date('Y-m-d H:i:s');
			$limit    = 
			$count    = 0;

			foreach ($messages as $key => $message) {
				$is_member = $this->Group_Member->find('count', array('conditions'=>array('Group_Member.user_id'=>$uid, 'Group_Member.group_id'=>$message['sendtogroup'])));

				if($is_member == 1)
				{
					$save_message = array('Group_Message'=>array('group_id'=>$message['sendtogroup'], 'user_id'=>$uid, 'message'=>$message['message'],
						'date_sent'=>$datetime));
					$this->Group_Message->create();
					$this->Group_Message->save($save_message);
				}
				else if($is_member == 0)
				{
					die('{ "status":"error", "error_msg":"A problem has occurred while sending your message"}');
				}
				$count++;
			}

			die('{"status":"success", "count":' . $count . ' }');

		}
		
		die('{ "status":"error", "error_msg":"A problem has occurred while sending your message"}');
	}

	public function join_group()
	{
		$nametag = $this->Session->read('User.name_tag');
		$uid     = $this->UE->getUserId($nametag);
		$owner   = preg_replace('#[^A-Za-z0-9_]#i', '', $_POST['owner']);
		$ziplock = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['ziplock']);
		$date    = date('Y-m-d H:i:s');

		$group_exists = $this->Backpack_Group->query("SELECT * FROM backpack__groups, backpack__users WHERE backpack__groups.group_ziplock like binary '$ziplock' && (backpack__users.name_tag like binary '$owner' || backpack__users.email_address like binary '$owner')");

		if(count($group_exists) == 1){
			$group = array_shift($group_exists);
			$group_id = $group['backpack__groups']['group_id'];
			$user_id  = $group['backpack__users']['user_id'];
			$owner_id = $group['backpack__groups']['group_owner'];
			$request_exists = $this->Group_Request->find('count', array(
				'conditions'=>array('Group_Request.group_id'=>$group_id, 'Group_Request.user_id'=>$uid, 'Group_Request.request_status'=>false)));
			if($request_exists == 0)
			{
				$date     = date('Y-m-d H:i:s');
				$join_group = array("Group_Request"=>array("group_id"=>$group_id, "user_id"=>$uid, "owner_id"=>$owner_id, "request_type"=>"join", "request_status"=>false, "request_created"=>$date, "request_seen"=>false));

				if($this->Group_Request->save($join_group))
				{
					die('{ "status":"success", "success_msg":"Sent group request successfully!"}');
				}
			}
			else
			{
				die('{ "status":"error", "error_msg":"You already have a pending request for this group."}');
			}
		}
		
		die('{ "status":"error", "error_msg":"This group does not exist"}');
		
	}

	public function group_info()
	{
		$nametag = $this->Session->read('User.name_tag');
		$uid     = $this->UE->getUserId($nametag);
		$group_id = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['groupopen']);

		$belong  = $this->Group_Member->find('count', array(
			'conditions'=>array('Group_Member.group_id'=>$group_id, 'Group_Member.user_id'=>$uid)));

		if($belong == 1)
		{
			$group_details = $this->Backpack_Group->query("SELECT * FROM backpack__groups AS Backpack_Group LEFT JOIN backpack__users AS Backpack_User ON Backpack_Group.group_owner = Backpack_User.user_id WHERE Backpack_Group.group_id = '$group_id'");
			
			$group = array_shift($group_details);
			$owner = $group['Backpack_User']['name_tag'];
			$group_name = $group['Backpack_Group']['group_name'];
			$group_ziplock = $group['Backpack_Group']['group_ziplock'];

			die('{"status":"success", "group_owner":"'. $owner .'", "group_name":"'. $group_name .'", "group_ziplock":"' . $group_ziplock . '" }');
		}
		else
		{
			die('{"status":"error", "error_msg":"You do not belong to group."}');
		}
	}

	public function tutorial()
	{
		$uid = $this->UE->getUserId($this->Session->read('User.name_tag'));
		$tofinish = $_POST['pocket'];

		if($tofinish == "main")
		{
			if($this->Backpack_User->updateAll(array('Backpack_User.main_visit'=>false), array('Backpack_User.user_id'=>$uid)))
			{
				die('{"status":"success", "success_msg":"Tutorial is finished!" }');
			}
		}

		die('{"status":"success", "success_msg":"Tutorial is finished."}');
	}

	public function recover()
	{

	}

}