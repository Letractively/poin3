<?php
switch($_GET['f']) {
	case 'k7':
		$x = $_GET['x'];
		$y = $_GET['y'];
		$xx = $_GET['xx'];
		$yy = $_GET['yy'];
		$howmany = $x - $xx;
		if($howmany == 12 || $howmany == -12) {
			include("style/core/ajax/mapscroll2.tpl");
		}
		else {
		include("style/core/ajax/mapscroll.tpl");
		}
		break;
	case 'kp':
		$z = $_GET['z'];
		include("style/core/ajax/plusmap.tpl");
		break;
}
?>
