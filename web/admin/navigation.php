<?php

$navmode = "management";
$res_prefix = "../";
require_once("../navigation.php");
if (!is_admins() && !is_manager())
	error("No permissions");
?>