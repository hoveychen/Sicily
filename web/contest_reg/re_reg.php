<?
require_once("include/db.php");
if ($_GET['mode'] != 'debug')
  require_once("include/checktime.php");
require_once("include/global.php");
session_start();

function assertPost($val)
{
  //	print "$val=".$_POST[;
  if (!isset($_POST[$val]) || empty($_POST[$val]))
    error("Fields must not be empty!");
}

function getTeamInfo()
{
  global $contest;
  global $team;
  global $type;
  global $avail;
  if ($avail["teamen"])
    assertPost("teamen");
  if ($avail["teamcn"])
    assertPost("teamcn");
  $_SESSION["teamen"] = $_POST["teamen"];
  $team["enname"] = $_POST["teamen"];
  $_SESSION["teamcn"] = $_POST["teamcn"];
  $team["cnname"] = $_POST["teamcn"];
  $team["date"] = "NOW()";
  $type["date"] = 1;
  $team["cid"] = $contest["cid"];
  return true;
}

function getContestans()
{
  global $contestant;
  $ok = true;
  global $fields;
  global $avail;
  for ($i = 1; $i <= 3; $i++)
  {
    foreach($fields as $val)
    {
      if (!$avail[$val])
        continue;
      if (!isset($_POST[$val.$i]) || (empty($_POST[$val.$i]) && $_POST[$val.$i] != '0'))
      {
        $ok = false;
        //print($val.$i.$_POST[$val.$i]);
      }
      $contestant[$i][$val] = $_POST[$val.$i];
      $_SESSION[$val.$i] = $_POST[$val.$i];
    }
  }
  return $ok;
}

function insert($table, $data, $type = NULL)
{
  if (empty($table)) return false;
  //	global $type;
  $field = "";
  $value = "";
  //	print_r($data);
  //	print_r($type);
  foreach($data as $key => $val)
  {
    if (empty($field))
    {
      $field = "(";
      $value = "(";
    }
    else
    {
      $field .= ",";
      $value .= ",";
    }
    $field .= $key;
    if (!$type[$key])
      $value .= "'$val'";
    else
      $value .= "$val";
  }
  if (empty($field))
  {
    return false;
  }
  $field .= ')';
  $value .= ')';
  $sql = "INSERT INTO $table $field VALUES $value";
  //	print $sql;
  if ($result = mysql_query($sql))
  {
    return mysql_insert_id();
  }
  else
  {
    if (mysql_affected_rows() == 0)
      error("Register Fail!");
  }
}

function update($table, $data, $condition = NULL)
{
  $field = "";
  foreach($data as $key => $val)
  {
    if (!empty($field))
    {
      $field .= ",";
    }
    $field .= "$key = '$val'";
  }
  //	print_r($condition);
  foreach($condition as $key => $val)
  {
    if (!empty($where))
      $where .= " AND ";
    $where .= $val;
  }
  if (empty($field))
  {
    return false;
  }
  $sql = "UPDATE $table SET $field";
  if (!empty($where))
    $sql .= " WHERE $where";
  return mysql_query($sql);
}

function clearSession()
{
  global $contestant;
  global $fields;
  for ($i = 1; $i <= 3; $i++)
  {
    foreach($fields as $val)
    {
      unset($_SESSION[$val.$i]);
    }
  }
  unset($_SESSION["teamcn"]);
  unset($_SESSION["teamen"]);
}

session_start();

if (getTeamInfo() && getContestans())
 {
   $teamid = insert("team", $team, $type);
   for ($i = 1; $i <= 3; $i++)
   {
     $contestant[$i]["tid"] = $teamid;
     $uid = insert("contestant", $contestant[$i]);
     if (1 == $i)
       $leader["leader"] = $uid;
   }
   $where[0] = "tid = $teamid";
   update("team", $leader, $where);
   mysql_close();
   clearSession(); 
   //	print_r($contestant);
 }
 else
 {
   error("Fields must not be empty!");
 }

$url = "reg_status.php#$teamid";
echo "<html><head><title>Register</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<meta http-equiv=\"refresh\" content=\"2;url=".$url."\">";
echo "<link rel=\"stylesheet\" href=\"style.css\">";
echo "</head><body bgcolor=\"#0071BD\" text=\"#FFFFFF\">";
echo "&nbsp;&nbsp;<font color=#FFFFFF>Register successful, your team id is $teamid</font><br><br>";
echo "<br>";
?>
