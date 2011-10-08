<?
require("include/db.php");
require_once("include/global.php");
session_start();
$type = $_GET["type"];
$id = $_GET["id"];

if (!checkTeam($id))
  error("Permission Denied!");
$sql = "SELECT * FROM team WHERE tid='$id'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0)
  error("This team doesn't exist");
$team = mysql_fetch_array ($result);
if ($team["status"] && !checkAdmin())
  error("The team is approved, contact administrator if you want to delete");
$sql = "DELETE FROM team WHERE tid='$id'";
mysql_query($sql);
$sql = "DELETE FROM contestant WHERE tid='$id'";
mysql_query($sql);
mysql_close();

echo "<html><head><title>Delete...</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
echo "<meta http-equiv=\"refresh\" content=\"1;url=reg_status.php?type=".$type;
if ($cid) echo "?cid=$cid";
echo "\">";
echo "<link rel=\"stylesheet\" href=\"style.css\">";
echo "</head><body bgcolor=\"#0071BD\" text=\"#FFFFFF\">";
echo "&nbsp;&nbsp;<font color=#FFFFFF>Deleted Team ".$id."...</font><br>";
echo "</body></html>";
?>

