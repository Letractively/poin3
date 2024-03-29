<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

class Profile {
	
	public function procProfile($post) {
		if(isset($post['ft'])) {
			switch($post['ft']) {
				case "p1":
				$this->updateProfile($post);
				break;
				case "p3":
				$this->updateAccount($post);
				break;
			}
		}
	}
	
	public function procSpecial($get) {
		if(isset($get['e'])) {
			switch($get['e']) {
				case 2:
				$this->removeMeSit($get);
				break;
				case 3:
				$this->removeSitter($get);
				break;
				case 4:
				$this->cancelDeleting($get);
				break;
			}
		}
	}
	
	private function updateProfile($post) {
		global $database;
		$birthday = $post['jahr'].'-'.$post['monat'].'-'.$post['tag'];
		$database->submitProfile($post['uid'],$post['mw'],$post['ort'],$birthday,$post['be2'],$post['be1']);
		$varray = $database->getProfileVillages($post['uid']);
		for($i=0;$i<=count($varray)-1;$i++) {
			$database->setVillageName($varray[$i]['wref'],$post['dname'.$i]);
		}
		header("Location: ?uid=".$post['uid']);
	}
	
	private function updateAccount($post) {
		global $database,$session,$form;
		if($post['pw2'] == $post['pw3']) {
			if($database->login($session->username,$post['pw1'])) {
				$database->updateUserField($post['uid'],"password",md5($post['pw2']),1);
			}
			else {
				$form->addError("pw",LOGIN_PW_ERROR);
			}
		}
		else {
			$form->addError("pw",PASS_MISMATCH);
		}
		if($post['email_alt'] == $session->userinfo['email']) {
			$database->updateUserField($post['uid'],"email",$post['email_neu'],1);
		}
		else {
			$form->addError("email",EMAIL_ERROR);
		}
		if($post['del'] && md5($post['del_pw']) == $session->userinfo['password']) {
			if($database->isAllianceOwner($post['uid'])) {
				$form->addError("del",ALLI_OWNER);
			}
			else {
				$database->setDeleting($post['uid'],0);
			}
		}
		else {
			$form->addError("del",PASS_MISMATCH);
		}
		if($post['v1'] != "") {
			$sitid = $database->getUserField($post['v1'],"id",1);
			if($sitid == $session->userinfo['sit1'] || $sitid == $session->userinfo['sit2']) {
				$form->addError("sit",SIT_ERROR);
			}
			else {
				if($session->userinfo['sit1'] == 0) {
					$database->updateUserField($post['uid'],"sit1",$sitid,1);
				}
				else if($session->userinfo['sit2'] == 0) {
					$database->updateUserField($post['uid'],"sit2",$sitid,1);
				}
			}
		}
		$_SESSION['errorarray'] = $form->getErrors();
		header("Location: spieler.php?s=3");
	}
	
	private function removeSitter($get) {
		global $database,$session;
		if($get['a'] == $session->checker) {
			if($session->userinfo['sit'.$get['type']] == $get['id']) {
				$database->updateUserField($session->uid,"sit".$get['type'],0,1);
			}
			$session->changeChecker();
		}
		header("Location: spieler.php?s=".$get['s']);
	}
	
	private function cancelDeleting($get) {
		global $database;
		$database->setDeleting($get['id'],1);
		header("Location: spieler.php?s=".$get['s']);
	}
	
	private function removeMeSit($get) {
		global $database,$session;
		if($get['a'] == $session->checker) {
			$database->removeMeSit($get['id'],$session->uid);
			$session->changeChecker();
		}
		header("Location: spieler.php?s=".$get['s']);
	}
};
$profile = new Profile;
?>
