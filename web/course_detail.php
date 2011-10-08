<?
require("./navigation.php");
global $logged;
if (!$logged)
	error("Please login first");

$course_id = safeget("course_id");
if (!is_course_registered($course_id)) {
	MsgAndRedirect("course_register.php?course_id=$course_id");
}


$courseTbl = new CourseTbl($course_id);
if (!$courseTbl->Get())
	error("Course not found");
$course = $courseTbl->detail;
$p = tryget("p", 1);

$authname = array('free' => _("Public Exercise"), 'password' => _("Password"), 'internal' => _("Internal"), "bound" => _("Netid"));

$rs = new RecordSet($conn);
$query_str = "SELECT * FROM contests WHERE avail = 1 AND course_id = $course_id ";
$count_str = "SELECT count(*) FROM contests WHERE avail = 1 AND course_id = $course_id ";

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY cid DESC";
$rs->dpQuery($query_str);

$now = time();
?>
<h1><?= _("Course Detail") ?></h1>
<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <thead>
        <tr  class="ui-widget-header">
            <th width="50"><?= _("ID") ?></th>
            <th width="200"><?= _("Name") ?></th>
            <th width="200"><?= _("Teacher") ?></th>
            <th><?= _("Statistics") ?></th>
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

<h1><?= _("Current Exercises") ?></h1>
<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
    <thead>
        <tr class="ui-widget-header" align="center">
            <th width="50"><?= _("ID") ?></th>
            <th width="200"><?= _("Name") ?></th>
            <th ><?= _("Information") ?></th>
            <th width="250"><?= _("Schedule") ?></th>
			<td width="100"><?= _("Authorzation") ?></td>
        </tr>
    </thead>
    <tbody>
		<?
		while ($rs->MoveNext()) {
			$contest = array();
			$contest['id'] = $rs->Fields["cid"];
			$contest['name'] = $rs->Fields["title"];
			$contest['info'] = $rs->Fields["information"];
			$authtype = $rs->Fields['authtype'];
			if (!is_contest_visiable($contest['id'])) continue;
			// snap from contests.php
			$time = $rs->Fields["starttime"];
			$during = $rs->Fields["during"];
			$timestamp = strtotime($time);
			$start = $timestamp;
			sscanf($during, "%d:%d:%d", $h, $m, $s);
			$end = $timestamp + $h * 3600 + $m * 60 + $s;
			if ($now < $start) {
				$disTime = _("Starts at ") . date('Y-m-d H:i:s', $timestamp);
			} else if ($now < $end) {
				$disTime = _("Running, ") . date('Y-m-d H:i:s', $timestamp)
						. "<br>" . sprintf(_("%02d:%02d:%02d Left"), ($end - $now) / 3600, ($end - $now) % 3600 / 60, ($end - $now) % 60);
			} else {
				$disTime = _("Finished at ") . date('Y-m-d', $end);
			}
			?>
			<tr>
				<td> <?= $contest['id'] ?> </td>
				<td> <a href="contest_detail.php?cid=<?= $contest['id'] ?>"><?= $contest['name'] ?></a> </td>
				<td> <?= $contest['info'] ?> </td>
				<td> <?= $disTime ?> </td>
				<td> <?= $authname[$authtype] ?> </td>
			</tr>
			<?
		}
		?>

    </tbody>
</table>

<?
echo $rs->Navigate();
require("./footer.php");
?>
