<?php

/*
 * Used for ajax process
 */

include_once( "inc/global.inc.php" );
require("inc/user.inc.php");

$act = $_GET['act'];
global $login_uid;

global $output;
$output = array("success" => 1);
$avail_functions = array(
    "PublishCode",
    "DisableCode",
    "RateProblem",
    "CheckProblem",
    "QueryRating",
    "QueryStatus",
    "Submit",
    "TestYes",
    "TestNo",
    "Login",
    "GetEmail",
    "ResetPwd",
    "AuthContest"
);
if (function_exists($act) && in_array($act, $avail_functions)) {
    if (strtoupper($_SERVER['REQUEST_METHOD']) == "GET") {
        $arg = &$_GET;
    } else {
        $arg = &$_POST;
    }
    $act($arg);
} else {
    Fail("No such function");
}
echo json_encode($output);

exit;

/*
 * Basic function of JSON
 */

function Fail($msg) {
    global $output;
    $output["success"] = 0;
    $output["status"] = $msg;
    echo json_encode($output);
    die();
}

function Output($param, $msg) {
    global $output;
    $output[$param] = $msg;
}

/*
 * Various kinds of actions with JSON
 */

function PublishCode(&$arg) {
    ShareCode($arg);
}

function DisableCode(&$arg) {
    ShareCode($arg, "0");
}

function ShareCode(&$arg, $enable = 1) {
    $sid = safefetch($arg, 'sid', "Fail");
    global $login_uid;
    if ($login_uid == -1)
        Fail("Must login first");
    $status = new StatusTbl($sid);
    if (!$status->Get())
        Fail("No such code");
    if ($login_uid != $status->detail["uid"])
        Fail("You are not the owner of the code");
    $status->update["public"] = $enable;
    $status->Update();
    $status->FreeResource();
}

function RateProblem(&$arg) {
    global $login_uid;
    global $logged;
    if (!$logged)
        Fail("Must login first");
    $pid = safefetch($arg, 'pid', 'Fail');
    $score = intval(safefetch($arg, 'score', 'Fail'));
    if ($score < 0 || $score > 5)
        Fail("Score out of range");

    $problem = new ProblemTbl($pid);
    if (!$problem->Get())
        Fail("Problem not found");
    $rating = new RatingTbl();
    $prikey = array('uid' => $login_uid, 'pid' => $pid);
    if ($rating->GetByFields($prikey)) {
        $cur_score = intval($rating->detail['rate']);
        $problem->update['rate_tot'] = intval($problem->detail['rate_tot']) - $cur_score + $score;
        if (!$problem->Update())
            Fail("Fail to update database");
        $rating->update['rate'] = $score;
        if (!$rating->UpdateByFields($prikey))
            Fail("Fail to update database");
    } else {
        $problem->update['rate_tot'] = intval($problem->detail['rate_tot']) + $score;
        $problem->update['rate_count'] = intval($problem->detail['rate_count']) + 1;
        if (!$problem->Update())
            Fail("Fail to update database");
        $rating->detail['uid'] = $login_uid;
        $rating->detail['pid'] = $pid;
        $rating->detail['rate'] = $score;
        $rating->Add();
    }
}

function QueryRating(&$arg) {
    $pid = safefetch($arg, "pid", "Fail");
    $problem = new ProblemTbl();
    if (!$problem->Get($pid))
        Fail("Problem not found");
    $sum = $problem->detail["rate_tot"];
    $count = $problem->detail["rate_count"];
    Output("sum", $sum);
    Output("count", $count);
}

function Login(&$arg) {
    if (validate($arg) == "yes")
        Output("status", "yes");
    else
        Fail("Invalid username or password");
}

function TestYes(&$arg) {
    Output("status", "yes");
}

function TestNo(&$arg) {
    Fail("It should be failed");
}

function CheckProblem(&$arg) {
    if (!isset($arg["pid"]))
        Fail("Invalid problem ID!");
    $pid = $arg["pid"];
    if (isset($arg["cid"]))
        $cid = $arg["cid"]; else
        $cid = "";
    if ($cid)
        $problem = new ContestProblem($cid);
    else
        $problem = new ProblemTbl();

    if (!$problem->Get($pid))
        Fail("Invalid problem ID!");
    Output("status", $problem->detail["title"]);
}

function ResetPwd(&$arg) {
    $username = safefetch($arg, "username", "Fail");
    if (empty($username))
        Fail("Username can't be null");
    $user = new UserTbl();
    if (!$user->GetByField("username", $username))
        Fail("Invalid username");
    $email = $user->detail["email"];
    $uid = intval($user->detail['uid']);
    $authcode = getAuthcode($uid);

    require("inc/email_template.inc.php");
    SendMail($email, "Reset Password", rstpwd_mail($username, $email, $uid, $authcode));
}

function GetEmail(&$arg) {
    $username = safefetch($arg, "username", "Fail");
    if (empty($username))
        Fail("Username can't be null");
    $user = new UserTbl();
    if (!$user->GetByField("username", $username))
        Fail("Invalid username");
    Output("email", $user->detail["email"]);
}

function QueryStatus(&$arg) {
    $sid = intval(safefetch($arg, 'sid', "Fail"));
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("SELECT sid, uid, status, run_time, run_memory, failcase FROM status WHERE sid = $sid");
    if (!$rs->MoveNext())
        Fail("Invalid run id");
    Output("status", $rs->Fields["status"]);
    Output("run_time", $rs->Fields["run_time"]);
    Output("run_memory", $rs->Fields["run_memory"]);
    Output("case_num", $rs->Fields["failcase"]);
    Output("uid", $rs->Fields["uid"]);
    Output("sid", $rs->Fields["sid"]);
    $rs->Query("select count(*) from queue");
    $rs->MoveNext();
    Output("queue_size", intval($rs->Fields[0]) + 1);
    $rs->free_result();
}

function AuthContest(&$arg) {
    $cid = safefetch($arg, 'cid', "Fail");
    $pwd = safefetch($arg, 'pwd', "Fail");
    $contest = new ContestsTbl($cid);
    if (!$contest->Get())
        Fail("No such contest");
    if ($contest->detail['authtype'] != 'password')
        Fail("No password is needed");
    if ($contest->detail['pwd'] != $pwd)
        Fail("Password Incorrect");
    Output("cid", $cid);
    $_SESSION["access$cid"] = 1;
}

function Submit(&$arg) {
    $pid = safefetch($arg, 'pid');
    $cid = safefetch($arg, 'cid');
    $lang = safefetch($arg, 'language');
    $source = isset($arg['source']) ? $arg['source'] : '';
    $ret = submit_source($pid, $cid, $lang, $source);

    if (is_numeric($ret)) {
        Output("status", "success");
        Output("sid", $ret);
    } else {
        Fail($ret);
    }
}

?>
