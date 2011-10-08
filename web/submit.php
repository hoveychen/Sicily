<?php
$cid = isset($_GET["cid"]) ? $_GET['cid'] : "";
if ($cid)
	$navmode = "contest";
require("./navigation.php");
?>

<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_compressor.php"></script>
<script type="text/javascript">
<?php
echo "var hintMsg = {";
$msg = array(
	array("Accepted", _("Accepted"), _("Congradulations. You have solved this problem")),
	array("Wrong Answer", _("Wrong Answer"), _("Some of your output doesn't match the standard output")),
	array("Compile Error", _("Compile Error"), _("Our compiler can not recognize your source code.")),
	array("Runtime Error", _("Runtime Error"), _("Your program got fatal errors in runtime.")),
	array("Time Limit Exceeded", _("Time Limit Exceeded"), _("The program cost too much time")),
	array("Memory Limit Exceeded", _("Memory Limit Exceeded"), _("The program cost too much memory space")),
	array("Output Limit Exceeded", _("Output Limit Exceeded"), _("It may be in an infinite loop.")),
	array("Presentation Error", _("Presentation Error"), _("It may be extra spaces/blank lines after each line/test case.")),
	array("Restrict Function", _("Restrict Function"), _("It may the same as Runtime Error"))
);
$first = true;
foreach ($msg as $i) {
	if ($first)
		echo $first = false; else
		echo ",";
	echo "\"{$i[0]}\":[\"{$i[1]}\", \"{$i[2]}\"]";
}
echo "};";
?>

</script>
<script language="javascript" type="text/javascript" src="js/submit.js"></script>
<link type="text/css" rel="stylesheet" href="css/submit.css"/>

<?
if (isset($_GET["problem_id"]))
	$problem_id = rtrim($_GET["problem_id"], "#");
else
	$problem_id = "";
// forbid to submit before login
if (!$logged) {
	error("Please login before submitting.");
}

if (isset($_GET["cid"]))
	$cid = $_GET["cid"]; else
	$cid = "";
if ($cid) {
	if (!is_contest_accessible($cid))
		error("You can't access to the contest");
	if (is_contest_ended($cid)) {
		error("The contest is finished");
	}
}
?>

<div class="background_container">
    <div id="judge_box" title="Judge Status">
        <div id="progressbar"></div>
        <div id="progressbar_info"></div>
        <div id="judge_info"></div>
        <div id="testcase_info"></div>
    </div>
    <div id="status_box" title="Final Submit Status">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Basic</a></li>
            </ul>
            <div id = "tabs-1" >
                <div id="status_info"></div>
                <hr>
                <div style="text-align: left">
                    <ul id="status_more_info"></ul>
                </div>
            </div>
        </div>        
    </div>

    <div id="submit_container">
        <form action="process.php?act=Submit" method="post" name="form1" id="submit_form">
            <table class="tblcontainer ui-widget-content ui-corner-all" border="0" cellpadding="4" cellspacing="2" class="normal" width="100%">
				<thead>
                <tr class="ui-widget-header">
                    <td height="20" colspan="2" align="center"><b><?= _("Submit") ?></b></td>
                </tr>
				</thead>
                <tr>
                    <td width="10%" align="right"><?= _("User name") ?></td>
                    <td align="left"><? echo $login_username; ?>
                        <!--[<a href="process.php?act=Logout&amp;redirect=submit.php">Logout</a>]-->
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Problem ID") ?></td>
                    <td align="left"><input name="pid" type="text" id="pid" value="<? echo $problem_id; ?>" size="5" maxlength="5">
                        <span id="problem_title"> </span>
						<? if ($cid)
							echo "<font color=red>" . _("Running Contest") . " $cid</font>"; ?>
						<? if ($cid)
							echo "<input type='hidden' id='cid' name='cid' value='$cid'>"; ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Language") ?></td>
                    <td align="left">
                        <select name="language" size="1" id="language">
                            <option value="1">GNU C 4.4.3</option>
                            <option value="2" selected>GNU C++ 4.4.3</option>
                            <option value="3">Free Pascal 2.4.0-2</option>
<? //			<option value="4">Sun JDK 1.5</option>   ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Source") ?> </td>
                    <td align="left">
                        <textarea name="source" cols="80" rows="20" id="source" style="width:100%"></textarea>
                    </td>
                </tr>
                <tr align="center">
                    <td height="20" colspan="2" align="center">
                        <input type="submit" value="<?= _("Submit") ?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>

</div>

<?php
require("./footer.php");
?>
