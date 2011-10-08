<?php
require("./navigation.php");
if (!isset($_GET['cid'])) {
	?>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
		cid:<input name="cid" type="text" />
	</form>
	<?php
	die();
}
$cid = $_GET['cid'];
global $conn;
$rs = new RecordSet($conn);
$rs->Query("SELECT status.sid, status.uid, cpid, run_memory, codelength, username, nickname FROM contest_status left join status on contest_status.sid = status.sid left join user on status.uid = user.uid WHERE status.status = 'Accepted' and contest_status.cid = $cid order by cpid asc, run_memory asc, codelength asc");


$submits = array();
while ($rs->MoveNext()) {
	$submits [] = $rs->Fields;
}


echo "<table class='tblContainer ui-widget-content ui-corner-all'><tr class='ui-widget-header'><td>cpid</td><td>sid1</td><td>username1</td><td>sid2</td><td>username2</td></tr>";

$count = 0;
for ($i = 0; $i < count($submits); ++$i) {
	for ($j = $i + 1; $j < count($submits) && is_similar($submits[$i], $submits[$j]); ++$j) {
		if (is_same($submits[$i], $submits[$j])) {
			output($submits[$i], $submits[$j]);
			++$count;
		}
	}
}

echo "</table>";
echo "Count = $count";

function output($sm1, $sm2) {
	echo "<tr class='tr_odd'>";
	echo "<td>{$sm1['cpid']}</td>";
	echo "<td><a href='../viewsource.php?sid={$sm1['sid']}'>{$sm1['sid']}</a></td>";
	echo "<td>{$sm1['username']}</td>";
	echo "<td><a href='../viewsource.php?sid={$sm2['sid']}'>{$sm2['sid']}</a></td>";
	echo "<td>{$sm2['username']}</td>";
	echo "</tr>";
}

function is_similar($sm1, $sm2) {
	return $sm1['cpid'] == $sm2['cpid'] &&
	$sm1['run_memory'] == $sm2['run_memory'] &&
	abs(intval($sm1['codelength']) - intval($sm2['codelength'])) < 5;
}

function is_same($sm1, $sm2) {
	if ($sm1['uid'] == $sm2['uid'])
		return false;
	$cnt1 = GetSource($sm1['sid']);
	$cnt2 = GetSource($sm2['sid']);
	$keywords = array("int", "return", "scanf",
		"cin", "double", "%", "memset", "sort", "new", "[");
	foreach ($keywords as $key) {
		if (substr_count($cnt1, $key) != substr_count($cnt2, $key))
			return false;
	}
	return true;
	return abs(strlen($cnt1) - strlen($cnt2)) <= 2;
}
?>
