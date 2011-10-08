<?php
require("./navigation.php");
?>

<?
$p = tryget('p', 1);

$perm = 'user';
if ($logged) {
	$user = new UserTbl($login_uid);
	if ($user->Get()) {
		$perm = $user->detail['perm'];
	}
}
$authname = array('free' => _("Public"), 'password' => _("Password"), 'internal' => _("Internal"), "bound" => _("Netid"));
$rs = new RecordSet($conn);
$query_str = "SELECT cid, title, starttime, during, authtype FROM contests WHERE course_id = 0 ";
$count_str = "SELECT count(*) FROM contests WHERE course_id = 0 ";

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY cid DESC";
$rs->dpQuery($query_str);

$now = time();
?>

<script type="text/javascript">
    function onAuthContest(data) {
        if (data.success) {
            window.location = "contest_detail.php?cid=" + data.cid;
        } else {
            alert(data.status);
        }
    }
    function onInputPassword(cid) {
        var pwd = prompt("<?= _("Please input password") ?>");
        if (pwd == null) {
            return false;
        }
        $.post("action.php?act=AuthContest", {'cid': cid, 'pwd': pwd}, onAuthContest, "json");
        return false;
    }
</script>

<h1>Current Contests</h1>
<table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2">
	<thead>
    <tr align="center" class="ui-widget-header">
        <td width="6%"><?= _("ID") ?></td>
        <td><?= _("Title") ?></td>
        <td width="30%"><?= _("Schedule") ?></td>
        <td width="10%"><?= _("Authorzation") ?></td>
    </tr>
	</thead>
	<?
	$i = 0;
	while ($rs->MoveNext()) {
		$i++;
		$id = $rs->Fields["cid"];
		$authtype = $rs->Fields['authtype'];
		$time = $rs->Fields["starttime"];
		$title = $rs->Fields["title"];
		$during = $rs->Fields["during"];
		if (!is_contest_visiable($id))
			continue;
		?>
		<tr>
			<td height=25 align="center"><? echo $id; ?></td>
			<td><a href="contest_detail.php?cid=<? echo $id; ?>" class="black"><? echo $title; ?></a></td>
	        <td align=center>
				<?
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
					echo "<font color=green>" . _("Finished at ") . date('Y-m-d', $end);
				?>
	        </td>
	        <td align="center"><?= $authname[$authtype] ?></td>
	    </tr>
	<? } ?>
</table>
<? echo $rs->Navigate() ?>



<?php
require("./footer.php");
?>
