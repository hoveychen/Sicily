<?
  require("include/db.php");
  require_once("include/global.php");
  session_start();
  if (isset($_GET['cid']) && is_numeric($_GET['cid']))
  	$contest['cid'] = (int)$_GET['cid'];
  if ($contest == NULL) die("No contest available!");
  $page = @$_GET["page"];
  $cid = @$_GET["cid"];
  $type = "zsucpc";
  $user_id = @$_COOKIE["ex_user_id"];
  if (isset($user_id)) {
//    $result = mysql_query("SELECT count(*) FROM user WHERE id='$user_id' AND perm LIKE '%admin%'");
//    $row = mysql_fetch_row($result);
//    if ($user_id == 10)
//    $admin = 1;
	$admin = 0;
  }
  function showMember($member)
  {
    global $avail;
    ?>
<?php if ($avail["firstname"] || $avail["lastname"] || $avail["cnname"]) { ?>
 <td align="center" nowrap="nowrap">
    <?php
    if ($avail["firstname"] || $avail["lastname"])
      echo $member["firstname"].' '.$member["lastname"];
    if ($avail["cnname"]) {
      if ($avail["firstname"] || $avail["lastname"])
        echo "(";
      echo $member["cnname"];
      if ($avail["firstname"] || $avail["lastname"])
        echo ')';
    }?>
 </td>
<?php }?>
<?php if ($avail["gender"]) {?>
<td align="center"><? echo $member["gender"]?"<span style=\"font-weight:bold;color:red;\">Female</span>":"Male"?></td>
<?php }?>
<?php if ($avail["email"]) {?>
		<td align="center"><? echo $member["email"]?></td>
<?php }?>
<?php if ($avail["major"] || $avail["majorcn"]) {?>
		<td align="center" width="200" nowrap="nowrap">
      <?php
      if ($avail["major"])
        echo $member["major"];
      if ($avail["majorcn"]) {
        if ($avail["major"])
          echo "<br />(";
        echo $member["majorcn"];
        if ($avail["major"])
          echo ")";
      }
      ?>
      </td>
<?php }?>
<?php if ($avail["grade"]) {?>
		<td align="center"><? echo $member["grade"]?></td>
<? }?>
<?php if ($avail["class"]) {?>
		<td align="center"><? echo $member["class"]?></td>
<?php }?>
<?php  }
?>
<html>
<head>
<title>Status</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body color="#FFFFFF" bgcolor="#005DA9" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?php require("navigation.php");?>
  <div id="content">
    <?php if ($vote) {?>
    <h2><a href="vote.php"><font color="#FFFF00">Vote for your favorite team name</font></a></h2>
    <?php }?>
    <table border="0" cellpadding="4" cellspacing="1">
      <tr align="center" bgcolor="#0071BD" class="white"> 
        <td height="20"><b>Team ID</b></td>
        <td width="100"><b>Team Name</b></td>
        <td width="50"><b>Team Username</b></td>
	<td>Register Time</td>
	<td>Status</td>
	<?php if ($avail["firstname"] || $avail["lastname"] || $avail["cnname"]) { ?>
	<td>Name</td>
	<?php }?>
	<?php if ($avail["gender"]) {?>
	<td>Gender</td>
	<?php }?>
	<?php if ($avail["email"]) {?>
	<td>E-mail</td>
	<?php }?>
	<?php if ($avail["major"] || $avail["majorcn"]) {?>
	<td>Major</td>
	<?php }?>
	<?php if ($avail["grade"]) {?>
	<td>Grade</td>
	<?php }?>
	<?php if ($avail["class"]) {?>
	<td>Class</td>
	<?php }?>
	<? if (checkAdmin()) { ?>
	<td>Approve</td>
	<td>Reset Password</td>
	<? } ?>
	<? 
    $id = @$_GET['id'];
    if (checkTeam($id)) { ?>
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
			$name = "";
                        if ($avail["teamen"])
                          $name=$name.$row["enname"];
                        if ($avail["teamcn"]) {
                          if ($avail["teamen"])
                            $name .= "(";
                          $name .= $row["cnname"];
                          if ($avail["teamen"])
                            $name .= ')';
                        }
			$status = $row["status"];
			$time = $row["date"];
            $username = $row['id'];
	 ?>
      <td height=20 rowspan="3" align="center"><a name="<? echo $id;?>"></a><? if ($rank > 0) echo $rank; else echo "0(sample)";?></td>
      <td rowspan="3">&nbsp;&nbsp;<a href="reg_info.php?id=<? echo $id;?>"><?php print "$name";?></a></td>
      <td rowspan="3" align="center"><? echo $username;?></td>
      <td rowspan="3" align="center"><? echo $time;?></td>
      <td rowspan="3" align="center">
<?
  echo "<span style=\"color:";
  echo ($status)?"green":"red";
  echo "\">";
  echo ($status)?"Approved":"New" ;
  echo "</span>"
?>
      </td>
      <?php
			$members = mysql_query("SELECT * FROM contestant WHERE tid = $id order by uid asc");
			for ($j = 0; $j < 1; $j++){
			$member = mysql_fetch_array($members, MYSQL_ASSOC);
			showMember($member);
	} ?>
	 <?php if (checkAdmin()) { ?>
      <td rowspan="3" align="center"><a href="reg_app.php?id=<?php echo $id;?>&type=<?echo $type;?>">Approve</a></td>
      <td rowspan="3" align="center"><a href="password.php?id=<?php echo $id;?>">Reset password</a></td>
               <?php } ?>
             <?php if (checkTeam($id)) {?>
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
</table>
</div>
<?require("footer.php");?>
</body>
</html>
