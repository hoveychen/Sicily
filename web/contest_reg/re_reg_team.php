<?php
require_once("include/db.php");
if ($_GET['mode'] != 'debug')
  require_once("include/checktime.php");
require_once("include/global.php");
session_start();
function assertPost($val) {
  //	print "$val=".$_POST[;
  if (!isset($_POST[$val]) || empty($_POST[$val]))
    error("Fields must not be empty!");
}

function checkUsername($username) {
  $len = strlen ($username);
  for ($i = 0; $i < $len; $i++)
    if ($username[$i] < 'a' || $username[$i] > 'z')
      error ("Only english letters allowed");
}

assertPost("tid");
assertPost("password");
$id=strtolower($_POST["tid"]);
checkUsername ($id);
$password=$_POST["password"];
$cid=$contest["cid"];
$sql = "SELECT * FROM team WHERE cid='$cid' AND id='$id'";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0)
  error("User name already exists");
$sql = "INSERT team (id, password, cid, date) VALUES ('$id', '$password', '$cid', Now())";
$result = mysql_query($sql);
$tid = mysql_insert_id();
$_SESSION["tid"]=$tid;
print "team id is $tid <br />";
$sql = "INSERT contestant (tid) VALUES ('$tid')";
for ($i = 0; $i < 3; $i++)
  mysql_query($sql);
?>
<html><head><title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="0;url=reg_edit.php?id=<?php print $tid?>">
<link rel="stylesheet" href="style.css">
</head><body bgcolor="#0071BD" text="#FFFFFF">
&nbsp;&nbsp;<font color=#FFFFFF>Register successful, your team id is $tid</font>
<br /><br />
?>
