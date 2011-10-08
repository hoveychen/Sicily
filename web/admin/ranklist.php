<?
require("../inc/global.inc.php");
require("./auth.inc.php");

$p = $_GET['p'];
if ($p == "")
	$p = 1;
$rs = new RecordSet($conn);
$rs->nPageSize = 16;
$rs->PageCount("SELECT count(*) FROM user");
$rs->SetPage($p);
$rs->dpQuery("SELECT uid, username, solved, submissions FROM user WHERE perm LIKE '%user%' ORDER BY solved DESC, submissions, uid");
?>
<html>
	<head>
		<title>Ranklist</title>
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
			<tr align="center" valign="top"> 
				<td height="440" colspan="2" background="images/bg2.gif">
					<br>
					<table width="85%" border="0" cellpadding="4" cellspacing="2">

						<tr align="center" bgcolor="#0071BD" class="white"> 

							<td width="10%" height="20">Rank</td>
							<td>User name</td>
							<td width="15%">Solved</td>
							<td width="15%">Submissions</td>
							<td width="15%">Reset password</td>
						</tr>
						<?
						$rank = ($p - 1) * $rs->nPageSize;
						while ($rs->MoveNext()) {
							$rank++;
							printf("<tr bgcolor=\"#%s\">\n", $rank % 2 ? "EEEEEE" : "FCFCFC");
							?>
							<td height=20 align="center"><? echo $rank; ?></td>
							<td>&nbsp;&nbsp;<a href="user.php?id=<? echo $rs->Fields["uid"]; ?>" class = "black"><? echo $rs->Fields["username"]; ?></a></td>
							<td align="center"><? echo $rs->Fields["solved"]; ?></td>
							<td align="center"><? echo $rs->Fields["submissions"]; ?></td>
							<td align="center">
								<a href="process.php?act=ResetPassword&uid=
								   <? echo $rs->Fields['uid'] ?>
		">Reset</a>
								</tr>
<? } ?>
					</table>
				</td>
			</tr>
			<tr align="center" valign="top">
				<td height="42" colspan="2" background="images/bg2.gif">
<? echo $rs->Navigate() ?>
				</td>
			</tr>
			<?
			require("../footer.php");
			?>
		</table>
	</body>
</html>
