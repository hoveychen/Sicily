<?
require("./navigation.php");

$course_id = safeget("course_id");
$courseTbl = new CourseTbl($course_id);
if (!$courseTbl->Get())
    error("Course not found");
$course = $courseTbl->detail;
if (!is_course_modifiable($course_id))
    error("No permission");
$p = tryget("p", 1);

$rs = new RecordSet($conn);
$query_str = "SELECT cid, title, information FROM contests WHERE avail = 1 AND course_id = $course_id ";
$count_str = "SELECT count(*) FROM contests WHERE avail = 1 AND course_id = $course_id ";

$reglist = array();
$regTbl = new CourseRegTbl($course_id);
if ($regTbl->Get()) {
    do {
        $uid = intval($regTbl->detail['uid']);
        $user = new UserTbl($regTbl->detail['uid']);
        $user->Get();
        $reglist[] = $user->detail;
    } while ($regTbl->MoreRows());
}

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY cid DESC";
$rs->dpQuery($query_str);

$now = time();
?>

<div class="blue_anchor">
    <fieldset>
        <legend>
            Basic Operation
        </legend>
        <a href="courses.php" title="Go back to course list">[Go Back]</a>
        &nbsp;|&nbsp
        <a href="contest_create.php?course_id=<?= $course_id ?>">[New Exercise]</a>
        &nbsp;|&nbsp
        <a href="course_edit.php?course_id=<?= $course_id ?>">[Edit Information]</a>
    </fieldset>
</div>

<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <caption> Course Detail </caption>

    <thead>
        <tr  class="ui-widget-header">
            <th width="50">ID</th>
            <th width="200">Name</th>
            <th width="200">Teacher</th>
            <th>Statistics</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= $course['name'] ?></td>
            <td><?= $course['teacher'] ?></td>
            <td> Totally <?= get_course_reg_num($course['course_id']) ?> accounts are registered in this course. </td>
        </tr>
        <tr>
            <td colspan="4" ><?= $course['description'] ?></td>
        </tr>
    </tbody>

</table>

<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <caption>Current Exercises</caption>
    <thead>
        <tr class="ui-widget-header">
            <th width="50">ID</th>
            <th width="200">Name</th>
            <th width="400">Information</th>
            <th>Operation</th>
        </tr>
    </thead>
    <tbody>
        <?
        while ($rs->MoveNext()) {
            $contest = array();
            $contest['id'] = $rs->Fields["cid"];
            $contest['name'] = $rs->Fields["title"];
            $contest['info'] = $rs->Fields["information"];
            ?>
            <tr>
                <td> <?= $contest['id'] ?> </td>
                <td> <a href="contest_detail.php?cid=<?= $contest['id'] ?>"><?= $contest['name'] ?></a> </td>
                <td> <?= $contest['info'] ?> </td>
                <td>
                    <a href="contest_edit.php?cid=<?= $contest['id'] ?>">[Edit]</a>
                    &nbsp;|&nbsp;
                    <a href="../standing.php?cid=<?= $contest['id'] ?>">[Standing]</a>
                    &nbsp;|&nbsp;
                    <a href="process.php?act=DeleteContest&cid=<?= $contest['id'] ?>"
                       onclick="return confirm('Are you sure to delete this exercise?')">[Delete]</a>
                </td>
            </tr>
            <?
        }
        ?>
    </tbody>
</table>

<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <caption>Current Registrantions</caption>
    <thead>
        <tr class="ui-widget-header">
            <th>ID</th>
            <th>UserName</th>
            <th>Chinese Name</th>
            <th>NetID</th>
            <th>Student ID</th>
            <th>Major</th>
            <th>Class</th>
            <th>Operation</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($reglist as $userdata): ?>
            <tr>
                <td> <?= $userdata['uid'] ?> </td>
                <td> <a href="../user.php?id=<?= $userdata['uid'] ?>"><?= $userdata['username'] ?></a> </td>
                <td> <?= $userdata['cn_name'] ?> </td>
                <td> <?= $userdata['netid'] ?> </td>
                <td> <?= $userdata['student_id'] ?> </td>
                <td> <?= $userdata['major'] ?> </td>
                <td> <?= $userdata['class'] ?> </td>
                <td>
                    <a href="process.php?act=KickoutUser&course_id=<?= $course_id ?>&uid=<?= $userdata['uid'] ?>">[Kickout]</a>
                </td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>


<?
require("../footer.php");
?>
