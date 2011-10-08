<?
require("./navigation.php");

$course_id = safeget("course_id");
$courseTbl = new CourseTbl($course_id);
if (!$courseTbl->Get())
	error("Course not found");
$course = $courseTbl->detail;

if ($course['require_cinfo'] && !is_info_complete()) {
	MsgAndRedirect("profile_edit.php", "Your information is not complete.");
}

if ($course['require_bound'] && !is_authorized()) {
	MsgAndRedirect("netid_bind.php", "You need to bind your netid with your account first.");
}

if (is_course_registered($course_id)) {
	MsgAndRedirect("course_detail.php?course_id=$course_id");
}

$now = time();
?>

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
            <td> Totally %d accounts are registered in this course. </td>
        </tr>
        <tr>
            <td colspan="4" ><?= $course['description'] ?></td>
        </tr>
    </tbody>
</table>

<form id="regcourse" action="process.php?act=RegisterCourse" method="post">
    <input type="hidden" name="course_id" value="<?= $course_id ?>" />
</form>

<script type="text/javascript">
    $(function(){
        var ans = confirm("Are you sure to register this course?\n" +
            "You can't exit this course untill contacting the teacher.");
        if (ans) {
            $("#regcourse").submit();
        } else {
            window.location = "courses.php";
        }
    });

</script>

<?
require("./footer.php");
?>
