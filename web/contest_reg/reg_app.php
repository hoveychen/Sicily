<?
	require_once("include/db.php");
	require_once("include/global.php");
	session_start();
	if (!checkAdmin())
		error("Permission Denied!");
	$type = $_GET["type"];
	$id = $_GET["id"];

	$query = "UPDATE team SET status=1 WHERE tid='$id'";
	mysql_query($query);
	mysql_close();

	echo "<html><head><title>Approve...</title>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
	echo "<meta http-equiv=\"refresh\" content=\"1;url=reg_status.php#$id";
	if ($cid) echo "?cid=$cid";
	echo "\">";
	echo "<link rel=\"stylesheet\" href=\"style.css\">";
	echo "</head><body bgcolor=\"#0071BD\" text=\"#FFFFFF\">";
	echo "&nbsp;&nbsp;<font color=#FFFFFF>Approved Team ".$id."...</font><br>";
	echo "</body></html>";
?>
