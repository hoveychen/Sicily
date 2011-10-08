<?php
require_once("include/config.php");
require_once("include/global.php");
session_start();
?>
<html>
<head>
<title>Info</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?
  require("navigation.php");
$type = @$_GET["type"];
$id = $_GET["id"];
if (!$id) {
  error("Bad Team ID");
 }
require("include/db.php");
$sql = "SELECT team.tid AS tid, enname, team.cnname AS cnname, contestant.cnname AS leader FROM team LEFT JOIN contestant ON contestant.uid = team.leader WHERE team.tid='$id'";
$result = mysql_query($sql);
//print $sql;
$row = mysql_fetch_array($result);
$tid = @$row['teamid'];
	?>
<div id="content">
  <table class="inner_content" border="0" cellpadding="4" cellspacing="2">
    <tr bgcolor="#0071BD" class="white"> 
      <td height="20" colspan="2" align="center" class="title">Register Info</td>
    </tr>
    <tr class="color2"> 
      <td width="130" height="20" align="right" class="fieldname">Team name </td>
      <td width="220" align="left" class="fieldcontent"><? echo "{$row['enname']}({$row['cnname']})"; ?> </td>
    </tr>
    <tr class="color1"> 
      <td height="20" align="right" class="fieldname">Leader</td>
      <td align="left" class="fieldcontent"><? echo $row["leader"]; ?></td>
    </tr>
<?php
$sql = "SELECT firstname, lastname, cnname, title, location, country, email, 
					phone, gender, institution, degree, major, majorcn, grade, class,
					admitdate, graduatedate, birthday, tshirt FROM contestant WHERE tid='$id' order by uid asc";
$result = mysql_query($sql);
$number = array("zero", "First", "Second", "Third");
for ($i = 1; $i <= 3; $i++) {
	$row = mysql_fetch_array($result);
?>
   <tr height="20">
     <td height="20" colspan="2" align="center" class="title">The <?php echo $number[$i]?> Contestant</td>
   </tr>
<?php if ($avail["cnname"]) {?>
    <tr class="color2"> 
      <td height="20" align="right" class="fieldname">Chinese Name</td>
      <td align="left" class="fieldcontent"><? echo $row["cnname"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["enname"]) {?>
    <tr class="color1"> 
      <td height="20" align="right" class="fieldname">English Name</td>
      <td align="left" class="fieldcontent"><? if ($avail["title"]) {echo $row["title"].' ';} echo $row["firstname"].' '.$row["lastname"]; ?>                  </td>
    </tr>
<?php }?>
<?php if ($avail["gender"]) {?>
    <tr class="color2"> 
      <td height="20" align="right" class="fieldname">Gender</td>
      <td align="left" class="fieldcontent"><? echo $row["gender"]?"Female":"Male"; ?></td>
    </tr>
<?php }?>
<?php if ($avail["email"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">Email Address </td>
      <td align="left" class="fieldcontent"><?
      if (checkTeam($_GET["id"]))
        echo $row["email"];
      else
        echo "****@****.***";
          ?></td>
    </tr>
<?php }?>
<?php if ($avail["phone"]) {?>
    <tr class="color2">
      <td height="20" align="right" class="fieldname">Telephone</td>
      <td align="left" class="fieldcontent"><?
         if (checkTeam($_GET["id"]))
           echo $row["phone"];
         else
           echo "***********";
            ?></td>
    </tr>
<?php }?>
<?php if ($avail["tshirt"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">T-Shirt Size</td>
      <td align="left" class="fieldcontent"><? echo $row["tshirt"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["institution"]) {?>
    <tr class="color2">
      <td height="20" align="right" class="fieldname">University</td>
      <td align="left" class="fieldcontent"><? echo $row["institution"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["location"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">Address</td>
      <td align="left" class="fieldcontent"><? echo $row["location"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["country"]) {?>
    <tr class="color2">
      <td height="20" align="right" class="fieldname">Country</td>
      <td align="left" class="fieldcontent"><? echo $row["country"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["degree"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">Degree Pursued </td>
      <td align="left" class="fieldcontent"><? echo $row["degree"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["major"] || $avail["majorcn"]) {?>
    <tr class="color2">
      <td height="20" align="right" class="fieldname">Major</td>
      <td align="left" class="fieldcontent">
      <?php if ($avail["major"]) echo "{$row['major']}";
          if ($avail["majorcn"]) echo "({$row['majorcn']})"; ?></td>
    </tr>
<?php }?>
<?php if ($avail["grade"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">Grade</td>
      <td align="left" class="fieldcontent"><? echo $row["grade"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["class"]) {?>
    <tr class="color2">
      <td height="20" align="right" class="fieldname">Class</td>
      <td align="left" class="fieldcontent"><? echo $row["class"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["admitdate"]) {?>
    <tr class="color1">
      <td height="20" align="right" class="fieldname">Began Degree </td>
      <td align="left" class="fieldcontent"><? echo $row["admitdate"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["graduatedate"]) {?>
    <tr class="color2"> 
      <td height="20" align="right" class="fieldname">Gradudation</td>
      <td align="left" class="fieldcontent"><? echo $row["graduatedate"]; ?></td>
    </tr>
<?php }?>
<?php if ($avail["birthday"]) {?>
    <tr class="color1"> 
      <td height="20" align="right" class="fieldname">Birthday</td>
      <td align="left" class="fieldcontent"><?  echo $row["birthday"]; ?></td>
    </tr>
<?php }?>
<?php 				}?>
    <tr>
      <td colspan="2" align="center"><a href="reg_status.php#<?php print $id?>">Status</a>
  <?php if (checkAdmin()) print '<a href="reg_app.php?id='.$id.'">Approve</a>'; ?>
<?php if (checkTeam($_GET["id"])) print '<a href="reg_edit.php?id='.$id.'">Edit</a> <a href="reg_del.php?id='.$id.'" onclick="return confirm(\'Are you sure to delete this team?\');">Delete</a>'; ?></td>
    </tr>
  </table>
</div>
<?
  require("footer.php");
?>
</table>
</body>
</html>
