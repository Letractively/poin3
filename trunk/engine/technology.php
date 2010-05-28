<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

class Technology {
	
	private $unarray = array(1=>"Legionnaire","Praetorian","Imperian","Equites Legati","Equites Imperatoris","Equites Caesaris","Battering Ram","Fire Catapult","Senator","Settler","Clubswinger","Spearman","Axeman","Scout","Paladin","Teutonic Knight","Ram","Catapult","Chief","Settler","Phalanx","Swordsman","Pathfinder","Theutates Thunder","Druidrider","Haeduan","Ram","Trebuchet","Chieftain","Settler","Rat","Spider","Snake","Bat","Wild Boar","Wolf","Bear","Crocodile","Tiger","Elephant","Pikeman","Thorned Warrior","Guardsman","Birds Of Prey","Axerider","Natarian Knight","War Elephant","Ballista","Natarian Emperor","Settler");
	
	public function grabAcademyRes() {
		global $village;
		$holder = array();
		foreach($village->researching as $research) {
			if(substr($research['tech'],0,1) == "t"){
				array_push($holder,$research);
			}
		}
		return $holder;
	}
	
	public function isResearch($tech,$type) {
		global $village;
		if(count($village->researching) == 0) {
			return false;
		}
		else {
		switch($type) {
			case 1: $string = "t"; break;
			case 2: $string = "a"; break;
			case 3: $string = "b"; break;
		}
		foreach($village->researching as $research) {
			if($research['tech'] == $string.$tech) {
				return true;
			}
		}
		return false;
		}
	}
	
	public function procTech($post) {
		if(isset($post['ft'])) {
			switch($post['ft']) {
				case "t1":
				$this->procTrain($post);
				break;
			}
		}
	}
	
	public function procTechno($get) {
		global $village;
		if(isset($get['a'])) {
			switch($village->resarray['f'.$get['id'].'t']) {
				case 22:
				$this->researchTech($get);
				break;
				case 13:
				//$this->upgradeArmour($get);
				break;
				case 12:
				//$this->upgradeSword($get);
				break;
			}
		}
	}
	
	public function getTrainingList($type) {
		global $database,$village;
		$trainingarray = $database->getTraining($village->wid);
		$listarray = array();
		$footies = array(1,2,3,11,12,13,14,21,22);
		$calvary = array(4,5,6,15,16,23,24,25,26);
		$workshop = array(7,8,17,18,27,28);
		if(count($trainingarray) > 0) {
			foreach($trainingarray as $train) {
				if($type == 1 && in_array($train['unit'],$footies)) {
				$train['name'] = $this->unarray[$train['unit']];
				array_push($listarray,$train);
				}
				if($type == 2 && in_array($train['unit'],$calvary)) {
					$train['name'] = $this->unarray[$train['unit']];
					array_push($listarray,$train);
				}
				if($type == 3 && in_array($train['unit'],$workshop)) {
					$train['name'] = $this->unarray[$train['unit']];
					array_push($listarray,$train);
				}
			}
		}
		return $listarray;
	}
	
	public function getUnitList() {
		global $database,$village;
		$unitarray = func_num_args() == 1? $database->getUnit(func_get_arg(0)) : $village->unitall;
		$listArray = array();
		for($i=1;$i<count($this->unarray);$i++) {
			$holder = array();
			if($unitarray['u'.$i] != 0 && $unitarray['u'.$i] != "") {
				$holder['id'] = $i;
				$holder['name'] = $this->unarray[$i];
				$holder['amt'] = $unitarray['u'.$i];
				array_push($listArray,$holder);
			}
		}
		return $listArray;
	}
	
	public function maxUnit($unit) {
		$unit = "u".$unit;
		global $village,$$unit;
		$unitarray = $$unit;
		$woodcalc = floor($village->awood / $unitarray['wood']);
		$claycalc = floor($village->aclay / $unitarray['clay']);
		$ironcalc = floor($village->airon / $unitarray['iron']);
		$cropcalc = floor($village->acrop / $unitarray['crop']);
		$popcalc = floor($village->getProd("crop")/$unitarray['pop']);
		return min($woodcalc,$claycalc,$ironcalc,$cropcalc,$popcalc);
	}
	
