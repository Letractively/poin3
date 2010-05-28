<?php
if(file_exists("include/config.php")) {
	include("include/database.php");
}
class Process {
			
	function Process() {
		if(isset($_POST['subconst'])) {
				$this->constForm();
		}
		else if(isset($_POST['substruc'])) {
				$this->createStruc();
		}
		else if(isset($_POST['subwdata'])) {
				$this->createWdata();
		}
		else if(isset($_POST['subacc'])) {
				$this->createAcc();
		}
		else{
          header("Location: install.php");
       }
	}

	function constForm() {
		$myFile = "include/config.php";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$text = file_get_contents("data/config_format.tpl");
		$text = preg_replace("'%SERVERNAME%'",$_POST['servername'],$text);
		$text = preg_replace("'%SPEED%'",$_POST['speed'],$text);
		$text = preg_replace("'%UTOUT%'",$_POST['timeout'],$text);
		$text = preg_replace("'%MAX%'",$_POST['wmax'],$text);
		$text = preg_replace("'%GP%'",$_POST['gpack'],$text);
		$text = preg_replace("'%ACTIVATE%'", $_POST['activate'], $text);
		$text = preg_replace("'%SSERVER%'",$_POST['sserver'],$text);
		$text = preg_replace("'%SUSER%'",$_POST['suser'],$text);
		$text = preg_replace("'%SPASS%'",$_POST['spass'],$text);
		$text = preg_replace("'%SDB%'",$_POST['sdb'],$text);
		$text = preg_replace("'%PREFIX%'",$_POST['prefix'],$text);
		$text = preg_replace("'%CONNECTT%'", $_POST['connectt'], $text);
		$text = preg_replace("'%AEMAIL%'",$_POST['aemail'],$text);
		$text = preg_replace("'%ANAME%'", $_POST['aname'], $text);
		$text = preg_replace("'%SUBDOM%'", $_POST['subdom'], $text);
		fwrite($fh, $text);
		if(file_exists("include/config.php")) {
		header("Location: install.php?s=2");
		}
		else {
			header("Location: install.php?s=1&c=1");
		}
		fclose($fh);
	}
	
	function createStruc() {
		global $database;
		$str = file_get_contents("data/structure.sql");
		$str = preg_replace("'%PREFIX%'",TB_PREFIX,$str);
		if(CONNECT_TYPE){
			$result = $database->connection->multi_query($str);
		}
		else {
			$result = $database->mysql_exec_batch($str);
		}
		if($result) {
			header("Location: install.php?s=3");
		}
		else {
			header("Location: install.php?s=2&c=1");
		}
	}
	
	function createWdata() {
		header("Location: include/wdata.php");
	}
	
	function createAcc() {
		global $database;
		$time = time();
		$q = "INSERT INTO ".TB_PREFIX."users (username,password,access,email,timestamp,tribe) VALUES ('".$_POST['user']."', '".md5($_POST['pass'])."', ".ADMIN.", '".$_POST['email']."', $time, 1)";
		if($database->query($q)) {
			if(CONNECT_TYPE) {
				$uid = $database->connection->insert_id;
			}
			else {
				$uid = mysql_insert_id($database->connection);
			}
		$wq2 = "UPDATE ".TB_PREFIX."wdata set occupied = 1 where id = 320801";
		$database->query($wq2);
		$vname = $_POST['user']."\'s village";
		$q2 = "INSERT into ".TB_PREFIX."vdata values (320801, $uid, '$vname', 1, 1, 2, 0, 750, 750, 750, 800, 750, 800, $time)";
		$database->query($q2);
		$q4 = "UPDATE ".TB_PREFIX."wdata set fieldtype = 3 where id = 320801";
		$database->query($q4);
		$q3 = "INSERT into ".TB_PREFIX."fdata (vref,f1t,f2t,f3t,f4t,f5t,f6t,f7t,f8t,f9t,f10t,f11t,f12t,f13t,f14t,f15t,f16t,f17t,f18t,f26,f26t) values(320801,1,4,1,3,2,2,3,4,4,3,3,4,4,1,4,2,1,2,1,15)";
		$database->query($q3);
		$q5 = "INSERT into ".TB_PREFIX."constdata (vref) values (320801)";
		$database->query($q5);
		$q6 = "INSERT into ".TB_PREFIX."units (vref) values (320801)";
		$database->query($q6);
		header("Location: install.php?s=5");
		}
		else {
			header("Location: install.php?s=4&c=1&d=$uid");
		}
	}
};

$process = new Process;
?>