<?php
require_once("include/db.php");
if ($_GET['mode'] != 'debug')
  require_once("include/checktime.php");
require_once("include/global.php");
session_start();
if (!checkAdmin())
  error ("You can't reset password");
function assertPost($val)
{
  //	print "$val=".$_POST[;
  if (!isset($_POST[$val]) || empty($_POST[$val]))
    error("Fields must not be empty!");
}

assertPost("password");
if (!isset($_GET["tid"]) || empty($_GET["tid"]))
  error ("Bad id");
$tid=$_GET["tid"];
$password=$_POST["password"];
$cid=$contest["cid"];
$sql = "SELECT * FROM team WHERE cid='$cid' AND id='$id'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0)
  error("No such team");
$sql = "UPDATE team SET password='$password' WHERE tid='$tid' AND cid='$cid'";
$result = mysql_query($sql);
?>
<html><head><title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="0;url=reg_status.php">
<link rel="stylesheet" href="style.css">
</head><body bgcolor="#0071BD" text="#FFFFFF">
&nbsp;&nbsp;<font color=#FFFFFF>Reset password successful</font>
<br /><br />
?>
