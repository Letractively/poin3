<?php
/*** Comments ***
* 2010-05-21 - Cleanup complete (fixed capitals)
*
*
*/

//include("Battle.php");
include("session.php");
include("building.php");
include("market.php");
include("technology.php");

class Village {
	
	public $type;
	public $coor = array();
	public $awood,$aclay,$airon,$acrop,$pop,$maxstore,$maxcrop;
	public $wid,$vname,$capital;
	public $resarray = array();
	public $unitarray,$techarray,$unitall,$researching,$abarray = array();
	private $infoarray = array();
	private $production = array();
	private $oasisowned,$ocounter = array();
	
	function Village() {
		global $session;
		if(isset($_SESSION['wid'])) {
			$this->wid = $_SESSION['wid'];
		}
		else {
			$this->wid = $session->villages[0];
		}
		$this->LoadTown();
		$this->calculateProduction();
		$this->processProduction();
		$this->ActionControl();
	}
	
	public function getProd($type) {
		return $this->production[$type];
	}
	
	private function LoadTown() {
		global $database,$session,$logging,$technology;
		$this->infoarray = $database->getVillage($this->wid);
		if($this->infoarray['owner'] != $session->uid && !$session->isAdmin) {
			unset($_SESSION['wid']);
			$logging->addIllegal($session->uid,$this->wid,1);
			$this->wid = $session->villages[0];
			$this->infoarray = $database->getVillage($this->wid);
		}
		$this->resarray = $database->getResourceLevel($this->wid);
		$this->coor = $database->getCoor($this->wid);
		$this->type = $database->getVillageType($this->wid);
		$this->oasisowned = $database->getOasis($this->wid);
		$this->ocounter = $this->sortOasis();
		$this->unitarray = $database->getUnit($this->wid);
		$this->enforcearray = $database->getEnforce($this->wid,1);
		$this->unitall =  $technology->getUnits($this->unitarray,$this->enforcearray);
		$this->techarray = $database->getTech($this->wid);
		$this->abarray = $database->getABTech($this->wid);
		$this->researching = $database->getResearching($this->wid);
		
		$this->capital = $this->infoarray['capital'];
		$this->wid = $this->infoarray['wref'];
		$this->vname = $this->infoarray['name'];
		$this->awood = $this->infoarray['wood'];
		$this->aclay = $this->infoarray['clay'];
		$this->airon = $this->infoarray['iron'];
		$this->acrop = $this->infoarray['crop'];
		$this->pop = $this->infoarray['pop'];
		$this->maxstore = $this->infoarray['maxstore'];
		$this->maxcrop = $this->infoarray['maxcrop'];

	}
	
	private function calculateProduction() { 
		$this->production['wood'] = $this->getWoodProd();
		$this->production['clay'] = $this->getClayProd();
		$this->production['iron'] = $this->getIronProd();
		$this->production['crop'] = $this->getCropProd();
	}
	
	private function processProduction() {
		global $database;
		$timepast = time() - $this->infoarray['lastupdate'];
		$nwood = ($this->production['wood'] / 3600) * $timepast;
		$nclay = ($this->production['clay'] / 3600) * $timepast;
		$niron = ($this->production['iron'] / 3600) * $timepast;
		$ncrop = ($this->production['crop'] / 3600) * $timepast;
		$database->modifyResource($this->wid,$nwood,$nclay,$niron,$ncrop,1);
		$database->updateVillage($this->wid);
		$this->LoadTown();
	}
	
	private function getWoodProd() {
		global $bid1,$bid5,$session;
		$wood = $sawmill = 0;
		$woodholder = array();
		for($i=1;$i<=38;$i++) {
			if($this->resarray['f'.$i.'t'] == 1) {
				array_push($woodholder,'f'.$i);
			}
			if($this->resarray['f'.$i.'t'] == 5) {
				$sawmill = $this->resarray['f'.$i];
			}
		}
		for($i=0;$i<=count($woodholder)-1;$i++) { $wood+= $bid1[$this->resarray[$woodholder[$i]]]['prod']; }
		if($sawmill >= 1) {
			$wood += $wood /100 * $bid5[$sawmill]['attri'];
		}
		if($this->ocounter[0] != 0) {
			$wood += $wood*0.25*$this->ocounter[0];
		}
		if($session->bonus >= 1000) {
			$wood *= 1.25;
		}
		$wood += $wood*$this->ocounter[0]*0.25;
		$wood *= SPEED;
		return round($wood);
	}
	
