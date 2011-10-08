<?
require("navigation.php");
$p = tryget("p", 1);

$rs = new RecordSet($conn);
$query_str = "SELECT course_id, name, teacher, description FROM courses WHERE avail = 1 ";
$count_str = "SELECT count(*) FROM courses WHERE avail = 1";

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY course_id DESC";
$rs->dpQuery($query_str);

$now = time();
?>
<h1><?= _("Current Courses") ?></h1>
<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <thead >
        <tr class="ui-widget-header">
            <th width="100"><?= _("ID") ?></th>
            <th width="300"><?= _("Name") ?></th>
            <th width="300"><?= _("Teacher") ?></th>
            <th width="100"><?= _("Registrant No.") ?></th>
            <th> <?= _("Status") ?> </th>
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
				<td> <?= get_course_reg_num($course_id) ?> </td>
				<td> Available
				</td>
			</tr>
			<?
		}
		?>
    </tbody>
</table>

<?
echo $rs->Navigate();
require("footer.php");
?>
