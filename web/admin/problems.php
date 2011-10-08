<?
require("./navigation.php");
$cid = @$_GET["cid"];

if (isset($login_uid)) {
	$user = new UserTbl();
	$user->Get($login_uid);
	$list = $user->detail['list'];
}
$rs = new RecordSet($conn);
$rs->nPageSize = 20;
if ($cid) {
	$contest = new ContestsTbl();
	if ($contest->Get($cid) < 0)
		error("No such contest ID");
	if (!is_admins()) {
		$now = time();
		if ($now < strtotime($contest->detail['starttime']))
			error("The contest is not started");
	}
	$rs->PageCount("SELECT count(*) FROM contest_problems");
	$rs->SetPage($p);
	$rs->dpQuery("SELECT pid, title, accepted, submissions, avail FROM contest_problems WHERE cid='$cid'");
} else {
	$rs->PageCount("SELECT count(*) FROM problems");
	$rs->SetPage($p);
	$rs->dpQuery("SELECT pid, title, accepted, submissions, avail FROM problems");
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td width="770">
		</td>
		<td background="images/navigation_bg.gif">&nbsp;</td>
	</tr>
	<tr valign="top"> 
		<td height="440" colspan="2" align="center" background="images/bg2.gif">
			<br>
			<table width="95%" border="0" cellpadding="4" cellspacing="2">

				<tr align="center" bgcolor="#0071BD" class="white"> 

					<td width="6%" height="20">Solved</td>
					<td width="6%">ID</a></td>
					<td>Title</td>
					<td width="10%">Accepted</td>
					<td width="10%">Submissions</td>
					<td width="10%">Ratio</td>
					<td width="10%">Edit</td>
					<td width="10%">Copy</td>
				</tr>
				<?
				if ($rs->MoveNext()) {
					$i = 0;
					do {
						$i++;
						printf("<tr bgcolor=\"#%s\">\n", ($i % 2) ? "EEEEEE" : "FCFCFC");
						$pid = $rs->Fields['pid'];
						?>
						<td height=25 align="center">
							<?
							if ($cid) {
								$rs1 = new RecordSet($conn);
								$rs1->Query("SELECT accepted FROM ranklist WHERE cid='$cid' AND uid='$login_uid' AND pid='$pid'");
								if ($rs1->MoveNext()) {
									if ($rs1->Fields['accepted'] == 1)
										echo "<img src=\"images/yes.gif\" width=\"20\" height=\"20\">";
									else
										echo "<img src=\"images/no.gif\" width=\"20\" height=\"20\">";
								}
							}
//	if ($rs->Fields['list'][$rs->Fields['pid'] - 1000] == 1) echo "<img src=\"images/no.gif\" width=\"20\" height=\"20\">";
//	if ($rs->Fields['list'][$rs->Fields['pid'] - 1000] == 2) echo "<img src=\"images/yes.gif\" width=\"20\" height=\"20\">";
							?>
						</td>
						<td align="center"><? echo $pid; ?></td>
						<td>
							<a href="show_problem.php?pid=<? echo $rs->Fields['pid'];
							if ($cid)
								echo "&cid=$cid"; ?>" class="black"><? echo $rs->Fields['title'] ?>
		<?
		if ($rs->Fields['avail'] == 0)
			echo "[Judge not ready]";
		?>
							</a></td>
						<td align="center"><? echo $rs->Fields['accepted'] ?></td>
						<td align="center"><? echo $rs->Fields['submissions'] ?></td>
						<td align="center"><? if ($rs->Fields['submissions'])
						printf("%.2f %%", $rs->Fields['accepted'] * 100 / $rs->Fields['submissions']); else
						printf("N/A"); ?></td>
						<td align="center"><a href="editproblem.php?pid=
							<?
							echo $rs->Fields['pid'];
							if ($cid)
								echo "&cid=" . $cid;
							?>
			">Edit</a></td>
						<td align="center"><a href="process.php?act=CopyProblem&pid=
			<?
			echo $rs->Fields['pid'];
			if ($cid)
				echo "&cid=" . $cid;
			?>
			">Copy</a></td>
			</tr>
	<? } while ($rs->MoveNext());
} else { ?>
			No Problem found!
<? } ?>
</table>
</td>
</tr>
<tr align="center" valign="top">
    <td height="42" colspan="2" background="images/bg2.gif">
<? echo $rs->Navigate(); ?>
	</td>
</tr>
</table>
<?
require("../footer.php");
?>
