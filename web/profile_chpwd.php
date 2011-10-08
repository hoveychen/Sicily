<?php
require("./navigation.php");
if (!$logged)
	error("You are not logged in.");
$user = new UserTbl();
if ($user->Get($login_uid)) {
	$email = $user->detail['email'];
	$address = $user->detail['address'];
	$phone = $user->detail['phone'];
	$nickname = $user->detail['nickname'];
	$signature = $user->detail['signature'];
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
                    <form action="process.php?act=ChangePwd" method="post" id="setting_form">
                        <table class="tblcontainer ui-widget-content ui-corner-all ui-widget" border="0" cellpadding="4" cellspacing="2">
                            <tr>
                                <th class="ui-widget-header" height="20" colspan="2" align="center"><b><?= _("Change Password") ?></b></th>
                            </tr>
                            <tr>
                                <td width="130" height="20" align="right"><?= _("User name") ?> &nbsp;</td>
                                <td width="220" align="left">&nbsp;
                                    <input disabled type="text" size="30" maxlength="20" value="<? echo $login_username ?>">
                                    <input name="username" type="hidden" id="username" size="30" maxlength="20" value="<? echo $login_username ?>">
                                </td>
                            </tr>
                            <tr>
                                <td height="20" align="right"><?= _("Original Password") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="password" id="password" name="password" size="30" maxlength="20">
                                </td>
                            </tr>
                            <tr>
                                <td height="20" align="right"><?= _("New password") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="password" name="pass1" size="30" maxlength="20">
                                </td>
                            </tr>
                            <tr>
                                <td height="20" align="right"><?= _("Confirm password") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="password" name="pass2" size="30" maxlength="20">
                                </td>
                            </tr>
                            <tr align="center">
                                <td height="20" colspan="2" align="center">
                                    <input type="submit" value="<?= _("Update") ?>" name="addsub" size="20">
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
