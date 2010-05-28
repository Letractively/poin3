<?php
include("engine/village.php");
$start = $generator->pageLoadTimeStart();
if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
}
else {
	$building->procBuild($_GET);
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
<div id="content"  class="village1">
<h1><?php echo $village->vname; ?><br /></h1>
<?php include("style/core/field.tpl");
$timer = 1;
?>
<div id="map_details">
	<table id="movements" cellpadding="1" cellspacing="1"><tbody><tr class="empty"><td></td></tr></tbody></table>
<!--   red sword = incoming attack <table id="movements" cellpadding="1" cellspacing="1"><thead><tr><th colspan="3">Troop Movements:</th></tr></thead><tbody><tr>
		<td class="typ"><a href="build.php?gid=16"><img src="img/x.gif" class="def1" alt="Arriving reinforcing troops" title="Arriving reinforcing troops" /></a><span class="d1">&raquo;</span></td>
		<td><div class="mov"><span class="d1">1&nbsp;Reinf.</span></div><div class="dur_r">in&nbsp;<span id="timer1">11:59:37</span>&nbsp;hrs.</div></div></td></tr></tbody></table>-->

<?php

include("style/core/production.tpl");
include("style/core/troops.tpl"); ?>
<?php 
if($building->NewBuilding) {
	include("style/core/Building.tpl");
}
?>
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
