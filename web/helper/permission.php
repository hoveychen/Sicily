<?php



/**
 * Check whether the contest is modifiable
 * @global type $login_uid
 * @param type $cid
 * @return type 
 */
function is_contest_modifiable($cid) {
    if (is_admins())
        return true;
    if (is_manager()) {
        // This is a manager. 
        $contest = new ContestsTbl($cid);
        if (!$contest->Get())
            return false;
        if (!$contest->detail['avail'])
            return false;
        $perm = $contest->detail['perm'];
        if ($perm == "user" || $perm == 'manager' || $perm == 'temp') {
            global $login_uid;
            return ($contest->detail['owner'] == $login_uid);
        }
    }
    return false;
}



/**
 * Check whether the contest is accessible
 * @param type $cid
 * @return type 
 */
function is_contest_accessible($cid) {
    if (is_contest_modifiable($cid))
        return true;
    // This is a user. Auth to login
    $contest = new ContestsTbl($cid);
    if (!$contest->Get())
        return false;
    if (!$contest->detail['avail']) {
        return false;
    }
    if ($contest->detail['course_id']
		&& !is_course_registered($contest->detail['course_id'])) {
        return false;
    }
    if (is_temporary_user()) {
        return $contest->detail['perm'] == 'temp'
                && is_contest_registered($cid)
                && is_contest_auth($cid)
                && is_contest_started($cid);
    } else {
        return $contest->detail['perm'] == 'user'
                && is_contest_auth($cid)
                && is_contest_started($cid);
    }

    return false;
}

/**
 * Check whether the contest is registered
 * @param type $cid
 * @return type 
 */
function is_contest_registered($cid) {
    global $login_uid;
    global $logged;
    if (!$logged)
        return false;
    $reg = new ContestRegistrationTbl();
    return $reg->GetByFields(array('uid' => $login_uid, 'cid' => $cid));
}

/**
 * Check whether the contest is visiable
 * @param type $cid
 * @return type 
 */
function is_contest_visiable($cid) {
    if (is_contest_accessible($cid))
        return true;
    $contest = new ContestsTbl($cid);
    if (!$contest->Get())
        return false;

    if (!$contest->detail['avail']) {
        return false;
    }
    return $contest->detail['perm'] == 'user';
}

/**
 * Check whether the user is auth for this contest
 * @param type $cid
 * @return type 
 */
function is_contest_auth($cid) {
    // No need to check this auth 
    if (is_admins())
        return true;
    $contest = new ContestsTbl($cid);
    if (!$contest->Get())
        return false;
    global $login_uid;
    if (is_manager() && $contest->detail['owner'] == $login_uid)
        return true;
    $authtype = $contest->detail['authtype'];
    if ($authtype == 'free')
        return true;
    if ($authtype == 'password')
        return isset($_SESSION["access$cid"]);
    if ($authtype == 'internal')
        return is_internal_IP() || is_authorized();
    if ($authtype == 'bound')
        return is_authorized();
    return false;
}

/**
 * Check whether the course is modifiable
 * @global type $login_uid
 * @param type $course_id
 * @return type 
 */
function is_course_modifiable($course_id) {
    if (is_admins())
        return true;
    if (is_manager()) {
        // This is a manager. 
        $courseTbl = new CourseTbl($course_id);
        if (!$courseTbl->Get())
            return false;
        if (!$courseTbl->detail['avail'])
            return false;
        global $login_uid;
        return ($courseTbl->detail['owner'] == $login_uid);
    }
    return false;
}

/**
 * Check whether the account is bound with a netid
 * @global type $logged
 * @global type $login_netid
 * @return type 
 */
function is_authorized() {
    global $logged;
    global $login_netid;
    if (!$logged)
        return false;
    if (empty($login_netid))
        return false;
    return true;
}


function is_admins() {
    return has_perm("admin");
}

function is_user() {
    return has_perm("user");
}

function is_manager() {
    return has_perm("manager");
}

function is_temporary_user() {
    return has_perm("temp");
}

