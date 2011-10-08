<?php
include_once("inc/global.inc.php");
require("inc/user.inc.php");
global $login_uid;
global $login_username;
global $logged;

$rs = new RecordSet($conn);

if ($logged) {
	$user = new UserTbl();
	$user->Get($login_uid);
	$list = array();
	$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid'");
	while ($rs->MoveNext()) {
		$list[$rs->Fields["pid"]] = 1;
	}
	$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid' AND status='Accepted'");
	while ($rs->MoveNext()) {
		$list[$rs->Fields["pid"]] = 2;
	}
}
?>
{ "aaData": [
<?
$first = true;
$rs->Query("SELECT pid, cid, title, accepted, submissions, special_judge, rate_tot, rate_count FROM problems WHERE avail = 1");
while ($rs->MoveNext()) {
	$cid = $rs->Fields["cid"];
	if ($cid && (!is_contest_accessible($cid) || !is_contest_ended($cid)))
		continue;

	$pid = $rs->Fields["pid"];
	$title = $rs->Fields["title"];
	$accepted = $rs->Fields["accepted"];
	$submissions = $rs->Fields["submissions"];
	$special_judge = $rs->Fields["special_judge"];
	if (isset($list[$pid])) {
		$status = $list[$pid];
	} else {
		$status = "";
	}

	$rate_tot = $rs->Fields["rate_tot"];
	$rate_count = $rs->Fields["rate_count"];
	if ($rate_count == 0) {
		$rate_score = "-";
	} else {
		$rate_score = round($rate_tot / $rate_count, 2);
	}
	if ($special_judge == '1')
		$title .= "[S]";
	if ($submissions == 0) {
		$ratio = 0;
	} else {
		$ratio = round($accepted * 100 / $submissions, 2);
	}
	if ($status == 1) {
		$status = "-";
	} else if ($status == 2) {
		$status = "Y";
	}
	if ($first)
		$first = false; else
		echo ",";
	echo "[";
	echo "\"$status\",";
	echo "\"$pid\",";
	echo "\"$title\",";
	echo "\"$accepted\",";
	echo "\"$submissions\",";

	echo "\"$ratio\",";
	echo "\"$rate_score\"";
	echo "]";
}
?>
]}