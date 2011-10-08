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

function convertLen($string, $len)
{
  $ret = iconv("UTF-8", "GBK", $string);
  $l = strlen($ret);
  if ($l < $len)
    for (; $l < $len; $l++)
      $ret .= ' ';
  else
  {
    $ret = substr($ret, 0, $len);
    if ($ret[$len - 1] > 127)
      $ret[$len - 1] = ' ';
  }
  return $ret;
}

function showMembers($member)
{
  for ($i = 0; $i < 3; $i++)
    showMember($member[$i]);
}

function showTeam($member)
{
  global $team;
  //printf ("team_%03d,", $team['id']);
  // for ($i = 0; $i < 6; $i++)
  //   print chr(rand(ord('a'), ord('z')));
  print convertLen($team['enname'], 30).convertLen($team['cnname'], 28);
  print convertLen($member[0]['cnname'], 7).convertLen($member[1]['cnname'], 7).convertLen($member[2]['cnname'], 6);
  print "\n";
}

function showMember($member)
{
  global $team;
  $gender = $member['gender']?'Female':'Male';
  print "{$team['id']}, {$team['enname']}, {$team['cnname']}, {$member['title']}, {$member['firstname']}, {$member['lastname']},".
    " {$member['cnname']}, {$gender}, {$member['email']}, {$member['phone']}, {$member['tshirt']}, {$member['institution']},".
    "\"{$member['location']}\", {$member['country']}, {$member['degree']}, {$member['major']}, {$member['majorcn']},".
    " {$member['grade']}, {$member['class']}, {$member['admitdate']}, {$member['graduatedate']}, {$member['birthday']}\n";
  //    <td align="center" nowrap="nowrap"><? echo $member["firstname"].' '.$member["lastname"].'('.$member["cnname"].')'</td>
  //    <td align="center"><? echo $member["gender"]?"Female":"Male"</td>
  //	 align="center"><? echo $member["email"]</td>
  //		<td align="center" width="200" nowrap="nowrap"><? echo $member["major"]."<br />(".$member["majorcn"].")"</td>
  //		<td align="center"><? echo $member["grade"]</td>
  //		<td align="center"><? echo $member["class"]</td>
}
header("Content-Type: text/plain");
$result = mysql_query("SELECT * FROM team WHERE cid = {$contest['cid']} ORDER BY tid ASC");
$rank = 0;
for ($i = 0;; $i++, $rank++) {
  $team = mysql_fetch_array($result);
  if ($team == null) break;
  $team["id"] = $rank;
  $members = mysql_query("SELECT * FROM contestant WHERE tid = {$team['tid']}");
  for ($j = 0; $j < 3; $j++){
    $member[$j] = mysql_fetch_array($members, MYSQL_ASSOC);
  }
  if ($fullmode)
    showMembers($member);
  else
    showTeam($member);
 }?>
