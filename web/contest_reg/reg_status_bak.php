<?
  require("./config.php");
  $page = $_GET["page"];
  $cid = $_GET["cid"];
  @mysql_connect($host, $user, $password) or die("Unable to connect database!");
  mysql_select_db($database);
  $user_id = $HTTP_COOKIE_VARS["ex_user_id"];
//  if (isset($user_id)) {
//    $result = mysql_query("SELECT count(*) FROM user WHERE id='$user_id' AND perm LIKE '%Admin%'");
//    $row = mysql_fetch_row($result);
//    $admin = $row[0];
//      if ($user_id == 77 || $user_id == 30 || $user_id == 10)
//      {
//        $admin = 1;
//      }
//      else $admin = 0;
//  }
  $admin = 0;
?>
<html>
<head>
<title>Status</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" href="style.css">
</head>
                           <!-- "#005DA9" -->
<body color="#FFFFFF" bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="770">
	<?
	  require("./navigation.php");
	?>
    </td>
    <td width="100%" background="images/navigation_bg.gif">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td height="440" colspan="2" align="center" background="images/bg2.gif">
	  <br>
      <table width="100%" border="0" cellpadding="4" cellspacing="2">
        <tr align="center" bgcolor="#0071BD" class="white"> 
          <td height="20" rowspan=2><b>Team ID</b></td>
          <td rowspan=2><b>Team Name</b></td>
	  <td rowspan=2><b>Contest Position</b></td>
	  <td colspan=7><b>Team Leader</b></td>
	  <td colspan=5><b>Second Member</b></td>
	  <td colspan=5><b>Third Member</b></td>
	  <td rowspan=2>Register Time</td>
	  <td rowspan=2>Status</td>
	  <? if ($admin) { ?> 
	    <td>Approve</td>
	    <td>Delete</td>
	  <? } ?>
        </tr>
	<tr align="center" bgcolor="#0071BD" class="white">
	  <!-- Team Leader -->
	  <td>name</td>
	  <td>sex</td>
	  <td>department</td>
	  <td>grade</td>
	  <td>class</td>
	  <td>telephone</td>
	  <td>email</td>
	  <!-- Second Member -->
	  <td>name</td>
	  <td>sex</td>
	  <td>department</td>
	  <td>grade</td>
	  <td>class</td>
	  <!-- Third Member -->
	  <td>name</td>
	  <td>sex</td>
	  <td>department</td>
	  <td>grade</td>
	  <td>class</td>
	</tr>
		<?
		  $type = "zsucpc";
		  $result = mysql_query("SELECT * FROM register2007 WHERE type='$type' order by id asc");
		  for ($i = 0;; $i++) {
		    if ($i % 2 == 0) echo "<tr bgcolor=\"#FCFCFC\">\n"; else echo "<tr bgcolor=\"#EEEEEE\">\n";
			$row = mysql_fetch_array($result);
			if ($row == null) break;
			$id = $row["id"];
			$name = $row["teamname"];
			$position = $row["position"];
			$status = $row["status"];
			$time = $row["date"];
		?>
        <td height=20 align="center"><? echo $id;?></td>
        <td>
	<a href=<?if(!$admin)echo "reg_info.php?id=$id";else echo "upd.php?id=$id";?> target="_blank"><? echo $name;?></a></td>
	<td height=20 align="center"><? echo $position;?></td>
	<!-- Team Leader -->
	<td align="center"><?echo $row["name1"]; ?></td>
	<td align="center"><?echo $row["sex1"]; ?></td>
	<td><?echo $row["department1"];?></td>
	<td><?echo $row["grade1"];?></td>
	<td><?echo $row["class1"];?></td>
	<td><?echo $row["telephone"];?></td>
	<td><?echo $row["email"];?></td>
	<!-- Second Member -->
	<td align="center"><?echo $row["name2"]; ?></td>
	<td><?echo $row["sex2"]; ?></td>
	<td><?echo $row["department2"];?></td>
	<td><?echo $row["grade2"];?></td>
	<td><?echo $row["class2"];?></td>
	<!-- Third Member -->
	<td align="center"><?echo $row["name3"]; ?></td>
	<td><?echo $row["sex3"];?></td>
	<td><?echo $row["department3"];?></td>
	<td><?echo $row["grade3"];?></td>
	<td><?echo $row["class3"];?></td>
	<td align="center"><? echo $time;?></td>
        <td align="center"><? echo ($status)?"Approved":"New" ;?></td>
	<? if ($admin) { ?> 
	<td align="center"><a href=reg_app.php?id=<?echo $id?>&type=<?echo $type?>>Approve</a></td>
	<td align="center"><a href=reg_del.php?id=<?echo $id?>&type=<?echo $type?>>Delete</a></td>
	<? } ?>
        </tr>
        <? }?>
        <tr><td colspan=10><a href="reg2.php" target="_blank"><font color="blue">Register a new team</font></a></td></tr>
      </table>
      </td>
  </tr>
  <?
  require("./footer.php");
?>
</table>
</body>
</html>
