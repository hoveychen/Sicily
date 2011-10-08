<?
  require("./config.php");

  $id = $_POST["id"];
  $teamname = $_POST["teamname"];
  $position = $_POST["position"];
  $name1 = $_POST["name1"];
  $sex1 = $_POST["sex1"];
  $department1 = $_POST["department1"];
  $grade1 = $_POST["grade1"];
  $class1 = $_POST["class1"];
  $email  = $_POST["email"];
  $telephone = $_POST["telephone"];
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

  @mysql_connect($host, $user, $password) or die("Unable to connect to database.");
  mysql_select_db($database);

  $query = "UPDATE register2007 SET teamname='$teamname',";
  $query.= "position='$position',email='$email',telephone='$telephone',";
  $query.= "name1='$name1',sex1='$sex1',department1='$department1',";
  $query.= "grade1='$grade1',class1='$class1',";
  $query.= "name2='$name2',sex2='$sex2',department2='$department2',";
  $query.= "grade2='$grade2',class2='$class2',";
  $query.= "name3='$name3',sex3='$sex3',department3='$department3',";
  $query.= "grade3='$grade3',class3='$class3' ";
  $query.= "WHERE id='$id'";
  
  //echo $query;
  mysql_query($query);
  mysql_close();

  echo "<html><head><title>Update...</title>";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
  echo "<meta http-equiv=\"refresh\" content=\"1;url=reg_status.php?type=".$type;
  echo "\">";
  echo "<link rel=\"stylesheet\" href=\"style.css\">";
  echo "</head><body bgcolor=\"#0071BD\" text=\"#FFFFFF\">";
  echo "&nbsp;&nbsp;<font color=#FFFFFF>Updated Team ".$id."...</font><br>";
  echo "</body></html>";
?>

