<?php
App::uses('Sanitize', 'Utility');
App::uses('BackpackHelper', 'Helper');

/**
* 
*/
class UsersExtension extends AppController
{

	public $uses = array('Backpack_User', 'User_Activity', 'Backpack_Group', 'User_Verification');

	function beforeFilter()
	{
		$nametag = $this->Session->read('User.name_tag');
		$uid = $this->getUserId($nametag);
		$this->User_Activity->useTable = $this->getActivityDB($uid);
	}

	public function check_nametag($nametag)
	{
		$title = '';
		$desc = '';
		$valid = false;
		$response = '';
		$htmlnametag = htmlspecialchars($nametag, ENT_QUOTES);

		if(empty($htmlnametag))
		{
			$title = 'empty';
			$desc = 'Name tag must not be blank';
			$valid = false;
			$response = 'Nametag must not be blank.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($nametag) > 20)
		{
			$title = 'too big';
			$desc = 'Name tag must be less than 21 characters';
			$valid = false;
			$response = 'Nametag must be less than 21 characters.';

			return array($title, $desc, $valid, $response);
		}
		else if(preg_match('#[^A-Za-z0-9_]#i', $nametag))
		{
			$title = 'special character';
			$desc = 'Name tag must not contain Special characters';
			$valid = false;
			$response = 'Only letters, numbers and underscores are allowed.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$b_user = $this->Backpack_User->query("SELECT * FROM backpack__users WHERE name_tag = '$htmlnametag' LIMIT 1");
			if(count($b_user) == 1)
			{
				$title = 'nametag taken';
				$desc = 'Nametag is already taken';
				$valid = false;
				$response = 'Name tag is already taken.';

				return array($title, $desc, $valid, $response);
			}
			else
			{
				$title = 'accepted';
				$desc = 'Nametag is good';
				$valid = true;
				$response = 'Name tag is great.';

				return array($title, $desc, $valid, $response);
			}
			
		}
	}

	public function check_email($email)
	{

		$title = '';
		$desc = '';
		$valid = false;
		$response = '';
		$htmlemail = htmlspecialchars($email, ENT_QUOTES);

		if(empty($htmlemail))
		{
			$title = 'empty';
			$desc = 'Email must not be blank';
			$valid = false;
			$response = 'Email must not be blank.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($email) > 50)
		{
			$title = 'too big';
			$desc = 'Email must be less than 51 characters';
			$valid = false;
			$response = 'Email must be less than 51 characters.';

			return array($title, $desc, $valid, $response);
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$title = 'not valid';
			$desc = 'Email is not valid';
			$valid = false;
			$response = 'Email looks invalid.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$b_user = $this->Backpack_User->query("SELECT * FROM backpack__users WHERE email_address = '$htmlemail' LIMIT 1");
			if(count($b_user) == 1)
			{
				$title = 'email taken';
				$desc = 'Email is already taken';
				$valid = false;
				$response = 'Email is already taken. <a href="#">Forgot Password?</a>';

				return array($title, $desc, $valid, $response);
			}
			else
			{
				$title = 'accepted';
				$desc = 'Email is good';
				$valid = true;
				$response = 'Email is good. We will send you a validation email.';

				return array($title, $desc, $valid, $response);
			}
			
		}
	}

	public function check_lockcode($lockcode)
	{
		$title = '';
		$desc = '';
		$valid = false;
		$response = '';

		if(empty($lockcode))
		{
			$title = 'empty';
			$desc = 'Lock code must not be blank';
			$valid = false;
			$response = 'Lock code must not be blank.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($lockcode) > 16)
		{
			$title = 'too big';
			$desc = 'Lock code must be less than 17 characters';
			$valid = false;
			$response = 'Lock code must be less than 17 characters.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($lockcode) < 8)
		{
			$title = 'too big';
			$desc = 'Lock code must be greater than 8 characters';
			$valid = false;
			$response = 'Lock code must be greater than 8 characters.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$title = 'accepted';
			$desc = 'Lock code is filled up correctly';
			$valid = true;
			$response = 'Lock code is filled up correctly.';

			return array($title, $desc, $valid, $response);
		}
	}

	public function check_bottomlockcode($lockcode)
	{
		$title = '';
		$desc = '';
		$valid = false;
		$response = '';

		if(empty($lockcode))
		{
			$title = 'empty';
			$desc = 'Bottom lockcode must not be blank';
			$valid = false;
			$response = 'Bottom lockcode must not be blank.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($lockcode) > 16)
		{
			$title = 'too big';
			$desc = 'Bottom lockcode must be less than 17 characters';
			$valid = false;
			$response = 'Bottom lockcode must be less than 17 characters.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($lockcode) < 8)
		{
			$title = 'too small';
			$desc = 'Bottom lockcode must be greater than 8 characters';
			$valid = false;
			$response = 'Bottom lockcode must be greater than 8 characters.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$title = 'accepted';
			$desc = 'Bottom lockcode is filled up correctly';
			$valid = true;
			$response = 'Bottom lockcode is filled up correctly.';

			return array($title, $desc, $valid, $response);
		}
	}

	public function check_name($name, $which)
	{
		$title = '';
		$desc = '';
		$valid = false;
		$response = '';

		if(empty($name))
		{
			$title = 'empty';
			$desc = $which . ' name must not be blank';
			$valid = false;
			$response = $which . ' name must not be blank.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($name) > 21)
		{
			$title = 'too big';
			$desc = $which . ' name must be less than 17 characters';
			$valid = false;
			$response = $which . ' name must be less than 17 characters.';

			return array($title, $desc, $valid, $response);
		}
		else if(preg_match('#[^A-Za-z ]#i', $name))
		{
			$title = 'not allowed';
			$desc = $which . ' name must contain letters and spaces only';
			$valid = false;
			$response = $which . ' name must contain letters and spaces only.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$title = 'accepted';
			$desc = $which . ' name is filled up correctly';
			$valid = true;
			$response = $which . ' name is filled up correctly.';

			return array($title, $desc, $valid, $response);
		}
	}

	public function verify_email($email)
	{
		$title = '';
		$desc = '';
		$valid = false;
		$response = '';
		$htmlemail = htmlspecialchars($email, ENT_QUOTES);

		if(empty($htmlemail))
		{
			$title = 'empty';
			$desc = 'Email must not be blank';
			$valid = false;
			$response = 'This email is not registered. Please register <a href="'. Router::url("/register") .'">here</a>.';

			return array($title, $desc, $valid, $response);
		}
		else if(strlen($email) > 50)
		{
			$title = 'too big';
			$desc = 'Email must be less than 51 characters';
			$valid = false;
			$response = 'This email is not registered. Please register <a href="'. Router::url("/register") .'">here</a>.';

			return array($title, $desc, $valid, $response);
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$title = 'not valid';
			$desc = 'Email is not valid';
			$valid = false;
			$response = 'This email is not registered. Please register <a href="'. Router::url("/register") .'">here</a>.';

			return array($title, $desc, $valid, $response);
		}
		else
		{
			$b_user = $this->Backpack_User->query("SELECT * FROM backpack__users WHERE email_address = '$htmlemail' LIMIT 1");
			if(count($b_user) == 1)
			{
				$title = 'email taken';
				$desc = 'Email is already taken';
				$valid = true;
				$response = 'Email do exist.';

				return array($title, $desc, $valid, $response);
			}
			else
			{
				$title = 'accepted';
				$desc = 'Email is good';
				$valid = false;
				$response = 'This email is not registered. Please register <a href="'. Router::url("/register") .'">here</a>.';

				return array($title, $desc, $valid, $response);
			}
			
		}
	}

	public function getUserId($nametag)
	{
		$b_uid = $this->Backpack_User->find('first', array(
				'conditions' => array('Backpack_User.name_tag' => $nametag),
				'field'     => array('Backpack_User.user_id'),
				'limit'     => 1));
		if(count($b_uid) == 1)
		{
			return $b_uid['Backpack_User']['user_id'];
		}

		return false;
	}

	public function getUid()
	{
		$valid = false;
		$uid = '';

		while($valid == false)
		{
			$uid = BackpackHelper::getRandomString(30);
			$v_uid = $this->Backpack_User->query("SELECT user_id FROM backpack__users WHERE user_id = '$uid' LIMIT 1");
			if(count($v_uid) == 0)
			{
				$valid = true;
			}
		}

		return $uid;
	}

	public function encryptLockcode($uid, $lockcode)
	{
		$eUid 	   = md5($uid);
		$elockcode = md5($lockcode);
		$elockcode = md5($elockcode . 'bp' . $eUid);

		return $elockcode;
	}

	public function getActivityId($uid, $nametag)
	{
		$valid = false;
		$aid = '';

		while($valid == false)
		{
			$aid = BackpackHelper::getRandomString(50);
			$v_aid = $this->User_Activity->find('count', array(
				'conditions'=>array('User_Activity.activity_id'=>$aid),
				'limit'=>1));
			if($v_aid == 0)
			{
				$valid = true;
			}
		}

		return $aid;
	}

	function getRealIPAddress(){ 
		$ip = 'asdf'; 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public function getGroupId()
	{
		$valid = false;
		$gid = '';

		while($valid == false)
		{
			$gid = BackpackHelper::getRandomString(30);
			$v_gid = $this->Backpack_Group->find('count', array(
				'conditions'=>array('Backpack_Group.group_id'=>$gid),
				'limit'=>1));
			if($v_gid == 0)
			{
				$valid = true;
			}
		}

		return $gid;
	}

	public function getGroupToken()
	{
		$valid = false;
		$zip = '';

		while($valid == false)
		{
			$zip = BackpackHelper::getRandomString(10);
			$v_zip = $this->Backpack_Group->find('count', array(
				'conditions'=>array('Backpack_Group.group_ziplock'=>$zip),
				'limit'=>1));
			if($v_zip == 0)
			{
				$valid = true;
			}
		}

		return $zip;
	}

	public function organizerStatus($uid)
	{
		$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$uid)));
		return $user_details['Backpack_User']['organizer'];
	}

	public function overwriteStatus($uid)
	{
		$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$uid)));
		return $user_details['Backpack_User']['file_overwrite'];
	}

	public function newToMain($uid)
	{
		$user_details = $this->Backpack_User->find('first', array('conditions'=>array('Backpack_User.user_id'=>$uid)));
		return $user_details['Backpack_User']['main_visit'];
	}

	public function checkUser($uid)
	{
		if($this->Backpack_User->find('count', array('conditions'=>array('Backpack_User.user_id'=>$uid))) == 1)
		{
			return true;
		}

		return false;
	}

	public function getVerificationId()
	{
		$valid = false;
		$vid   = '';

		while($valid == false)
		{
			$vid = BackpackHelper::getRandomString(30);
			$v_vid = $this->User_Verification->find('count', array('conditions'=>array('User_Verification.verification_code'=>$vid)));

			if($v_vid == 0)
			{
				$valid = true;
			}
		}

		return $vid;
	}

}