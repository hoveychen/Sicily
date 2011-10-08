<?php

include_once( "../inc/global.inc.php" );
require("../inc/user.inc.php");
/*
 * 请求处理，如果没有相应的处理程序，发出错误信息
 */

if (!is_admins() && !is_manager())
    error("Admin Only Operation");
$act = safeget('act');

$avail_functions = array(
    'AddContestProblem',
    'AddProblem',
    'ContestEditProblem',
    'CreateContest',
    'EditContest',
    'DeleteContest',
    'IncContestProblem',
    'DecContestProblem',
    'EditContestProblem',
    'DeleteProblem',
    'DeleteContestProblem',
    'EditProblem',
    'ImportArchiveProblem',
    'ExportProblem',
    'ExportSource',
    'ExportContest',
    'RejudgeProblem',
    'ResetContest',
    'StartContest',
    'CreateCourse',
    'EditCourse',
    'DeleteCourse',
    'KickoutUser',
    'StdSubmit'
);

if (in_array($act, $avail_functions) && function_exists($act)) {
    if (strtoupper($_SERVER['REQUEST_METHOD']) == "GET") {
        $arg = &$_GET;
    } else {
        $arg = &$_POST;
    }

    $act($arg);
} else {
    error('Illegal Opeartions');
}

exit;


/*
 * 上传rar和zip测试数据
 */

function ArchiveUpload(&$arg, $problem_prefix, $handle) {
    // extract files
    $archive_file_name = $_FILES['arcfile']['tmp_name'];

    // unrar
    exec("rar x $archive_file_name $problem_prefix -o+");
    // unzip
    exec("unzip -o $archive_file_name -d $problem_prefix");

    // index the files
    for ($i = 0; $i < $arg['countnumber']; ++$i) {
        $infile = sprintf($arg['infile'], $i + $arg['startnumber']);
        $outfile = sprintf($arg['outfile'], $i + $arg['startnumber']);
        fwrite($handle, "$infile $outfile\n");
    }
}

function NormalUpload($problem_prefix, $handle) {
    for ($datanum = 0; $_FILES['input_data' . $datanum]['name']; ++$datanum) {
        $input_file_name = $problem_prefix . "/" . $_FILES['input_data' . $datanum]['name'];
        copy($_FILES['input_data' . $datanum]['tmp_name'], $input_file_name);
        $output_file_name = $problem_prefix . "/" . $_FILES['standard_output' . $datanum]['name'];
        copy($_FILES['standard_output' . $datanum]['tmp_name'], $output_file_name);

        // index the files
        fwrite($handle, $_FILES['input_data' . $datanum]['name'] . " " . $_FILES['standard_output' . $datanum]['name'] . "\n");
    }
}

function SpjUpload($problem_prefix, $pid) {
    $spj_filename = "$problem_prefix/$pid" . "_spj.cpp";
    copy($_FILES['spjfile']['tmp_name'], $spj_filename);

    $spj_exe = "$problem_prefix/spjudge";
    exec("g++ " . $spj_filename . " -o " . $spj_exe);

    exec("chmod 777 " . $spj_exe);
}

function FwUpload($problem_prefix, $pid) {
    $spj_filename = "$problem_prefix/framework.cpp";
    copy($_FILES['fwfile']['tmp_name'], $spj_filename);
}

function CreateProblem(&$arg) {
    $problem = new ProblemTbl();
    // Create data folder
    global $app_config;
    global $problem_prefix;
    $problem_prefix = $app_config['testdata_path'] . $arg['pid'];
    @mkdir($problem_prefix);

    // Open index file
    $handle = fopen($problem_prefix . "/.DIR", "w");

    // Archive upload
    if ($_FILES['arcfile']['name'] && $arg['arcupload'])
        ArchiveUpload($arg, $problem_prefix, $handle);

    // Normal data upload
    NormalUpload($problem_prefix, $handle);

    // Transform the style of eoln from window to unix
    // In too many case that dos style carriage return causes the testdata incorrect,
    // we try to avoid such problem. by bug.
    exec('fromdos ' . $problem_prefix . '/*.*');

    // Close index file
    fclose($handle);

    $fields = array();
    // special judge
    if ($arg['spj'] && $_FILES['spjfile']['name']) {
        $fields['special_judge'] = 1;
        SpjUpload($problem_prefix, $arg['pid']);
    } else {
        $fields['special_judge'] = 0;
    }

    // judge with framework
    if ($arg['usefw'] && $_FILES['fwfile']['name']) {
        $fields['has_framework'] = 1;
        FwUpload($problem_prefix, $arg['pid']);
    } else {
        $fields['has_framework'] = 0;
    }

    // copy fields from HTTP_VAR
    $storage_key = array("avail", "cid", "pid", "title", "time_limit", "memory_limit", "description", "input",
        "output", "sample_input", "sample_output", "hint", "author");
    foreach ($storage_key as $key) {
        $fields[$key] = $arg[$key];
        if (!get_magic_quotes_gpc()) {
            $fields[$key] = mysql_escape_string($arg[$key]);
        } else {
            $fields[$key] = $arg[$key];
        }
    }


    $problem->detail = $fields;
    $problem->Add();
}

