<?php
$navmode = "contest";
require("navigation.php");
$cid = safeget("cid");

$contest = new ContestsTbl($cid);
$contest->Get() or error("No such contest");

if (!is_contest_accessible($cid)) {
	if ($contest->detail['authtype'] == 'password' &&
			!isset($_SESSION["access$cid"])) {
		MsgAndRedirect("contest_password.php?cid=$cid");
	} else if (!is_contest_started($cid)) {
		error(_("This contest is not started yet."));
	} else {
		error(_("You can't access to the contest"));
	}
}

$problem = new ContestProblem($cid);
if (!$problem->GetByFields(array()))
	error("No problem found");

global $conn;
$rs = new RecordSet($conn);
$rs->Query("SELECT cpid, COUNT(*) AS num FROM contest_status left join status on contest_status.sid = status.sid " .
		"WHERE cid = $cid and status = 'Accepted' GROUP BY cpid ORDER BY cpid");
$ac_num = array();
while ($rs->MoveNext()) {
	$ac_num[$rs->Fields['cpid']] = intval($rs->Fields['num']);
}
$rs->Query("SELECT cpid, COUNT(*) AS num FROM contest_status " .
		"WHERE cid = $cid GROUP BY cpid ORDER BY cpid");
$sm_num = array();
while ($rs->MoveNext()) {
	$sm_num[$rs->Fields['cpid']] = intval($rs->Fields['num']);
}
$rs->free_result();
?>

<script type="text/javascript" src="js/cproblem_list.js"> </script>
<script type="text/javascript" src="js/jquery.dataTables.min.js" > </script> 
<link type="text/css" rel="stylesheet" href="css/data_table.css"/>

<div id="problem_list"> 
    <h1><?= $contest->detail['title'] ?></h1>

    <table class="display advtable_fix ui-widget-content">
        <thead class="tr_header"><tr><th><?= _("Status") ?></th><th><?= _("ID") ?></th><th class="place_left"><?= _("Title") ?></th><th><?= _("Accepted") ?></th><th><?= _("Submissions") ?></th></tr></thead>
		<?php
		do {
			echo "<tr>";
			$spj = empty($problem->detail['special_judge']) ? "" : _("[Speical judge]");
			if (!$logged) {
				echo "<td></td>";
			} else {
				$status = new ContestStatus($cid);
				if ($status->GetByFields(array('cpid' => $problem->detail['cpid'], 'status' => 'Accepted', 'uid' => $login_uid))) {
					echo "<td>Y</td>";
				} else if ($status->GetByFields(array('cpid' => $problem->detail['cpid'], 'uid' => $login_uid))) {
					echo "<td>-</td>";
				} else {
					echo "<td></td>";
				}
			}
			$cpid = $problem->detail['cpid'];
			if (!isset($ac_num[$cpid]) || !isset($sm_num[$cpid])) {
				$ac_num[$cpid] = $problem->detail['accepted'];
				$sm_num[$cpid] = $problem->detail['submissions'];
			}
			echo "<td>" . $cpid . "</td>";
			echo "<td><a class='black' href='show_problem.php?pid={$problem->detail['cpid']}&cid=$cid'>{$problem->detail['title']}$spj</a></td>";
			echo "<td>" . $ac_num[$cpid] . "</td>";
			echo "<td>" . $sm_num[$cpid] . "</td>";
			echo "</tr>";
		} while ($problem->MoreRows());
		?>
        <tbody></tbody>
    </table>
</div>


<?php
require("./footer.php");
?>
