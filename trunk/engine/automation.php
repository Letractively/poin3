<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

class Automation {
	
	public function Automation() {
		$this->ClearUser();
		$this->ClearInactive();
		$this->pruneResource();
		if(!file_exists("engine/session/research.txt")) {
			$this->researchComplete();
		}
		if(!file_exists("engine/session/cleardeleting.txt")) {
			$this->clearDeleting();
		}
		if(!file_exists("engine/session/build.txt")) {
			$this->buildComplete();
		}
		if(!file_exists("engine/session/market.txt")) {
			$this->marketComplete();
		}
		if(!file_exists("engine/session/training.txt")) {
			$this->trainingComplete();
		}
	}
	
	private function clearDeleting() {
		global $database;
		$ourFileHandle = fopen("engine/session/cleardeleting.txt", 'w');
		fclose($ourFileHandle);
		$needDelete = $database->getNeedDelete();
		if(count($needDelete) > 0) {
			foreach($needDelete as $need) {
				$needVillage = $database->getVillagesID($need['uid']); //wref
				foreach($needVillage as $village) {
					$q = "DELETE FROM ".TB_PREFIX."abdata where wref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."bdata where wid = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."enforcement where vref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."fdata where vref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."market where vref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."movement where to = ".$village['wref']." or from = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."odata where wref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."research where vref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."tdata where vref = ".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."training where vref =".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."units where vref =".$village['wref'];
					$database->query($q);
					$q = "DELETE FROM ".TB_PREFIX."vdata where wref = ".$village['wref'];
					$database->query($q);
					$q = "UPDATE ".TB_PREFIX."wdata set occupied = 0 where id = ".$village['wref'];
					$database->query($q);
				}
				$q = "DELETE FROM ".TB_PREFIX."mdata where target = ".$need['uid']." or owner = ".$need['uid'];
				$database->query($q);
				$q = "DELETE FROM ".TB_PREFIX."ndata where uid = ".$need['uid'];
				$database->query($q);
				$q = "DELETE FROM ".TB_PREFIX."users where id = ".$need['uid'];
				$database->query($q);
			}
		}
		unlink("engine/session/cleardeleting.txt");
	}
	
	private function ClearUser() {
		global $database;
		if(AUTO_DEL_INACTIVE) {
			$time = time()+UN_ACT_TIME;
			$q = "DELETE from ".TB_PREFIX."users where timestamp >= $time and act != ''";
			$database->query($q);
		}
	}
	
	private function ClearInactive() {
		global $database;
		if(TRACK_USR) {
			$timeout = time()-USER_TIMEOUT*60;
     		 $q = "DELETE FROM ".TB_PREFIX."active WHERE timestamp < $timeout";
			 $database->query($q);
		}
	}
	
	private function pruneResource() {
		global $database;
		if(!ALLOW_BURST) {
			$q = "UPDATE ".TB_PREFIX."vdata set wood = maxstore where wood > maxstore";
			$database->query($q);
			$q = "UPDATE ".TB_PREFIX."vdata set clay = maxstore where clay > maxstore";
			$database->query($q);
			$q = "UPDATE ".TB_PREFIX."vdata set iron = maxstore where iron > maxstore";
			$database->query($q);
			$q = "UPDATE ".TB_PREFIX."vdata set crop = maxcrop where crop > maxcrop";
			$database->query($q);
		}
	}
	
