<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

class Alliance {
	
	public $gotInvite = false;
	public $inviteArray = array();
	public $allianceArray = array();
	
	public function procAlliance($get) {
		global $session,$database;
		if($session->alliance != 0) {
			$this->allianceArray = $database->getAlliance($session->alliance);
		}
		else {
			$this->inviteArray = $database->getInvitation($session->uid);
			$this->gotInvite = count($this->inviteArray) == 0? false : true;
		}
		if(isset($get['a'])) {
			switch($get['a']) {
				case 2:
				$this->rejectInvite($get);
				break;
				case 3:
				$this->acceptInvite($get);
				break;
			}
		}
	}
	
	public function procAlliForm($post) {
		if(isset($post['ft'])) {
			switch($post['ft']) {
				case "ali1":
				$this->createAlliance($post);
				break;
			}
		}
	}
	
	private function rejectInvite($get) {
		global $database;
		foreach($this->inviteArray as $invite) {
			if($invite['id'] == $get['d']) {
				$database->removeInvitation($get['d']);
			}
		}
		header("Location: build.php?id=".$get['id']);
	}
	
	private function acceptInvite($get) {
		global $database;
		foreach($this->inviteArray as $invite) {
			if($invite['id'] == $get['d']) {
				$database->removeInvitation($get['d']);
				$database->updateUserField($invite['uid'],"alliance",$invite['alliance'],1);
			}
		}
		header("Location: build.php?id=".$get['id']);
	}
	
	private function createAlliance($post) {
		global $form,$database,$session,$bid18,$village;
		if(!isset($post['ally1']) || $post['ally1'] == "") {
			$form->addError("ally1",ATAG_EMPTY);
		}
		if(!isset($post['ally1']) || $post['ally2'] == "") {
			$form->addError("ally2",ANAME_EMPTY);
		}
		if($database->aExist($post['ally1'],"tag")) {
			$form->addError("ally1",ATAG_EXIST);
		}
		if($database->aExist($post['ally2'],"name")) {
			$form->addError("ally2",ANAME_EXIST);
		}
		if($form->returnErrors() != 0) {
			$_SESSION['errorarray'] = $form->getErrors();
			$_SESSION['valuearray'] = $post;
			
			header("Location: build.php?id=".$post['id']);
		}
		else {
			$max = $bid18[$village->resarray['f'.$post['id']]]['attri'];
			$aid = $database->createAlliance($post['ally1'],$post['ally2'],$session->uid,$max);
			$database->updateUserField($session->uid,"alliance",$aid,1);
			header("Location: build.php?id=".$post['id']);
		}
	}
	
}

$alliance = new Alliance;
?>
