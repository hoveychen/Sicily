<?php
include_once("inc/global.inc.php");
set_language("cn");

if (isset($_POST['username'])) {
	require 'inc/user.inc.php';
	if (validate($_POST) == "yes")
		MsgAndRedirect("index.php");
	else
		$errorMsg = _("Invalid username or password");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <title>Sicily Online Judge</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link type="text/css" rel="stylesheet" href="css/global.css"/>
		<link type="text/css" rel="stylesheet" href="css/jquery-ui-1.8.6.custom.css"/>
		<script type="text/javascript" src="js/jquery-1.4.2.min.js" > </script>
		<script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js" > </script>
		<script type="text/javascript">
			$(function(){
				$(window).resize(function() {
					$("#content").position({of: $(window), my: "center center"});
				});
				$("input:submit").button();
				$(window).resize();
			});
                

		</script>
    </head>

    <div style="width: 300px" id ="content">
        <div class="ui-widget-header ui-widget ui-corner-top" style="font-size:x-large; padding: 5px;">
			<?= _("Sicily Online Judge System") ?>
        </div>
        <form id="loginform" action="login.php" method="post" name="loginform">
            <input name="lsession" type="hidden" value="1" id="lsession"/>  
            <div class="ui-widget ui-widget-content ui-corner-bottom" style="font-size: x-large; margin-top: 0px">
                <div style="margin: 10px; text-align: left; padding: 8px;"><?= _("Username:") ?></div>
                <div style="margin: 10px; "><input name="username" type="text" id="username" size="16" maxlength="30" value="sysy_"/></div>
                <div style="margin: 10px; text-align: left; padding: 8px;"><?= _("Password:"); ?></div>
                <div style="margin: 10px;"><input name="password" type="password" id="password" size="16" maxlength="16" /></div>
                <div style="margin: 15px;"><input type="submit" value="<?= _("Login"); ?>"/></div>
				<? if (isset($errorMsg)) { ?>
					<div class="ui-state-error ui-widget ui-corner-all"><?= $errorMsg ?></div>
				<? } ?>
            </div>


        </form> 
    </div>
</html>
