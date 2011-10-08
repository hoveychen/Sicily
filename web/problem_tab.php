<?php
include_once("inc/global.inc.php");
require("inc/user.inc.php");

$vol = intval(tryget("vol", 1));
if ($vol == 0) {
    $start_pid = 1000;
    $end_pid = 9999;
} else {
    $vol_size = 200;
    $start_pid = ($vol - 1) * $vol_size + 1000;
    $end_pid = $vol * $vol_size + 999;
}
global $login_uid;
global $login_username;
global $logged;

$rs = new RecordSet($conn);
if ($logged) {
    $user = new UserTbl();
    $user->Get($login_uid);
    $list = array();
    $rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid'");
    while ($rs->MoveNext()) {
        $list[$rs->Fields["pid"]] = 1;
    }
    $rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid' AND status='Accepted'");
    while ($rs->MoveNext()) {
        $list[$rs->Fields["pid"]] = 2;
    }
}
if (!isset($login_uid))
    $login_uid = 0;
$cache_time = 60; // Time in seconds to keep a page cached  
$cache_folder = "../cache/$login_uid"; // Folder to store cached files (no trailing slash)  
@mkdir($cache_folder, 0775, true);
$cache_filename = $cache_folder . '/' . md5($_SERVER['REQUEST_URI']); // Location to lookup or store cached file  
//Check to see if this file has already been cached  
// If it has get and store the file creation time  
$cache_created = (file_exists($cache_filename)) ? filemtime($cache_filename) : 0;

if ((time() - $cache_created) < $cache_time) {
    $probs = unserialize(file_get_contents($cache_filename));
} else {
    $probs = array();
    $rs->Query("SELECT pid, problems.cid as cid, problems.title as title, accepted, submissions, special_judge, rate_tot, rate_count FROM problems left join contests on problems.cid = contests.cid WHERE problems.avail = 1 and (problems.cid = 0 or contests.avail = 1) and pid >= $start_pid and pid <= $end_pid and (problems.cid = 0 or addrepos = 1)");
    while ($rs->MoveNext()) {
        $cid = $rs->Fields["cid"];
        if ($cid && (!is_contest_accessible($cid) || !is_contest_ended($cid)))
            continue;

        $pid = $rs->Fields["pid"];
        $title = $rs->Fields["title"];
        $accepted = $rs->Fields["accepted"];
        $submissions = $rs->Fields["submissions"];
        $special_judge = $rs->Fields["special_judge"];

        $rate_tot = $rs->Fields["rate_tot"];
        $rate_count = $rs->Fields["rate_count"];
        if ($rate_count == 0) {
            $rate_score = "-";
        } else {
            $rate_score = round($rate_tot / $rate_count, 2);
        }
        if ($special_judge == '1')
            $title .= "[S]";
        if ($submissions == 0) {
            $ratio = 0;
        } else {
            $ratio = round($accepted * 100 / $submissions, 2);
        }

        $probs[] = array($pid, $title, $accepted, $submissions, $ratio, $rate_score);
    }
    file_put_contents($cache_filename, serialize($probs));
}
?>

<table class="display advtable_fix ui-widget-content">
    <thead class="tr_header"><tr><th><?= _("Status"); ?></th><th><?= _("ID"); ?></th><th class="place_left"><?= _("Title"); ?></th><th><?= _("Accepted"); ?></th><th><?= _("Submissions"); ?></th><th><?= _("Ratio"); ?></th><th><?= _("Rating"); ?></th></tr></thead>
    <tbody>
        <?
        foreach ($probs as $items) {
            $pid = $items[0];
            if (isset($list[$pid])) {
                $status = $list[$pid];
            } else {
                $status = "";
            }

            if ($status == 1) {
                $status = "-";
            } else if ($status == 2) {
                $status = "Y";
            }

            echo "<tr>";
            echo "<td>$status</td>";
            foreach ($items as $item) {
                echo "<td>$item</td>";
            }
            echo "</tr>";
        }
        ?>        

    </tbody>
    <!--<tfoot class="tr_header"><tr><th>Solved</th><th>ID</th><th class="place_left">Title</th><th>Accepted</th><th>Submissions</th><th>Ratio</th><th>Rating</th></tr></tfoot>-->
</table>
