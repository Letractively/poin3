<?php
include("engine/account.php");
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
	<script src="mt-core.js?0faaa" type="text/javascript"></script>
		<script src="mt-more.js?0faaa" type="text/javascript"></script>
		<script src="unx.js?0faaa" type="text/javascript"></script>
		<script src="new.js?0faaa" type="text/javascript"></script>
    <link href="gpack/travian36/lang/en/lang.css?0faaa" rel="stylesheet" type="text/css" />
    <link href="gpack/travian36/lang/en/compact.css?0faaa" rel="stylesheet" type="text/css" />     
        <link href="img/travian_basics.css" rel="stylesheet" type="text/css" />
</head>

<body class="v35 ie ie7" onload="initCounter()">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />

<div id="dynamic_header">
	</div>


<div id="header"></div>

<div id="mid">
<?php include("style/core/menu.tpl");
if(isset($_GET['e'])) {
	switch($_GET['e']) {
		case 1:
		include("style/core/delete.tpl");
		break;
		case 2:
		include("style/core/activated.tpl");
		break;
		case 3:
		include("style/core/cantfind.tpl");
		break;
	}
}
if(isset($_GET['id']) && isset($_GET['c']) && $_GET['c'] == $generator->encodeStr($_COOKIE['COOKEMAIL'],5)) {
	include("style/core/delete.tpl");
}
if(isset($_GET['id']) && !isset($_GET['c']) && !isset($_GET['e'])) {
	include("style/core/activate.tpl");
}
else if(isset($_GET['usr']) && !isset($_GET['c']) && !isset($_GET['e'])) {
	$_COOKIE['COOKUSR'] = $_GET['usr'];
	$_COOKIE['COOKEMAIL'] = $database->getUserField($_GET['usr'],"email",1);
	include("style/core/activate.tpl");
}
if(isset($_GET['npw'])) {
}
?>

<div id="side_info" class="outgame">
<?php
/*if(SHOW_NEWSBOX1) { include("Templates/News/newsbox1.tpl"); }
if(SHOW_NEWSBOX2) { include("Templates/News/newsbox2.tpl"); }
if(SHOW_NEWSBOX3) { include("Templates/News/newsbox3.tpl"); }*/
?>
			</div>

<div class="clear"></div>

</div>
<div class="clear"></div>
<?php include("style/core/footer.tpl"); ?>
<div id="ce"></div>
</body>
</html>
