<?php
require_once("include/db.php");
?>
<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body leftmargin=0 topmargin=0>
<? require("./navigation.php");?>
<div id="content">
  <table align=center border="0" width="770" class="inner_content" height="80%">
    <tr>
      <td align="center">
	<h1>Welcome to <?php print $contest["name"]?> Registration WebSite!</h1>
      </td>
    </tr>
    <tr>
      <td align="center"><font color="red">
	  <h2>The contest registeration will be available : </h2></font>
      </td>
    </tr>
    <tr>
      <td align="center"><font color="green">&nbsp;
	</font>
	<h2><font color="green"><?php print $contest["startreg"]?> -- <?php print $contest["endreg"]?></font></h2>
      </td>
    </tr>
    <tr>
      <td align="center">
	<a href="reg_team.php"><font color="blue">Register a new team</font></a>
      </td>
    </tr>
    <tr>
      <td align="center">
	<a href="reg_status.php"><font color="blue">View registration status</font></a>
      </td>
    </tr>
    <!--<tr>
      <td align="center">
	<a href="vote.php"><font color="blue">Vote for your favorite team name</font></a>
      </td>
    </tr>-->
    <tr><td align="center">&nbsp;Administrator: &nbsp;acmm (BBSID: acmm, email: <a href="mailto:acmm@163.com">sysuacmm@163.com</a>)</td></tr>
  </table>
</div>
<? require("./footer.php");?>
</body>
</html>
