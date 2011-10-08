<?php

/**
 * Get the source code with in a status(backward compatibility)
 * @global type $app_config
 * @param type $sid
 * @param type $lang
 * @return type 
 */
function getSource($sid, $lang = null) {
    $status = new StatusTbl($sid);
    if ($status->Get() && !empty($status->detail['sourcecode'])) {
        return $status->detail['sourcecode'];
    }
    global $app_config;
    $path = sprintf($app_config["source_path"] . "/%03d/%06d", $sid / 1000, $sid);
    $exts = array('C' => '.c', 'C++' => '.cpp', 'Pascal' => '.pas', 'Java' => '.java');
    if ($lang == null) {
        foreach ($exts as $value) {
            if (file_exists($path . $value)) {
                $path.= $value;
                break;
            }
        }
    } else {
        $path .=$exts[$lang];
    }

    return file_get_contents($path);
}


/**
 * Submit Source code to server
 * @global type $login_uid
 * @global type $login_username
 * @global type $logged
 * @param type $arg
 * @return mixed if success, return sid. otherwise error message
 */
function submit_source($pid, $cid, $language, $source) {

    if (!is_logged())
        return "Invalid login";
    $pid = intval(trim($pid));
    $source = trim($source);
    if ($cid) {
        $problem = new ContestProblem($cid);
        if (!is_contest_accessible($cid)) {
            return "You can't access to the contest";
        }
        if (is_contest_ended($cid) && !is_contest_modifiable($cid)) {
            return "Contest is finished";
        }
    } else {
        $problem = new ProblemTbl();
    }
    if (!$problem->Get($pid)) {
        return "Invalid Problem ID!";
    }
    $acutal_cid = $problem->detail['cid'];

    if (!$cid && $acutal_cid) {
        // this is a problem automaticly added after the end of contest
        if (!is_contest_accessible($acutal_cid))
            return "You can't access to this problem";
        if (!is_contest_modifiable($acutal_cid)
                && !is_contest_ended($acutal_cid)) {
            return "Contest is not finished. Can't submit to normal problem";
        }
    }


    $sdata = array();
    $sdata["contest"] = $cid;

    if ($language < 1 || $language > 4) {
        return "Invalid language!";
    }
    $sdata['language'] = $language;
    $app_config = get_app_config();
    $codelength = strlen($source);
    if ($codelength > $app_config['max_sourcecode_length'])
        return "Size of your submittion exceeds limitation.";
    if ($codelength == 0)
        return "You can't submit an empty source code";

    $sdata['uid'] = get_uid();
    $sdata['time'] = date("Y-m-d H:i:s");
    if ($cid) {
        $sdata['pid'] = $problem->detail['pid'];
        $cpid = $pid;
        $pid = $sdata['pid'];
    } else {
        $sdata['pid'] = $pid;
    }
    $sdata['codelength'] = $codelength;
    $sdata['sourcecode'] = mysql_real_escape_string($source);
    $status = new StatusTbl();
    $status->detail = $sdata;
    $status_id = $status->Add();

    $user = new UserTbl(get_uid());
    $user->Get();
    $user->update['submissions'] = $user->detail['submissions'] + 1;
    $user->Update();

    $problem = new ProblemTbl($pid);
    $problem->Get();
    $problem->update['submissions'] = $problem->detail['submissions'] + 1;
    $problem->Update();
    if ($cid) {
        $con_status = new ContestStatus($cid);
        $con_status->detail = Array('cid' => $cid, 'sid' => $status_id, 'cpid' => $cpid);
        $con_status->Add();
    }

    $queue = new QueueTbl();
    $queue->detail['sid'] = $status_id;
    if ($cid) {
        $queue->detail['cid'] = $cid;
        $queue->detail['cpid'] = $cpid;
    }
    $queue->Add();

    return $status_id;
}


?>