	public function getUnits() {
		global $database,$village;
		if(func_num_args() == 1) {
			$base = func_get_arg(0);
		}
		$ownunit = func_num_args() == 2? func_get_arg(0) : $database->getUnit($base);
		$enforcementarray = func_num_args() == 2? func_get_arg(1) : $database->getEnforce($base,1);
		if(count($enforcementarray) > 0) {
			foreach($enforcementarray as $enforce) {
				for($i=1;$i<=50;$i++) {
					$ownunit['u'.$i] += $enforce['u'.$i];
				}
			}
		}
		return $ownunit;
	}
	
	public function meetTRequirement($unit) {
		global $session;
		switch($unit) {
			case 1:
			if($session->tribe == 1) { return true; } else { return false; }
			break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			if($this->getTech($unit) && $session->tribe == 1) { return true; } else { return false; }
			break;
			case 11:
			if($session->tribe == 2) { return true; } else { return false; }
			break;
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			if($session->tribe == 2 && $this->getTech($unit)) { return true; } else { return false; }
			break;
			case 21:
			if($session->tribe == 3) { return true; } else { return false; }
			break;
			case 22: 
			case 23:
			case 24:
			case 25:
			case 26:
			case 27:
			case 28:
			if($session->tribe == 3 && $this->getTech($unit)) { return true; } else { return false; }
			break;
		}
	}
	
	public function getTech($tech) {
		global $village;
		return ($village->techarray['t'.$tech] == 1);
	}
	
	private function procTrain($post) {
		global $session;
		$start = ($session->tribe == 1)? 1 : (($session->tribe == 2)? 11 : 21);
		for($i=$start;$i<=($start+9);$i++) {
			if(isset($post['t'.$i]) && $post['t'.$i] != 0) {
				$amt = $post['t'.$i];
				$this->trainUnit($i,$amt);
			}
		}
		header("Location: build.php?id=".$post['id']);
	}
	
	public function getUpkeep($array,$type) {
		$upkeep = 0;
		switch($type) {
			case 0:
			$start = 1;
			$end = 50;
			break;
			case 1:
			$start = 1;
			$end = 10;
			break;
			case 2:
			$start = 11;
			$end = 20;
			break;
			case 3:
			$start = 21;
			$end = 30;
			break;
			case 4:
			$start = 31;
			$end = 40;
			break;
		}	
		for($i=$start;$i<=$end;$i++) {
			$unit = "u".$i;
			global $$unit;
			$dataarray = $$unit;
			$upkeep += $dataarray['pop'] * $array[$unit];
		}
		return $upkeep;
	}
	
	private function trainUnit($unit,$amt) {
		global $session,$database,${'u'.$unit},$building,$village,$bid19,$bid20,$bid21;
		if($this->meetTRequirement($unit)) {
			$footies = array(1,2,3,11,12,13,14,21,22);
			$calvary = array(4,5,6,15,16,23,24,25,26);
			$workshop = array(7,8,17,18,27,28);
			if(in_array($unit,$footies)) {
				$each = round(($bid19[$building->getTypeLevel(19)]['attri'] / 100) * ${'u'.$unit}['time'] / SPEED);
			}
			if(in_array($unit,$calvary)) {
				$each = round(($bid20[$building->getTypeLevel(20)]['attri'] / 100) * ${'u'.$unit}['time'] / SPEED);
			}
			if(in_array($unit,$workshop)) {
				$each = round(($bid21[$building->getTypeLevel(21)]['attri'] / 100) * ${'u'.$unit}['time'] / SPEED);
			}
			$wood = ${'u'.$unit}['wood'] * $amt;
			$clay = ${'u'.$unit}['clay'] * $amt;
			$iron = ${'u'.$unit}['iron'] * $amt;
			$crop = ${'u'.$unit}['crop'] * $amt;
			if($this->maxUnit($unit) <= $amt) {
				$amt = $this->maxUnit($unit);
			}
			if($database->modifyResource($village->wid,$wood,$clay,$iron,$crop,0)) {
				$database->trainUnit($village->wid,$unit,$amt,${'u'.$unit}['pop'],$each,time(),0);
			}
		}
	}
	
