<?php

date_default_timezone_set('Asia/Manila');

/**
* 
*/
class CometHandler extends AppController
{
	
	public $uses = array('Backpack_User', 'User_Activity', 'User_File', 'Backpack_Group', 'Group_Member', 'Group_File', 'Group_Message', 'Group_Requests', 'User_Message');

	public function topLatest($uid, $nametag, $last_visit, $used_space)
	{
		$datetime = date('Y-m-d H:i:s');
		$data     = '';
		$submit   = false;
		$group_noti = '';
		$bagmates_noti = '';
		$backpacksize_noti = '';

		$this->holdingBackpack($uid);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		$data = $backpacksize_noti . $group_noti . $bagmate_noti;
		$data = '{"status":"success", '. $data .' "last_visit":"'. strtotime($datetime) .'" }';
		return array($data, $submit);
	}

	public function mainLatest($uid, $nametag, $last_visit, $used_space, $files_present, $pocket_open, $organizer)
	{
		$datetime = date('Y-m-d H:i:s');
		$data     = '';
		$submit   = false;
		$group_noti = '';
		$bagmate_noti = '';
		$backpacksize_noti = '';
		$files_noti = '';

		$this->holdingBackpack($uid);

		list($org_status, $changed) = $this->organizerStatus($uid, $organizer);
		$submit = $this->checkNotification($submit, $changed);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		$data = $files_noti . $backpacksize_noti . $group_noti . $bagmate_noti . $org_status;
		$data = '{"status":"success", '. $data .' "last_visit":"'. strtotime($datetime) .'" }';
		return array($data, $submit);
	}

	public function sideLatest($uid, $nametag, $last_visit, $group_open, $group_count, $used_space)
	{
		$datetime = date('Y-m-d H:i:s');
		$data = '';
		$submit = false;
		$group_noti = '';
		$group_data = '';
		$groupchat_data = '';
		$bagmate_noti = '';
		$backpacksize_noti = '';
		$grouprequest_noti = '';

		$this->holdingBackpack($uid);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($grouprequest_noti, $new_noti) = $this->groupRequest($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_data, $new_noti) = $this->retrieveGroups($uid, $nametag, $last_visit, $group_count);
		$submit = $this->checkNotification($submit, $new_noti);

		if(!empty($group_open))
		{
			list($groupchat_data, $new_noti) = $this->groupChat($uid, $nametag, $group_open);
			$submit = $this->checkNotification($submit, $new_noti);

			$this->updateLastOpen($uid, $datetime, $group_open);
		}

		$data = $backpacksize_noti . $group_noti . $bagmate_noti . $group_data . $groupchat_data . $grouprequest_noti;
		$data = '{"status":"success", ' . $data . ' "last_visit":"' . strtotime($datetime) . '"}';
		return array($data, $submit);
	}

	public function bottomLatest($uid, $nametag, $last_visit, $used_space)
	{
		$datetime = date('Y-m-d H:i:s');
		$data     = '';
		$submit   = false;
		$group_noti = '';
		$bagmates_noti = '';
		$backpacksize_noti = '';

		$this->holdingBackpack($uid);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		$data = $backpacksize_noti . $group_noti . $bagmate_noti;
		$data = '{"status":"success", '. $data .' "last_visit":"'. strtotime($datetime) .'" }';
		return array($data, $submit);
	}

	public function extraLatest($uid, $nametag, $last_visit, $online, $offline, $all_bagmates, $selected_bagmate, $used_space)
	{
		$datetime = date('Y-m-d H:i:s');
		$data     = '';
		$submit   = false;
		$group_noti = '';
		$bagmates = '';
		$newchat = '';
		$bagmate_noti = '';
		$backpacksize_noti = '';

		$this->holdingBackpack($uid);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmates, $new_noti) = $this->bagMates($uid, $last_visit, $online, $offline, $all_bagmates);
		$submit = $this->checkNotification($submit, $new_noti);

		if($selected_bagmate != "")
		{
			list($newchat, $new_noti) = $this->bagmatesMessages($uid, $last_visit, $selected_bagmate);
			$submit = $this->checkNotification($submit, $new_noti);

			$this->seenMessages($uid, $selected_bagmate);
		}

