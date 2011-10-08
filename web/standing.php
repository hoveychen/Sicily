<?php
$navmode = "contest";
require("./navigation.php");

$cid = safeget('cid');
$contest = new ContestsTbl($cid);
if (!$contest->Get())
    error("No such contest");
$time = $contest->detail['starttime'];
$during = $contest->detail['during'];
$now = time();

$timestamp = strtotime($time);
$start = $timestamp;
sscanf($during, "%d:%d:%d", $h, $m, $s);
$end = $timestamp + $h * 3600 + $m * 60 + $s;
if ($now < $start) {
    echo "<font color=red>" . _("Starts at ") . date('Y-m-d H:i:s', $timestamp) . ' ';
    printf(_("%02d:%02d:%02d Left"), ($start - $now) / 3600, ($start - $now) % 3600 / 60, ($start - $now) % 60);
} else if ($now < $end) {
    echo "<font color=red>" . _("Running, ");
    printf(_("%02d:%02d:%02d Left"), ($end - $now) / 3600, ($end - $now) % 3600 / 60, ($end - $now) % 60);
}
else
    echo "<font color=green>" . _("Finished");
echo "</font>";
?>

<? if (is_admins()): ?>
<script type="text/javascript">	
	$(function(){
		window.setTimeout(function() {
			location.reload();
		}, 10000);	
	});
</script>
<? endif; ?>

<script type="text/javascript" src="js/table2csv.js"></script>
<script type="text/javascript" src="js/FixedHeader.min.js"></script>
<script type="text/javascript"> 
    function export2csv() {
        $("#standing").table2CSV();
    }
    $(function(){
        // bug with non-chrome browser?
        // new FixedHeader( document.getElementById('standing') );
    });
</script>
<?
// stat problem set
global $conn;
$rs = new RecordSet($conn);
$rs->Query("SELECT cpid FROM contest_problems WHERE cid='$cid'");
$problemstat = array();

while ($rs->MoveNext()) {
    $cpid = $rs->Fields['cpid'];
    $problemstat[$cpid] = array(
        'submission' => 0,
        'firstac' => 9999999,
        'solved' => 0
    );
}
ksort($problemstat, SORT_NUMERIC);

// fetch ranklist data
$userdata = array();
$ranklist = new RanklistTbl($cid);
if ($ranklist->Get()) {
    do {
        $data['submissions'] = intval($ranklist->detail['submissions']);
        $data['ac_time'] = intval($ranklist->detail['ac_time']);
        if ($data['ac_time'] < 0)
            $data['ac_time'] = 0;
        $data['accepted'] = intval($ranklist->detail['accepted']);
        $id = $ranklist->detail['uid'];
        $pid = $ranklist->detail['pid'];
        if (!isset($userdata[$id])) {
            $userdata[$id] = array();
        }
        if (!isset($userdata[$id][$pid]) || $data['accepted'])
            $userdata[$id][$pid] = $data;
    } while ($ranklist->MoreRows());
}

// plus who had registered yet with no submission
$course_id = $contest->detail['course_id'];
if ($course_id) {
    $courseReg = new CourseRegTbl($course_id);
    if ($courseReg->Get()) {
        do {
            if (!isset($userdata[$courseReg->detail['uid']])) {
                $userdata[$courseReg->detail['uid']] = array();
            }
        } while ($courseReg->MoreRows());
    }
}

// fetch user info
$rs = new RecordSet($conn);
$querystr = "SELECT * FROM user WHERE 1=0";
foreach ($userdata as $uid => $data) {
    $querystr .= " OR uid = $uid";
}
$rs->Query($querystr);

while ($rs->MoveNext()) {
    $user = $rs->Fields;
    $user_id = $user["uid"];
    $user['nickname'] = htmlspecialchars($user['nickname']);
    $user['signature'] = htmlspecialchars($user['signature']);
    $userdata[$user_id]['info'] = $user;
}

// stat problem
$total_submission = 0;
$total_solved = 0;
foreach ($userdata as $uid => $data) {
    $data['acnum'] = 0;
    $data['penalty'] = 0;
    foreach ($problemstat as $pid => $pdata) {
        if (!isset($data[$pid]))
            continue;
        $status = $data[$pid];
        if ($status['accepted']) {
            $data['acnum']++;
            $data['penalty'] += $status['ac_time'];
            $data['penalty'] += ( $status['submissions'] - 1) * 20;
            $pdata['solved']++;
            $total_solved++;
            if ($pdata['firstac'] > $status['ac_time']) {
                $pdata['firstac'] = $status['ac_time'];
            }
        }
        $pdata['submission'] += $status['submissions'];
	$problemstat[$pid] = $pdata;
        $total_submission += $status['submissions'];
    }
    // hotfix for missing info
    if (!isset($data['info'])) {
        $data['info'] = array(
            'username' => 'unknown',
            'nickname' => '',
            'signature' => '',
            'student_id' => ''
        );
    }
    if ($course_id) {
        $data['info']['signature'] = $data['info']['student_id'];
    }
    $userdata[$uid] = $data;
}

