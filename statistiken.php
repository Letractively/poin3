<?php 
include("engine/village.php");
$start = $generator->pageLoadTimeStart();
$ranking->procRankReq($_GET);
$ranking->procRank($_POST);
if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php echo SERVER_NAME ?></title>
    <link REL="shortcut icon" HREF="favicon.ico"/>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="mt-full.js?0faaa" type="text/javascript"></script>
	<script src="unx.js?0faaa" type="text/javascript"></script>
	<script src="new.js?0faaa" type="text/javascript"></script>
    <link href="gpack/travian_0002/lang/en/lang.css?6fa07" rel="stylesheet" type="text/css" /><link href="gpack/travian_0002/lang/en/compact.css?6fa07" rel="stylesheet" type="text/css" />        <link href="img/travian_basics.css" rel="stylesheet" type="text/css" />	<script type="text/javascript">
		window.addEvent('domready', start);
	</script>
</head>
 
 
<body class="v35 ie ie8">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<?php include("style/core/header.tpl"); ?>
<div id="mid">
<?php include("style/core/menu.tpl"); ?>
		<div id="content"  class="statistics">
<h1>Statistics</h1>
<div id="textmenu">
   <a href="statistiken.php" <?php if(!isset($_GET['id']) || (isset($_GET['id']) && ($_GET['id'] == 1 || $_GET['id'] == 31 || $_GET['id'] == 32 || $_GET['id'] == 7))) { echo "class=\"selected \""; } ?>>Player</a>
 | <a href="statistiken.php?id=4" <?php if(isset($_GET['id']) && ($_GET['id'] == 4 || $_GET['id'] == 41 || $_GET['id'] == 42 || $_GET['id'] == 47)) { echo "class=\"selected \""; } ?>>Alliances</a>
 | <a href="statistiken.php?id=2" <?php if(isset($_GET['id']) && $_GET['id'] == 2) { echo "class=\"selected \""; } ?>>Villages</a>
 | <a href="statistiken.php?id=8" <?php if(isset($_GET['id']) && $_GET['id'] == 8) { echo "class=\"selected \""; } ?>>Heroes</a>
 | <a href="statistiken.php?id=0" <?php if(isset($_GET['id']) && $_GET['id'] == 0) { echo "class=\"selected \""; } ?>>General</a>
</div>
<?php
if(isset($_GET['id'])) {
	switch($_GET['id']) {
		case 31:
		include("style/core/ranking/player_attack.tpl");
		break;
		case 32:
		include("style/core/ranking/player_defend.tpl");
		break;
		case 7:
		include("style/core/ranking/player_top10.tpl");
		break;
		case 2:
		include("style/core/ranking/villages.tpl");
		break;
		case 4:
		include("style/core/ranking/alliance.tpl");
		break;
		case 41:
		include("style/core/ranking/alliance_attack.tpl");
		break;
		case 42:
		include("style/core/ranking/alliance_defend.tpl");
		break;
		case 1:
		default:
		include("style/core/ranking/overview.tpl");
		break;
	}
}
else {
	include("style/core/ranking/overview.tpl");
}
?>
 </div>
						</td>
					</tr>
				</table>
</div>

<div id="side_info">
<?php
include("style/core/quest.tpl");
include("style/core/multivillage.tpl");
include("style/core/links.tpl");
?>
 </div>
<div class="clear"></div>
</div>
<div class="footer-stopper"></div>
<div class="clear"></div>

<?php 
include("style/core/footer.tpl"); 
include("style/core/res.tpl"); 
?>
<div id="stime">
<div id="ltime">
<div id="ltimeWrap">
Calculated in <b><?php
echo round(($generator->pageLoadTimeEnd()-$start)*1000);
?></b> ms
 
<br />Server time: <span id="tp1" class="b"><?php echo date('H:i:s'); ?></span>
</div>
	</div>
</div>

<div id="ce"></div>
</body>
</html>
