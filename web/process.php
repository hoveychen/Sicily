<?php

// used for static page-redirect process
include_once( "inc/global.inc.php" );

/*
 * 请求处理，如果没有相应的处理程序，发出错误信息
 */

$act = $_GET['act'];
if (function_exists($act)) {
	if (strtoupper($_SERVER['REQUEST_METHOD']) == "GET") {
		$arg = &$_GET;
	} else {
		$arg = &$_POST;
	}

	$act($arg);
} else {
	error('非法操作！');
}

exit;

function Register(&$arg) {
	global $app_config;
	$user = new UserTbl();

	$username = chop($arg["username"]);
	$pass1 = $arg["pass1"];
	$pass2 = $arg["pass2"];
	$email = $arg["email"];

	if (strlen($username) < 3
			|| preg_match("/[^a-z0-9]+/", $username)
			|| $username{0} < 'a'
			|| $username{0} > 'z')
		MsgAndBack('Your username should be made up of 3~20 lowercase letters or digits, and begin with a letter.');

	if ($user->GetByField("username", $username))
		MsgAndBack("The username has been registered, please select another one.");

	if ($pass1 != $pass2)
		MsgAndBack("Your passwords do not match.");

	if ($pass1 == "") {
		MsgAndBack("The passwords can't be null");
	}

	if (!$email) {
		MsgAndBack("Email can't be empty");
	}

	if (!is_email_valid($email)) {
		MsgAndBack("Email is not valid");
	}

	$md5pass = md5($pass1);

	$reg_time = date("Y.m.d G:i:s");

	$user->detail["username"] = $username;
	$user->detail["password"] = $md5pass;
	$user->detail["reg_time"] = date("Y.m.d G:i:s");
	$user->detail["list"] = null;
	$user->detail["email"] = $email;

	$id = $user->Add();

	MsgAndRedirect("index.php", "Register is done. Please login from right top corner.");
}

function ChangePwd(&$arg) {
	$pass = $arg["password"];
	$md5pass = md5($pass);
	$pass1 = $arg["pass1"];
	$pass2 = $arg["pass2"];

	if ($arg['password'] == NULL)
		MsgAndBack("The password can not be null!");
	require("inc/user.inc.php");
	global $login_uid;
	$retmsg = validate($arg);
	if ($retmsg != "yes") {
		error("Your password is not valid.");
		exit;
	}
	if ($pass1 != $pass2) {
		error("Your passwords do not match.");
		exit;
	}
	if ($pass1 == "") {
		$pass1 = $pass;
	}
	$md5pass = md5($pass1);

	$user = new UserTbl();
	$user->update['password'] = $md5pass;
	$user->Update($login_uid);
	MsgAndRedirect("profile_edit.php", "Password has been changed");
}

function BindNetid(&$arg) {
	$netid = strval($arg["netid"]);
	$uid = intval($arg['uid']);
	empty($netid) and error("Netid should not be empty.");
	$user = new UserTbl($uid);
	$user->Get() or error("User ID invalid");
	empty($user->detail['netid']) or error("This account is bound with netid {$user->detail['netid']}");
	$netidTbl = new UserTbl();
	$netidTbl->GetByField("netid", $netid) and error("This netid is bound with another account");

	$authcode = getAuthcode($uid);
	if ($authcode == null)
		error("Fail to generate authorization code");

	if ($user->detail['applynetid'] != $netid) {
		$user->update["applynetid"] = $netid;
		$user->Update();
	}
	// send mail
	$username = $user->detail["username"];
	$email = $user->detail["email"];
	require("inc/email_template.inc.php");
	SendMailByNetid($netid, "Authorzation email for Binding netid ", binding_mail($username, $email, $netid, $uid, $authcode));
	MsgAndRedirect("index.php", "Authorization email will be sent to mailbox of $netid for $username.");
}

function AuthBinding(&$arg) {
	$authcode = strval($arg["authcode"]);
	$uid = intval($arg["uid"]);
	if (!authorize($uid, $authcode))
		error("Authroization Error");

	$user = new UserTbl($uid);
	$user->Get() or error("User ID invalid");
	empty($user->detail["netid"]) or error("This account is bound with netid {$user->detail["netid"]}");
	empty($user->detail["applynetid"]) and error("No binding apply is detected.");

	$netid = $user->detail["applynetid"];
	$netidTbl = new UserTbl();
	$netidTbl->GetByField("netid", $netid) and error("This netid is bound with another account");

	$user->update["netid"] = $netid;
	$user->update["applynetid"] = "";
	$user->Update() or error("Fail to update Database");
	@session_start();
	$_SESSION["snetid"] = $netid;
	MsgAndRedirect("index.php", "You have succeeded to bind netid $netid with account {$user->detail['username']}");
}

function NewPassword(&$arg) {
	$uid = intval(safeget("uid"));
	$authcode = strval(safeget("authcode"));
	if (!authorize($uid, $authcode))
		error("Fail to authorize.");

	$newpwd = "";
	for ($i = 0; $i < 3; $i++) {
		$newpwd .= rand(0, 9);
	}
	$user = new UserTbl($uid);
	if (!$user->Get())
		error("Invalid User ID");
	$user->update["password"] = md5($newpwd);
	if (!$user->Update())
		error("Fail to update Database");
	MsgAndRedirect("index.php", "Your password has been reset as $newpwd");
}

function Logout(&$arg) {
	@session_start();
	$_SESSION = array();
	session_destroy();
	cleanCookieHash();
	MsgAndBack();
}

function ViewCE(&$arg) {
	global $app_config;
	$sid = safeget("sid");
	$path = sprintf($app_config["source_path"] . "/%03d/%06d", $sid / 1000, $sid);
	$extmap = array('C' => '.c', 'C++' => '.cpp', 'Pascal' => '.pas', 'Java' => '.java');
	$status = new StatusTbl($sid);
	if (!$status->Get())
		error("Fail to get status");
	$content = $status->detail['compilelog'];
	$content = ereg_replace("[A-Z0-9a-z/_]+\.(cpp|c|java|pas):", "", $content);
	$content = ereg_replace("[\n]", "<br>", $content);
	echo "<div style='text-align:left'>";
	echo $content;
	echo "</div>";
}

function ChangeLocale(&$arg) {
	$locale = safeget('locale');
	setcookie('locale', $locale, time() + 24 * 60 * 60 * 365, '/');
	set_language($locale);
	MsgAndBack("", false);
}

function RegisterCourse(&$arg) {
	require("inc/user.inc.php");
	global $logged;
	global $login_uid;
	if (!$logged) {
		error("Please login first");
	}
	$course_id = safepost('course_id');
	$course = new CourseTbl($course_id);
	$course->Get() or error("Course not found");
	$courseReg = new CourseRegTbl();
	$arr = array("course_id" => $course_id, "uid" => $login_uid);
	if ($courseReg->GetByFields($arr)) {
		MsgAndRedirect("course_detail.php?course_id=" . $course_id);
	}
	$courseReg->detail = $arr;
	$courseReg->Add();
	MsgAndRedirect("course_detail.php?course_id=" . $course_id);
}

?>