function acm_cmp($a, $b) {
    if ($a['acnum'] != $b['acnum']) {
        return $a['acnum'] < $b['acnum'];
    }
    return $a['penalty'] > $b['penalty'];
}

function sid_cmp($a, $b) {
    if ($a['info']['student_id'] != $b['info']['student_id']) {
        return $a['info']['student_id'] > $b['info']['student_id'];
    }
    return strcasecmp($a['info']['username'], $b['info']['username']) > 0;
}

$orderby = tryget('orderby', 'acm');
switch ($orderby) {
    case 'acm': uasort($userdata, 'acm_cmp');
        break;
    case 'student_id': uasort($userdata, 'sid_cmp');
        break;
}
$rank = 0;
?>
<? if (is_contest_modifiable($cid)): ?>
<br>
<a href="#" onclick="export2csv()">[Export as CSV]</a>
<? endif; ?>
<table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2" id="standing">
    <thead>
        <tr align="center" class="ui-widget-header" style="font-weight: bold">
            <th width="50" height="20"><?= _("No") ?></th>
            <th><a href='standing.php?cid=<?= $cid ?>'><?= _("User Name") ?></a></th>
            <? foreach ($problemstat as $pid => $pdata): ?>
                <th>
                    <a href="show_problem.php?cid=<?= $cid ?>&pid=<?= $pid ?>" class="white"><?= $pid ?></a>
                </th>
            <? endforeach; ?>
            <th width="70">
                <a href='standing.php?cid=<?= $cid ?>&orderby=acm'>
                    <?= _("Solved") ?></a></th>
            <th width="70"><?= _("Penalty") ?></th>
        </tr>
    </thead>
    <tbody>
        <tr >
            <td> </td>
            <td> <?= _("Solved") ?></td>
            <? foreach ($problemstat as $pid => $pdata): ?>
                <td  align="center">
                    <?=$pdata['solved']?>
                </td>
            <? endforeach; ?>
	    
            <td align="center"><?= $total_solved ?></td>
	    <td></td>
        </tr>

        <tr >
            <td> </td>
            <td> <?= _("Submissions") ?></td>
            <? foreach ($problemstat as $pid => $pdata): ?>
                <td  align="center">
                    <?=$pdata['submission']?>
                </td>
            <? endforeach; ?>
	    
            <td align="center"><?= $total_submission ?></td>
	    <td></td>
        </tr>

        <tr >
            <td> </td>
            <td> <?= _("Earliest AC Time") ?></td>
            <? foreach ($problemstat as $pid => $pdata): ?>
                <td  align="center">
                    <?
                    if ($pdata['solved'] == 0) {
                        $pdata['firstac'] = "--";
                    } else {
			$pdata['firstac'].= "'";
		    }
                    echo $pdata['firstac']
		    ?>
                </td>
            <? endforeach; ?>
	    <td></td><td></td>
        </tr>

        <? foreach ($userdata as $uid =>
        $data): ?>
            <tr>
                <td height=20 align="center"><? $rank++;
        echo $rank; ?></td>
                <td><div><a href="user.php?id=<?= $uid ?>"
                            class="black"
                            title="<?= $data['info']['username'] ?>"><?
                if ($data['info']['nickname']) {
                    echo $data['info']['nickname'];
                } else {
                    echo $data['info']['username'];
                }
            ?></a></div><div style="color:grey"><?= $data['info']['signature'] ?></div></td>
                            <? foreach ($problemstat as $pid => $pdata): ?>
                    <td align="center">
                        <?
                        if (isset($data[$pid])) {
                            if ($course_id) {
                                if ($data[$pid]['accepted']) {
                                    echo "<img src='images/yes.gif' alt='yes'/>Yes";
                                } else {
                                    echo "<img src='images/no.gif' alt='no'/>No";
                                }
                            } else {
                                if ($data[$pid]['accepted']) {


                                    echo "<font color=blue>{$data[$pid]['ac_time']}";
                                    if ($data[$pid]['submissions'] > 1) {
                                        echo "(" . ($data[$pid]['submissions'] - 1) . ")";
                                    }
                                } else {
                                    echo "<font color=gray>({$data[$pid]['submissions']})</font>";
                                }
                            }
                        }
                        ?>
                    </td>
                <? endforeach; ?>
                <td align="center"><?= $data['acnum'] ?></td>
                <td align="center"><?= $data['penalty'] ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<?php
require("./footer.php");
?>

