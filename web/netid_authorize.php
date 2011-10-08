<?
require_once("inc/global.inc.php");
require("inc/user.inc.php");
$logged or error("Please login first");
$ticket = safeget('ticket');
$unbind = tryget('unbind', 0);
if ($unbind)
	$query_suffix = "?unbind=1"; else
	$query_suffix = "";
$handle = fopen("http://cas.sysu.edu.cn/cas/validate?service=http://soj.me/netid_authorize.php$query_suffix&ticket=$ticket", "r");
feof($handle) and error("Can not connect to cas server");
$result = trim(fgets($handle));
if (strtolower($result) == "no") {
	error("Authorization fail.");
}
$netid = trim(fgets($handle));
fclose($handle);

$user = new UserTbl($login_uid);
$user->Get() or error("User ID invalid");
$real_netid = $user->detail["netid"];
if ($unbind) {
	empty($real_netid) and error("This account is not bound.");
	if ($real_netid != $netid)
		error("You are not the owner of this bound netid");
	$user->update["netid"] = "";
} else {
	empty($real_netid) or error("This account is bound with netid $real_netid");
	$netidTbl = new UserTbl();
	$netidTbl->GetByField("netid", $netid) and error("This netid is bound with another account");
	$user->update["netid"] = $netid;
}

$user->Update() or error("Fail to update Database");
@session_start();

if ($unbind) {
	$_SESSION["snetid"] = "";
	$_SESSION['msg'] = " Congratulations. You have succeeded to anti-authorize netid '$netid' with account '$login_username'";
} else {
	$_SESSION["snetid"] = $netid;
	$_SESSION['msg'] = " Congratulations. You have succeeded to authorize netid '$netid' with account '$login_username'";
}
?>
<script type="text/javascript ">
    parent.location.href="index.php";    
</script>