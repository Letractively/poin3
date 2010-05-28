<?php
include('engine/account.php');
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
<div id="content"  class="signup">

<h1><img src="img/x.gif" class="anmelden" alt="register for the game"></h1>
<h5><img src="img/x.gif" class="img_u05" alt="registration"/></h5>

<p>Before you register an account you should read the <a href="../anleitung.php" target="_blank">instructions</a> of Travian to see the specific advantages and disadvantages of the three tribes.</p>

<form name="snd" method="post" action="anmelden.php">
<input type="hidden" name="ft" value="a1" />

<table cellpadding="1" cellspacing="1" id="sign_input">
	<tbody>
		<tr class="top">
			<th>Nickname</td>
			<td><input class="text" type="text" name="name" value="<?php echo $form->getValue('name'); ?>" maxlength="15" />
			<span class="error"><?php echo $form->getError('name'); ?></span>
			</td>
		</tr>
		<tr>
			<th>Email</th>
			<td>
				<input class="text" type="text" name="email" value="<?php echo $form->getValue('email'); ?>" maxlength="40" />
				<span class="error"><?php echo $form->getError('email'); ?></span>
				</td>
			</tr>
		<tr class="btm">
			<th>Password</th>
			<td>
				<input class="text" type="password" name="pw" value="<?php echo $form->getValue('pw'); ?>" maxlength="20" />
				<span class="error"><?php echo $form->getError('pw'); ?></span>
			</td>
		</tr>
	</tbody>
</table>

<table cellpadding="1" cellspacing="1" id="sign_select">
	<tbody>
		<tr class="top">
			<th><img src="img/x.gif" class="img_u06" alt="choose tribe"></th>
			<th colspan="2"><img src="img/x.gif" class="img_u07" alt="starting position"></th>
		</tr>
		<tr>
			<td class="nat"><label><input class="radio" type="radio" name="vid" value="1" <?php echo $form->getRadio('vid',1); ?>>&nbsp;Romans</label></td>
			<td class="pos1"><label><input class="radio" type="radio" name="kid" value="0" checked>&nbsp;random</label></td>
			<td class="pos2">&nbsp;</td>
		</tr>		
		<tr>
			<td><label><input class="radio" type="radio" name="vid" value="2" <?php echo $form->getRadio('vid',2); ?>>&nbsp;Teutons</label></td>
			<td><label><input class="radio" type="radio" name="kid" value="1" <?php echo $form->getRadio('kid',1); ?>>&nbsp;north west (-|+)</label></td>
			<td><label><input class="radio" type="radio" name="kid" value="2" <?php echo $form->getRadio('kid',2); ?>>&nbsp;north east (+|+)</label></td>
		</tr>		
		<tr class="btm">
			<td><label><input class="radio" type="radio" name="vid" value="3" <?php echo $form->getRadio('vid',3); ?>>&nbsp;Gauls</label></td>
			<td><label><input class="radio" type="radio" name="kid" value="3" <?php echo $form->getRadio('kid',3); ?>>&nbsp;south west (-|-)</label></td>
			<td><label><input class="radio" type="radio" name="kid" value="4" <?php echo $form->getRadio('kid',4); ?>>&nbsp;south east (+|-)</label></td>
		</tr>
	</tbody>
</table>

<ul class="important">
<?php
echo $form->getError('tribe');
echo $form->getError('agree');
?>
		</ul>

<p>
		<input class="check" type="checkbox" name="agb" value="1" <?php echo $form->getRadio('agb',1); ?>/> I accept the <a href="http://www.travian.com/spielregeln.php" target="_blank">game rules</a> and <a href="http://www.travian.com/spielregeln.php?agb" target="_blank">general terms and conditions</a>.</p>

<p class="btn">
	<input type="image" value="anmelden" name="s1" id="btn_signup" class="dynamic_img" src="img/x.gif" alt="register"/>
</p>
</form>

<p class="info">Each player may only own ONE account per server.</p>
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
