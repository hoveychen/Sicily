<?php
$navmode = "contest";
require("./navigation.php");
?>
<script type="text/javascript" src="js/FixedHeader.min.js"></script>
<script type="text/javascript"> 
    $(function(){
        // bug with non-chrome browser?
        // new FixedHeader( document.getElementById('standing') ); 
    });

</script>
<?
$cid = safeget('cid');
$contest = new ContestsTbl($cid);
if (!$contest->Get())
	error("No such contest");
$time = $contest->detail['starttime'];
$during = $contest->detail['during'];
$now = time();
$userdata = array();
$ranklist = new RanklistTbl($cid);
if ($ranklist->Get()) {
	do {
		$data['submissions'] = intval($ranklist->detail['submissions']);
		$data['ac_time'] = intval($ranklist->detail['ac_time']);
		$data['accepted'] = intval($ranklist->detail['accepted']);
		$id = $ranklist->detail['uid'];
		$pid = $ranklist->detail['pid'];
		if (!isset($userdata[$id]))
			$userdata[$id] = array();
		if (isset($userdata[$id]) || $data['accepted'])
			$userdata[$id][$pid] = $data;
	} while ($ranklist->MoreRows());
}
$timestamp = strtotime($time);
$start = $timestamp;
sscanf($during, "%d:%d:%d", $h, $m, $s);
$end = $timestamp + $h * 3600 + $m * 60 + $s;
if ($now < $start) {
	echo "<font color=red>" . _("Starts at ") . date('Y-m-d H:i:s', $timestamp) . '<br />';
	printf(_("%02d:%02d:%02d Left"), ($start - $now) / 3600, ($start - $now) % 3600 / 60, ($start - $now) % 60);
} else if ($now < $end) {
	echo "<font color=red>" . _("Running, ");
	printf(_("%02d:%02d:%02d Left"), ($end - $now) / 3600, ($end - $now) % 3600 / 60, ($end - $now) % 60);
}
else
	echo "<font color=green>" . _("Finished");
echo "</font>";
?>
<table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2" id="standing">
    <thead>
        <tr align="center" bgcolor="#0071BD" class="white ui-widget-header"> 
            <th width="8%" height="20"><b><?= _("No") ?></b></th>
            <th><b><?= _("User Name") ?></b></th><th></th><th></th>
			<?
			global $conn;
			$rs = new RecordSet($conn);
			$rs->Query("SELECT cpid FROM contest_problems WHERE cid='$cid'");
			$problemset = array();
			$problemstat = array();

			while ($rs->MoveNext()) {
				$cpid = $rs->Fields['cpid'];
				$problemset[] = $cpid;
				$pstat = array();
				$pstat['submission'] = 0;
				$pstat['firstac'] = 9999999;
				$pstat['solved'] = 0;
				$problemstat[$cpid] = $pstat;
				?>
				<th width="7%"><b>
				<?
				//echo "<a href=show_problem.php?cid=$cid&pid=".$rs->Fields['pid']." style=\"text-decoration:none;color:#FFFFFF\">".$rs->Fields['pid']."</a>";
				echo "<a href=show_problem.php?cid=$cid&pid=" . $rs->Fields['cpid'] . " class=\"white\">" . $rs->Fields['cpid'] . "</a>";
				?>
					</b></th>
					<? } ?>
            <th width="8%"><b><?= _("Solved") ?></b></th>
            <th width="8%"><b><?= _("Penalty") ?></b></th>
        </tr>
    </thead>
    <tbody>
<?
$rs = new RecordSet($conn);

$rs->Query("SELECT signature, nickname, user.uid AS uid, username, SUM(accepted) AS solved, SUM(((ranklist.submissions-1) * 20 + ac_time) * accepted) AS penalty FROM ranklist LEFT JOIN user on ranklist.uid=user.uid WHERE cid='$cid' GROUP BY uid ORDER BY solved DESC, penalty");
$i = 0;
while ($rs->MoveNext()) {
	$i++;
	echo "<tr>";
	$user_id = $rs->Fields["uid"];
	$username = $rs->Fields["username"];
	$penalty = $rs->Fields["penalty"];
	$solved = $rs->Fields["solved"];
	if (empty($rs->Fields["nickname"]))
		$displayname = $username; else
		$displayname = $rs->Fields["nickname"];
	?>
		<td height=20 align="center"><? echo $i; ?></td>
		<td>&nbsp;&nbsp;<a href="user.php?id=<? echo $user_id; ?>" class="black" title="<? echo $username; ?>"><? echo $displayname; ?></a></td>
		<?
		echo "<td>{$rs->Fields['signature']}</td>";
		echo "<td>{$rs->Fields['username']}</td>";
		foreach ($problemset as $pid) {
			?>
			<td align="center">
				<?
				$data = &$userdata[$user_id][$pid];
				$submissions = $data["submissions"];
				$ac_time = $data["ac_time"];
				$accepted = $data["accepted"];
				if ($submissions > 0) {
					$problemstat[$pid]['submission'] += $submissions;
					if ($accepted > 0 && $ac_time > 0) {
						$problemstat[$pid]['solved']++;
						if ($ac_time < $problemstat[$pid]['firstac']) {
							$problemstat[$pid]['firstac'] = $ac_time;
						}
						echo "<font color=blue>" . $ac_time;
						if ($submissions > 1)
							echo "(" . ($submissions - 1) . ")";
						echo "</font>";
					}else {
						echo "<font color=gray>(" . $submissions . ")</font>";
					}
				}
				?>
			</td>
		<? } ?>
		<td align="center"><? echo $solved; ?></td>
		<td align="center"><? echo $penalty ?></td>
	</tr>
	<?
}
// output stat data
echo "<tr><td></td><td>&nbsp;&nbsp;" . _("Summary") . "</td><td></td><td></td>";
$total_submission = 0;
$total_solved = 0;
foreach ($problemset as $pid) {
	$pstat = $problemstat[$pid];
	if ($pstat['solved'] == 0)
		$pstat['firstac'] = "--";
	$total_submission += $pstat['submission'];
	$total_solved += $pstat['solved'];
	echo "<td>" . $pstat['submission'] . "/" . $pstat['firstac'] . "/" . $pstat['solved'] . "</td>";
}
echo "<td>$total_submission/$total_solved</td><td></td></tr>";
?>
</tbody>
</table>

<?php
require("./footer.php");
?>