function OnRestoreImageMatch($match) {
    global $problem_prefix;
    global $image_prefix;
    global $image_relative_prefix;
    @mkdir($image_prefix);
    $filename = $match[1];
    $realfilename = urldecode($filename);
    @copy("$problem_prefix/$realfilename", "$image_prefix/$realfilename");
    return "'$image_relative_prefix/$filename'";
}

function RestoreImage(&$content) {
    return preg_replace_callback(
                    "/\'%IMGPATH%([^\']*)\'/i", 'OnRestoreImageMatch', $content
    );
}

/**
 * Import set of problems(automatically generate new pid)
 * @param ZipArchive $zipfile
 * @return array list of pids
 */
function ImportContest(ZipArchive $zipfile) {
    $metadata = $zipfile->getFromName('metadata.json');
    if ($metadata === FALSE) {
        error('Error reading meta data');
    }
    $actual_pids = array();
    $cpids = json_decode($metadata);
    foreach ($cpids as $cpid) {
        $content = $zipfile->getFromName("$cpid.zip");
        if ($content === FALSE) {
            error("Error reading file [$cpid.zip]");
        }
        $pzip = new ZipArchive();
        $pfilename = tempnam(sys_get_temp_dir(), 'prob_') . '.zip';
        file_put_contents($pfilename, $content);
        if ($pzip->open($pfilename) !== TRUE) {
            error("Error opening archive file [$cpid.zip]");
        }
        $pid = gen_new_pid();
        ImportProblem($pzip, $pid);
        $actual_pids[] = $pid;
        $pzip->close();
    }
    return $actual_pids;
}

/**
 * Import problem from archive file
 * @param ZipArchive $zipfile
 * @param type $pid
 */
function ImportProblem(ZipArchive $zipfile, $pid) {
    global $app_config;
    global $problem_prefix;
    $problem_prefix = $app_config['testdata_path'] . $pid;
    @mkdir($problem_prefix);

    if (!$zipfile->extractTo($problem_prefix . '/')) {
        error("Error extracting to destination [$problem_prefix/]");
    }

    $metadata = $zipfile->getFromName('metadata.json');
    if ($metadata === FALSE) {
        error('error reading meta data');
    }
    $indexpath = $problem_prefix . '/.DIR';
    if (!file_exists($indexpath)) {
        error("Invalid problem archive file");
    }
    $data = json_decode($metadata, true);
    global $image_prefix;
    global $image_relative_prefix;
    $image_prefix = __DIR__ . "/../../UserFiles/$pid";
    $image_relative_prefix = "/UserFiles/$pid";
    $restore_key = array('description', 'input', 'output', 'hint');
    foreach ($restore_key as $value) {
        $data[$value] = RestoreImage($data[$value]);
    }
    $storage_key = array("title", "time_limit", "memory_limit", "description", "input",
        "output", "sample_input", "sample_output", "hint", "special_judge", "has_framework", "author");
    $storage = array();
    foreach ($storage_key as $key) {
        $storage[$key] = mysql_escape_string($data[$key]);
    }
    $storage['pid'] = $pid;

    $problem = new ProblemTbl($pid);
    if ($problem->Get()) {
        $problem->update = $storage;
        $problem->Update();
    } else {
        $problem->detail = $storage;
        $problem->Add();
    }
}

