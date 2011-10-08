<?
require_once("db.php");
if ($contest == NULL) die("There is no contest available!");
$currenttime = time();
if($currenttime > $contest["endtime"]) die("Registration time expired!");
if($currenttime < $contest["starttime"]) die("You come early! Registration time is {$contest['startreg']} -- {$contest['endreg']}!");
?>