function is_logged() {
    global $logged;
    return $logged;
}

function has_perm($perm) {
    // protect stristr
    if ($perm == "")
        return false;

    if (!is_logged())
        return false;
    if (!isset($_SESSION['sperm']))
        return false;
    return stristr($_SESSION['sperm'], $perm) ? true : false;
}

function is_contest_started($cid) {
    if (!$cid)
        return true;
    $contest = new ContestsTbl();
    if ($contest->Get($cid) < 0)
        error("No such contest ID");
    if (!is_admins()) {
        $now = time();
        if ($now < strtotime($contest->detail['starttime']))
            return false;
    }
    return true;
}

function is_contest_ended($cid) {
    if (!$cid)
        return true;
    $contest = new ContestsTbl();
    if ($contest->Get($cid) < 0)
        error("No such contest ID");
    $now = time();
    $during = sscanf($contest->detail['during'], "%d:%d:%d", $h, $m, $s);
    $endtime = strtotime($contest->detail['starttime']);
    $endtime += $h * 3600 + $m * 60 + $s;
    if ($now < $endtime)
        return false;
    else
        return true;
}


/**
 * Check whether the course is registerd
 * @global type $logged
 * @global type $login_uid
 * @param type $course_id
 * @return type 
 */
function is_course_registered($course_id) {
    $course = new CourseTbl($course_id);
    $course->Get() or error("Course not found");
    global $logged;
    global $login_uid;
    if (!$logged)
        error("Please login first");
    $couresReg = new CourseRegTbl();
    $courseReg = new CourseRegTbl();
    $arr = array("course_id" => $course_id, "uid" => $login_uid);
    return $courseReg->GetByFields($arr);
}


function checkAuthcode($uid, $authcode) {
    $user = new UserTbl($uid);
    if (!$user->Get())
        return false;
    $authlast = intval($user->detail["authtime"]);
    $authnow = time();
    if ($authnow - $authlast >= 15 * 60) {
        return false;
    }
    $real_authcode = strval($user->detail["authcode"]);
    if ($authcode != $real_authcode) {
        return false;
    }
    return true;
}

function authorize($uid, $authcode) {
    $result = checkAuthcode($uid, $authcode);
    // clean authcode and authtime anyway
    $user = new UserTbl($uid);
    if ($user->Get()) {
        $user->update["authcode"] = "";
        $user->update["authtime"] = "";
        $user->Update();
        $user->FreeResource();
    }
    return $result;
}

function getAuthcode($uid) {
    $authcode = "";
    for ($i = 0; $i < 10; ++$i) {
        $authcode .= rand(0, 9);
    }

    $user = new UserTbl($uid);
    if (!$user->Get())
        return null;
    $user->update["authcode"] = $authcode;
    $user->update["authtime"] = time();
    if (!$user->Update())
        return null;
    return $authcode;
}


/**
 * check whether internal ip in sysu
 * Not really accurate, only cover dorms
 */
function is_internal_IP() {
    if (is_admins())
        return true;
    $ip_prefix = array("172.31.",
        "172.",
        "10.",
        "222.200.",
        "192.168.",
        "202.116.",
        "211.66",
        "219.222",
        "125.217",
        "127.0.0.1"
    );
    foreach ($ip_prefix as $ip) {
        if (!strncmp($_SERVER['REMOTE_ADDR'], $ip, strlen($ip)))
            return true;
    }
    return false;
}

/**
 * Get Login Username
 * @global type $logged
 * @global type $login_username
 * @return mixed If user are logged in, return it's username, otherwise FALSE
 */
function get_username() {
    if (is_logged()) {
        global $login_username;
        return $login_username;
    } else {
        return FALSE;
    }
}

/**
 * Get Login User ID
 * @global type $logged
 * @global type $login_uid
 * @return mixed If user are logged in, return it's uid, otherwise FALSE
 */
function get_uid() {
    if (is_logged()) {
        global $login_uid;
        return $login_uid;
    } else {
        return FALSE;
    }
}




?>
