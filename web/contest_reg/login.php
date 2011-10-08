<?php
require_once("include/config.php");
require_once("include/global.php");
if ($_POST['adminid'] != $admin_id || $_POST['password'] != $admin_password)
	error("Login name or password incorrect!");
else
{
	session_start();
	$_SESSION['admin'] = 1;
	redirect('reg_status.php');
}
?>