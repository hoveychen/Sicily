<?
  require("include/db.php");
  require_once("include/global.php");
  session_start();
  if (isset($_GET['cid']) && is_numeric($_GET['cid']))
    $contest['cid'] = $_GET['cid'];
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
  function showMember($member)
  {?>
        <td align="center" nowrap="nowrap"><? echo $member["firstname"].' '.$member["lastname"]?></td>
<?php  }
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
	  require("navigation.php");
	?>
    </td>
    <td background="images/navigation_bg.gif">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td height="440" colspan="2" align="center" background="../zsucpc2006/images/bg2.gif">
	  <br>
      <table border="0" cellpadding="4" cellspacing="1">
        <tr align="center" bgcolor="#0071BD" class="white"> 
          <td height="20"><b>Team ID</b></td>
          <td width="150"><b>Team Name</b></td>
	  <td>Status</td></tr>
	  <tr align="center" bgcolor="#0071BD" class="white">
	  <td>Member 1</td>
	  <td>Member 2</td>
	  <td>Member 3</td>
	  <? if (false) { ?>
	    <td>Approve</td>
	    <td>Edit</td>
	    <td>Delete</td>
	  <? } ?>
        </tr>
		<?
		  $result = mysql_query("SELECT * FROM team WHERE cid = {$contest['cid']} ORDER BY tid ASC");
//		  print mysql_num_rows($result);
          $rank = 1;
		  for ($i = 0;; $i++, $rank++) {
		    if ($i % 2 == 0) echo "<tr bgcolor=\"#FCFCFC\">\n"; else echo "<tr bgcolor=\"#EEEEEE\">\n";
			$row = mysql_fetch_array($result);
			if ($row == null) break;
			$id = $row["tid"];
			$name = $row["enname"].'('.$row["cnname"].')';
			$status = $row["status"];
			$time = $row["date"];
		?>
        <td height=20 align="center"><a name="<? echo $id;?>"></a><? echo $rank;?></td>
        <td>&nbsp;&nbsp;<a href=reg_info.php?id=<? echo $id.">".$name;?></a></td>
        <td align="center"><? echo ($status)?"Approved":"New" ;?></td>
		<?php
			$members = mysql_query("SELECT * FROM contestant WHERE tid = $id");
			print "</tr><tr bgcolor=\"#FCFCFC\"><td colspan='3'><table width='100%'><tr>";
			for ($j = 0; $j < 3; $j++){
				$member = mysql_fetch_array($members, MYSQL_ASSOC);
				showMember($member);
			}
			print "</tr></table></td></tr><tr><td colspan='3'>-------------------------------------------------------------------------------</td></tr>";
		?>
		<? if (false) { ?>
		<td align="center"><a href="reg_app.php?id=<?echo $id?>&type=<?echo $type?>">Approve</a></td>
		<td align="center"><a href="reg_edit.php?id=<?echo $id?>">Edit</a></td>
		<td align="center"><a href="reg_del.php?id=<?echo $id?>&type=<?echo $type?>" onClick="return confirm('Are you sure to delete team <?php print $name;?>?')">Delete</a></td>
		<? } ?>
        </tr>
        <? }?>
        <tr><td><center><a href="reg.php" target="_blank">Register</a></center></td></tr>
      </table>
      </td>
  </tr>
  <?
  require("footer.php");
?>
</table>
</body>
</html>
