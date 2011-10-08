<?

include_once(dirname(__FILE__) . "/global.inc.php");
@session_start();




global $conn;
global $login_uid;
global $login_username;
global $login_netid;
global $logged;
$logged = false;
if (isset($_SESSION['suid'])) {
	// in session time, logged in
	$login_uid = $_SESSION['suid'];
	$login_username = $_SESSION['susername'];
	$login_netid = $_SESSION['snetid'];
	$logged = true;
} else if (isset($_COOKIE["uid"]) && isset($_COOKIE["hash"])) {
	// remembered
	$user = new UserTbl($_COOKIE["uid"]);
	if (!$user->Get()) {
		cleanCookieHash();
	}

	$hashstr = md5($user->detail["uid"] . $user->detail["password"]);
	if ($hashstr == $_COOKIE["hash"]) {
		$login_uid = $user->detail["uid"];
		$login_username = $user->detail['username'];
		$login_netid = $user->detail["netid"];
		$_SESSION = array();
		$_SESSION["suid"] = $login_uid;
		$_SESSION["susername"] = $login_username;
		$_SESSION["snetid"] = $login_netid;
		$_SESSION['sperm'] = $user->detail['perm'];
		$_SESSION['snickname'] = htmlspecialchars($user->detail['nickname']);
		$_SESSION['ssignature'] = $user->detail['signature'];
		$logged = true;
	} else {
		cleanCookieHash();
	}
}

function validate(&$arg) {
	global $conn;
	global $login_uid;
	global $login_username;
	global $login_netid;
	global $logged;
	$logged = false;
	if (isset($arg)
			&& isset($arg['username']) && !empty($arg['username'])
			&& isset($arg['password']) && !empty($arg['password'])) {
		$rs = new RecordSet($conn);
		// new login request
		$username = chop($arg["username"]);
		$password = substr(md5($arg['password']), 0, 20);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$rs->Query("SELECT uid, netid, perm, nickname, signature FROM user WHERE username='$username' AND password='$password'");
		if (!$rs->MoveNext())
			return "Incorrect username or password!";
		$rs->free_result();
		//login success
		$login_uid = $rs->Fields['uid'];
		$login_username = $username;
		$login_netid = $rs->Fields['netid'];

		if (isset($arg['lsession']) && $arg['lsession'] == "1") {
			$cookietime = time() + 24 * 60 * 60 * 365;
		} else {
			$cookietime = time() + 24 * 60;
		}

		setcookie("uid", "$login_uid", $cookietime, "/");
		$hashstr = md5($login_uid . $password);
		setcookie("hash", $hashstr, $cookietime, "/");

		$_SESSION = array();
		$_SESSION["suid"] = $login_uid;
		$_SESSION["susername"] = $login_username;
		$_SESSION["snetid"] = $login_netid;
		$_SESSION["sperm"] = $rs->Fields['perm'];
		$_SESSION['snickname'] = htmlspecialchars($rs->Fields['nickname']);
		$_SESSION['ssignature'] = $rs->Fields['signature'];
		$logged = true;
		return "yes";
	}
	return "guest";
}

?>