	private function getClayProd() {
		global $bid2,$bid6,$session;
		$clay = $brick = 0;
		$clayholder = array();
		for($i=1;$i<=38;$i++) {
			if($this->resarray['f'.$i.'t'] == 2) {
				array_push($clayholder,'f'.$i);
			}
			if($this->resarray['f'.$i.'t'] == 6) {
				$brick = $this->resarray['f'.$i];
			}
		}
		for($i=0;$i<=count($clayholder)-1;$i++) { $clay+= $bid2[$this->resarray[$clayholder[$i]]]['prod']; }
		if($brick >= 1) {
			$clay += $clay /100 * $bid6[$brick]['attri'];
		}
		if($this->ocounter[1] != 0) {
			$clay += $clay*0.25*$this->ocounter[1];
		}
		if($session->bonus >= 200) {
			$clay *= 1.25;
		}
		$clay += $clay*$this->ocounter[1]*0.25;
		$clay *= SPEED;
		return round($clay);
	}
	
	private function getIronProd() {
		global $bid3,$bid7,$session;
		$iron = $foundry = 0;
		$ironholder = array();
		for($i=1;$i<=38;$i++) {
			if($this->resarray['f'.$i.'t'] == 3) {
				array_push($ironholder,'f'.$i);
			}
			if($this->resarray['f'.$i.'t'] == 7) {
				$foundry = $this->resarray['f'.$i];
			}
		}
		for($i=0;$i<=count($ironholder)-1;$i++) { $iron+= $bid3[$this->resarray[$ironholder[$i]]]['prod']; }
		if($foundry >= 1) {
			$iron += $iron /100 * $bid7[$foundry]['attri'];
		}
		if($this->ocounter[2] != 0) {
			$iron += $iron*0.25*$this->ocounter[2];
		}
		if($session->bonus >= 30) {
			$iron *= 1.25;
		}
		$iron += $iron*$this->ocounter[2]*0.25;
		$iron *= SPEED;
		return round($iron);
	}
	
	private function getCropProd() {
		global $bid4,$bid8,$bid9,$session;
		$crop = $grainmill = $bakery = 0;
		$cropholder = array();
		for($i=1;$i<=38;$i++) {
			if($this->resarray['f'.$i.'t'] == 4) {
				array_push($cropholder,'f'.$i);
			}
			if($this->resarray['f'.$i.'t'] == 8) {
				$grainmill = $this->resarray['f'.$i];
			}
			if($this->resarray['f'.$i.'t'] == 9) {
				$bakery = $this->resarray['f'.$i];
			}
		}
		for($i=0;$i<=count($cropholder)-1;$i++) { $crop+= $bid4[$this->resarray[$cropholder[$i]]]['prod']; }
		if($grainmill >= 1) {
			$crop += $crop /100 * $bid8[$grainmill]['attri'];
		}
		if($bakery >= 1) {
			$crop += $crop /100 * $bid9[$bakery]['attri'];
		}
		if($this->ocounter[3] != 0) {
			$crop += $crop*0.25*$this->ocounter[3];
		}
		if($session->bonus >= 4) {
			$crop *= 1.25;
		}
		$crop += $crop*$this->ocounter[3]*0.25;
		$crop *= SPEED;
		$crop -= $this->pop;
		return round($crop);
	}
	
	private function sortOasis() {
		$crop = $clay = $wood = $iron = 0;
		foreach ($this->oasisowned as $oasis) {
		switch($oasis['type']) {
				case 1:
				case 2:
				$wood += 1;
				break;
				case 3:
				$wood += 1;
				$crop += 1;
				break;
				case 4:
				case 5:
				$clay += 1;
				break;
				case 6:
				$clay += 1;
				$crop += 1;
				break;
				case 7:
				case 8:
				$iron += 1;
				break;
				case 9:
				$iron += 1;
				$crop += 1;
				break;
				case 10:
				case 11:
				$crop += 1;
				break;
				case 12:
				$crop += 2;
				break;
			}
		}
		return array($wood,$clay,$iron,$crop);
	}
	
	private function ActionControl() {
		global $session;
		if(SERVER_WEB_ROOT) {
			$page = $_SERVER['SCRIPT_NAME'];
		}
		else {
			$explode = explode("/",$_SERVER['SCRIPT_NAME']);
			$page = $explode[2];
		}
		if($page == "build.php" && $session->uid != $this->infoarray['owner']) {
			unset($_SESSION['wid']);
			header("Location: dorf1.php");
		}
	}
	
};
$village = new Village;
$building = new Building;

?>
