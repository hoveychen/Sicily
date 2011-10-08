<?
require("./navigation.php");
$p = tryget("p", 1);

$rs = new RecordSet($conn);
global $login_uid;
$query_str = "SELECT course_id, name, teacher, description FROM courses WHERE avail = 1 ";
$count_str = "SELECT count(*) FROM courses WHERE avail = 1 ";
if (!is_admins()) {
	$query_str.= "AND owner = $login_uid ";
	$count_str.= "AND owner = $login_uid ";
}

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY course_id DESC";
$rs->dpQuery($query_str);

$now = time();
?>

<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <caption>Current Courses</caption>
    <thead >
        <tr class="ui-widget-header">
            <th width="50">ID</th>
            <th width="200">Name</th>
            <th width="200">Teacher</th>
            <th>Operation</th>
        </tr>
    </thead>
    <tbody>
		<?
		while ($rs->MoveNext()) {
			$course_id = $rs->Fields["course_id"];
			$name = htmlspecialchars($rs->Fields["name"]);
			$teacher = htmlspecialchars($rs->Fields["teacher"]);
			?>
			<tr>
				<td> <?= $course_id ?> </td>
				<td> <a href="course_detail.php?course_id=<?= $course_id ?>"><?= $name ?></a> </td>
				<td> <?= $teacher ?> </td>
				<td>
					<a href="course_edit.php?course_id=<?= $course_id ?>">Edit</a>
					&nbsp;|&nbsp;
					<a href="process.php?act=DeleteCourse&course_id=<?= $course_id ?>">Delete</a>
				</td>
			</tr>
			<?
		}
		?>
        <tr>
            <td colspan="4"><a href="course_create.php">Add Course</a></td>
        </tr>
    </tbody>
</table>

<?
echo $rs->Navigate();
require("../footer.php");
?>
