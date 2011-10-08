<?php
global $cinfo;
$cid = safeget("cid");
$contest = new ContestsTbl($cid);
$contest->Get() or error("No such contest");
$cinfo = $contest->detail;
$contest_start = strtotime($cinfo['starttime']);
sscanf($cinfo['during'], "%d:%d:%d", $h, $m, $s);
$contest_end = $contest_start + $h * 3600 + $m * 60 + $s;
$contest_time = "var startTime = new Date(Date.UTC(" . date("Y,m,d,H,i,s", $contest_start) . "));\n";
$contest_time .= "var endTime = new Date(Date.UTC(" . date("Y,m,d,H,i,s", $contest_end) . "));\n";
?>

<script type="text/javascript">
<?php
echo "var startTime = new Date(Date.UTC(" . date("Y,m,d,H,i,s", $contest_start) . "));";
echo "var endTime = new Date(Date.UTC(" . date("Y,m,d,H,i,s", $contest_end) . "));";
echo "var currentTime = new Date(Date.UTC(" . date("Y,m,d,H,i,s", time()) . "));";
echo "var msg_pending = '" . _("Contest is not started yet.") . "';";
echo "var msg_contestends = '" . _("Contest ends in ") . "';";
echo "var msg_contestfinished = '" . _("Contest finished.") . "';";
echo "var msg_months = '" . _(" months ") . "';";
echo "var msg_days = '" . _(" days ") . "';";
echo "var msg_hours = '" . _(" hours ") . "';";
echo "var msg_mins = '" . _(" minutes ") . "';";
echo "var msg_secs = '" . _(" seconds ") . "';";
?>
</script>
<script type="text/javascript" src="<?= $script_prefix ?>/contest.js" > </script>

<div id="contest_box">
    <h1 style="margin: 0px"><?= $cinfo['title'] ?></h1>
    <div id = "contestClock"></div>
    <div id = "hintClock"></div>
</div>

<script type="text/javascript">
    $("#topbar").append($("#contest_box"));
</script>