/**
 * Import multiple types of archive files.
 * Automatically detect types
 * Automatically generate new pids
 * Support 2 types: contest and problem
 * @param type $filename 
 * @return array list of pids
 */
function ImportProblems($filename) {
    $zip = new ZipArchive();
    if ($zip->open($filename) !== TRUE) {
        error("Fail to open archive file");
    }
    $pids = array();
    if ($zip->locateName('.DIR') !== FALSE) {
        $pid = gen_new_pid();
        ImportProblem($zip, $pid);
        $pids = array($pid);
    } else if ($zip->locateName('.SET') !== FALSE) {
        $pids = ImportContest($zip);
    } else {
        error("Not a problem archive file for sicily");
    }
    $zip->close();
    return $pids;
}

function ImportArchiveProblem(&$arg) {
    $cid = safefetch($arg, 'cid');
    $filename = $_FILES['archive']['tmp_name'];
    if (empty($filename)) {
        error('No file is submitted');
    }
    $pids = ImportProblems($filename);
    foreach ($pids as $pid) {
        $cpid = gen_new_cpid($cid);
        set_problem_cid($pid, $cid, $cpid);
    }
    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function AddProblem(&$arg, $redirect=true) {
    global $app_config;
    $problem = new ProblemTbl();
    if ($problem->Get($arg['pid']))
        error("The ID has been used, please select another one.");

    if ($arg['editmode'] == "import") {
        $zip = new ZipArchive();
        if ($zip->open($_FILES["importfile"]["tmp_name"]) !== TRUE) {
            error("Fail to open archive file");
        }
        ImportProblem($zip, $arg['pid']);
    } else if ($arg['editmode'] == "raw") {
        CreateProblem($arg);
    } else if ($arg['editmode'] == "refer") {
        error("Function not available");
    }

    if ($redirect)
        MsgAndRedirect("problem_create.php");
}

function DeleteProblem(&$arg, $redirect = true) {
    $problem = new ProblemTbl($arg['pid']);
    if (!$problem->Get())
        error("No problem found");

    $problem->update['avail'] = 0;
    $problem->Update();
    if ($redirect)
        MsgAndRedirect("problem_list.php");
}

function DeleteContestProblem(&$arg) {
    $cid = $arg['cid'];
    if ($cid == 0) {
        DeleteProblem($arg);
        exit;
    }
    $problem = new ContestProblem($cid);
    $cpid = $arg['pid'];
    if (!$problem->Get($cpid))
        error("No problem found");
    $arg['pid'] = $problem->detail['pid'];

    DeleteProblem($arg, false);
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("DELETE FROM contest_problems WHERE cid = $cid AND cpid = $cpid");
    $rs->affected_rows();
    SortContestProblems($cid);
    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function AddContestProblem(&$arg) {
    $cid = $arg['cid'];
    if ($cid == 0) {
        AddProblem($arg);
        exit;
    }
    $cpid = $arg['pid'];
    $pid = gen_new_pid();
    $arg['pid'] = $pid;
    AddProblem($arg, false);
    set_problem_cid($pid, $cid, $cpid);
    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function ContestEditProblem(&$arg) {
    EditContestProblem($arg);
}

function CreateContest(&$arg) {
    $contest = new ContestsTbl();
    $contest->detail = $arg;
    global $login_uid;
    $contest->detail['owner'] = $login_uid;
    if (trim($arg['title']) == "") {
        error("Contest title can't be null");
    }
    $course_id = tryfetch($arg, 'course_id', "");

    if ($contest->Add()) {
        if ($course_id) {
            MsgAndRedirect("course_detail.php?course_id=$course_id");
        } else {
            MsgAndRedirect("contests.php");
        }
    } else {
        MsgAndBack("Can't create contest");
    }
}

function EditContest(&$arg) {
    $cid = $arg['cid'];
    $contest = new ContestsTbl($cid);
    if (!$contest->Get()) {
        error("No such contest.");
    }
    if (!is_contest_modifiable($arg['cid'])) {
        error("No permission");
    }
    if (trim($arg['title']) == "") {
        error("Contest title can't be null");
    }
    $course_id = $contest->detail['course_id'];

    $contest->update = $arg;
    $contest->Update($arg['cid']);
    if ($course_id) {
        MsgAndRedirect("contest_detail.php?cid=$cid");
    } else {
        MsgAndRedirect("contests.php");
    }
}

function DeleteContest(&$arg) {
    $contest = new ContestsTbl($arg['cid']);
    if (!$contest->Get()) {
        error("No such contest.");
    }
    if (!is_contest_modifiable($arg['cid'])) {
        error("No permission");
    }
    $course_id = $contest->detail['course_id'];

    $contest->update['avail'] = 0;
    $contest->Update();
    if ($course_id) {
        MsgAndRedirect("course_detail.php?course_id=$course_id");
    } else {
        MsgAndRedirect("contests.php");
    }
}

function CreateCourse(&$arg) {
    $course = new CourseTbl();
    $course->detail = $_POST;
    global $login_uid;
    $course->detail['owner'] = $login_uid;
    if (trim($course->detail['name']) == "") {
        error("Course name can't be empty");
    }
    if ($course->Add()) {
        MsgAndRedirect("courses.php", "Course has been created.");
    } else {
        MsgAndBack("Can't create course");
    }
}

function EditCourse(&$arg) {

    $course = new CourseTbl($_POST['course_id']);

    if (!$course->Get()) {
        error("No such course.");
    }
    if (!is_course_modifiable($_POST['course_id']))
        error("No permission");
    if (trim($_POST['name']) == "") {
        error("Course name can't be empty");
    }
    $_POST['require_bound'] = $_POST['require_bound'] == 'on' ? 1: 0;
    $_POST['require_cinfo'] = $_POST['require_cinfo'] == 'on' ? 1: 0;
    $course->update = $_POST;
    $course->Update();
    MsgAndRedirect("course_detail.php?course_id={$_POST['course_id']}");
}

function DeleteCourse(&$arg) {
    $course = new CourseTbl($arg['course_id']);
    if (!$course->Get()) {
        error("No such course.");
    }
    if (!is_course_modifiable($arg['course_id'])) {
        error("No permission");
    }
    $course->update['avail'] = 0;
    $course->Update();
    MsgAndRedirect("courses.php");
}

function EditContestProblem(&$arg) {
    global $app_config;
    $cid = $arg['cid'];
    if ($cid == "0") {
        EditProblem($arg);
        exit;
    }
    $problem = new ContestProblem($cid);
    $cpid = $arg['pid'];
    if (!$problem->Get($cpid))
        error("No such problem.");
    $arg['pid'] = $problem->detail['pid'];
    EditProblem($arg, false);

    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function SortContestProblems($cid) {
    $cpidMap = GetContestProblemsMap($cid);
    UpdateContestProblemMap($cid, $cpidMap);
}

function GetContestProblemsMap($cid) {
    $contestProblemTbl = new ContestProblemTbl($cid);
    $cpidMap = array();
    if ($contestProblemTbl->Get()) {
        do {
            $cpidMap[$contestProblemTbl->detail['cpid']] = $contestProblemTbl->detail['pid'];
        } while ($contestProblemTbl->MoreRows());
    }
    return $cpidMap;
}

function UpdateContestProblemMap($cid, $cpidMap) {
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("DELETE FROM contest_problems WHERE cid = $cid");
    $rs->affected_rows();

    $indexs = array_keys($cpidMap);
    sort($indexs);
    $count = 1000;
    foreach ($indexs as $index) {
        $cpTbl = new ContestProblemTbl();
        $cpTbl->detail['cid'] = $cid;
        $cpTbl->detail['pid'] = $cpidMap[$index];
        $cpTbl->detail['cpid'] = $count;
        $cpTbl->Add();
        ++$count;
    }
}

function IncContestProblem(&$arg) {
    $cid = mysql_escape_string($arg['cid']);
    $cpid = mysql_escape_string($arg['pid']);

    $cpidMap = GetContestProblemsMap($cid);
    $nextcpid = strval(intval($cpid) + 1);
    if (!array_key_exists($cpid, $cpidMap)) {
        error("No contest problem found");
    }
    if (array_key_exists($nextcpid, $cpidMap)) {
        $tmp = $cpidMap[$nextcpid];
        $cpidMap[$nextcpid] = $cpidMap[$cpid];
        $cpidMap[$cpid] = $tmp;
    } else {
        error("Can't increase");
    }
    UpdateContestProblemMap($cid, $cpidMap);

    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function DecContestProblem(&$arg) {
    $cid = mysql_escape_string($arg['cid']);
    $cpid = mysql_escape_string($arg['pid']);

    $cpidMap = GetContestProblemsMap($cid);
    $nextcpid = strval(intval($cpid) - 1);
    if (!array_key_exists($cpid, $cpidMap)) {
        error("No contest problem found");
    }
    if (array_key_exists($nextcpid, $cpidMap)) {
        $tmp = $cpidMap[$nextcpid];
        $cpidMap[$nextcpid] = $cpidMap[$cpid];
        $cpidMap[$cpid] = $tmp;
    } else {
        error("Can't increase");
    }
    UpdateContestProblemMap($cid, $cpidMap);

    MsgAndRedirect("contest_detail.php?cid=$cid");
}

function EditProblem(&$arg, $redirect=true) {
    global $app_config;
    $problem = new ProblemTbl();
    if (!$problem->Get($arg['pid']))
        error("No such problem");
    if (trim($arg['title']) == "")
        error("Title can't be null");

    // Create data folder(it should make no sence)
    $problem_prefix = $app_config['testdata_path'] . $arg['pid'];
    @mkdir($problem_prefix);

    if ($arg["data_mode"] == '1' || $arg["data_mode"] == '2') {

        if ($arg["data_mode"] == "1") {
            // rewrite index only
            $handle = fopen($problem_prefix . "/.DIR", "w");
        } else {
            // append to index
            $handle = fopen($problem_prefix . "/.DIR", "a");
        }

        // Archive upload
        if ($_FILES['arcfile']['name'] && $arg['arcupload'])
            ArchiveUpload($arg, $problem_prefix, $handle);

        // Normal data upload
        NormalUpload($problem_prefix, $handle);

        // Transform the style of eoln from window to unix
        // In too many case that dos style carriage return causes the testdata incorrect,
        // we try to avoid such problem. by bug.
        exec('fromdos ' . $problem_prefix . '/*.*');

        // Close index file
        fclose($handle);
    }

    $fields = array();
    // special judge
    if ($arg['spj'] && $_FILES['spjfile']['name']) {
        $fields['special_judge'] = 1;
        SpjUpload($problem_prefix, $arg['pid']);
    } else if (!$arg['spj']) {
        $fields['special_judge'] = 0;
    }

    // judge with framework
    if ($arg['usefw'] && $_FILES['fwfile']['name']) {
        $fields['has_framework'] = 1;
        FwUpload($problem_prefix, $arg['pid']);
    } else if (!$arg['usefw']) {
        $fields['has_framework'] = 0;
    }

    // copy fields from HTTP_VAR
    $storage_key = array("avail", "cid", "pid", "title", "time_limit", "memory_limit", "description", "input",
        "output", "sample_input", "sample_output", "hint", "author");
    foreach ($storage_key as $key) {
        $fields[$key] = $arg[$key];
        if (!get_magic_quotes_gpc()) {
            $fields[$key] = mysql_escape_string($arg[$key]);
        } else {
            $fields[$key] = $arg[$key];
        }
    }

    $problem->update = $fields;
    $problem->Update($arg['pid']);
    if ($redirect)
        MsgAndRedirect("problem_edit.php?pid=" . $arg['pid']);
}

function Rejudge(&$arg) {
    $cid = $arg['cid'];
    unset($arg['act']);
    if ($cid) {
        $queue = new ContestQueue($cid);
        $queue->detail = $arg;
        $queue->Add();
    } else {
        $queue = new QueueTbl();
        $queue->detail = $arg;
        $queue->Add();
    }
    MsgAndRedirect("status.php?cid=" . $cid);
}

function RejudgeAll(&$arg) {
    $pid = $arg['pid'];
    //	if ($cid == 0 || $pid == 0) break;
    unset($arg['act']);
    global $conn;
    $rs = new RecordSet($conn);
    $rs->nPageSize = 10000;
    $rs->SetPage(0);
    $rs->dpQuery("SELECT sid, uid, pid, status FROM status WHERE pid='$pid' ORDER BY sid");
    while ($rs->MoveNext()) {
        echo $rs->Fields["sid"] . "\t" . $rs->Fields["pid"] . "\n";
        $queue = new QueueTbl();
        $queue->detail["sid"] = $rs->Fields["sid"];
        $queue->Add();
    }
}

function RejudgeProblem(&$arg) {
    global $conn;
    $rs = new RecordSet($conn);
    $pid = safefetch($arg, 'pid');
    $cid = tryfetch($arg, 'cid', 0);
    $rejudge_all = tryfetch($arg, 'rjall', true);
    $cpid = $pid;
    if ($cid) {
        $prob = new ContestProblem($cid, $cpid);
        if (!$prob->Get())
            error("No such problem in the contest");
        $pid = $prob->detail['pid'];
    }
    $prob = new ProblemTbl($pid);
    if (!$prob->Get())
        error("No such problem.");

    if (!$rejudge_all) {
        $rejudge_cond = "and status!='Accepted'";
    } else {
        $prob->update['accepted'] = 0;
        $prob->Update();
        $rejudge_cond = "";
        if ($cid) {
            $rs->Query("UPDATE ranklist SET accepted = 0, submissions = 0 WHERE pid='$cpid' AND cid='$cid'");
        }
    }
    $rs->Query("INSERT INTO queue (sid,cid,cpid) select sid,'$cid','$cpid' from status where pid='$pid' $rejudge_cond");
    $rs->insert_id();
    $count_inserted = $rs->affected_rows();
    if ($cid)
        MsgAndRedirect("../status.php?cid=$cid&cpid=$cpid", "Total $count_inserted submissions will be rejudged.");
    else
        MsgAndRedirect("../status.php?pid=$cpid", "Total $count_inserted submissions will be rejudged.");
}

function ResetPassword(&$arg) {
    $uid = $arg['uid'];

    if (!$uid) {
        MsgAndBack("No such user");
    }

    $user = new UserTbl();
    if (!$user->Get($uid)) {
        MsgAndBack("No such user");
    }

    $newpass = rand();
    $md5pass = md5($newpass);

    $user->update['password'] = $md5pass;
    if ($user->Update($uid)) {
        echo "Reset successful, the new password is " . $newpass;
    } else {
        MsgAndBack("fail to reset password");
    }
}

function CopyProblem(&$arg) {
    global $app_config;
    // Write data to file
    $newcid = 3;
    $problem_prefix = sprintf("%s/c%02d/%d", $app_config['contest_testdata_path'], $arg['cid'], $arg['pid']);
    $new_prefix = sprintf("%s/c%02d/%d", $app_config['contest_testdata_path'], $newcid, $arg['pid']);
    _makedir($new_prefix, 0777);
    exec("copy " . $problem_prefix . "/* " . $new_prefix . "/");

    $problem = new ContestProblem($arg['cid']);
    $problem->Get($arg['pid']);
    die();
    unset($arg['add_problem']);
    unset($arg['input_data']);
    unset($arg['standard_output']);
    $problem->detail = $arg;
    $problem->Add();
    MsgAndRedirect("problem_create.php");
}

function OnExtractImageMatch($match) {
    global $img_list;
    $path = trim(trim($match[1], '"'), "'");
    $path = str_replace(array("http:/", "/soj.me", "/sicily", "../"), "", $path);
    $path = trim($path, "/");
    $img_list[] = realpath(dirname(__FILE__) . "/../../" . urldecode($path));
    $filename = substr(strrchr($path, "/"), 1);
    return "<img src='%IMGPATH%$filename'/>";
}

function ExtractImage(&$content) {
    return preg_replace_callback(
                    "~<img[^>]*src=\"?'?([^\\s\"']*)\"?'?[^>]*/?>~i", 'OnExtractImageMatch', $content
    );
}

function ExportSource(&$arg) {
    $cid = safefetch($arg, 'cid');
    $contest = new ContestsTbl($cid);
    $contest->Get() or error('Invalid Contest ID');
    if (!is_contest_modifiable($cid)) {
        error("Permission Denied");
    }
    $contest = $contest->detail;
    $zip = new ZipArchive();
    $filename = tempnam(sys_get_temp_dir(), "cnt") . '.zip';
    if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
        error("cannot Create file <$filename>");
    }


    $cpid_hash = get_cpids($cid);
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("SELECT * FROM status WHERE contest = '$cid'");
    while ($rs->MoveNext()) {
        $ret = $rs->Fields;
        $sid = $ret['sid'];
        $pid = $ret['pid'];
        if (array_key_exists($pid, $cpid_hash)) {
            $pid = $cpid_hash[$pid];
        }
        $uid = $ret['uid'];
        $user = new UserTbl($uid);
        $user->Get();
        $user = $user->detail;
        $src_code = $ret['sourcecode'];
        $status = $ret['status'];
        $lang = $ret['language'];
        $ext_hash = array('C++' => '.cpp', 'C' => '.c', 'Pascal' => '.pas', 'Java' => '.java');
        $ext = $ext_hash[$lang];

        // multiple catalog
        // by pid
        if (!$zip->addFromString('pid/' . $pid . '/' . $sid . $ext, $src_code)) {
            error("Fail to write file for submit [$sid]\n");
        }
        // by uid
        if (!$zip->addFromString('uid/' . $user['username'] . '/' . $pid . '_' . $sid . $ext, $src_code)) {
            error("Fail to write file for submit [$sid]\n");
        }
    }
    $zip->close();

    output_file($filename, "export_contest$cid.zip");
}

/**
 * Export a single problem to a temporay file. 
 * @global type $app_config
 * @global array $img_list
 * @global type $img_count
 * @param type $pid
 * @return string the path of the temporay archive file
 */
function ExportProblem2File($pid) {
    global $app_config;
    global $img_list;
    $img_list = array();
    $problem = new ProblemTbl($pid);
    if (!$problem->Get())
        error("Invalid problem ID");

    $zip = new ZipArchive();
    $filename = tempnam(sys_get_temp_dir(), "prob") . '.zip';
    if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
        error("cannot Create file <$filename>");
    }


    $problem_prefix = $app_config['testdata_path'] . $pid . "/";


    $indexpath = $problem_prefix . '.DIR';

    if (!$zip->addFile($indexpath, basename($indexpath))) {
        error("Error adding index file to archive");
    }

    if ($problem->detail['special_judge']) {
        $spj_filename = $problem_prefix . $pid . '_spj.cpp';
        if (file_exists($spj_filename)) {
            if (!$zip->addFile($spj_filename, basename($spj_filename))) {
                error('Error adding special judge source file');
            }
        }
        $spj_exename = $problem_prefix . "spjudge";
        if (!file_exists($spj_exename))
            error("missing spjudge");
        if (!$zip->addFile($spj_exename, basename($spj_exename))) {
            error('Error adding special judge binary file');
        }
    }

    if ($problem->detail['has_framework']) {
        $framework_filename = "$problem_prefix" . "framework.cpp";
        if (!file_exists($framework_filename))
            error("missing framework.cpp");
        if (!$zip->addFile($framework_filename, basename($framework_filename))) {
            error('Error adding framework source file');
        }
    }

    if (!file_exists($indexpath))
        error("Index file is missing");
    $handle = fopen($indexpath, "r");

    while (!feof($handle)) {
        $line = trim(fgets($handle));
        $paths = explode(" ", $line);
        if (count($paths) < 2)
            continue;
        $input_path = $problem_prefix . $paths[0];
        $output_path = $problem_prefix . $paths[1];
        if (!file_exists($input_path)) {
            error("File [$input_path] is missing");
        }
        if (!$zip->addFile($input_path, basename($input_path))) {
            error("Error adding file [$input_path]");
        }
        if (!file_exists($output_path)) {
            error("File [$output_path] is missing");
        }
        if (!$zip->addFile($output_path, basename($output_path))) {
            error("Error adding file [$output_path]");
        }
    }
    fclose($handle);

    global $img_count;
    $extract_key = array('description', 'input', 'output', 'hint');
    foreach ($extract_key as $value) {
        $problem->detail[$value] = ExtractImage($problem->detail[$value]);
    }
    $export_problem = $problem->detail;
    // clean up all the number keys
    for ($i = 0; array_key_exists($i, $export_problem); ++$i)
        unset($export_problem[$i]);
    if (!$zip->addFromString('metadata.json', json_encode($export_problem))) {
        error("Error writing meta data file");
    }

    foreach ($img_list as $img_path) {
        if (!$zip->addFile($img_path, basename($img_path))) {
            error("Error adding image [$img_path]");
        }
    }
    
    // add standard problem source code into archive for later use
    if ($problem->detail['stdsid']) {
        $status = new StatusTbl($problem->detail['stdsid']);
        $status->Get();
        $lang = $status->detail['language'];
        $src = $status->detail['sourcecode'];
        $ext_hash = array('C++' => '.cpp', 'C' => '.c', 'Pascal' => '.pas', 'Java' => '.java');
        $ext = $ext_hash[$lang];
        if (!$zip->addFromString('standard'.$ext, $src)) {
            error("Error writing standard source code");
        }
    }

    if (!$zip->close())
        error("Compressing error");
    return $filename;
}

function ExportProblem(&$arg) {
    $pid = safefetch($arg, 'pid');
    $problem = new ProblemTbl($pid);
    $problem->Get() or error("Invalid pid");
    $problem = $problem->detail;
    $file_path = ExportProblem2File($pid);

    output_file($file_path, "$pid - {$problem['title']}.zip");
}

function ExportContest(&$arg) {
    $cid = safefetch($arg, 'cid');
    $contest = new ContestsTbl($cid);
    $contest->Get() or error("Invalid cid");
    $contest = $contest->detail;
    $zip = new ZipArchive();
    $filename = tempnam(sys_get_temp_dir(), "export_contest") . '.zip';
    if (!$zip->open($filename, ZIPARCHIVE::CREATE)) {
        error("Error creating archive file");
    }
    $cpids = get_cpids($cid);
    $problem_files = array();
    foreach ($cpids as $pid => $cpid) {
        $file_path = ExportProblem2File($pid);
        if (!$zip->addFile($file_path, "$cpid.zip")) {
            error("Error adding file [$cpid.zip]");
        }
        $problem_files[] = $file_path;
    }
    if (!$zip->addFromString('metadata.json', json_encode(array_values($cpids)))) {
        error("Error writing meta data");
    }
    if (!$zip->addFromString('.SET', date(DATE_W3C))) {
        error("Error writing .SET file");
    }
    if (!$zip->close()) {
        error("Error compressing");
    }
    foreach ($problem_files as $file_path) {
        unlink($file_path);
    }
    output_file($filename, "$cid - {$contest['title']}.zip");
}

function ResetContest(&$arg) {
    $cid = safefetch($arg, 'cid');
    global $conn;
    $rs = new RecordSet($conn);
    $str = "delete from ranklist where cid = $cid";
    $rs->Query($str);
    $str = "delete from contest_status where cid = $cid";
    $rs->Query($str);
    $str = "update problems set accepted = 0, submissions = 0 where cid = $cid";
    $rs->Query($str);
    $rs->free_result();
    MsgAndRedirect("contests.php", "Contest has benn reset.");
}

function StartContest(&$arg) {
    $cid = safefetch($arg, 'cid');
    $contest = new ContestsTbl($cid);
    $contest->Get() or error("No such contest");
    $contest->update['starttime'] = date('Y-m-d H:i:s');
    $contest->Update();
    MsgAndRedirect("contests.php");
}

function KickoutUser(&$arg) {
    $uid = mysql_real_escape_string(safefetch($arg, 'uid'));
    $course_id = mysql_real_escape_string(safefetch($arg, 'course_id'));
    global $conn;
    $rs = new RecordSet($conn);
    $rs->Query("DELETE FROM course_reg WHERE uid = $uid AND course_id = $course_id");
    $rs->affected_rows();
    MsgAndBack();
}

function StdSubmit(&$arg) {
    $pid = safefetch($arg, 'pid');
    $source = isset($arg['source']) ? $arg['source'] : '';
    $language = safefetch($arg, 'language');
    $cid = 0;
    $ret = submit_source($pid, $cid, $language, $source);
    if (is_numeric($ret)) {
        // success
        $sid = intval($ret);
        $problem = new ProblemTbl($pid);
        $problem->Get();
        $problem->update['stdsid'] = $sid;
        $problem->Update();
        MsgAndRedirect('stdprogram.php?pid='. $pid);
    } else {
        MsgAndRedirect('stdprogram.php?pid='. $pid, $ret);
    }
    
}

?>
