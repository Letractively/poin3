<?php
include("engine/account.php");
if(isset($_GET['del_cookie'])) {
	setcookie("COOKUSR","",time()-3600*24,"/");
	header("Location: login.php");
}
if(!isset($_COOKIE['COOKUSR'])) {
	$_COOKIE['COOKUSR'] = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo SERVER_NAME; ?></title>
        <link REL="shortcut icon" HREF="favicon.ico"/>
		<meta name="content-language" content="en" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<script src="mt-core.js?0faaa" type="text/javascript"></script>
		<script src="mt-more.js?0faaa" type="text/javascript"></script>
		<script src="unx.js?0faaa" type="text/javascript"></script>
		<script src="new.js?0faaa" type="text/javascript"></script>
            <link href="gpack/travian_0002/lang/en/lang.css?6fa07" rel="stylesheet" type="text/css" /><link href="gpack/travian_0002/lang/en/compact.css?6fa07" rel="stylesheet" type="text/css" />   
       </head>

<body class="v35 ie ie7" onload="initCounter()">

<div class="wrapper">
<div id="dynamic_header">
</div>
<div id="header"></div>
<div id="mid">
<?php include("style/core/menu.tpl"); ?>

<div id="content"  class="login">

<h1><img class="img_login" src="img/x.gif" alt="log in the game" /></h1>
<h5><img class="img_u04" src="img/x.gif" alt="login" /></h5>
<p>You must have cookies enabled to be able to log in. If you share this computer with other people you should log out after each session for your own safety.</p>

<form method="post" name="snd" action="login.php">
<input type="hidden" name="ft" value="a4" />

<table cellpadding="1" cellspacing="1" id="login_form">
	<tbody>
		<tr class="top">
			<th>Name</th>
			<td><input class="text" type="text" name="user" value="<?php echo $form->getDiff("user",$_COOKIE['COOKUSR']); ?>" maxlength="15" autocomplete='off' /> <span class="error"> <?php echo $form->getError("user"); ?></span></td>
		</tr>
		<tr class="btm">
			<th>Password</th>
			<td><input class="text" type="password" name="pw" value="<?php echo $form->getValue("pw");?>" maxlength="20" autocomplete='off' /> <span class="error"><?php echo $form->getError("pw"); ?></span></td>
		</tr>
	</tbody>
</table>

<p class="btn">
	<!--<input type="hidden" name="e1d9d0c" value="" />-->
		<input type="image" value="login" name="s1"	onclick="xy();" id="btn_login" class="dynamic_img" src="img/x.gif" alt="login button"	/>
</p>

</form>
<?php
if ($form->getError("pw") == LOGIN_PW_ERROR) {
echo "<p class=\"error_box\">
	<span class=\"error\">Password forgotten?</span><br>
	Then you can request a new one which will be sent to your email address.<br>
	<a href=\"activate.php?npw=71699\">Generate new password.</a>
</p>";
}
if($form->getError("activate") != "") {
	echo "<p class=\"error_box\">
	<span class=\"error\">Email not verified!</span><br>
	Follow this link to activate your account.<br>
	<a href=\"activate.php?usr=".$form->getError("activate")."\">Verify Email.</a>
	</p>";
}
?>
</div>
<div id="side_info" class="outgame">
<?php
/*if(SHOW_NEWSBOX1) { include("Templates/News/newsbox1.tpl"); }
if(SHOW_NEWSBOX2) { include("Templates/News/newsbox2.tpl"); }
if(SHOW_NEWSBOX3) { include("Templates/News/newsbox3.tpl"); }*/
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
