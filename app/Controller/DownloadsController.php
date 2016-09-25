<?php
App::uses('Sanitize', 'Utility');

/**
* 
*/
class DownloadsController extends AppController
{

	public $uses = array('User_Sharefile', 'Backpack_User', 'Group_File', 'User_File', 'User_Activity');

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
	
	public function download_file()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		if(isset($_GET['file']) && isset($_GET['full_path']) && !empty($_GET['full_path']) && !empty($_GET['file']))
		{
			$download = $_GET['file'];
			$pocket   = $_GET['full_path'];
			$nametag = $this->Session->read('User.name_tag');
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

	public function download_files()
	{
		if($this->Session->check('User') == false)
		{
			$this->redirect(array('controller' => 'users', 'action'=>'login'));
		}

		if(isset($_GET['full_path']) && !empty($_GET['full_path']) && isset($_GET['files']) && !empty($_GET['files']))
		{
			$nametag     = $this->Session->read('User.name_tag');
			$uid         = $this->UE->getUserId($nametag);
			$folder_name = $this->FH->getFolderName($uid, $nametag);
			$folder_nt   = $this->FH->getOuterFolder($nametag);
			$file_path   = rtrim($_GET['full_path'], '/');
			$files       = explode('/', rtrim($_GET['files'], '/'));
			$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . $file_path;

			$file_download = 'u' . '/' . $folder_nt . '/' . $folder_name . '/' . 'download';
			$zip_name = $nametag . '.zip';
			$count = 0;
			while(file_exists($file_download . '/' . $zip_name))
			{
				$count++;
				$zip_name = $nametag . "(" . $count . ")" . '.zip';
			}
			copy('files' . '/' . 'backpack-zip-create.zip', $file_download . '/' . $zip_name);
			$zip = new ZipArchive;
			$create = $zip->open($file_download . '/' . $zip_name, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

			if($create == true)
			{
				foreach ($files as $key => $file) {
					$zip->addFile($full_path . '/' . $file, $file);
				}
				$zip->close();
			}	

			header('Content-type: application/zip');
			header('Content-disposition: filename="' . $zip_name . '"');
			header("Content-length: " . filesize($file_download . '/' . $zip_name));
			if(readfile($file_download . '/' . $zip_name))
			{
				unlink($file_download . '/' . $zip_name);
				exit();
			}
		}

		//throw error 404
	}

	public function download_shared($share_id)
	{
		if(!empty($share_id))
		{
			$share_exists = $this->User_Sharefile->find('count', array('conditions'=>array('User_Sharefile.share_id'=>$share_id)));

			if($share_exists == 1)
			{
				$share_details = $this->User_Sharefile->query("SELECT User_Sharefile.*, Backpack_User.name_tag FROM user__sharefiles AS User_Sharefile LEFT JOIN backpack__users AS Backpack_User ON Backpack_User.user_id = User_Sharefile.user_id WHERE User_Sharefile.share_id like binary '$share_id' LIMIT 1");
				$details = array_shift($share_details);
				$nametag = $details['Backpack_User']['name_tag'];
				$uid     = $details['User_Sharefile']['user_id'];
				$folder_nt = $this->FH->getOuterFolder($nametag);
				$folder_name = $this->FH->getFolderName($uid, $nametag);
				$full_path   = 'u/' . $folder_nt . '/' . $folder_name . '/' . $details['User_Sharefile']['file_dir'];
				$file = $details['User_Sharefile']['file_name'];

				if(file_exists($full_path . '/' . $file) && !is_dir($full_path . '/' . $file))
				{
					$this->FH->download_file($file, $full_path . '/' . $file);
				}
				else
				{
					throw new NotFoundException('Sorry, this link might be expired or the shared file was deleted or moved.');
				}
			}
			else
			{
				throw new NotFoundException('Sorry, this link might be expired or the shared file was deleted or moved.');
			}

		}
		//throw error 404
		die();
	}

	public function groups($share_id = null)
	{
		if($share_id != null)
		{
			$share_exist = $this->Group_File->find('count', array('conditions'=>array('Group_File.share_id'=>$share_id)));
			if($share_exist == 1)
			{
				$share_details = array();
				$share_details = $this->Group_File->query("SELECT Group_File.*, Backpack_User.name_tag FROM group__files AS Group_File LEFT JOIN backpack__users AS Backpack_User ON Backpack_User.user_id = Group_File.user_id WHERE Group_File.share_id = '$share_id'");
				$share_details = array_shift($share_details);
				$file_name     = $share_details['Group_File']['file_name'];
				$file_dir      = $share_details['Group_File']['file_dir'];
				$folder_nt     = $this->FH->getOuterFolder($share_details['Backpack_User']['name_tag']);
				$folder_name   = $this->FH->getFolderName($share_details['Group_File']['user_id'], $share_details['Backpack_User']['name_tag']);
				$file_path     = 'u/' . $folder_nt . '/' . $folder_name . '/' . $file_dir;

				if(file_exists($file_path . '/' .$file_name))
				{
					if(is_dir($file_path . '/' .$file_name))
					{
						$nametag = $this->Session->read('User.name_tag');
						$uid     = $this->UE->getUserId($nametag);
						$user_nt = $this->FH->getOuterFolder($nametag);
						$user_folder = $this->FH->getFolderName($uid, $nametag);
						$file_download = 'u/' . $user_nt . '/' . $user_folder . '/download/';
						$zip_name = preg_replace('/ /', '_', $file_name) . '.zip';
						$zip_path = $file_download . '/' . $zip_name;
						copy('files' . '/' . 'backpack-zip-create.zip', $zip_path);
						$zip = new ZipArchive;
						$create = $zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

						if($create == true)
						{
							$this->FH->addToZip($zip, $file_path . '/' . $file_name, $file_name);
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
						$this->FH->download_file($file_name, $file_path . '/' .$file_name);
					}
				}
			}
		}

		die();
	}

}