		$data = $backpacksize_noti . $group_noti . $bagmate_noti . $bagmates . $newchat;
		$data = '{"status":"success", '. $data .' "last_visit":"'. strtotime($datetime) .'" }';
		return array($data, $submit);
	}

	public function otherLatest($uid, $nametag, $last_visit, $used_space)
	{
		$data = '';
		$submit = false;

		$this->holdingBackpack($uid);

		list($backpacksize_noti, $new_noti) = $this->backpackSize($uid, $nametag, $used_space);
		$submit = $this->checkNotification($submit, $new_noti);

		list($group_noti, $new_noti) = $this->groupsNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		list($bagmate_noti, $new_noti) = $this->bagmateNotification($uid, $last_visit);
		$submit = $this->checkNotification($submit, $new_noti);

		$data = $backpacksize_noti . $group_noti . $bagmate_noti;
		$data = '{"status":"success", '. $data .' "last_visit":"'. strtotime(date('Y-m-d H:i:s')) .'" }';
		return array($data, $submit);
	}

	private function organizerStatus($uid, $organizer)
	{
		App::uses("UsersExtension", "Validate");
		$UE = new UsersExtension();
		$status = ($UE->organizerStatus($uid)) ? '1' : '0';
		if($status == $organizer)
		{
			return array('"org_changed":false,', false);
		}
		else
		{
			return array('"org_changed":true, "org_status":'. $status .',', true);
		}
	}

	private function backpackSize($uid, $nametag, $used_space)
	{
		App::uses("FilesHandler", "Validate");
		$FH = new FilesHandler();
		$new_noti = false;	
		$folder_name = $FH->getFolderName($uid, $nametag);
		$folder_nt   = $FH->getOuterFolder($nametag);
		$full_path   = 'u' . '/' . $folder_nt . '/' . $folder_name;

		$user_details = $this->Backpack_User->find('first', array(
			'conditions'=>array('Backpack_User.user_id'=>$uid),
			'fields'    =>array('Backpack_User.backpack_size'),
			'limit'     =>1));

		$backpack_size = $user_details['Backpack_User']['backpack_size'];
		$space_used    = $FH->getFolderSize($full_path, 'main');

		$total_space   = $FH->getFileSize($backpack_size);
		$used          = $FH->getFileSize($space_used);
		if($used != $used_space)
		{
			$new_noti = true;
		}

		$percent       = ($used != 0) ? number_format(($space_used / $backpack_size) * 100, 4, '.', '') : 0;

		if($new_noti == true)
		{
			return array('"new_space" :true , "t_space" : "' . $total_space . '", "s_used" : "' . $used . '", "percent" : ' . $percent . ', ', true);
		}
		else
		{
			return array('"new_space":false, ', false);
		}

	}

	private function seenMessages($uid, $selected_bagmate)
	{
		$this->User_Message->updateAll(array('User_Message.message_seen'=>true), array('User_Message.sender_id'=>$selected_bagmate, 'User_Message.reciever_id'=>$uid, 'User_Message.message_seen'=>false));
	}

	private function bagmatesMessages($uid, $last_visit, $selected_bagmate)
	{
		$new_noti = false;
		$count_new_message = $this->User_Message->find('count', array('conditions'=>array('User_Message.sender_id'=>$selected_bagmate, 'User_Message.reciever_id'=>$uid, 'User_Message.message_seen'=>false, 'User_Message.date_sent >'=>date($last_visit))));
		$messages = '';

		if($count_new_message > 0)
		{
			$new_noti = true;
			$new_messages = $this->User_Message->find('all', array('conditions'=>array('User_Message.sender_id'=>$selected_bagmate, 'User_Message.reciever_id'=>$uid, 'User_Message.message_seen'=>false, 'User_Message.date_sent >'=>date($last_visit))));

			foreach ($new_messages as $key => $message) {
				$messages .= '{ "message":"'. $message['User_Message']['message'] .'", "sender":false, "date_sent":"'. date("M j, Y, g:i a", strtotime($message['User_Message']['date_sent'])) .'" },';
			}

			$messages = rtrim($messages, ',');
			return array('"new_messages":true, "messages":['. $messages .'], ', true);
		}
		
		return array('"new_messages":false,', false);
	}

	private function bagMates($uid, $last_visit, $on, $off, $all_bagmates)
	{
		$new_noti = ($last_visit == 0) ? true : false;
		$mygroups = $this->Group_Member->find('all', array('conditions'=>array('Group_Member.user_id'=>$uid)));
		$member_id = array();
		$members = '';
		$online_bagmates = 0;
		$offline_bagmates = 0;
		$c_bagmates = 0;
		App::uses('FilesHandler', 'Validate');
		$FH = new FilesHandler();
		
		foreach ($mygroups as $key => $group) {
			$group_id = $group['Group_Member']['group_id'];

			$bagmates = $this->Group_Member->query("SELECT * FROM group__members AS Group_Member LEFT JOIN backpack__users AS Backpack_User ON Group_Member.user_id = Backpack_User.user_id WHERE Group_Member.group_id = '$group_id' AND Group_Member.user_id != '$uid'");

			foreach ($bagmates as $key => $bagmate) {
				if(in_array($bagmate['Group_Member']['user_id'], $member_id) == false)
				{
					$online = "false";
					if(time() - $bagmate['Backpack_User']['last_seen'] <= 30 )
					{
						$online = "true";
						$online_bagmates++;
					}
					else
					{
						$offline_bagmates++;
					}

					$new_messages = $this->User_Message->find('count', array('conditions'=>array('User_Message.reciever_id'=>$uid, 'User_Message.sender_id'=>$bagmate['Group_Member']['user_id'], 'User_Message.message_seen'=>false, 'User_Message.date_sent >'=>date('Y-m-d H:i:s', $last_visit))));
					if($new_messages > 0)
					{
						$new_noti = true;
					}
					$new_messages = $this->User_Message->find('count', array('conditions'=>array('User_Message.reciever_id'=>$uid, 'User_Message.sender_id'=>$bagmate['Group_Member']['user_id'], 'User_Message.message_seen'=>false)));
					$folder_nt = $FH->getOuterFolder($bagmate['Backpack_User']['name_tag']);
					$files = scandir("u/". $folder_nt ."/v/profile-pic");
					$img_url = '';
					foreach ($files as $key => $image) {
						if($image != "." && $image != "..")
						{
							$img_url = Router::url("/u/". $folder_nt ."/v/profile-pic/". $image);
						}
					}
					$c_bagmates++;
					array_push($member_id, $bagmate['Group_Member']['user_id']);

					$members .= '{"bagmate_id":"'. $bagmate['Group_Member']['user_id'] .'", "bagmate_name":"'. $bagmate['Backpack_User']['name_tag'] .'", "bagmate_status":'. $online .', "bagmate_image":"'. $img_url .'", "bagmate_message":'. $new_messages .' },';
				}
			}
		}

		if($online_bagmates != $on || $offline_bagmates != $off)
		{
			$new_noti = true;
		}

		if($c_bagmates != $all_bagmates)
		{
			$new_noti = true;
		}

		$members = rtrim($members, ',');
		if($new_noti == true)
		{
			return array('"getbagmates":true, "bagmates":['. $members .'], "online_bagmates":'. $online_bagmates .', "offline_bagmates":'. $offline_bagmates .', "all_bagmates":'. $c_bagmates .', ', true);
		}
		else
		{
			return array('"getbagmates":false,', false);
		}
	}

	private function holdingBackpack($uid)
	{
		$time    = time();
		$this->Backpack_User->updateAll(array('Backpack_User.last_seen'=>$time), array('Backpack_User.user_id'=>$uid));
	}

	private function bagmateNotification($uid, $last_visit)
	{
		$new_noti = false;
		$all_messages = $this->User_Message->find('count', array('conditions'=>array('User_Message.reciever_id'=>$uid, 'User_Message.date_sent >'=>date('Y-m-d H:i:s', $last_visit), 'User_Message.message_seen'=>false)));
		if($all_messages > 0)
		{
			$new_noti = true;
			$all_messages = $this->User_Message->find('count', array('conditions'=>array('User_Message.reciever_id'=>$uid, 'User_Message.message_seen'=>false)));
		}
		if($new_noti == true)
		{
			return array('"bagmate_noti":true, "bagmate_notifications":"'. $all_messages .'", ', true);
		}
		else
		{
			return array('"bagmate_noti":false,', false);
		}
		
	}

	private function groupsNotification($uid, $last_visit)
	{
		$new_noti = false;
		$group_messages = 0;
		$group_requests = 0;
		$group_shared   = 0;
		$group_details = $this->Group_Member->find('all', array('conditions'=>array('Group_Member.user_id'=>$uid)));

		$requests = $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.owner_id'=>$uid, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', $last_visit), 'Group_Requests.request_seen'=>false, 'Group_Requests.request_type'=>'join', 'Group_Requests.request_status'=>false)));

		if($requests > 0)
		{
			$group_requests += $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.owner_id'=>$uid, 'Group_Requests.request_status'=>false, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', 0), 'Group_Requests.request_seen'=>false, 'Group_Requests.request_type'=>'join')));
			$new_noti = true;
		}

		foreach ($group_details as $key => $details) {
			$group_id = $details['Group_Member']['group_id'];
			$last_view = ($last_visit == 0) ? date($details['Group_Member']['last_visit']) : date('Y-m-d H:i:s', $last_visit);
			$user_visit = date($details['Group_Member']['last_visit']);

			$messages = $this->Group_Message->find('count', array('conditions'=>array('Group_Message.group_id'=>$group_id, 'Group_Message.date_sent >'=>$last_view)));
			$shared   = $this->Group_File->find('count', array('conditions'=>array('Group_File.user_id'=>$uid, 'Group_File.group_id'=>$group_id, 'Group_File.date_shared >'=>$last_view)));

			if($messages + $requests + $shared > 0 || $new_noti == true)
			{
				$group_messages += $this->Group_Message->find('count', array('conditions'=>array('Group_Message.group_id'=>$group_id, 'Group_Message.date_sent >'=>$user_visit, 'Group_Message.user_id !='=> $uid)));
				$group_shared   += $this->Group_File->find('count', array('conditions'=>array('Group_File.group_id'=>$group_id, 'Group_File.date_shared >'=>$user_visit, 'Group_File.user_id !='=> $uid)));
			}
		}

		$total_noti = $group_messages + $group_requests + $group_shared;
		if($total_noti > 0)
		{
			$new_noti = true;
		}

		if($new_noti == true)
		{
			return array('"group_noti":true, "group_notifications":'. $total_noti .',', $new_noti);
		}
		else
		{
			return array('"group_noti":false,', $new_noti);
		}

	} 

	private function groupRequest($uid, $last_visit)
	{
		$new_request = $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.user_id'=>$uid, 'Group_Requests.request_type'=>'add', 'Group_Requests.request_status'=>false, 'Group_Requests.request_seen'=>false, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', $last_visit))));
		$new_request += $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.owner_id'=>$uid, 'Group_Requests.request_type'=>'join', 'Group_Requests.request_status'=>false, 'Group_Requests.request_seen'=>false, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', $last_visit))));

		if($new_request > 0)
		{
			$requests = $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.user_id'=>$uid, 'Group_Requests.request_type'=>'add', 'Group_Requests.request_status'=>false, 'Group_Requests.request_seen'=>false, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', 0))));
			$requests += $this->Group_Requests->find('count', array('conditions'=>array('Group_Requests.owner_id'=>$uid, 'Group_Requests.request_type'=>'join', 'Group_Requests.request_status'=>false, 'Group_Requests.request_seen'=>false, 'Group_Requests.request_created >'=>date('Y-m-d H:i:s', 0))));
			return array('"group_requests":true, "request_notifications":"'. $requests .'", ', true);
		}
		else
		{
			return array('"group_requests":false,', false);
		}
	}

	private function updateLastOpen($uid, $datetime, $group_open)
	{
		$is_member = $this->Group_Member->find('count', array(
			'conditions'=>array('Group_Member.group_id'=>$group_open, 'Group_Member.user_id'=>$uid)));

		if($is_member > 0)
		{
			$this->Group_Member->updateAll(array('Group_Member.last_visit'=>"'$datetime'"),
				array('Group_Member.group_id'=>$group_open, 'Group_Member.user_id'=>$uid));
			return true;
		}

		return false;
	}


	private function checkNotification($submit, $new)
	{
		if($submit == true)
		{
			return true;
		}
		else
		{
			$submit = $new;
			return $submit;
		}
	}

	private function groupChat($uid, $nametag, $group_open)
	{
		$get_last_view = $this->Group_Member->find('first', array(
			'conditions'=>array('Group_Member.user_id'=>$uid, 'Group_Member.group_id'=>$group_open),
			'fields'    =>array('Group_Member.last_visit')));

		$last_visit    = date($get_last_view['Group_Member']['last_visit']);

		$user_messages = $this->Group_Message->query("SELECT Group_Message.*, Backpack_User.name_tag FROM group__messages AS Group_Message LEFT JOIN backpack__users AS Backpack_User ON Group_Message.user_id = Backpack_User.user_id WHERE Group_Message.group_id LIKE BINARY '$group_open' AND Group_Message.user_id != '$uid' AND Group_Message.date_sent >= '$last_visit' ");

		if(count($user_messages) == 0)
		{
			return array('"chat":false,', false);
		}

		$messages      = '';
		foreach ($user_messages as $key => $message) {
			$message_time = date("M j, Y, g:i a", strtotime($message['Group_Message']['date_sent']));
			$recieve     = $message['Group_Message']['message'];
			$messages   .= '{"sender_nametag":"'. $message['Backpack_User']['name_tag'] .'", "date_sent":"'. $message_time .'", "message":"'. $recieve .'" },';
		}

		$messages = rtrim($messages, ',');
		return array('"chat":true, "group_chat":[' . $messages . '],', true);
	}

	private function retrieveGroups($uid, $nametag, $last_visit, $group_count)
	{
		$user_groups = $this->Group_Member->query("SELECT * FROM group__members LEFT JOIN backpack__groups ON group__members.group_id = backpack__groups.group_id WHERE group__members.user_id = '$uid'");
		$my_groups = '';
		$c_groups  = 0;

		$new_noti = false;
		foreach ($user_groups as $key => $group) {
			$group_name = $group['backpack__groups']['group_name'];
			$group_id   = $group['backpack__groups']['group_id'];

			$visit = ($last_visit != 0) ? date('Y-m-d H:i:s', $last_visit) : date($group['group__members']['last_visit']);

			$latest_msg = $this->Group_Message->find('count', array(
				'conditions'=>array('Group_Message.group_id'=>$group_id, 'Group_Message.date_sent >'=>$visit,
					'Group_Message.user_id !='=> $uid)));
			$latest_share = $this->Group_File->find('count', array(
				'conditions'=>array('Group_File.group_id'=>$group_id, 'Group_File.date_shared >'=>$visit)));

			if($latest_msg > 0 || $latest_share > 0)
			{
				$last_open  = date($group['group__members']['last_visit']);
				$latest_msg = $this->Group_Message->find('count', array(
					'conditions'=>array('Group_Message.group_id'=>$group_id, 'Group_Message.date_sent >'=>$last_open,
						'Group_Message.user_id !='=> $uid)));
				$latest_share = $this->Group_File->find('count', array(
					'conditions'=>array('Group_File.group_id'=>$group_id, 'Group_File.date_shared >'=>$last_open, 'Group_File.user_id !='=> $uid)));


				$new_noti = true;
			}
			$my_groups .= '{"group_name":"'. $group_name .'", "group_id":"' . $group_id . '", "msg_noti":' . $latest_msg . ', "share_noti":' . $latest_share . ' },';
			$c_groups++;

		}

		if($c_groups != $group_count)
		{
			$new_noti = true;
		}

		$my_groups = rtrim($my_groups, ',');
		if($new_noti == true)
		{
			return array('"group":true, "group_count":"'. $c_groups .'", "groups":[' . $my_groups . '],', $new_noti);
		}
		elseif($new_noti == false)
		{
			return array('"group":false,', $new_noti);
		}
	}
}