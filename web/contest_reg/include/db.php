<?php
function parseTime($timestr)
{
	preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/", $timestr, $result);
	return mktime($result[4], $result[5], $result[6], $result[2], $result[3], $result[1]);
}

require_once("config.php");
mysql_connect($host, $user, $password) or die("Unable to connect to database.");
mysql_select_db($database);
mysql_query("SET NAMES UTF8");
$sql = "SELECT * FROM contest WHERE finishtime > NOW() ORDER BY startreg ASC";
$query = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($query))
{
	do
	{
		$contest = mysql_fetch_array($query, MYSQL_ASSOC);
		$contest["starttime"] = parseTime($contest["startreg"]);
		$contest["endtime"] = parseTime($contest["endreg"]);
	}while (mysql_num_rows($query) > 1 && $contest["endtime"] < time());
//	print_r($contest);
}
else
{
	$contest = NULL;
}
?>
