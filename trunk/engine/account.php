<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/


include("session.php");

class Account {
	
	function Account() {
		global $session;
		if(isset($_POST['ft'])) {
			switch($_POST['ft']) {
				case "a1":
				$this->Signup();
				break;
				case "a2":
				$this->Activate();
				break;
				case "a3":
				$this->Unreg();
				break;
				case "a4":
				$this->Login();
				break;
			}
		}
		else {
			if($session->logged_in && in_array("logout.php",explode("/",$_SERVER['PHP_SELF']))) {
				$this->Logout();
			}
		}
	}
	
	private function Signup() {
		global $database,$form,$mailer,$generator,$session;
		if(!isset($_POST['name']) || $_POST['name'] == "") {
			$form->addError("name",USRNM_EMPTY);
		}
		else {
			if(strlen($_POST['name']) < USRNM_MIN_LENGTH) {
				$form->addError("name",USRNM_SHORT);
			}
			else if(!USRNM_SPECIAL && preg_match('/[^0-9A-Za-z]/',$_POST['name'])) {
				$form->addError("name",USRNM_CHAR);
			}
			else if($database->checkExist($_POST['name'],0)) {
				$form->addError("name",USRNM_TAKEN);
			}
		}
		if(!isset($_POST['pw']) || $_POST['pw'] == "") {
			$form->addError("pw",PW_EMPTY);
		}
		else {
			if(strlen($_POST['pw']) < PW_MIN_LENGTH) {
				$form->addError("pw",PW_SHORT);
			}
			else if($_POST['pw'] == $_POST['name']) {
				$form->addError("pw",PW_INSECURE);
			}
		}
		if(!isset($_POST['email'])) {
			$form->addError("email",EMAIL_EMPTY);
		}
		else {
			if(!$this->validEmail($_POST['email'])) {
				$form->addError("email",EMAIL_INVALID);
			}
			else if($database->checkExist($_POST['email'],1)) {
				$form->addError("email",EMAIL_TAKEN);
			}
		}
		if(!isset($_POST['vid'])) {
			$form->addError("tribe",TRIBE_EMPTY);
		}
		if(!isset($_POST['agb'])) {
			$form->addError("agree",AGREE_ERROR);
		}
		if($form->returnErrors() > 0) {
			$_SESSION['errorarray'] = $form->getErrors();
			$_SESSION['valuearray'] = $_POST;
			
			header("Location: anmelden.php");
		}
		else {
			$act = $generator->generateRandStr(10);
			$uid = $database->register($_POST['name'],md5($_POST['pw']),$_POST['email'],$_POST['vid'],$_POST['kid'],$act);
			if($uid) {
				setcookie("COOKUSR",$_POST['name'],time()+COOKIE_EXPIRE,COOKIE_PATH);
				setcookie("COOKEMAIL",$_POST['email'],time()+COOKIE_EXPIRE,COOKIE_PATH);
				if(AUTH_EMAIL) {
					$mailer->sendActivate($_POST['email'],$_POST['name'],$_POST['password'],$act);
					header("Location: activate.php?id=$uid");
				}
				else {
					$database->updateUserField($uid,"act","",1);
					$this->generateBase($_POST['kid'],$uid,$_POST['name']);
					header("Location: login.php");
				}
			}
		}
	}
	
	private function Activate() {
		global $database;
		$actcode = $database->getUserField($_COOKIE['COOKUSR'],"act",1);
		if($actcode == $_POST['id']) {
			$kid = $database->getUserField($_COOKIE['COOKUSR'],"location",1);
			$uid = $database->getUserField($_COOKIE['COOKUSR'],"id",1);
			$this->generateBase($kid,$uid,$_COOKIE['COOKUSR']);
			$database->updateUserField($uid,"act","",1);
			unset($_COOKIE['COOKEMAIL']);
			header("Location: activate.php?e=2");
		}
		else {
			header("Location: activate.php?e=1");
		}
	}
	
	private function Unreg() {
		global $database;
		$pw = $database->getUserField($_COOKIE['COOKUSR'],"password",1);
		if(md5($_POST['pw']) == $pw) {
			$database->unreg($_COOKIE['COOKUSR']);
			unset($_COOKIE['COOKUSR']);
			unset($_COOKIE['COOKEMAIL']);
			header("Location: login.php");
		}
		else {
			header("Location: activate.php?e=3");
		}
	}
	
	private function Login() {
		global $database,$session,$form;
		if(!isset($_POST['user']) || $_POST['user'] == "") {
			$form->addError("user",LOGIN_USR_EMPTY);
		}
		else if(!$database->checkExist($_POST['user'],0)) {
			$form->addError("user",USR_NT_FOUND);
		}
		if(!isset($_POST['pw']) || $_POST['pw'] == "") {
			$form->addError("pw",LOGIN_PASS_EMPTY);
		}
		else if(!$database->login($_POST['user'],$_POST['pw']) && !$database->sitterLogin($_POST['user'],$_POST['pw'])) {
			$form->addError("pw",LOGIN_PW_ERROR);
		}
		if($database->getUserField($_POST['user'],"act",1) != "") {
			$form->addError("activate",$_POST['user']);
		}
		if($form->returnErrors() > 0) {
			$_SESSION['errorarray'] = $form->getErrors();
			$_SESSION['valuearray'] = $_POST;
			
			header("Location: login.php");
		}
		else {
			setcookie("COOKUSR",$_POST['user'],time()+COOKIE_EXPIRE,COOKIE_PATH);
			$session->login($_POST['user']);
		}
	}
	
	private function Logout() {
		global $session,$database;
		unset($_SESSION['wid']);
		$database->activeModify($session->username,1);
		$session->Logout();
	}
	
	private function validEmail($email) {
	  $regexp="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
	  if ( !preg_match($regexp, $email) ) {
		   return false;
	  }
	  return true;
	}
	
	private function generateBase($kid,$uid,$username) {
		global $database,$message;
		$database->updateUserField($uid,"location","",1);
		$kid = $_POST['kid'];
		if($kid == 0) {
			$kid = round(rand(1,4));
		}
		$wid = $database->generateBase($kid);
		$database->setFieldTaken($wid);
		$database->addVillage($wid,$uid,$username,1);
		$database->addResourceFields($wid,$database->getVillageType($wid));
		$database->addUnits($wid);
		$database->addTech($wid);
		$database->addABTech($wid);
		$database->updateUserField($uid,"access",USER,1);
		$message->sendWelcome($uid,$username);
	}
	
};
$account = new Account;
?>