	private function buildComplete() {
		global $database,$bid18,$bid10,$bid11;
		$ourFileHandle = fopen("engine/session/build.txt", 'w');
		fclose($ourFileHandle);
		$time = time();
		$array = array();
		$q = "SELECT * FROM ".TB_PREFIX."bdata where timestamp < $time";
		$array = $database->query_return($q);
		foreach($array as $indi) {
			$q = "UPDATE ".TB_PREFIX."fdata set f".$indi['field']." = f".$indi['field']." + 1, f".$indi['field']."t = ".$indi['type']." where vref = ".$indi['wid'];
			if($database->query($q)) {
				$level = $database->getFieldLevel($indi['wid'],$indi['field']);
				$pop = $this->getPop($indi['type'],$level);
				$database->modifyPop($indi['wid'],$pop[0],0);
				$database->addCP($indi['wid'],$pop[1]);
				if($indi['type'] == 18) {
					$owner = $database->getVillageField($indi['wid'],"owner");
					$max = $bid18[$level]['attri'];
					$q = "UPDATE ".TB_PREFIX."alidata set max = $max where leader = $owner";
					$database->query($q);
				}
				if($indi['type'] == 10) {
					$max = $bid10[$level]['attri'];
					$database->setVillageField($indi['wid'],"maxstore",$max);
				}
				if($indi['type'] == 11) {
					$max = $bid11[$level]['attri'];
					$database->setVillageField($indi['wid'],"maxcrop",$max);
				}
				$q4 = "UPDATE ".TB_PREFIX."bdata set loopcon = 0 where loopcon = 1 and wid = ".$indi['wid'];
				$database->query($q4);
				$q = "DELETE FROM ".TB_PREFIX."bdata where id = ".$indi['id'];
				$database->query($q);
			}
		}
		unlink("engine/session/build.txt");
	}
	
	private function getPop($tid,$level) {
		$name = "bid".$tid;
		global $$name,$village;
		$dataarray = $$name;
		$pop = $dataarray[($level+1)]['pop'];
		$cp = $dataarray[($level+1)]['pop'];
		return array($pop,$cp);
	}
	
	private function marketComplete() {
		global $database,$generator;
		$ourFileHandle = fopen("engine/session/market.txt", 'w');
		fclose($ourFileHandle);
		$time = time();
		$q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."send where ".TB_PREFIX."movement.ref = ".TB_PREFIX."send.id and ".TB_PREFIX."movement.proc = 0 and type = 0 and endtime < $time";
		$dataarray = $database->query_return($q);
		foreach($dataarray as $data) {
			$database->modifyResource($data['to'],$data['wood'],$data['clay'],$data['iron'],$data['crop'],1);
			$tocoor = $database->getCoor($data['from']);
			$fromcoor = $database->getCoor($data['to']);
			$targettribe = $database->getUserField($database->getVillageField($data['from'],"owner"),"tribe",0);
			$endtime = $this->procDistanceTime($tocoor,$fromcoor,$targettribe,0) + $time;
			$database->addMovement(2,$data['to'],$data['from'],$data['merchant'],$endtime);
			$database->setMovementProc($data['moveid']);
		}
		$q = "UPDATE ".TB_PREFIX."movement set proc = 1 where endtime < $time and type = 2";
		$database->query($q);
		unlink("engine/session/market.txt");
	}
	
	private function researchComplete() {
		global $database;
		$ourFileHandle = fopen("engine/session/research.txt", 'w');
		fclose($ourFileHandle);
		$time = time();
		$q = "SELECT * FROM ".TB_PREFIX."research where timestamp < $time";
		$dataarray = $database->query_return($q);
		foreach($dataarray as $data) {
			$type = substr($data['tech'],0,1);
			switch($type) {
				case "t":
				$q = "UPDATE ".TB_PREFIX."tdata set ".$data['tech']." = 1 where vref = ".$data['vref'];
				break;
				case "a":
				case "b":
				$q = "UPDATE ".TB_PREFIX."abdata set ".$data['tech']." = ".$data['tech']." + 1 where vref = ".$data['vref'];
				break;
			}
			$database->query($q);
			$q = "DELETE FROM ".TB_PREFIX."research where id = ".$data['id'];
			$database->query($q);
		}
		unlink("engine/session/research.txt");
	}
	
