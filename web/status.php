<?php
$cid = isset($_GET["cid"]) ? $_GET['cid'] : "";
if ($cid)
    $navmode = "contest";
require("./navigation.php");
?>

<?
$where = array();
$status_vals = array("", "Accepted", "Wrong Answer", "Compile Error", "Runtime Error", "Time Limit Exceeded",
    "Memory Limit Exceeded", "Output Limit Exceeded", "Presentation Error", "Restrict Function",
    "Running", "Other", "Waiting");
$display_status = array("", _("Accepted "), _("Wrong Answer"), _("Compile Error"),
    _("Runtime Error"), _("Time Limit Exceeded"), _("Memory Limit Exceeded"),
    _("Output Limit Exceeded"), _("Presentation Error"), _("Restrict Function"),
    _("Running"), _("Other"), _("Waiting"));
$lang_vals = array("", "C", "C++", "Pascal");

function add_where($condition) {
    global $where;
    $where[] = $condition;
}

function set_where($field, $field_arr = NULL) {
    if (!isset($_GET[$field]) || empty($_GET[$field]))
        return;
    if (!empty($field_arr)) {
        $val = $field_arr[$_GET[$field]];
    } else {
        $val = $_GET[$field];
    }
    add_where("$field='" . $val . "'");
}

$p = tryget('p', 1);
$cid = tryget('cid', "");
$rs = new RecordSet($conn);
$rs->nPageSize = 10;
$uid = $login_uid;

set_where("pid");
set_where("cpid");
set_where("language", $lang_vals);
set_where("status", $status_vals);
if (!empty($_GET['username'])) {
    $user = new UserTbl();
    if ($user->GetByField('username', $_GET['username'])) {
        add_where("status.uid = {$user->detail['uid']}");
    } else {
        add_where("1 = 0");
    }
}

if ($cid) {
    if (!is_contest_accessible($cid))
        error("Sorry, you can't access to the contest!");
    $rs->Query("SELECT MIN(csid) FROM contest_status WHERE cid='$cid'");
    $rs->MoveNext();
    $min_sid = $rs->Fields[0] - 1;
    $rs->PageCount("SELECT count(*) FROM contest_status WHERE cid='$cid'");
    $rs->SetPage($p);
    add_where("status.uid=user.uid");
    add_where("cid='$cid'");
    add_where("contest_status.sid=status.sid");
    $where = implode(" AND ", $where);
    if (is_contest_modifiable($cid))
        $rs->dpQuery("SELECT csid AS sid, status.uid, username, cpid AS pid, language, status, run_time, run_memory, time, failcase, codelength FROM contest_status,user,status WHERE $where ORDER BY sid DESC");
    else
        $rs->dpQuery("SELECT csid AS sid, status.uid, username, cpid AS pid, language, status, run_time, run_memory, time, failcase, codelength FROM contest_status,user,status WHERE $where AND user.uid='$uid' ORDER BY sid DESC");
    $contest = new ContestsTbl();
    $contest->Get($cid);
} else {
    $rs->PageCount("SELECT count(*) FROM status WHERE contest = 0");
    $rs->SetPage($p);
    add_where("contest = 0");
    $where = implode(" AND ", $where);
    $sql = "SELECT * FROM status WHERE $where ORDER BY sid DESC";
    $rs->dpQuery($sql);
}
?>

<fieldset>
    <legend>Filter Status</legend>
    <form name="search" method="get" action="status.php">
        <?php if ($cid) { ?><input name="cid" type="hidden" value="<?php print $cid; ?>" /><?php } ?>
        <?= _("Problem ID") ?>:
        <?php if ($cid) { ?>
            <input name="cpid" type="text" id="cpid" value="<? echo tryget('cpid', ""); ?>" size="8">
        <? } else { ?>
            <input name="pid" type="text" id="pid" value="<? echo tryget('pid', ""); ?>" size="8">
        <? } ?>
        <?= _("User ID") ?>:
        <input name="username" type="text" id="username" value="<? if (isset($_GET['username']))
            echo $_GET['username']; ?>" size="15">
        <?= _("Result") ?>:
        <select name="status" size="1" id="status">
            <option value=""><?= _("All") ?></option>
            <?php
            $status = isset($_GET['status']) ? $_GET['status'] : "";
            for ($i = 1; $i < count($status_vals); $i++) {
                print '<option value="' . ($i) . '"';
                if ($status == $i)
                    print ' selected="selected"';
                print '>' . $display_status[$i] . '</option>';
            }
            ?>
        </select>
        <?= _("Language") ?>:
        <select size="1" name="language">
            <option value="0"><?= _("All") ?></option>
            <?php
            $language = isset($_GET['language']) ? $_GET['language'] : "";
            for ($i = 1; $i < count($lang_vals); $i++) {
                print '<option value="' . ($i) . '"';
                if ($language == $i)
                    print ' selected="selected"';
                print '>' . $lang_vals[$i] . '</option>';
            }
            ?>
        </select>
        <input name="submit" type="submit" value="<?= _("Go") ?>" width="8">
    </form>
