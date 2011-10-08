<?
require("./navigation.php");

$cid = safeget("cid");
$contestTbl = new ContestsTbl($cid);
if (!$contestTbl->Get())
    error("Contest not found");
$course_id = $contestTbl->detail['course_id'];
if (!is_contest_modifiable($cid)) {
    error("No permission");
}

$contestProblemTbl = new ContestProblemTbl($cid);
$cpidIndex = array();
if ($contestProblemTbl->Get()) {
    do {
        $cpidIndex[$contestProblemTbl->detail['pid']] = $contestProblemTbl->detail['cpid'];
    } while ($contestProblemTbl->MoreRows());
}
$contest = $contestTbl->detail;
$p = tryget("p", 1);

$rs = new RecordSet($conn);
$query_str = "SELECT problems.pid FROM problems LEFT JOIN contest_problems ON contest_problems.pid = problems.pid WHERE avail = 1 AND problems.cid = $cid ";
$count_str = "SELECT count(*) FROM problems WHERE avail = 1 AND cid = $cid ";

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY cpid ASC";
$rs->dpQuery($query_str);

$now = time();
?>

<script type="text/javascript">
    $(function(){
        $("#reset").click(function(){
            return confirm("Are you sure to reset contest?");
        });
        $("#start").click(function(){
            return confirm("Are you sure to start/restart contest?");
        });
    });
</script>

<div class="blue_anchor">
<fieldset>
    <legend>Basic Operation</legend>
    <? if ($course_id): ?>
        <a href="course_detail.php?course_id=<?= $course_id ?>" title="Go Back to course">[Go Back]</a>
        &nbsp;|&nbsp;				
    <? endif; ?>
    <a href="problem_create.php?cid=<?= $cid ?>" title="Create New Problem">[New Problem]</a>
    &nbsp;|&nbsp;
    <a href="contest_edit.php?cid=<?= $cid ?>" title="Edit Contest Infomation">[Edit]</a>
    &nbsp;|&nbsp;
    <a href="../standing.php?cid=<?= $cid ?>" title="View Current Standing">[Standing]</a>
    &nbsp;|&nbsp;
    <a id="start" href="process.php?act=StartContest&cid=<?=$cid?>" title="Start Contest">[Start]</a>
    &nbsp;|&nbsp;
	<a id="reset" href="process.php?act=ResetContest&cid=<?=$cid?>" title="Reset Contest">[Reset]</a>
</fieldset>

<br>

<fieldset>
    <legend>Import/Export</legend>
    <a href="#" onclick="$('#Import_Box').show()" title="Import single problem/set of problems">[Import Problems]</a>
    &nbsp;|&nbsp;
    <a href="import_user.php?cid=<?= $cid ?>" title="Import Namelist">[Import Namelist]</a>
    &nbsp;|&nbsp;
    <a href="process.php?act=ExportSource&cid=<?= $cid ?>" title="Export All Submitted Source Code">[Export Source Code]</a>
    &nbsp;|&nbsp;
    <a href="process.php?act=ExportContest&cid=<?= $cid ?>">[Export All Problems]</a>
    <div id="Import_Box" style="display: none">
        <form method="post" enctype="multipart/form-data" 
              action="process.php?act=ImportArchiveProblem">
            <input name="archive" type="file" />
            <input name="cid" type="hidden" value='<?= $cid ?>'/>
            <input value="Submit" type="submit" />
        </form>
    </div>
</fieldset>
</div>

<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <caption>Current Problems</caption>
    <thead>
        <tr class="ui-widget-header">
            <th>PID</th>
            <th>CPID</th>
            <th>Title</th>
            <th>Operation</th>
            <th>Export/Data</th>
            <th>Testing</th>
            <th>Rejudge</th>
        </tr>
    </thead>
    <tbody>
        <?
        while ($rs->MoveNext()) {
            $pid = $rs->Fields['pid'];
            $problemTbl = new ProblemTbl($pid);
            if (!$problemTbl->Get())
                continue;
            $problem = $problemTbl->detail;
            $quickarg = "cid=$cid&pid={$cpidIndex[$problem['pid']]}";
            ?>
            <tr>
                <td> <?= $problem['pid'] ?> </td>
                <td> <?= $cpidIndex[$problem['pid']] ?> </td>
                <td> <a href="../show_problem.php?<?= $quickarg ?>"><?= $problem['title'] ?></a> </td>
                <td>
                    <a href="process.php?act=IncContestProblem&<?= $quickarg ?>" title="Inc Index">[↓]</a>
                    &nbsp;|&nbsp;
                    <a href="process.php?act=DecContestProblem&<?= $quickarg ?>" title="Dec Index">[↑]</a>
                    &nbsp;|&nbsp;
                    <a href="problem_edit.php?<?= $quickarg ?>">[Edit]</a>
                    &nbsp;|&nbsp;
                    <a href="process.php?act=DeleteContestProblem&<?= $quickarg ?>"
                       onclick="return confirm('Are you sure to delete this problem?')" title="Delete">[Delete]</a>
                </td>
                <td>
                    <a href="process.php?act=ExportProblem&pid=<?= $problem['pid'] ?>">[Download]</a>
                </td>
                <td>
                    <? if ($problem['stdsid']): ?>
                    <img src="images/yes.gif"/>
                    <a href="stdprogram.php?pid=<?= $problem['pid'] ?>">[Resubmit]</a>
                    <? else: ?>
                    <img src="images/no.gif" />
                    <a href="stdprogram.php?pid=<?= $problem['pid'] ?>">[Submit]</a>
                    <? endif; ?>
                </td>
                <td>
                    <a href="process.php?act=RejudgeProblem&<?= $quickarg ?>"
                       onclick="return confirm('Are you sure to rejudge this problem?')">[All]</a>
                    &nbsp;|&nbsp;
                    <a href="process.php?act=RejudgeProblem&rjall=0&<?= $quickarg ?>"
                       onclick="return confirm('Are you sure to rejudge this problem?')">[Non-ac]</a>
                </td>
            </tr>
            <?
        }
        ?>
    </tbody>
</table>

<?
echo $rs->Navigate();
require("../footer.php");
?>
