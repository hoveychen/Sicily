<?php
$cid = isset($_GET["cid"]) ? $_GET['cid'] : "";
if ($cid)
	$navmode = "contest";
require("./navigation.php");
?>

<?
global $app_config;

$sid = $_GET['sid'];
if (isset($_GET['cid']))
	$cid = $_GET['cid']; else
	$cid = "";

if ($cid) {
	$problem = new ContestStatus($cid);
	$problem->Get($sid);
	$sid = $problem->detail['sid'];
}
$status = new StatusTbl();

if (!$status->Get($sid))
	error("No such submission!");
$content = $status->detail['compilelog'];
$content = ereg_replace("[0-9a-zA-Z]*/", "", $content);
?>

<div style="background-image: url(images/bg2.gif); padding: 15px;">

    <table width="100%">
        <tr><td>
                <pre><?php echo $content; ?></pre>
            </td></tr>

    </table>
</div>

<?php
require("./footer.php");
?>
