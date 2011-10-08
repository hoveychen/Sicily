<?php
include_once("inc/global.inc.php");
if (!is_admins())
	error("forbidden");

$login_uid = $_COOKIE["ex_user_id"];
$keys = $_GET["keys"];

//mysql_query("SET NAMES UTF8");

$perm = 'user';

$rs = new RecordSet($conn);

if (isset($login_uid)) {
	$user = new UserTbl();
	$user->Get($login_uid);
	$list = array();
	$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid'");
	while ($rs->MoveNext()) {
		$list[$rs->Fields["pid"]] = 1;
	}
	$rs->Query("SELECT DISTINCT pid FROM status WHERE uid='$login_uid' AND status='Accepted'");
	while ($rs->MoveNext()) {
		$list[$rs->Fields["pid"]] = 2;
	}
}

$rs = new RecordSet($conn);
if (!is_numeric($keys)) {
	$queryString = "";
	$first = 1;
	foreach (split(" ", $keys) as $word) {
		$sWord = addslashes($word);
		if ($first)
			$first = 0; else
			$queryString=$queryString . " AND ";
		$queryString = $queryString . "title LIKE '%$sWord%'";
		$queryString = $queryString . " AND avail =1";
	}

	$rs->Query("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, avail, special_judge, author FROM problems WHERE $queryString ORDER BY pid");
	//	echo "SELECT problems.cid AS cid, pid, problems.title AS title, accepted, avail, special_judge, author FROM problems WHERE $queryString ORDER BY pid";
	$ap = array();
	$arrayN = 0;
	while ($rs->MoveNext()) {
		if ($rs->Fields['cid'] && (!is_contest_accessible($rs->Fields['cid']) || !is_contest_ended($rs->Fields['cid'])))
			continue;

		$ap[$arrayN] = $rs->Fields;
		$arrayN++;
	}
}
else {
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "show_problem.php?pid=$keys";
	header("Location: http://$host$uri/$extra");
	exit;
}

require("./navigation.php");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">


    <tr align="center" valign="top">
        <td height="42" colspan="2" background="images/bg2.gif">

        </td>
    </tr>

    <tr valign="top"> 
        <td height="440" colspan="2" align="center" background="images/bg2.gif">

			<table class="tblcontainer" width="100%" border="0" cellpadding="4" cellspacing="2">

				<tr align="center"> 

					<td width="6%" height="20">Solved</td>
					<td width="6%">ID</td>
					<td>Title</td>
					<td width="10%">Accepted</td>
					<td width="30%">Source</a></td>
				</tr>
<?
if ($arrayN > 0) {
	$i = 0;
	do {
		printf("<tr>");
		$pid = $ap[$i]['pid'];
		?>
		                <td height=25 align="center">
						<?
						if (isset($_SESSION["ex_user_id"]) && $_SESSION["ex_user_id"] == $login_uid) {

							if ($cid) {
								$rs1 = new RecordSet($conn);
								$rs1->Query("SELECT accepted FROM ranklist WHERE cid='$cid' AND uid='$login_uid' AND pid='$pid'");
								if ($rs1->MoveNext()) {
									if ($rs1->Fields['accepted'] == 1)
										echo "<img src=\"images/yes.gif\" width=\"20\" height=\"20\">";
									else
										echo "<img src=\"images/not_yet.gif\" width=\"20\" height=\"20\">";
								}
							}
							else {
								if ($list[$ap[$i]['pid']] == 1)
									echo "<img src=\"images/not_yet.gif\" width=\"20\" height=\"20\">";
								if ($list[$ap[$i]['pid']] == 2)
									echo "<img src=\"images/yes.gif\" width=\"20\" height=\"20\">";
							}
						}
						?>
		                </td>
		                <td align="center">
		<? echo $pid; ?>
		                </td>
		                <td>
		                    <a href="show_problem.php?pid=<?
		echo $ap[$i]['pid'];
		if ($cid)
			echo "&cid=$cid";
		?>" class="black">
								<? echo $ap[$i]['title'] ?>
								<?
								if ($ap[$i]['avail'] == 0)
									echo "<font color='blue'>[Judge not ready]";
								if ($ap[$i]['special_judge'] == "true")
									echo "<font color='blue'>[Special judge]";
								?>
		                    </a></td>
		                <td align="center">
							<?
							echo $ap[$i]['accepted'];
							?>
		                    </a></td>
		                <td align="center">
							<?
							echo $ap[$i]['author'];
							?>
		                    </a></td>



			</tr>
			<?
			$i++;
		} while ($i < $arrayN);
	}else {
		?>
		No Problem found!
<? } ?>
</table>
</td>
</tr>

</table>

<?php
require("./footer.php");
?>
