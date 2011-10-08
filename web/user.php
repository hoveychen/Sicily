<?php
require("./navigation.php");
?>

<?
//  global $conn;
$id = $_GET["id"];
if ($id == "")
	error("Invalid user ID!", "ranklist.php");
$user = new UserTbl();
if (!$user->Get($id))
	error("Invalid user ID!", "ranklist.php");
$rs = new RecordSet($conn);
foreach ($user->detail as $k => $v) {
	$user->detail[$k] = htmlspecialchars($v);
}
$solved = $user->detail['solved'];
$submissions = $user->detail['submissions'];

$rs->Query("SELECT count(*) FROM user WHERE perm LIKE '%user%' AND (solved > '$solved' OR (solved = '$solved' AND submissions < '$submissions') OR (solved = '$solved' AND submissions = '$submissions' AND uid < '$id'))");
$rs->MoveNext();
$rank = $rs->Fields[0] + 1;
$rs->Query("SELECT max(pid) FROM problems");
$rs->MoveNext();
$problem_max_n = $rs->Fields[0];

$list = array();
$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$id' AND status='Accepted'");
while ($rs->MoveNext()) {
	$list[] = $rs->Fields["pid"];
}
sort($list);
$solved = $user->detail["solved"];
if (strstr($user->detail["perm"], "admin")) {
	$class = "Administrator";
	$fn = "spanworm.gif";
} else {
	$levels = array(
		array(0, "Class 1 - Spider", "spider.gif"),
		array(10, "Class 2 - Bee", "bee.gif"),
		array(50, "Class 3 - Cicada", "cicada.gif"),
		array(100, "Class 4 - Rearhorse", "rearhorse.gif"),
		array(150, "Class 5 - Beetle", "beetle.gif"),
		array(200, "Class 6 - Red Ladybug", "red_ladybug.gif"),
		array(300, "Class 7 - Blue Ladybug", "blue_ladybug.gif"),
		array(400, "Class 8 - Gold Ladybug", "gold_ladybug.gif"),
		array(500, "Class 9 - Blue Dragonfly", "blue_dragonfly.gif"),
		array(600, "Class 10 - Green Dragonfly", "green_dragonfly.gif"),
	);
	foreach ($levels as $level) {
		if ($solved >= $level[0]) {
			$class = $level[1];
			$fn = $level[2];
		}
	}
}
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">

	<tr valign="top">
		<td width="300">
			<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellspacing="2" cellpadding="2">
				<thead>
					<tr  class="ui-widget-header">
						<th width="100" colspan="2">Detail of <?= $user->detail['username'] ?></th>
					</tr>
				</thead>
				<tr>
					<td  align="right"><?= _("Nickname") ?></td>
					<td align="left"><? echo $user->detail["nickname"]; ?></td>
				</tr>
				<tr>
					<td  align="right"><?= _("Signature") ?></td>
					<td align="left"><? echo $user->detail["signature"]; ?></td>
				</tr>
				<tr>
					<td  align="right"><?= _("Rank") ?></td>
					<td align="left"><? echo $rank; ?></td>
				</tr>
				<tr>
					<td align="right"><?= _("Solved No.") ?></td>
					<td align="left"><? echo $solved ?></td>
				</tr>
				<tr>
					<td  align="right"><?= _("Submissions No.") ?></td>
					<td align="left"><? echo $submissions ?></td>
				</tr>
				<tr>
					<td  align="right"><?= _("Email") ?></td>
					<td align="left">
						<a href="mailto:<? echo $user->detail["email"]; ?>" class="black">
							<? echo $user->detail["email"]; ?>
						</a>
					</td>
				</tr>
				<tr>
					<td  align="right"><?= _("Contact1") ?></td>
					<td align="left"><? echo $user->detail["phone"]; ?></td>
				</tr>
				<tr>
					<td  align="right"><?= _("Contact2") ?></td>
					<td align="left"><? echo $user->detail["address"]; ?></td>
				</tr>
				<? if (is_admins() || is_manager()): ?>
					<tr>
						<td  align="right"><?= _("NetID") ?></td>
						<td align="left"><? echo $user->detail["netid"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("Chinese Name") ?></td>
						<td align="left"><? echo $user->detail["cn_name"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("English Name") ?></td>
						<td align="left"><? echo $user->detail["en_name"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("Student ID") ?></td>
						<td align="left"><? echo $user->detail["student_id"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("Major") ?></td>
						<td align="left"><? echo $user->detail["major"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("Grade") ?></td>
						<td align="left"><? echo $user->detail["grade"]; ?></td>
					</tr>
					<tr>
						<td  align="right"><?= _("Class") ?></td>
						<td align="left"><? echo $user->detail["class"]; ?></td>
					</tr>

				<? endif; ?>
				<tr>
					<td  align="right"><?= _("Register time") ?></td>
					<td align="left"><? echo $user->detail["reg_time"]; ?></td>
				</tr>
				<tr>
					<td align="right"><?= _("Level") ?></td>
					<td align="left"><? echo $class ?>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<img alt="" src="images/icon/<? echo $fn; ?>" width="150" height="150">
					</td>
				</tr>

			</table>
		</td>
		<td width="50"></td>
		<td>

			<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%"border="0" cellspacing="2" cellpadding="2">
				<thead>
					<tr class="ui-widget-header">
						<td align="center"><?= _("List of solved problems") ?></td>
					</tr>
				</thead>
				<tr align="center">
					<td>
						<form action=usercmp.php method=get>
							<?= _("Compare") ?> <input type="text" size="10" name="user1"
								   value="<? echo $user->detail["username"]; ?>"/> <?= _("and") ?>
							<input type="text" size="10" name="user2" />
							<input type="submit" value="GO" />
						</form>
					</td>
				</tr>
				<tr>
					<td align="center" style="font-size: large; word-spacing: 0.3em">
						<? if ($solved > 0): ?>
							<? foreach ($list as $pid): ?>
								<a href="show_problem.php?pid=<?= $pid ?>" class="black"><?= $pid ?></a>
							<? endforeach; ?>
						<? else: ?>
							<?= _("No problems has been solved :(") ?>
						<? endif; ?>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>

<?php
require("./footer.php");
?>
