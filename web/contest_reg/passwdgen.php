<?
require("include/db.php");
require_once("include/global.php");
session_start();
if (isset($_GET['cid']) && is_numeric($_GET['cid']))
  $contest['cid'] = (int)$_GET['cid'];
if ($contest == NULL) die("No contest available!");
$page = $_GET["page"];
$cid = $_GET["cid"];
if ($_GET["mode"] == "full")
  $fullmode = true;
else
  $fullmode = false;

function showTeam($team)
{
  printf ("team_%03d,", $team['id']);
  for ($i = 0; $i < 6; $i++)
    print chr(rand(ord('a'), ord('z')));
  print ",{$team['name']},{$team['member']}";
  print "\n";
}

if (!isset($_GET["debug"]))
 {
   header("Content-Disposition: attachment; filename=\"{$contest[name]}.csv\"");
   header("Content-Type: text/csv");
 }
echo "Username, Password, English Team Name, Members\n";
$result = mysql_query("SELECT * FROM temp");
$rank = 0;
for ($i = 0;; $i++, $rank++) {
  $team = mysql_fetch_array($result);
  if (!$team) break;
  $team['id'] = $i + 1;
  showTeam($team);
 }?>
