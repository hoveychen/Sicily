<?php

/**
 * Get the number of account registered in the course
 * @global type $conn
 * @param type $course_id
 * @return type 
 */
function get_course_reg_num($course_id) {
    global $conn;
    $newrs = new RecordSet($conn);
    $newrs->Query("SELECT count(*) as num FROM course_reg WHERE course_id = $course_id");
    if ($newrs->MoveNext()) {
        return $newrs->Fields['num'];
    } else {
        return 0;
    }
}


/**
 * Get all the contests registered
 * @global type $login_uid
 * @global type $logged
 * @return type 
 */
function get_contests_reg() {
    global $login_uid;
    global $logged;
    if (!$logged)
        return FALSE;
    $reg = new ContestRegistrationTbl();
    if (!$reg->GetByField("uid", $login_uid))
        return array();
    $cidlist = array();
    do {
        $cidlist [] = intval($reg->detail['cid']);
    } while ($reg->MoreRows());
    return $cidlist;
}

/**
 * Get a pid to cpid mapping array of a particular contest
 * @param type $cid contest id
 * @return array pid to cpid mapping 
 */
function get_cpids($cid) {
    $cpid_hash = array();
    $contest_problem_tbl = new ContestProblemTbl($cid);
    if ($contest_problem_tbl->Get()) {
        do {
            $cpid_hash[$contest_problem_tbl->detail['pid']] =
                    $contest_problem_tbl->detail['cpid'];
        } while ($contest_problem_tbl->MoreRows());
    }
    return $cpid_hash;
}

/**
 * Get a pid list for a particular contest
 * @param type $cid contest id
 * @return array pid list
 */
function get_pids($cid) {
    $cpids = get_cpids($cid);
    return array_keys($cpids);
}


/**
 * Generate new pid
 * @global type $conn
 * @return int new unused pid
 */
function gen_new_pid() {
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("SELECT max(pid)+1 FROM problems");
    $rs->MoveNext();
    return (int) $rs->Fields[0];
}

/**
 * Generate new cpid for contest
 * @global type $conn
 * @param type $cid 
 * @return type new unused cpid
 */
function gen_new_cpid($cid) {
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("SELECT * FROM contest_problems WHERE cid='$cid'");
    $problems_n = $rs->num_rows() + 1000;
    return $problems_n;
}

/**
 * Registering problem into contest
 * @param type $pid
 * @param type $cid
 * @param type $cpid 
 */
function set_problem_cid($pid, $cid, $cpid) {
    $problem = new ProblemTbl($pid);
    $problem->Get() or error('Error setting problem cid');
    $problem->update['cid'] = $cid;
    $problem->Update();

    $contest_problem = new ContestProblem($cid);

    $contest_problem->detail = Array("cpid" => $cpid,
        "cid" => $cid,
        "pid" => $pid);
    $contest_problem->Add();
}



?>