</fieldset>
<table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2">
    <thead>
        <tr align="center" class="ui-widget-header"> 
            <td width="8%" height="20"><b><?= _("Run ID") ?></b></td>
            <td>&nbsp;&nbsp;</td>
            <td><b><?= _("User Name") ?></b></td>
            <td width="10%"><b><?= _("Problem") ?></b></td>
            <td width="10%"><b><?= _("Language") ?></b></td>
            <td width="16%"><b><?= _("Status") ?></b></td>
            <td width="10%"><b><?= _("Run Time") ?></b></td>
            <td width="10%"><b><?= _("Run Memory") ?></b></td>
            <td width="10%"><b><?= _("Code Length") ?></b></td>
            <td width="18%"><b><?= _("Submit Time") ?></b></td>		  
        </tr>
    </thead>
    <?
    if ($rs->MoveNext()) {
        $i = 0;
        do {
            printf("<tr bgcolor=\"#%s\">\n", ($i % 2) ? "EEEEEE" : "FCFCFC");
            $user_id = $rs->Fields["uid"];
            global $login_uid;
            if ($user_id == $login_uid)
                $username = $login_username;
            else {
                $user = new UserTbl($user_id);
                if ($user->Get())
                    $username = $user->detail['username'];
                else
                    $username = 'unknown';
            }
            $problem_id = $rs->Fields["pid"];
            $language = $rs->Fields["language"];
            $status = $rs->Fields["status"];
            $run_time = $rs->Fields["run_time"];
            $run_memory = $rs->Fields["run_memory"];
            $failcase = $rs->Fields["failcase"];
            $codelength = $rs->Fields["codelength"];
            if ($run_time == NULL)
                $run_time = _("N/A");
            if ($run_memory == NULL)
                $run_memory = _("N/A");
            if ($run_time != _("N/A"))
                $run_time .= _("sec");
            if ($run_memory != _("N/A"))
                $run_memory .= _(" KB");
            if ($codelength == "0") {
                $codelength = _("N/A");
            } else {
                $codelength .= _(" Bytes");
            }
            $time = $rs->Fields["time"];
            if (!$cid && $rs->Fields["contest"])
                continue;
            ?>
            <td height=20 align="center">
                <?
                if ($cid == 0) {
                    echo $rs->Fields['sid'];
                } else {
                    echo $rs->Fields['sid'] - $min_sid;
                }
                ?> </td>
            <td>
                <?
                if ($cid == 0) {
                    if ($user_id == $uid || is_admins() || is_manager()) {
                        echo "<a href=\"viewsource.php?sid=" . $rs->Fields['sid'] . "\" class=\"black\">";
                        echo "<center><img src='images/source-code.png' alt='view' title='View source code' /></center>";
                        echo "</a>";
                    }
                } else {
                    $timestamp = strtotime($time) - strtotime($contest->detail['starttime']);
                    if ($user_id == $uid || is_contest_modifiable($cid)) {
                        echo "<a href=\"viewsource.php?cid=" . $cid . "&sid=" . $rs->Fields['sid'] . "\" class=\"black\">";
                        echo "<center><img src='images/source-code.png' alt='view' title='View source code' /></center>";
                        echo "</a>";
                    }
                }
                ?>

            </td>
            <td>&nbsp;&nbsp;<a href="user.php?id=<? echo $user_id; ?>" class="black"><? echo $username; ?></a></td>
            <td align="center"><a href="show_problem.php?pid=<?
        echo $problem_id;
        if ($cid)
            echo "&cid=$cid";
                ?>" class="black"><? echo $problem_id; ?></a></td>
            <td align="center"><? echo $language; ?></td>
            <td align="center">
                <?
                if (($status == "Wrong Answer" || $status == "Memory Limit Exceeded" ||
                        $status == "Time Limit Exceeded" || $status == "Runtime Error" ||
                        $status == "Presentation Error" || $status == "Judging") &&
                        $failcase != -1)
                    $failmsg = "(" . ($failcase + 1) . ")"; else
                    $failmsg="";
                if ($status == "Accepted")
                    echo "<font color=\"#008000\">";
                else
                    echo "<font color=\"#FF0000\">";
                $sid = $rs->Fields['sid'];
                if ($status == "Compile Error")
                    echo "<a href=\"compileresult.php?cid=$cid&amp;sid=$sid\" style=\"text-decoration:none;color:blue\">";
                for ($i = 1; $i < count($status_vals); ++$i) {
                    if ($status_vals[$i] == $status) {
                        echo $display_status[$i] . $failmsg;
                        break;
                    }
                }
                if ($status == "Compile Error")
                    echo "</a>";
                echo "</font>";
                ?>
            </td>
            <td align="center">
                <?
                if ($status == "Time Limit Exceeded")
                    echo "&gt;";
                echo $run_time;
                ?>
            </td>
            <td align="center"><? echo $run_memory; ?></td>
            <td align="center"><? echo $codelength; ?></td>
            <td align="center"><? echo $time; ?></td>		
        </tr>
    <? } while ($rs->MoveNext());
} ?>
</table>
<? echo $rs->Navigate(); ?>
<? if ($logged): ?>
    <a href="sharecodes.php?uid=<?= $login_uid ?>" class="black">
        <img src="images/icon_share.gif" alt="share" />Manage all my sharing source codes.        
    </a>
<? endif; ?>



<?php
require("./footer.php");
?>
