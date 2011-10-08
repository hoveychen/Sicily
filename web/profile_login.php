<?php
require("./navigation.php");

global $logged;
if ($logged)
	error("You have been logged in");
if (isset($_POST['username'])) {
	if (validate($_POST) == "yes") {
		MsgAndRedirect("index.php");
	} else {
		$errorMsg = _("Invalid username or password");
	}
}
?>

<div class="background_container">
    <div class="ui-corner-all ui-widget-content">


        <table width="900" border="0" cellspacing="0" cellpadding="0">
            <tr> 
                <td width="300" height="100" bgcolor="#F0F0F0"><img src="images/register_01.jpg" width="300" height="100"></td>
                <td rowspan="3" align="center" valign="top" background="images/register_02.jpg">
                    <br>
                    <br>
                    <br>
                    <br>
                    <form action="profile_login.php" method="post" id="setting_form">
                        <table class="tblcontainer ui-widget-content ui-corner-all ui-widget" border="0" cellpadding="4" cellspacing="2">
                            <tr>
                                <th class="ui-widget-header" height="20" colspan="2" align="center"><b><?= _("User Login") ?></b></th>
                            </tr>
                            <tr>
                                <td width="130" height="20" align="right"><?= _("User name") ?> &nbsp;</td>
                                <td width="220" align="left">&nbsp;
                                    <input name="username" type="text" id="username" size="30" maxlength="20" value="">
                                </td>
                            </tr>
                            <tr>
                                <td height="20" align="right"><?= _("Password") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="password" name="password" size="30" maxlength="20">
                                </td>
                            </tr>
							<tr>
                                <td height="20" align="right"><?= _("Remember me") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="checkbox" name="lsession" value="1" checked="checked"/>
                                </td>
                            </tr>
                            <tr align="center">
                                <td height="20" colspan="2" align="center">
                                    <input type="submit" value="<?= _("Login") ?>" size="20" />
									<? if (isset($errorMsg)): ?>
										<div class="ui-state-error ui-widget ui-corner-all"><?= $errorMsg ?></div>
									<? endif; ?>
                                </td>
                            </tr>
                        </table>

                    </form>
                </td>
            </tr>
            <tr>
                <td height="282" bgcolor="#F0F0F0"><img src="images/register_03.jpg" width="300" height="282"></td>
            </tr>
            <tr>
                <td height="100" bgcolor="#F0F0F0"><img src="images/register_04.jpg" width="300" height="100"></td>
            </tr>
        </table>

    </div>
</div>
<?php
require("./footer.php");
?>
