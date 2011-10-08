<?php
require("./navigation.php");
if (!is_admins())
	error("Forbidden");
?>

<?
if (isset($_GET["cid"]))
	$cid = $_GET["cid"]; else
	$cid = "";
if (isset($_GET["p"]))
	$p = $_GET["p"]; else
	$p = 1;
if (isset($_GET["index"]))
	$index = $_GET["index"]; else
	$index = "";
if (isset($_GET["orderby"]))
	$orderby = $_GET["orderby"]; else
	$orderby = "";

$perm = 'user';
//mysql_query("SET NAMES GBK");
$rs = new RecordSet($conn);

if ($logged) {
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
$rs->nPageSize = 100;
if ($cid) {
	if (!is_contest_accessible($cid))
		error("You can't access to this contest");

	$rs->PageCount("SELECT count(*) FROM contest_problems WHERE contest_problems.cid='$cid'");
	$rs->SetPage($p);
	$rs->dpQuery("SELECT cpid AS pid, title, accepted, submissions, avail, special_judge FROM contest_problems LEFT JOIN problems ON problems.pid = contest_problems.pid WHERE contest_problems.cid='$cid' ORDER BY pid");
}else {

	if ($index == "normal") {
		$rs->PageCount("SELECT count(*) FROM problems WHERE author IS NULL");
		$rs->SetPage($p);
		$rs->dpQuery("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, submissions, avail, special_judge, rate_tot, rate_count FROM problems LEFT JOIN contests ON contests.cid = problems.cid WHERE author IS NULL ORDER BY pid");
	} else if ($index == "author") {
		$rs->PageCount("SELECT count(*) FROM problems WHERE author IS NOT NULL");
		$rs->SetPage($p);
		$rs->dpQuery("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, submissions, avail, special_judge, rate_tot, rate_count FROM problems LEFT JOIN contests ON contests.cid = problems.cid WHERE author IS NOT NULL ORDER BY pid");
	} else if ($index == "accepted") {
		$rs->PageCount("SELECT count(*) FROM problems WHERE EXISTS( SELECT * FROM status AS s WHERE problems.pid=s.pid AND s.uid='$login_uid' AND s.status='Accepted')");
		$rs->SetPage($p);
		$rs->dpQuery("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, submissions, avail, special_judge, rate_tot, rate_count FROM problems LEFT JOIN contests ON contests.cid = problems.cid WHERE EXISTS( SELECT * FROM status AS s WHERE problems.pid=s.pid AND s.uid='$login_uid' AND s.status='Accepted') ORDER BY pid");
	} else if ($index == "wrong") {
		$rs->PageCount("SELECT count(*) FROM problems WHERE NOT EXISTS( SELECT * FROM status AS s WHERE problems.pid=s.pid AND s.uid='$login_uid' AND s.status='Accepted')");
		$rs->SetPage($p);
		$rs->dpQuery("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, submissions, avail, special_judge, rate_tot, rate_count FROM problems LEFT JOIN contests ON contests.cid = problems.cid WHERE NOT EXISTS( SELECT * FROM status AS s WHERE problems.pid=s.pid AND s.uid='$login_uid' AND s.status='Accepted') ORDER BY pid");
	} else {
		$rs->Query("SELECT max(pid) FROM problems");
		$rs->MoveNext();
		$pageCount = (int) ($rs->Fields[0] / 100) - 9;
		$pageBegin = ($p + 9) * 100;
		$pageEnd = ($p + 10) * 100;
		if (!is_admins()) {
			$avail_restrict = " AND avail = 1";
		} else {
			$avail_restrict = "";
		}
		$rs->Query("SELECT problems.cid AS cid, pid, problems.title AS title, accepted, submissions, avail, special_judge, rate_tot, rate_count FROM problems LEFT JOIN contests ON contests.cid = problems.cid WHERE pid>=" . $pageBegin . " AND pid<" . $pageEnd . $avail_restrict . " ORDER BY pid");
	}
}
$ap = array();
$arrayN = 0;
while ($rs->MoveNext()) {
	if (!$cid && $rs->Fields['cid'] != 0 && !is_contest_accessible($rs->Fields['cid']))
		continue;
	$ap[$arrayN] = $rs->Fields;
	$arrayN++;
}

function cmp1($a, $b) {
	if ($a['accepted'] == $b['accepted'])
		return $a['pid'] - $b['pid'];
	return $b['accepted'] - $a['accepted'];
}

function cmp2($a, $b) {
	if ($a['submissions'] == $b['submissions'])
		return $a['pid'] - $b['pid'];
	return $b['submissions'] - $a['submissions'];
}

function cmp3($a, $b) {
	if ($a['submissions'] > 0)
		$ar = $a['accepted'] / $a['submissions'] * 100;
	else
		$ar = -1;
	if ($b['submissions'] > 0)
		$br = $b['accepted'] / $b['submissions'] * 100;
	else
		$br = -1;
	if ($ar == $br)
		return $a['pid'] - $b['pid'];
	return $br - $ar;
}

function cmp4($a, $b) {
	if ($a['rate_count'] > 0)
		$ar = $a['rate_tot'] / $a['rate_count'] * 100;
	else
		$ar = -1;
	if ($b['rate_count'] > 0)
		$br = $b['rate_tot'] / $b['rate_count'] * 100;
	else
		$br = -1;
	if ($ar == $br)
		return $a['pid'] - $b['pid'];
	return $br - $ar;
}

if ($orderby == "accepted") {
	usort($ap, cmp1);
} else if ($orderby == "submissions") {
	usort($ap, cmp2);
} else if ($orderby == "ratio") {
	usort($ap, cmp3);
} else if ($orderby == "rating") {
	usort($ap, cmp4);
}

function Navigate() {
	global $p, $pageCount;
	$buf = "<a href={$_SERVER['PHP_SELF']}?p=%d class=\"black\"><img src=\"images/%s1.gif\" width=\"120\" height=\"35\" border=\"0\"></a>";

	$buf2 = "<img src=\"images/%s0.gif\" width=\"120\" height=\"35\" border=\"0\">";

	echo "<div class='vol_navigate'>";
	if ($p > 1) {
		echo sprintf($buf, $p - 1, "prev");
	} else {
		echo sprintf($buf2, "prev");
	}

	for ($i = 1; $i <= $pageCount; ++$i) {
		echo "<a href='problems.php?p=$i'>&nbsp;&nbsp;$i&nbsp;&nbsp;</a>&nbsp;";
	}

	if ($p < $pageCount) {
		echo sprintf($buf, $p + 1, "next");
	} else {
		echo sprintf($buf2, "next");
	}
	echo "</div>";
}
?>


<?
if (!$cid) {
	?>
	<!-- fixed page link 
	<div id="volume">
	Volume &nbsp;
	<a href="problems.php?index=accepted">Solved</a>&nbsp;&nbsp;
	<a href="problems.php?index=wrong">UnSolved</a>
	</div>
	<br />
	end -->

	<?
} else {
	$rs->dpQuery("SELECT title,starttime,during FROM contests WHERE cid=$cid");
	if ($rs->MoveNext()) {
		// clipped from contests.php
		$time = $rs->Fields["starttime"];
		$title = $rs->Fields["title"];
		$during = $rs->Fields["during"];
		$now = time();
		printf("<div id=\"contest_title\">%s</div>", $title);
		$timestamp = strtotime($time);
		$start = $timestamp;
		sscanf($during, "%d:%d:%d", $h, $m, $s);
		$end = $timestamp + $h * 3600 + $m * 60 + $s;
		echo "<div id=\"contest_info\">";
		if ($now < $start) {
			printf("Contest starts at %s, %02d:%02d:%02d left.", date('l, F dS Y h:i:s A', $timestamp), ($start - $now) / 3600, ($start - $now) % 3600 / 60, ($start - $now) % 60);
		} else if ($now < $end) {
			printf("Contest is running, %02d:%02d:%02d left.", ($end - $now) / 3600, ($end - $now) % 3600 / 60, ($end - $now) % 60);
		} else {
			echo "<font color=green>Contest is finished.</font>";
		}
		echo "</div>";
	}
}
if (!$cid) {
	if (!$index) {
		Navigate();
	} else if ($index != "author" && $index != "normal") {
		echo $rs->Navigate();
	}
}
?>


<table class="tblcontainer" width="100%" border="0" cellpadding="4" cellspacing="2">

    <tr align="center" > 

        <td width="6%" height="20">Solved</td>
        <td width="6%" ><a href=problems.php?p=<? echo $p ?>&cid=<? echo $cid;
?>&orderby=id<?
if ($index == "normal" || $index == "author" || $index == "accepted" || $index == "wrong")
	echo "&index=$index";
?> class=white>ID</a></td>
        <td align="left">Title</td>
        <td width="10%"><a href=problems.php?p=<? echo $p ?>&cid=<? echo $cid;
?>&orderby=accepted<?
if ($index == "normal" || $index == "author" || $index == "accepted" || $index == "wrong")
	echo "&index=$index";
?> class=white>Accepted</a></td>
        <td width="10%"><a href=problems.php?p=<? echo $p ?>&cid=<? echo $cid; ?>&orderby=submissions<?
if ($index == "normal" || $index == "author" || $index == "accepted" || $index == "wrong")
	echo "&index=$index";
?> class=white>Submissions</a></td>
        <td width="10%"><a href=problems.php?p=<? echo $p ?>&cid=<? echo $cid; ?>&orderby=ratio<?
if ($index == "normal" || $index == "author" || $index == "accepted" || $index == "wrong")
	echo "&index=$index";
?> class=white>Ratio</a></td>
						   <?
						   if (!$cid) {
							   echo "<td width=\"15%\">";
							   echo "<a href=problems.php?p=$p&cid=$cid&orderby=rating";
							   if ($index == "normal" || $index == "author" || $index == "accepted" || $index == "wrong")
								   echo "&index=$index";
							   echo " class=white>";
							   echo "Rating";
							   echo "</a>";
							   echo "</td>";
						   }
						   ?>
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
								   if ($logged) {

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
										   $query_pid = $ap[$i]['pid'];
										   if (isset($list[$query_pid]) && $list[$query_pid] == 1)
											   echo "<img src=\"images/not_yet.gif\" width=\"20\" height=\"20\">";
										   if (isset($list[$query_pid]) && $list[$query_pid] == 2)
											   echo "<img src=\"images/yes.gif\" width=\"20\" height=\"20\">";
									   }
								   }
								   ?>
			</td>
			<td align="center"><? echo $pid;
								   ?></td>
			<td align="left">
				<a href="show_problem.php?pid=<? echo $ap[$i]['pid'];
		if ($cid)
			echo "&cid=$cid"; ?>" class="black">
				<? //printf("<div id=\"contest_title\">%s</div>", $ap[$i]['title'])?>
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
				if (!$cid)
					echo "<a href=problem_status.php?pid=" . $ap[$i]['pid'] . ">";
				echo $ap[$i]['accepted'];
				?>
				</a></td>
			<td align="center"><? echo $ap[$i]['submissions'] ?></td>
			<td align="center"><?
				if ($ap[$i]['submissions'])
					printf("%.2f %%", $ap[$i]['accepted'] * 100 / $ap[$i]['submissions']);
				else
					printf("N/A");
				?></td>
					<?
					if (!$cid) {
						echo "<td align=\"center\">";
						if ($ap[$i]['rate_count'] == 0) {
							echo "unrated";
						} else {
							printf("%.1lf/5(%d votes)", $ap[$i]["rate_tot"] / $ap[$i]["rate_count"], $ap[$i]["rate_count"]);
						}
						echo "</td>";
					}
					?>
		</tr>
			<?
			$i++;
		} while ($i < $arrayN);
	} else {
		?>
	No Problem found!
	<? }
	?>
</table>


<?
if (!$cid) {
	if (!$index) {
		Navigate();
	} else {
		echo $rs->Navigate();
	}
}
?>

<?php
require("./footer.php");
?>
