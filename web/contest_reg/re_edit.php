<?
require_once("include/db.php");
require_once("include/global.php");
session_start();
$fields = array("firstname", "lastname", "cnname", "title", "location", "country", "email", 
                "phone", "gender", "institution", "degree", "major", "majorcn", "grade", "class",
                "admitdate", "graduatedate", "birthday", "tshirt");
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
  global $teamid;
  global $avail;
  $teamid = $_GET['id'];
  if (!checkTeam($teamid))
    error ("You can't edit this team");
  if ($avail["teamen"])
    assertPost("teamen");
  if ($avail["teamcn"])
    assertPost("teamcn");
  $_SESSION["teamen"] = $_POST["teamen"];
  $team["enname"] = $_POST["teamen"];
  $_SESSION["teamcn"] = $_POST["teamcn"];
  $team["cnname"] = $_POST["teamcn"];
  //	$team["date"] = "NOW()";
  $type["date"] = 1;
  $team["cid"] = $contest["cid"];
  return true;
}

function getContestans()
{
  global $contestant;
  $ok = true;
  global $fields;
  global $teamid;
  global $avail;
  $sql = "SELECT uid FROM contestant WHERE tid=$teamid ORDER BY uid ASC";
  //	print $sql;
  $result = mysql_query($sql);
  for ($i = 1; $i <= 3; $i++)
  {
    $row = mysql_fetch_array($result);
    $contestant[$i]['uid'] = $row[0];
    foreach($fields as $val)
    {
      if (!$avail[$val])
        continue;
      if (!isset($_POST[$val.$i]) || (empty($_POST[$val.$i]) && $_POST[$val.$i] != '0'))
      {
        $ok = false;
        //				print($val.$i.$_POST[$val.$i]);
      }
      $contestant[$i][$val] = $_POST[$val.$i];
      //			$_SESSION[$val.$i] = $_POST[$val.$i];
    }
  }
  //	print_r($contestant);
  //	print $ok;
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

function update($table, $data, $type, $condition = NULL)
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
  $where = "";
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

if (getTeamInfo() && getContestans())
 {
   //	print_r($contestant);
   $where[0] = "tid = $teamid";
   update("team", $team, $type, $where);
   for ($i = 1; $i <= 3; $i++)
   {
     $contestant[$i]["tid"] = $teamid;
     //		print_r($contestant[$i]);
     $wheremember[0] = "uid={$contestant[$i]['uid']}";
     update("contestant", $contestant[$i], NULL, $wheremember);
     if (1 == $i)
       $leader["leader"] = $contestant[$i]['uid'];
   }
   update("team", $leader, NULL, $where);
   mysql_close();
   clearSession(); 
   //	print_r($contestant);
 }
 else
 {
   //	error("Fields must not be empty!");
 }
//$type = $_POST["type"];
/*$type = "zsucpc";
 $name1 = $_POST["name1"];
 $sex1 = $_POST["sex1"];
 $department1 = $_POST["department1"];
 $grade1 = $_POST["grade1"];
 $class1 = $_POST["class1"];
 $name2 = $_POST["name2"];
 $sex2 = $_POST["sex2"];
 $department2 = $_POST["department2"];
 $grade2 = $_POST["grade2"];
 $class2 = $_POST["class2"];
 $name3 = $_POST["name3"];
 $sex3 = $_POST["sex3"];
 $department3 = $_POST["department3"];
 $grade3 = $_POST["grade3"];
 $class3 = $_POST["class3"];
 $collage = $_POST["collage"];
 $school = $_POST["school"];
 $email = $_POST["email"];
 $department = $_POST["department"];
 $telephone = $_POST["telephone"];
 $teamname = chop($_POST["teamname"]);
 $coach = $_POST["coach"];
 $leader = $_POST["leader"];

 if (!isset($teamname)) error("Please complete all informations");
 $date = nowtime();

 setcookie("collage", $collage);
 setcookie("school", $school);
 setcookie("department", $department);
 setcookie("telephone", $telephone);
 setcookie("leader", $leader);
 setcookie("email", $email);
 setcookie("coach", $coach);

 switch ($type) {
 case "zsucpc":
 $query = "INSERT INTO register (type, name1, sex1, department1, grade1, class1, ";
 $query .= "name2, sex2, department2, grade2, class2, ";
 $query .= "name3, sex3, department3, grade3, class3, ";
 $query .= "telephone, teamname, email, date) VALUES (";
 $query .= "$type, '$name1', '$sex1', '$department1', '$grade1', '$class1', ";
 $query .= "'$name2', '$sex2', '$department2', '$grade2', '$class2', ";
 $query .= "'$name3', '$sex3', '$department3', '$grade3', '$class3', ";
 $query .= "'$telephone', '$teamname', '$email', '$date');";
 $url = "reg2.php";
 case "gdcpc":
 $query = "INSERT INTO register (type, name1, sex1, department1, grade1, class1, ";
 $query .= "name2, sex2, department2, grade2, class2, ";
 $query .= "name3, sex3, department3, grade3, class3, ";
 $query .= "telephone, teamname, email, collage, school, department, coach, leader, date) VALUES (";
 $query .= "'$type', '$name1', '$sex1', '$department1', '$grade1', '$class1', ";
 $query .= "'$name2', '$sex2', '$department2', '$grade2', '$class2', ";
 $query .= "'$name3', '$sex3', '$department3', '$grade3', '$class3', ";
 $query .= "'$telephone', '$teamname', '$email', '$collage', '$school', '$department', '$coach', '$leader', '$date');";
 $url = "reg_status.php";
 }

 @mysql_connect($host, $user, $password) or die("Unable to connect to database.");
 mysql_select_db($database);
 mysql_query($query);
 $id = mysql_insert_id();
 mysql_close();*/
$url = "reg_info.php?id=$teamid";
echo "<html><head><title>Register</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
echo "<meta http-equiv=\"refresh\" content=\"2;url=".$url."\">";
echo "<link rel=\"stylesheet\" href=\"style.css\">";
echo "</head><body bgcolor=\"#0071BD\" text=\"#FFFFFF\">";
echo "&nbsp;&nbsp;<font color=#FFFFFF>Update successful, your team id is $teamid</font><br><br>";
echo "<br>";
?>
