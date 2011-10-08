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

function showMembers($member)
{
  for ($i = 0; $i < 3; $i++)
    showMember($member[$i]);
}

function showTeam($member)
{
  global $team;
  for ($i = 0; $i < 3; $i++)
    if (!$member[$i]['gender'])
      return;
  printf ("team_%03d,", $team['id']);
  for ($i = 0; $i < 6; $i++)
    print chr(rand(ord('a'), ord('z')));
  print ",{$team['enname']},{$team['cnname']},";
  for ($i = 0; $i < 3; $i++)
    print "{$member[$i]['cnname']} ";
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
if (!isset($_GET["debug"]))
 {
   header("Content-Disposition: attachment; filename=\"{$contest[name]}.csv\"");
   header("Content-Type: text/csv");
 }
if ($fullmode)
  echo "Team ID, English Team Name, Chinese Team Name, Title, FirstName, Last Name, Chinese Name, Gender, E-mail, Telephone, T-Shirt Size, University, Address, Country, Degree Pursued, Major, Major(CN), Grade, Class, Began Degree, Gradudation, Birthday";
else
  echo "Username, Password, English Team Name, Chinese Team Name, Members\n";
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
