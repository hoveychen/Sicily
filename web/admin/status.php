<?
include_once("../inc/global.inc.php");
include_once ("auth.inc.php");

$p = $_GET["p"];
$cid = $_GET["cid"];
$pid = $_GET["pid"];
if ($p == "")
	$p = 1;
$rs = new RecordSet($conn);
$rs->nPageSize = 16;
if ($cid) {
	$rs->PageCount("SELECT count(*) FROM contest_status");
	$rs->SetPage($p);
	if ($pid)
		$rs->dpQuery("SELECT sid, contest_status.uid, username, pid, language, status, run_time, run_memory, time FROM contest_status,user WHERE contest_status.uid=user.uid AND cid='$cid' AND pid='$pid' ORDER BY time DESC");
	else
		$rs->dpQuery("SELECT sid, contest_status.uid, username, pid, language, status, run_time, run_memory, time FROM contest_status,user WHERE contest_status.uid=user.uid AND cid='$cid' ORDER BY time DESC");
	$contest = new ContestsTbl();
	$contest->Get($cid);
} else {
	$rs->PageCount("SELECT count(*) FROM status");
	$rs->SetPage($p);

	$rs->dpQuery("SELECT sid, status.uid, username, pid, language, status, run_time, run_memory, time FROM status,user WHERE status.uid=user.uid ORDER BY sid DESC");
}
?>
<html>
	<head>
		<title>Status</title>
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
			<tr valign="top"> 
				<td height="440" colspan="2" align="center" background="images/bg2.gif">
					<br>
					<table width="95%" border="0" cellpadding="4" cellspacing="2">
						<tr align="center" bgcolor="#0071BD" class="white"> 
							<td width="8%" height="20"><b>Run ID</b></td>
							<td><b>User Name</b></td>
							<td width="10%"><b>Problem</b></td>
							<td width="10%"><b>Language</b></td>
							<td width="16%"><b>Status</b></td>
							<td width="10%"><b>Run Time</b></td>
							<td width="10%"><b>Run Memory</b></td>
							<td width="18%"><b>Submit Time</b></td>
							<td width="10%"><b>Rejudge</b></td>
							<td width="10%"><b>Source</b></td>
						</tr>
						<?
						if ($rs->MoveNext()) {
							$i = 0;
							do {
								printf("<tr bgcolor=\"#%s\">\n", ($i % 2) ? "EEEEEE" : "FCFCFC");
								$user_id = $rs->Fields["uid"];
								$username = $rs->Fields["username"];
								$problem_id = $rs->Fields["pid"];
								$language = $rs->Fields["language"];
								$status = $rs->Fields["status"];
								$run_time = $rs->Fields["run_time"];
								$run_memory = $rs->Fields["run_memory"];
								if ($run_time == NULL)
									$run_time = "N/A";
								if ($run_memory == NULL)
									$run_memory = "N/A";
								if ($run_time != "N/A")
									$run_time .= " sec";
								if ($run_memory != "N/A")
									$run_memory .= " KB";
								$time = $rs->Fields["time"];
								?>
								<td height=20 align="center">
									<?
									if ($cid == 0)
										echo $rs->Fields['sid'];
									else {
										$timestamp = strtotime($time) - strtotime($contest->detail['starttime']);
										echo intval($timestamp / 60) + 1;
									}
									?>
								</td>
								<td>&nbsp;&nbsp;<a href="user.php?id=<? echo $user_id; ?>" class="black"><? echo $username; ?></a></td>
								<td align="center"><a href="show_problem.php?pid=<?
											  echo $problem_id;
											  if ($cid)
												  echo "&cid=$cid";
									?>" class="black"><? echo $problem_id; ?></a></td>
								<td align="center"><? echo $language; ?></td>
								<td align="center">
		<?
		if ($status == "Accepted")
			echo "<font color=\"#008000\">";
		else
			echo "<font color=\"#FF0000\">";
		echo $status;
		echo "</font>";
		?>
								</td>
								<td align="center"><? echo $run_time; ?></td>
								<td align="center"><? echo $run_memory; ?></td>
								<td align="center"><? echo $time; ?></td>
								<td align="center"><a href="process.php?act=Rejudge&
					<? echo "cid=" . $cid . "&sid=" . $rs->Fields['sid'] ?>
			">Rejudge</a>
								</td>
								<td align="center"><a href="process.php?act=ViewSource&
		<? echo "cid=" . $cid . "&sid=" . $rs->Fields['sid'] ?>
			">Source</a>
								</td>
					</tr>
	<? } while ($rs->MoveNext());
} ?>
		</table>
	</td>
</tr>
<tr align="center" valign="top">
    <td height="42" colspan="2" background="images/bg2.gif">
<? echo $rs->Navigate(); ?>
	</td>
</tr>
<?
require("../footer.php");
?>
</table>
</body>
</html>
