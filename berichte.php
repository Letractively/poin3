<?php
include("engine/village.php");
$start = $generator->pageLoadTimeStart();
$message->noticeType($_GET);
$message->procNotice($_POST);
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
		<div id="content"  class="reports">
<h1>Reports</h1>
<div id="textmenu">
   <a href="berichte.php" <?php if (!isset($_GET['t'])) { echo "class=\"selected \""; } ?>>All</a>
 | <a href="berichte.php?t=2" <?php if (isset($_GET['t']) && $_GET['t'] == 2) { echo "class=\"selected \""; } ?>>Trade</a>
 | <a href="berichte.php?t=1" <?php if (isset($_GET['t']) && $_GET['t'] == 1) { echo "class=\"selected \""; } ?>>Reinforcement</a>
 | <a href="berichte.php?t=3" <?php if (isset($_GET['t']) && $_GET['t'] == 3) { echo "class=\"selected \""; } ?>>Attacks</a>
 | <a href="berichte.php?t=4" <?php if (isset($_GET['t']) && $_GET['t'] == 4) { echo "class=\"selected \""; } ?>>Miscellaneous</a>
 <?php if($session->plus) {
 echo "| <a href=\"berichte.php?t=5\"";
 if (isset($_GET['t']) && $_GET['t'] == 5) { echo "class=\"selected \""; } 
 echo ">Archive</a>";
 }
 ?>
</div>
<?php 
if(isset($_GET['id'])) {
	$type = ($message->readingNotice['ntype'] == 5)? $message->readingNotice['archive'] : $message->readingNotice['ntype'];
	include("style/core/notice".$type.".tpl");
}
else {
	include("style/core/notice/all.tpl");
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
