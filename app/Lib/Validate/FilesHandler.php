<?php
App::uses('BackpackHelper', 'Helper');
/**
* 
*/
class FilesHandler extends AppController
{

	public $uses = array('User_Sharefile', 'Backpack_User', 'User_File', 'Group_File', 'Backpack_Word', 'Backpack_Genetic');

	private $select_color = array('blueDark' =>array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'dds', 'pspimage', 'tga', 'thm', 'tif', 'tiff', 'yuv', 'ai', 'eps', 'ps', 'svg', 'pct'),
							      'red'      =>array('mp4', 'ogg', 'flv', 'wav', '3gp', 'asf', 'asx', 'avi', 'mov', 'mpg', 'rm', 'srt', 'wov', 'wmv'),
							      'green'    =>array('mp3', 'm3u', 'm4a', 'wma', 'ra', 'mid', 'mpa'),
							      'orange'   =>array('pdf', 'pps', 'xml', 'dat', 'doc', 'docx', 'ppt', 'pptx', 'log', 'msg', 'odt', 'pages', 'rtf', 'tex', 'txt', 'wpd', 'wps', 'xlr', 'xls', 'xlsx'),
							      'darken'   =>array('folder'),
							      'greenDark'=>array('accdb', 'db', 'dbf', 'mdb', 'pdb', 'sql'),
							      'yellow'	 =>array('apk', 'app', 'bat', 'cgi', 'com', 'exe', 'gadget', 'jar', 'pif', 'vb', 'wsf'),
							      'pink'	 =>array('cab', 'cpl', 'cur', 'deskthemepack', 'dll', 'dmp', 'bak', 'tmp', 'drv', 'icns', 'ico', 'lnk', 'sys', 'ini', 'prf', 'cfg'),
							      'greenLight'=>array('rar', 'sitx', 'rpm', 'zip', 'zipx', 'deb', 'gz', 'pkg'),
							      'blueLight'=>array('bin', 'dmg', 'iso', 'mdf', 'cue', 'toast', 'vcd'),
							      'purple'	 =>array('c', 'class', 'cpp', 'cs', 'dtd', 'fla', 'h', 'java', 'lua', 'm', 'pl', 'py', 'sh', 'sln', 'vcxproj', 'xcodeproj'),
							      'pinkDark' =>array('crdownload', 'msi', 'ics', 'part', 'torrent'));
	private $select_act   = array('upload'       =>'red',
								  'download'     =>'greenDark',
								  'share'        =>'orange',
								  'newfolder'    =>'darken',
								  'update'       =>'green',
								  'rename'       =>'pink',
								  'open'		 =>'pinkDark',
								  'delete'		 =>'purple',
								  'move'		 =>'blue',
								  'copy'		 =>'blueDark',
								  );

	private $image_types = array('png', 'jpg', 'jpeg', 'gif',);

	public function activityColorCode($act)
	{
		foreach ($this->select_act as $type => $color) {
			if($act == $type)
			{
				return $color;
			}
		}

		return 'darken';
	}
	
	public function fileColorCode($ext)
	{
		$ext = strtolower($ext);
		foreach($this->select_color as $key=>$value)
		{

			foreach($value as $extname)
			{
				if($ext == $extname)
				{
					return($key);
				}
			}
		}
	}

	public function isImage($ext)
	{
		$ext = strtolower($ext);
		foreach($this->image_types as $image_type)
		{
			if($ext == $image_type)
			{
				return true;
			}
		}
		return false;
	}

		public function getFolderName($uid, $nametag)
	{
		$eUid = md5($uid);
		$enametag = md5($nametag);
		$enametag = md5($eUid . $enametag);
		$enametag = str_replace('.', 'p', $enametag);
		$enametag = str_replace('/', 's', $enametag);

		return $enametag;
	}


	public function getOuterFolder($nametag)
	{
		$enametag = md5('bp' . $nametag . 'backpack');
		return $enametag;
	}

	public function delTree($dir) 
	{
		$files = array_diff(scandir($dir), array('.','..'));
	    foreach ($files as $file) {
	      (is_dir("$dir/$file")) ? $this->delTree($dir . '/' . $file) : unlink($dir . '/' . $file);
	    }
	    return rmdir($dir);
	}

	public function delFiles($dir) 
	{
		$files = array_diff(scandir($dir), array('.','..'));
	    foreach ($files as $file) {
	      if(is_dir("$dir/$file")) { continue; }
	      else                     { unlink($dir . '/' . $file);}
	    }
	    return true;
	}

	public function recCopy($uid, $destination, $folder, $from, $to)
	{
		$created = date('Y-m-d H:i:s');
		if (is_dir($from)) 
		{
		    mkdir($to);
		    $files = scandir($from);
		    foreach ($files as $file)
		    {
		    	if ($file != "." && $file != "..")
			    {
			    	if(is_dir($from . '/' . $file))
			    	{
			    		$this->recCopy($uid, $destination . '/' . $file, $file, $from . '/' . $file, $to . '/' . $file);
			    	}
			    	else
			    	{
			    		$file_id = $this->getFid();
			    		if(copy($from . '/' . $file, $to . '/' . $file))
			    		{
							$copy = array('User_File'=>array('file_id'=>$file_id, 'file_owner'=>$uid, 'file_name'=>$file, 'file_dir'=>$destination . '/' . $folder, 'file_modified'=>$created));
							$this->User_File->create();
							$this->User_File->save($copy);
						}
			    	}
			 	}
		    }
		}

		return true;
	}

	public function zip_files()
	{

	}

	function getFileSize($file_size){
		$size = $file_size;
		$count = 0;
		while($size > 1024){
			$size /= 1024;
			$count++;
		}
		
		if($count == 0){
			$bit = 'Bytes';
		}elseif($count == 1){
			$bit = 'KB';
		}elseif($count == 2){
			$bit = 'MB';
		}elseif($count == 3){
			$bit = 'GB';
		}
		
		return number_format($size, 2, '.', '') . $bit;
	}

	public function getFolderSize($dir, $name)
	{
		$full_path = $dir . '/' . $name;
		$file_size = 0;
		$files     = scandir($full_path);

		foreach($files as $file)
		{
			if($file != '.' && $file != '..')
			{
				$file_size += (is_dir($full_path . '/' . $file)) ? $this->getFolderSize($full_path , $file) : filesize($full_path . '/' . $file);
			}
		}

		return $file_size;
	}

	public function getFid()
	{
		$valid = false;
		$fid = '';

		while($valid == false)
		{
			$fid = BackpackHelper::getRandomString(30);
			$v_fid = $this->User_File->find('count', array(
				'conditions'=>array('User_File.file_id'=>$fid)));
			if($v_fid == 0)
			{
				$valid = true;
			}
		}

		return $fid;
	}

	public function getFileId($file_name, $file_dir)
	{
		$fid = $this->User_File->find('first', array(
			'conditions'=>array('User_File.file_name'=>$file_name, 'User_File.file_dir'=>$file_dir),
			'fields'    =>array("User_File.file_id")));

		return $fid['User_File']['file_id'];
	}

	public function scanAllFiles($file_dir, $full_path)
	{
		$path  = $full_path . '/' . $file_dir;
		$files = scandir($path);
		$decay = array();
		$push_files = array();

		foreach($files as $file)
		{
			if($file != '.' && $file != '..')
			{
				if(is_dir($path . '/' . $file))
				{
					$values = $this->scanAllFiles($file_dir . '/' . $file, $full_path);
					foreach($values as $value)
					{
						array_push($decay, $value);
					}

				}
				else
				{
					$file_id = $this->getFileId($file, $file_dir);
					$file_details = $this->User_File->find('first', array('conditions'=>array('User_File.file_id'=>$file_id)));
					$added_life                  = ($file_details['User_File']['file_download'] * 86400) + ($file_details['User_File']['file_share'] * 43200);
					$push_files['file_name']     = $file;
					$push_files['date_modified'] = date("Y-m-d H:i:s", filemtime($full_path . '/' . $file_dir . '/' . $file)); 
					$push_files['date_lapse']    = time()-strtotime($push_files['date_modified']);
					$push_files['date_expiry']   = 2592000 + $added_life;
					$days                        = floor(($push_files['date_lapse'] / 86400));
					$push_files['decay']         = $push_files['date_expiry'];
					for($i=0;$i<$days;$i++)
					{
						$push_files['decay']     = $push_files['decay'] / 2;
					}
					$push_files['file_dir']      = $file_dir;
					$push_files['file_ext']      = substr($file, strrpos($file, '.') + 1);
					$push_files['file_share']    = $file_details['User_File']['file_share'];
					$push_files['file_download'] = $file_details['User_File']['file_download'];
					array_push($decay, $push_files);
 				}
			}
		}

		return $decay;
	}

	public function sortFiles($pivot, $files)
	{
		$left   = array();
		$right  = array();
		$middle = array();
		$sorted = array();
		for($i=0;$i<count($files);$i++)
		{
			if($i != $pivot)
			{
				if($files[$i]['decay'] < $files[$pivot]['decay'])
				{
					array_push($left, $files[$i]);
				}
				else if($files[$i]['decay'] > $files[$pivot]['decay'])
				{
					array_push($right, $files[$i]);
				}
				else
				{
					array_push($middle, $files[$i]);
				}
			}
		}

		if(count($left)>1)
		{
			$l_pivot = floor(count($left) / 2);
			$l_result = $this->sortFiles($l_pivot, $left);
			foreach($l_result as $result)
			{
				array_push($sorted, $result);
			}
		}
		else
		{
			foreach($left as $result)
			{
				array_push($sorted, $result);
			}
		}

		array_push($sorted, $files[$pivot]);
		foreach ($middle as $key => $file) {
			array_push($sorted, $file);
		}

		if(count($right)>1)
		{
			$r_pivot = floor(count($right) / 2);
			$r_result = $this->sortFiles($r_pivot, $right);
			foreach($r_result as $result)
			{
				array_push($sorted, $result);
			}
		}
		else
		{
			foreach($right as $result)
			{
				array_push($sorted, $result);
			}
		}

		return $sorted;
	}

	public function addToZip($zip, $folder, $folder_name)
	{
		$files = scandir($folder);
						
		foreach ($files as $file) {
			if($file != '.' && $file != '..')
			{
				(is_dir($folder . '/' . $file)) ? $this->addToZip($zip, $folder . '/' . $file, $folder_name . '/' . $file) : $zip->addFile($folder . '/' . $file, $folder_name . '/' .$file);
			}
		}

		return true;
	}

	public function getGroupShareId()
	{
		$valid = false;
		$sid = '';

		while($valid == false)
		{
			$sid = BackpackHelper::getRandomString(30);
			$v_sid = $this->Group_File->find('count', array('conditions'=>array('Group_File.share_id'=>$sid)));
			if($v_sid == 0)
			{
				$valid = true;
			}
		}

		return $sid;
	}

	public function getShareId()
	{
		$valid = false;
		$sid = '';

		while($valid == false)
		{
			$sid = BackpackHelper::getRandomString(20);
			$v_sid = $this->User_Sharefile->find('count', array('conditions'=>array('User_Sharefile.share_id'=>$sid)));
			if($v_sid == 0)
			{
				$valid = true;
			}
		}

		return $sid;
	}

	public function getUserImage($image_path)
	{
		$image = scandir($image_path);
		$full_path = '';
		foreach ($image as $key => $img) {
			if($img != '.' && $img != '..')
			{
				$img_type = strtolower(substr($img, strrpos($img, ".") + 1));
				if($img_type == "jpg" || $img_type == "jpeg" || $img_type == "gif" || $img_type == "png" || $img_type == "bmp")
				{
					return $image_path . '/' . $img;
				}
			}
		}
	}

	public function download_file($name, $download)
	{
		ob_start();
		header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename=' . preg_replace('/ /', '_', $name));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($download));
	    ob_clean();
	    flush();
		if(readfile($download))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function learnFileName($classification, $file_name)
	{
		$name  = substr($file_name, 0, strrpos($file_name, "."));

		if($this->Backpack_Genetic->find('count', array('conditions'=>array('Backpack_Genetic.gen_class like'=>$classification, 'Backpack_Genetic.file_names like'=>'%' .$name . ";" . '%'))) == 0)
		{
			if($this->Backpack_Genetic->find('count', array('conditions'=>array('Backpack_Genetic.gen_class like'=>$classification))) == 0)
			{
				if($this->checkFileName($classification, $name))
				{
					$date = date('Y-m-d H:i:s');
					$new_word = array('Backpack_Genetic'=>array('gen_class'=>ucfirst($classification), 'file_names'=>$name, 'date_learned'=>$date));
					$this->Backpack_Genetic->save($new_word);
				}
			}
			else
			{
				if($this->checkFileName($classification, $name))
				{
					$date = date('Y-m-d H:i:s');
					$names = $this->Backpack_Genetic->find('first', array('conditions'=>array('Backpack_Genetic.gen_class'=>$classification)));
					$file_names = $names['Backpack_Genetic']['file_names'] . ';' . $name;

					$this->Backpack_Genetic->updateAll(array('Backpack_Genetic.file_names'=>"'$file_names'"), array('Backpack_Genetic.gen_class'=>$classification));
				}
			}

		}
		else
		{
			return;
		}
	}

	private function checkFileName($class, $name)
	{
		$words = array();
		$words = explode(' ', preg_replace("#[^A-Za-z0-9]#i", ' ', $name));

		if(count($words) < 8 && strlen($name) < 60)
		{
			foreach ($words as $key => $word) {
				if($this->Backpack_Word->find('count', array('conditions'=>array('Backpack_Word.dic_class like'=>'%' . $class . '%', 'Backpack_Word.dic_words like'=>$word))) > 0)
				{
					return true;
				}
			}
		}

		return false;
	}

	public function getAllFiles($dir, $pocket)
	{
		$files = scandir($dir . '/' . $pocket);
		$result = '';

		foreach ($files as $key => $file) {
			if($file != '.' && $file != '..')
			{
				if(is_dir($dir . '/' . $pocket . '/' . $file))
				{
					$result .= '{"file_name": "'. $file .'", "file_type":"folder", "file_dir":"'. $pocket .'", "file_size":"", "date_mod":"" },';
					$result .= $this->getAllFiles($dir, $pocket . '/' . $file);
				}
				else
				{
					$file_name = substr($file, 0, strrpos($file, '.'));
					$file_type = substr($file, strrpos($file, '.') + 1);
					$file_size = filesize($dir . '/' . $pocket . '/' . $file);
					$date_mod  = filemtime($dir . '/' . $pocket . '/' . $file);
					$result .= '{"file_name": "'. $file_name .'", "file_type":"'. $file_type .'", "file_dir":"'. $pocket .'", "file_size":"'. $this->getFileSize($file_size) .'", "date_mod":"'. $date_mod .'" },';
				}
			}
		}

		return $result;
	}

}