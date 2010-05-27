<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 
<html>
<head>
<title><?php echo SERVER_NAME ?></title>
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="content-type"
	content="text/html; charset=UTF-8" />
    <link href="gpack/travian_0002/lang/en/lang.css?6fa07" rel="stylesheet" type="text/css" /><link href="gpack/travian_0002/lang/en/compact.css?6fa07" rel="stylesheet" type="text/css" /></head>
    <body class="manual">
<?php
if(!isset($_GET['typ']) && !isset($_GET['s'])) {
	include("style/core/manual/00.tpl");
}
else if (!isset($_GET['typ']) && $_GET['s'] == 1) {
	include("style/core/manual/00.tpl");
}
else if (!isset($_GET['typ']) && $_GET['s'] == 2) {
	include("style/core/manual/direct.tpl");
}
else if (isset($_GET['typ']) && $_GET['typ'] == 5 && $_GET['s'] == 3) {
	include("style/core/manual/medal.tpl");
}
else {
	if(isset($_GET['gid'])) {
		include("style/core/manual/".$_GET['typ'].($_GET['gid']).".tpl");
	}
	else {
		if($_GET['typ'] == 4 && $_GET['s'] == 0) {
			$_GET['s'] = 1;
		}
	include("style/core/manual/".$_GET['typ'].$_GET['s'].".tpl");
	}
}
?>
</body>

</html>
