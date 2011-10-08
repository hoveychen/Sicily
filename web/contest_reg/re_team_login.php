<?php
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

assertPost("tid");
assertPost("password");
$id=$_POST["tid"];
$password=$_POST["password"];
$cid=$contest["cid"];
$sql = "SELECT * FROM team WHERE cid='$cid' AND id='$id' AND password='$password'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0)
  error("Team id or password error");
$info = mysql_fetch_array ($result);
$tid=$info["tid"];
$_SESSION["tid"] = $tid;
if ($info["status"])
  $url="reg_info.php?id=$tid";
else
  $url="reg_edit.php?id=$tid";
?>
<html><head><title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="0;url=<?php print $url?>">
<link rel="stylesheet" href="style.css">
</head><body bgcolor="#0071BD" text="#FFFFFF">
&nbsp;&nbsp;<span style="color:#FFFFFF;">Login successful, your team id is <?php print $tid;?></font>
<br /><br />
