<?php
include("engine/account.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo SERVER_NAME; ?></title>
		<meta name="content-language" content="en" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<script src="mt-core.js?2389c" type="text/javascript"></script>

		<script src="mt-more.js?2389c" type="text/javascript"></script>
		<script src="unx.js?2389c" type="text/javascript"></script>
		<script src="new.js?2389c" type="text/javascript"></script>
         <link href="gpack/travian_0002/lang/en/lang.css?6fa07" rel="stylesheet" type="text/css" /><link href="gpack/travian_0002/lang/en/compact.css?6fa07" rel="stylesheet" type="text/css" />        <link href="img/travian_basics.css" rel="stylesheet" type="text/css" />	<script type="text/javascript">
		window.addEvent('domready', start);
	</script>
</head>
 
 
<body class="v35 ie ie8">
<div class="wrapper">
<img style="filter:chroma();" src="img/x.gif" id="msfilter" alt="" />
<div id="dynamic_header">
	</div>
<div id="header"></div>

<div id="mid">
<?php include("style/core/menu.tpl"); ?>
		<div id="content"  class="logout">
<h1>Logout successful.</h1><img class="roman" src="img/x.gif" alt=""><p>Thank you for your visit.</p>

		<p>If other people use this computer too, you should delete your cookies for your own safety:<br /><a href="login.php?del_cookie">&raquo; delete cookies</a></p>
</div>

<div id="side_info" class="outgame">
<?php
/*if(SHOW_NEWSBOX1) { include("style/core/News/newsbox1.tpl"); }
if(SHOW_NEWSBOX2) { include("style/core/News/newsbox2.tpl"); }
if(SHOW_NEWSBOX3) { include("style/core/News/newsbox3.tpl"); }*/
?>
			</div>

<div class="clear"></div>
			</div>

			<div class="footer-stopper outgame"></div>
            <div class="clear"></div>
            
<?php include("style/core/footer.tpl"); ?>
<div id="ce"></div>
</body>
</html>

