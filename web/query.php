<?php

include_once("./inc/config.inc.php");
$link = mysql_connect($db_host_name, $db_user_name, $db_password);
mysql_select_db($db_database, $link);

mysql_query("SET NAMES 'utf8'", $link);
mysql_query("SET CHARACTER SET UTF8", $link);
mysql_query("SET CHARACTER_SET_RESULTS=UTF8", $link);

$output = array("success" => 0);
if (isset($_POST["sid"])) {
	$sid = $_POST["sid"];
} else if (isset($_GET["sid"])) {
	$sid = $_GET["sid"];
} else {
	$output["status"] = "arguments sid not exists";
}
if ($sid) {
	$ret = mysql_query("select sid, status, failcase, uid from status where sid = $sid", $link);
	$fields = mysql_fetch_array($ret);

	if (is_array($fields)) {
		$output["status"] = $fields["status"];
		$output["failcase"] = $fields["failcase"];
		$output["uid"] = $fields["uid"];
		$output["sid"] = $fields["sid"];
		$output["success"] = 1;
	}
}
echo json_encode($output);
?>