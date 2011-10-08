<?
include_once ("../inc/global.inc.php");
include_once ("auth.inc.php");
global $conn;
$id = $_GET["id"];
if ($id == "")
	error("Invalid user ID!", "ranklist.php");
$user = new UserTbl();
if (!$user->Get($id))
	error("Invalid user ID!", "ranklist.php");
$solved = $user->detail['solved'];
$submissions = $user->detail['submissions'];

$rs = new RecordSet($conn);
$rs->Query("SELECT count(*) FROM user WHERE solved > '$solved' OR (solved = '$solved' AND submissions < '$submissions') OR (solved = '$solved' AND submissions = '$submissions' AND uid < '$id')");
$rank = $rs->Fields[0] + 1;

/*
  @mysql_connect($host, $user, $password) or die("Unable to connect database!");
  mysql_select_db($database);
  $table = "user";
  $query = "SELECT * FROM $table WHERE id = '$id'";
  $result = mysql_query($query);
  if (mysql_num_rows($result) == 0) error("Invalid user ID!", "ranklist.php");
  $row = mysql_fetch_array($result);
  $id = $row["id"];
  $username = $row["username"];
  $email = $row["email"];
  $address = $row["address"];
  $solved = $row["solved"];
  $submissions = $row["submissions"];
  $list = $row["list"];
  $ip = $row["ip"];
  $ip_array=explode(".",$ip);
  $ip="$ip_array[0].$ip_array[1].$ip_array[2].*";
  $time = $row["reg_time"];
  $perm = $row["perm"];
  $query = "SELECT count(*) FROM $table WHERE solved > $solved OR (solved = $solved AND submissions < $submissions) ";
  $query .= "OR (solved = $solved AND submissions = $submissions AND id < $id)";
  $result = mysql_query($query);
  $row = mysql_fetch_row($result);
  $rank = $row[0] + 1;
  $table = "problems";
  $query = "SELECT count(*) FROM $table";
  $result = mysql_query($query);
  $row = mysql_fetch_row($result);
  $problem_n = $row[0];
 */
?>
<html>
	<head>
		<title>User - <? echo $user->detail["username"] ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
		<link rel="stylesheet" href="style.css">
	</head>
	<body color="#FFFFFF" bgcolor="#005DA9" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="770">
					<?
					require("./navigation.php");
					?>  
				</td>
				<td background="images/navigation_bg.gif">&nbsp;</td>
			</tr>
			<tr background="images/bg3.gif"> 
				<td height="482" colspan="2" align="center" valign="top" background="images/bg3.gif"><br>
					<table width="500" border="0" cellspacing="2" cellpadding="2">
						<tr>
							<td width="200" align="right" bgcolor="#EFEFEF">User name</td>
							<td><? echo $user->detail["username"]; ?></td>
						</tr>
						<tr>
							<td width="200" align="right">Rank</td>
							<td bgcolor="#EEEEEE"><? echo $rank; ?></td>
						</tr>
						<tr>
							<td width="200" align="right" bgcolor="#EEEEEE">Solved</td>
							<td><? echo $user->detail["solved"]; ?></td>
						</tr>
						<tr>
							<td width="200" align="right">Submissions</td>
							<td bgcolor="#EEEEEE"><? echo $user->detail["submissions"]; ?></td>
						</tr>
						<tr>
							<td width="200" align="right" bgcolor="#EEEEEE">Email</td>
							<td><a href="mailto:<? echo $user->detail["email"]; ?>" class="black"><? echo $user->detail["email"]; ?></a></td>
						</tr>
						<tr>
							<td width="200" align="right">Address</td>
							<td bgcolor="#EEEEEE"><? echo $user->detail["address"]; ?></td>
						</tr>
						<tr>
							<td width="200" align="right">Register time</td>
							<td bgcolor="#EEEEEE"><? echo $user->detail["reg_time"]; ?></td>
						</tr>
					</table>
					<br>
					<?
					$solved = $user->detail["solved"];
					if (strstr($user->detail["perm"], "admin")) {
						$class = "Administrator";
						$fn = "spanworm.gif";
					} else {
						if ($solved < 10) {
							$class = "Class 1 - Spider";
							$fn = "spider.gif";
						}
						if ($solved >= 10 && $solved < 50) {
							$class = "Class 2 - Bee";
							$fn = "bee.gif";
						}
						if ($solved >= 50 && $solved < 100) {
							$class = "Class 3 - Cicada";
							$fn = "cicada.gif";
						}
						if ($solved >= 100 && $solved < 150) {
							$class = "Class 4 - Rearhorse";
							$fn = "rearhorse.gif";
						}
						if ($solved >= 150 && $solved < 200) {
							$class = "Class 5 - Beetle";
							$fn = "beetle.gif";
						}
						if ($solved >= 200 && $solved < 300) {
							$class = "Class 6 - Red Ladybug";
							$fn = "red_ladybug.gif";
						}
						if ($solved >= 300 && $solved < 400) {
							$class = "Class 7 - Blue Ladybug";
							$fn = "blue_ladybug.gif";
						}
						if ($solved >= 400 && $solved < 500) {
							$class = "Class 8 - Gold Ladybug";
							$fn = "gold_ladybug.gif";
						}
					}
					?>
					<table width="200" border="0" cellpadding="2" cellspacing="2">
						<tr>
							<td align="center" bgcolor="#EEEEEE"><? echo $class ?></td>
						</tr>
						<tr>
							<td align="center">
								<br>
								<img src="images/icon/<? echo $fn; ?>" width="150" height="150">
								<br>
							</td>
						</tr>
					</table>
					<br>
					<table width="500" border="0" cellspacing="2" cellpadding="2">
						<tr>
							<td align="center" bgcolor="#EEEEEE">List of solved problems</td>
						</tr>
						<tr>
							<td align="center"><?
					if ($solved > 0) {
						for ($i = 0; $i < $problem_max_n; $i++)
							if ($list[$i] == '2')
								printf("<a href=\"show_problem.php?id=%d\" class=\"black\">%d</a> &nbsp; ", $i + 1000, $i + 1000);
					} else
						echo "<br>No problems has been solved :(";
					?>
								<br>
								<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
<?
require("./footer.php");
?>
		</table>
	</body>
</html>