	public function meetRRequirement($tech) {
		global $session,$building;
		switch($tech) {
			case 2:
			if($building->getTypeLevel(22) >= 1 && $building->getTypeLevel(13) >= 1) { return true; } else { return false; }
			break;
			case 3:
			if($building->getTypeLevel(22) >= 5 && $building->getTypeLevel(12) >= 1) { return true; } else { return false; }
			break;
			case 4:
			case 23:
			if($building->getTypeLevel(22) >= 5 && $building->getTypeLevel(20) >= 1) { return true; } else { return false; }
			break;
			case 5:
			case 25:
			if($building->getTypeLevel(22) >= 5 && $building->getTypeLevel(20) >= 5) { return true; } else { return false; }
			break;
			case 6:
			if($building->getTypeLevel(22) >= 5 && $building->getTypeLevel(20) >= 10) { return true; } else { return false; }
			break;	
			case 9:
			case 29:
			if($building->getTypeLevel(22) >= 20 && $building->getTypeLevel(16) >= 10) { return true; } else { return false; }
			break;
			case 12:
			if($building->getTypeLevel(22) >= 1 && $building->getTypeLevel(19) >= 3) { return true; } else { return false; }
			break;
			case 13:
			if($building->getTypeLevel(22) >= 3 && $building->getTypeLevel(11) >= 1) { return true; } else { return false; }
			break;
			case 14:
			if($building->getTypeLevel(22) >= 1 && $building->getTypeLevel(15) >= 5) { return true; } else { return false; }
			break;
			case 15:
			if($building->getTypeLevel(22) >= 1 && $building->getTypeLevel(20) >= 3) { return true; } else { return false; }
			break;
			case 16:
			case 26:
			if($building->getTypeLevel(22) >= 15 && $building->getTypeLevel(20) >= 10) { return true; } else { return false; }
			break;
			case 7:
			case 17:
			case 27:
			if($building->getTypeLevel(22) >= 10 && $building->getTypeLevel(21) >= 1) { return true; } else { return false; }
			break;
			case 8:
			case 18:
			case 28:
			if($building->getTypeLevel(22) >= 15 && $building->getTypeLevel(21) >= 10) { return true; } else { return false; }
			break;
			case 19:
			if($building->getTypeLevel(22) >= 20 && $building->getTypeLevel(16) >= 5) { return true; } else { return false; }
			break;
			case 22:
			if($building->getTypeLevel(22) >= 3 && $building->getTypeLevel(12) >= 1) { return true; } else { return false; }
			break;
			case 24:
			if($building->getTypeLevel(22) >= 5 && $building->getTypeLevel(20) >= 3) { return true; } else { return false; }
			break;
		}
	}
	
	private function researchTech($get) {
		global $database,$session,${'r'.$get['a']},$village,$logging;
		if($this->meetRRequirement($get['a']) && $get['c'] == $session->mchecker) {
			$data = ${'r'.$get['a']};
			$time = time() + round($data['time']/SPEED);
			$database->modifyResource($village->wid,$data['wood'],$data['clay'],$data['iron'],$data['crop'],0);
			$database->addResearch($village->wid,"t".$get['a'],$time);
			$logging->addTechLog($village->wid,"t".$get['a'],1);
		}
		$session->changeChecker();
		header("Location: build.php?id=".$get['id']);
	}
	
	public function getUnitName($i) {
		return $this->unarray[$i];
	}
	
	public function finishTech() {
		global $database,$village;
		$q = "SELECT * FROM ".TB_PREFIX."research where vref = ".$village->wid;
		$array = $database->query_return($q);
		foreach($array as $tech) {
			$type = substr($tech['tech'],0,1);
			switch($type){
				case "t":
				$q = "UPDATE ".TB_PREFIX."tdata set ".$tech['tech']." = 1 where vref = ".$village->wid;
				break;
				case "a":
				case "b":
				$q = "UPDATE ".TB_PREFIX."abdata set ".$tech['tech']." = ".$tech['tech']." + 1 where vref = ".$village->wid;
				break;
			}
			$database->query($q);
		}
	}
	
	public function calculateAvaliable($id) {
		global $village,$generator,${'r'.$id};
		$rwood = ${'r'.$id}['wood']-$village->awood;
		$rclay = ${'r'.$id}['clay']-$village->aclay;
		$rcrop = ${'r'.$id}['crop']-$village->acrop;
		$riron = ${'r'.$id}['iron']-$village->airon;
		$rwtime = $rwood / $village->getProd("wood") * 3600;
		$rcltime = $rclay / $village->getProd("clay")* 3600;
		$rctime = $rcrop / $village->getProd("crop")* 3600;
		$ritime = $riron / $village->getProd("iron")* 3600;
		$reqtime = max($rwtime,$rctime,$rcltime,$ritime);
		$reqtime += time();
		return $generator->procMtime($reqtime);
	}
	
}
$technology = new Technology;
?>
