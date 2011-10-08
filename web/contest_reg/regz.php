<?
  require("include/db.php");
  require_once("include/global.php");
  session_start();
//  if (isset($_GET['cid']) && is_numeric($_GET['cid']))
//  	$contest['cid'] = (int)$_GET['cid'];
  $contest['cid'] = 2;
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
        <td align="center" nowrap="nowrap"><? echo $member["firstname"].' '.$member["lastname"].'('.$member["cnname"].')'?></td>
        <td align="center"><? echo $member["gender"]?"Female":"Male"?></td>
		<td align="center"><? echo $member["email"]?></td>
		<td align="center" width="200" nowrap="nowrap"><? echo $member["major"]."<br />(".$member["majorcn"].")"?></td>
		<td align="center"><? echo $member["grade"]?></td>
		<td align="center"><? echo $member["class"]?></td>
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
<!--      <h2><a href="vote.php"><font color="#FFFF00">Vote for your favorite team name</font></a></h2>  -->
      <table width="100%"  border="0" cellpadding="4" cellspacing="1">
        <tr align="center" bgcolor="#0071BD" class="white"> 
          <td height="20"><b>Team ID</b></td>
          <td width="150"><b>Team Name</b></td>
	  <td>Register Time</td>
	  <td>Status</td>
	  <td>Name</td>
	  <td>Gender</td>
	  <td>E-mail</td>
	  <td>Major</td>
	  <td>Grade</td>
	  <td>Class</td>
	  <? if (checkAdmin()) { ?>
	    <td>Approve</td>
	    <td>Edit</td>
	    <td>Delete</td>
	  <? } ?>
        </tr>
		<?
		  $result = mysql_query("SELECT * FROM team WHERE cid = {$contest['cid']} ORDER BY tid ASC");
//		  print mysql_num_rows($result);
          $rank = 0;
		  for ($i = 0;; $i++, $rank++) {
		    if ($i % 2 == 0) echo "<tr bgcolor=\"#FCFCFC\">\n"; else echo "<tr bgcolor=\"#EEEEEE\">\n";
			$row = mysql_fetch_array($result);
			if ($row == null) break;
			$id = $row["tid"];
			$name = $row["enname"].'('.$row["cnname"].')';
			$status = $row["status"];
			$time = $row["date"];
		?>
        <td height=20 rowspan="3" align="center"><a name="<? echo $id;?>"></a><? if ($rank > 0) echo $rank; else echo "0(sample)";?></td>
        <td rowspan="3">&nbsp;&nbsp;<a href=reg_info.php?id=<? echo $id.">".$name;?></a></td>
		<td rowspan="3" align="center"><? echo $time;?></td>
        <td rowspan="3" align="center"><? echo ($status)?"Approved":"New" ;?></td>
		<?php
			$members = mysql_query("SELECT * FROM contestant WHERE tid = $id");
			for ($j = 0; $j < 1; $j++){
			$member = mysql_fetch_array($members, MYSQL_ASSOC);
			showMember($member);
		}?>
		<? if (checkAdmin()) { ?>
		<td rowspan="3" align="center"><a href="reg_app.php?id=<?echo $id?>&type=<?echo $type?>">Approve</a></td>
		<td rowspan="3" align="center"><a href="reg_edit.php?id=<?echo $id?>">Edit</a></td>
		<td rowspan="3" align="center"><a href="reg_del.php?id=<?echo $id?>&type=<?echo $type?>" onClick="return confirm('Are you sure to delete team <?php print $name;?>?')">Delete</a></td>
		<? } ?>
        </tr>
			<?php 
			for ($j = 1; $j < 3; $j++){
		    	if ($i % 2 == 0) echo "<tr bgcolor=\"#FCFCFC\">\n"; else echo "<tr bgcolor=\"#EEEEEE\">\n";
				$member = mysql_fetch_array($members, MYSQL_ASSOC);
				showMember($member);
				print "</tr>";
			}
			?>
        <? }?>
        <tr><td><center><a href="reg.php" target="_blank"><font color="#FFFFFF">Register</font></a></center></td></tr>
<!--				<tr>
					<td align="center">
						<a href="vote.php"><font color="#FFFFFF">Vote for your favorite team name</font></a>
					</td>
				</tr>   -->
      </table>
      </td>
  </tr>
  <?
  require("footer.php");
?>
</table>
</body>
</html>
