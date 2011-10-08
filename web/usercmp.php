<?php
require("./navigation.php");
?>

<?

function checkUser(&$user) {
	if (strlen($user) < 3 || eregi("[^a-z0-9]+", $user) ||
			$user{0} < 'a' || $user{0} > 'z')
		return false;
	else
		return true;
}

$user1 = trim($_GET["user1"]);
$user2 = trim($_GET["user2"]);
$user1 = strtolower($user1);
$user2 = strtolower($user2);
if (!checkUser($user1) || !checkUser($user2))
	error("Invalid user!", "index.php");

$rs = new RecordSet($conn);

$rs->Query("SELECT uid FROM user WHERE username='$user1'");
if ($rs->MoveNext())
	$id1 = $rs->Fields[0];
else
	error("Invalid user!", "index.php");

$rs->Query("SELECT uid FROM user WHERE username='$user2'");
if ($rs->MoveNext())
	$id2 = $rs->Fields[0];
else
	error("Invalid user!", "index.php");

$list1 = array();
$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$id1' AND status='Accepted'");
while ($rs->MoveNext()) {
	$list1[$rs->Fields["pid"]] = 0;
}

$list2 = array();
$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$id2' AND status='Accepted'");
while ($rs->MoveNext()) {
	$list2[$rs->Fields["pid"]] = 0;
}

$rs->Query("SELECT max(pid) FROM problems");
$rs->MoveNext();
$problem_max_n = $rs->Fields[0];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <td align="center">
		<table width="500" class="ui-widget tblcontainer ui-widget-content ui-corner-all"  border="0" cellspacing="2" cellpadding="2">
			<caption style="font-size: large"><?= $user1 ?> vs <?= $user2 ?></caption>
			<tr class="ui-widget-header">
				<td align="center">
					<? printf("Problems both <a href=\"user.php?id=%d\" class=\"black\">
                %s</a> and <a href=\"user.php?id=%d\" class=\"black\">%s</a> solved:", $id1, $user1, $id2, $user2) ?></td>
			</tr>
			<tr>
				<td>
					<?
					$flag = false;
					for ($i = 1000; $i <= $problem_max_n; $i++) {
						if (isset($list1[$i]) && isset($list2[$i])) {
							printf("<a href=\"show_problem.php?pid=%d\" class=\"black\">%d</a> &nbsp; ", $i, $i);
							$flag = true;
						}
					}
					if (!$flag) {
						printf("none.");
					}
					?>
					<br />
					<br />
				</td>
			</tr>

			<tr class="ui-widget-header">
				<td align="center">
<? printf("Problems only solved by <a href=\"user.php?id=%d\" class=\"black\">%s</a>:", $id1, $user1); ?> 
				</td>
			</tr>
			<tr>
				<td>
					<?
					$flag = false;
					for ($i = 1000; $i <= $problem_max_n; $i++) {
						if (isset($list1[$i]) && !isset($list2[$i])) {
							printf("<a href=\"show_problem.php?pid=%d\" class=\"black\">%d</a> &nbsp; ", $i, $i);
							$flag = true;
						}
					}
					if (!$flag) {
						printf("none.");
					}
					?>
					<br />
					<br />
				</td>
			</tr>

			<tr class="ui-widget-header">
				<td align="center">
<? printf("Problems only solved by <a href=\"user.php?id=%d\" class=\"black\">%s</a>:", $id2, $user2); ?> 
				</td>
			</tr>
			<tr>
				<td>
					<?
					$flag = false;
					for ($i = 1000; $i <= $problem_max_n; $i++) {
						if (!isset($list1[$i]) && isset($list2[$i])) {
							printf("<a href=\"show_problem.php?pid=%d\" class=\"black\">%d</a> &nbsp; ", $i, $i);
							$flag = true;
						}
					}
					if (!$flag) {
						printf("none.");
					}
					?>
					<br />
					<br />
				</td>
			</tr>
		</table>
</table>

<?php
require("./footer.php");
?>
