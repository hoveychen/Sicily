<?php
require("./navigation.php");

$cid = safeget('cid');
$contest = new ContestsTbl($cid);
$contest->Get() or error("No such contest");

if (isset($_POST['pwd'])) {
	$pwd = safepost('pwd');
	if ($contest->detail['authtype'] != 'password')
		error("No password is needed");
	if ($contest->detail['pwd'] == $pwd) {
		$_SESSION["access$cid"] = 1;
		MsgAndRedirect("contest_detail.php?cid=$cid");
	} else {
		$error_msg = "Password Incorrect";
	}
}
if (isset($_SESSION["access$cid"]) && $_SESSION["access$cid"] == 1
		|| $contest->detail['authtype'] != 'password') {
	// already auth
	MsgAndRedirect("contest_detail.php?cid=$cid");
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
                    <form action="contest_password.php?cid=<?= $cid ?>" method="post">
                        <table class="tblcontainer ui-widget-content ui-corner-all ui-widget" border="0" cellpadding="4" cellspacing="2">
                            <tr>
                                <th class="ui-widget-header" height="20" colspan="2" align="center"><b><?= _("Enter password") ?></b></th>
                            </tr>
                            <tr>
                                <td width="130" height="20" align="right"><?= _("Contest/Exercise") ?> &nbsp;</td>
                                <td width="220" align="left">&nbsp;
                                    <?=  $contest->detail['title'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" align="right"><?= _("Password") ?> &nbsp;</td>
                                <td align="left">&nbsp;
                                    <input type="password" name="pwd" size="30" maxlength="20">
                                </td>
                            </tr>
                            <tr align="center">
                                <td height="20" colspan="2" align="center">
                                    <input type="submit" size="20" />
									<? if (isset($error_msg)): ?>
										<div class="ui-state-error ui-widget ui-corner-all"><?= $error_msg ?></div>
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
