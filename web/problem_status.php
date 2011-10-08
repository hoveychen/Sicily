<?php
require("./navigation.php");
?>
<?

function replace($text) {
    $text = preg_replace("/\r\n/", "<br>", $text);
    $text = preg_replace("/\n/", "<br>", $text);
    return $text;
}

$pid = $_GET["pid"];
if (isset($_GET["p"]))
    $start_page = $_GET["p"]; else
    $start_page = 1;

$rs = new RecordSet($conn);
$rs->nPageSize = 20;

if ($pid == "" || $pid < 1000)
    error("Invalid problem ID!", "problems.php");
$problem = new ProblemTbl();
if (!$problem->Get($pid))
    error("Invalid problem ID!", "problems.php");

$rs->PageCount("SELECT count(DISTINCT uid) FROM status WHERE pid='$pid' AND status='Accepted'");
$rs->SetPage(0);
$rs->Query("SELECT uid, language, run_time, run_memory, time, sid, codelength FROM status WHERE pid='$pid' AND status='Accepted' ORDER BY run_time, run_memory, codelength, time");
//  $rs->dpQuery("SELECT uid as u2, language, run_time, run_memory, time FROM status WHERE pid='$pid' AND status='Accepted' AND run_time = (SELECT MIN(run_time) FROM status WHERE pid='$pid' AND status='Accepted' AND uid=u2 ORDER BY run_time, run_memory, time) ORDER BY run_time, run_memory, time");

$rs->nPageSize = 20;
$rs->SetPage($start_page);

$rs2 = new RecordSet($conn);
$rs3 = new RecordSet($conn);
$rs4 = new RecordSet($conn);
$userArray = array();

$title = $problem->detail["title"];
$submit = $problem->detail["submissions"];
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Accepted'");
$accepted = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Wrong Answer'");
$wrong = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Presentation Error'");
$pe = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Time Limit Exceeded'");
$tle = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Memory Limit Exceeded'");
$mle = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Output Limit Exceeded'");
$ole = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND status='Runtime Error'");
$re = $rs4->nTotalRecord;
$rs4->PageCount("SELECT count(*) FROM status WHERE pid='$pid' AND (status='Compile Error' OR status='Restrict Function')");
$ce = $rs4->nTotalRecord;

$title = replace($title);
$submit = replace($submit);
$accepted = replace($accepted);
$wrong = replace($wrong);
$pe = replace($pe);
$tle = replace($tle);
$mle = replace($mle);
$ole = replace($ole);
$re = replace($re);
$ce = replace($ce);
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/bg2.gif">
    <tr valign="top">
        <td width="30%" colspan="2" align="center" >
            <table class="tblcontainer ui-widget-content ui-corner-all" width="100%" >
                <caption>Problem Status</caption>
                <thead>
                    <tr align="center" class="ui-widget-header"><td> Stats </td> <td> Amount </td> </tr>
                </thead>
                <tbody>
                    <tr align="center"><td>Total Submissions</td><td><? echo $submit ?></td></tr>
                    <tr align="center"><td>Accepted</td><td><? echo $accepted ?></td></tr>
                    <tr align="center"><td>Wrong Answer</td><td><? echo $wrong ?></td></tr>
                    <tr align="center"><td>Presentation Error</td><td><? echo $pe ?></td></tr>
                    <tr align="center"><td>Time Limit Exceed</td><td><? echo $tle ?></td></tr>
                    <tr align="center"><td>Memory Limit Exceed</td><td><? echo $mle ?></td></tr>
                    <tr align="center"><td>Output Limit Exceed</td><td><? echo $ole ?></td></tr>
                    <tr align="center"><td>Runtime Error</td><td><? echo $re ?></td></tr>
                    <tr align="center"><td>Compilation Error</td><td><? echo $ce ?></td></tr>
                </tbody>
            </table>
        </td>
        <td width="5%">

        </td>
        <td colspan="2" align="center">
            <table class="tblcontainer  ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2">
                <caption><?= $pid . " " . $title ?></caption>
                <thead>
                    <tr align="center" class="ui-widget-header"> 
                        <td width="6%" height="20">Rank</td>
                        <td>Submit&nbsp;Time</td>
                        <td>Run&nbsp;Time</td>
                        <td>Run&nbsp;Memory</td>
                        <td>Code&nbsp;Length</td>
                        <td>Language</td>
                        <td>User</td>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if ($rs->MoveNext()) {
                        $i = ($start_page - 1) * $rs->nPageSize;
                        $j = 0;
                        do {
                            if (in_array($rs->Fields['uid'], $userArray))
                                continue;
                            else
                                array_push($userArray, $rs->Fields['uid']);
                            $j++;
                            if ($j <= $i)
                                continue;
                            $i++;
                            printf("<tr>");
                            ?>
                        <td height=20 align="center"><? echo $i; ?></td>
                        <?
                        $uid = $rs->Fields['uid'];
                        $rs2->Query("SELECT username FROM user WHERE uid='$uid'");
                        $rs2->MoveNext();
                        ?>
                        <td align="center"><? echo $rs->Fields['time'] ?></td>
                        <td align="center"><?
                if ($rs->Fields['run_time'] == '0')
                    echo "0.00";
                else
                    echo $rs->Fields['run_time'];
                        ?> sec</td>
                        <td align="center"><? echo $rs->Fields['run_memory'] ?> KB</td>
                        <td align="center"><?
                    $codelength = $rs->Fields['codelength'];
                    if ($codelength == 0) {
                        $codelength = "N/A";
                    } else {
                        $codelength .= " Bytes";
                    }
                    echo $codelength;
                        ?>
                        </td>
                        <td align="center"><?
                    if ($uid == $login_uid || is_admins() || is_manager()) {
                        echo "<a href=\"viewsource.php?sid=";
                        echo $rs->Fields['sid'];
                        echo "\" class=\"blue\">";
                        echo $rs->Fields['language'];
                        echo "</a>";
                    }
                    else
                        echo $rs->Fields['language'];
                        ?></td>
                        <td align="center"><a href="user.php?id=<? echo $uid ?>" class = "black"><? echo $rs2->Fields['username'] ?></a></td>
                        <?
                        echo "</tr>";
                    } while ($rs->MoveNext() && $i < $start_page * $rs->nPageSize);
                }
                ?>
                </tbody>
            </table>
</table>

<? echo $rs->Navigate(); ?>



<?php
require("./footer.php");
?>