	private function trainingComplete() {
		global $database;
		$ourFileHandle = fopen("engine/session/training.txt", 'w');
		fclose($ourFileHandle);
		$trainlist = $database->getTrainingList();
		if(count($trainlist) > 0) {
			foreach($trainlist as $train) {
				$timepast = $train['timestamp'] - $train['commence'];
				$trained = floor($timepast/$train['eachtime']);
				$pop = $train['pop'] * $trained;
				if($trained >= $train['amt']) {
					$trained = $train['amt'];
				}
				$database->modifyUnit($train['vref'],$train['unit'],$trained,1);
				$database->modifyPop($train['vref'],$pop,0);
				if($train['amt']-$trained <= 0) {
					$database->trainUnit($train['id'],0,0,0,0,0,1);
				}
				if($trained > 0) {
					$database->modifyCommence($train['id']);
				}
				$database->updateTraining($train['id'],$trained);
			}
		}
		unlink("engine/session/training.txt");
	}
	
	private function procDistanceTime($coor,$thiscoor,$ref,$mode) {
		global $bid28,$bid14,$database,$generator;
		$resarray = $database->getResourceLevel($generator->getBaseID($coor['x'],$coor['y']));
		if($thiscoor['x'] > $coor['x']) {
			$xdistance = $thiscoor['x'] - $coor['x'];
		}
		else {
			$xdistance = $coor['x'] - $thiscoor['x'];
		}
		if(($coor['x'] < 0 && $thiscoor['x'] > 0) || ($thiscoor['x'] < 0 && $coor['x'] > 0)) {
			$xdistance += 1;
		}
		if($xdistance >= WORLD_MAX) {
			while($xdistance >= WORLD_MAX):
			$xdistance -= WORLD_MAX;
			endwhile;
		}
		if($thiscoor['y'] > $coor['y']) {
			$ydistance = $thiscoor['y'] - $coor['y'];
		}
		else {
			$ydistance = $coor['y'] - $thiscoor['y'];
		}
		if(($coor['y'] < 0 && $thiscoor['y'] > 0) || ($thiscoor['y'] < 0 && $coor['y'] > 0)) {
			$ydistance += 1;
		}
		if($ydistance >= WORLD_MAX) {
			while($ydistance >= WORLD_MAX):
			$ydistance -= WORLD_MAX;
			endwhile;
		}
		$distance = $xdistance + $ydistance;
		if(!$mode) {
			if($ref == 1) {
				$speed = 16;
			}
			else if($ref == 2) {
				$speed = 24;
			}
			else {
				$speed = 12;
			}
			if($this->getTypeLevel(28,$resarray) != 0) {
				$speed *= $bid28[$this->getTypeLevel(28,$resarray)]['attri'] / 100;
			}
		}
		else {
			$speed = $ref;
			if($this->getTypeLevel(14,$resarray) != 0) {
				$speed *= $bid14[$this->getTypeLevel(14,$resarray)]['attri'] / 100;
			}
		}
		return round(($distance/$speed) * 3600 / INCREASE_SPEED);
	}
	
	private function getTypeLevel($tid,$resarray) {
		global $village;
		$keyholder = array();
		foreach(array_keys($resarray,$tid) as $key) {
			if(strpos($key,'t')) { 
				$key = preg_replace("/[^0-9]/", '', $key);
				array_push($keyholder, $key);
			} 
		}
		$element = count($keyholder);
		if($element >= 2) {
			if($tid <= 4) {
				$temparray = array();
				for($i=0;$i<=$element-1;$i++) {
					array_push($temparray,$resarray['f'.$keyholder[$i]]);
				}
				foreach ($temparray as $key => $val) {
					if ($val == max($temparray)) 
					$target = $key; 
				}
			}
			else {
				for($i=0;$i<=$element-1;$i++) {
					if($resarray['f'.$keyholder[$i]] != $this->getTypeMaxLevel($tid)) {
						$target = $i;
					}
				}
			}
		}
		else if($element == 1) {
			$target = 0;
		}
		else {
			return 0;
		}
		if($keyholder[$target] != "") {
			return $resarray['f'.$keyholder[$target]];
		}
		else {
			return 0;
		}
	}
};

$automation = new Automation;
?>
