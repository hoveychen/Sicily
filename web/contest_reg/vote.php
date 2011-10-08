<?
  require("include/db.php");
  require_once("include/global.php");
  session_start();
  if (isset($_GET['cid']) && is_numeric($_GET['cid']))
  	$contest['cid'] = (int)$_GET['cid'];
  if ($contest == NULL) die("No contest available!");
  $page = $_GET["page"];
  $cid = $_GET["cid"];
  $type = "zsucpc";
  $user_id = $HTTP_COOKIE_VARS["ex_user_id"];
  if (isset($user_id)) {
//    $result = mysql_query("SELECT count(*) FROM user WHERE id='$user_id' AND perm LIKE '%admin%'");
//    $row = mysql_fetch_row($result);
//    if ($user_id == 10)
//    $admin = 1;
	$admin = 0;
  }
  $team_table = "team";
  $vote_table = "vote";
  $vote_record_table = "vote_record";
  $vote_detail_table = "vote_detail";

  // delete the vote info of all the out-date teams
  $sql = "DELETE FROM $vote_table WHERE NOT EXISTS ".
         "(SELECT * FROM $team_table WHERE $team_table.tid = vote.tid AND status = 1 AND cid = {$contest['cid']})";
  if (!mysql_query($sql)) die("0 An Error was encountered, please contact the administrators");

  // initiate the vote info for teams if it didn't exist before
  $sql = "INSERT INTO $vote_table (tid, score) ".
         "(SELECT $team_table.tid, 0 FROM $team_table WHERE status = 1 AND cid = {$contest['cid']} AND NOT EXISTS ".
	 "(SELECT * FROM $vote_table WHERE $vote_table.tid = $team_table.tid))";
  if (!mysql_query($sql)) die("An Error was encountered, please contact the administrators");

  // deal with the voting request if any
  if (isset($_GET['vote_tid']) && is_numeric($_GET['vote_tid'])) {
    // check the ip/day constraint
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = "SELECT * FROM $vote_record_table WHERE last_time >= CURDATE() AND ip = '$ip'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) {
      echo "<script>alert('每个ip每天只能投票一次，感谢您的参与');</script>";
      echo "<script>location='vote.php';</script>";
    } else {
      // vote
      $vote_tid = $_GET['vote_tid'];
      $sql = "UPDATE $vote_table SET score = score + 1 WHERE tid = $vote_tid";
      if (!mysql_query($sql)) die("1 An Error was encountered, please contact the administrators");

      // update the ip record
      $sql = "DELETE FROM $vote_record_table WHERE ip = '$ip'";
      if (!mysql_query($sql)) die("2 An Error was encountered, please contact the administrators");
      $sql = "INSERT INTO $vote_record_table (ip, last_time) values('$ip', CURDATE())";
      if (!mysql_query($sql)) die("3 An Error was encountered, please contact the administrators");
      $sql = "INSERT INTO $vote_detail_table (ip, vote_time) values('$ip', NOW())";
      if (!mysql_query($sql)) die("4 An Error was encountered, please contact the administrators");

      echo "<script>alert('投票成功，感谢您的参与');</script>";
      echo "<script>location='vote.php';</script>";
    }
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
    <td>
	<?
	  require("navigation.php");
	?>
    </td>
   <!-- <td background="images/navigation_bg.gif">&nbsp;</td>-->
  </tr>
  <tr valign="top"> 
    <td height="440" align="center" background="../zsucpc2006/images/bg2.gif">
	  <br>
      <table width="80%"  border="0" cellpadding="4" cellspacing="1">
        <tr align="center" bgcolor="#0071BD" class="white"> 
          <td width="200"><b>Rank</b></td>
          <td><b>Team Name</b></td>
	  <td width="200"><b>Current Scores</b></td>
	  <td width="200"><b>Vote for it</b></td>
	  <? if (checkAdmin()) { ?>
	    <td><b>Edit</b></td>
	  <? } ?>
        </tr>
	<?
	$sql = "SELECT T.cid, T.tid, T.enname, T.cnname, T.status, V.score FROM $team_table AS T, $vote_table AS V ".
	       "WHERE T.cid = {$contest['cid']} AND T.status = 1 AND T.tid = V.tid ".
	       "ORDER BY V.score DESC";
	$result = mysql_query($sql);
	for ($i = 1; $i <= mysql_num_rows($result); $i++) {
	  $row = mysql_fetch_array($result);
	  $tid = $row["tid"];
	  $name = $row["enname"]."(".$row["cnname"].")";
	  $score = $row["score"];
	  if ($i % 2 == 0) echo "<tr align=\"center\" bgcolor=\"#FCFCFC\">"; else echo "<tr align=\"center\" bgcolor=\"#EEEEEE\">";
	?>
	    <td><?=$i?></td>
	    <td><a href="reg_info.php?id=<?=$tid?>"><?=$name?></a></td>
	    <td><?=$score?></td>
	    <td><a href="vote.php?vote_tid=<?=$tid?>">vote</a></td>
	  </tr>
	<?
	}
	?>
      </table>
      </td>
      <tr align="center"><td><a href="reg.php"><font size="4px" color="#FFFFFF">Register</font></a></h4></td></tr>
      <tr align="center"><td><a href="reg_status.php"><font size="4px" color="#FFFFFF">Registration Status</font></a></td></tr>
  </tr>
  <?
  require("footer.php");
?>
</table>
</body>
</html>
