<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" href="style.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php require("./navigation.php");?></td>
		<td background="images/navigation_bg.gif">&nbsp;</td>
	</tr>
	<tr>
		<td width="770">
			<table width="770" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="300" height="100" bgcolor="#F0F0F0"><img src="images/register_01.jpg" width="300" height="100"></td>
					<td width="470" rowspan="3" align="center" valign="top" background="images/register_02.jpg"><br><br><br><br>
					<form action="login.php" method="post">
					<table border="0" cellpadding="4" cellspacing="2">
						<tr bgcolor="#0071BD" class="white">
							<td height="20" colspan="2" align="center"><b>Login </b></td>
						</tr>
						<tr bgcolor="#EEEEEE">
							<td width="130" height="20" align="right">Admin name &nbsp;</td>
							<td width="220" align="left" bgcolor="#F0F0F0"><input name="adminid" type="text" id="adminid" size="30" maxlength="20">
							</td>
						</tr>
						<tr bgcolor="#FCFCFC">
							<td height="20" align="right">Password &nbsp;</td>
							<td align="left"><input type="password" name="password" size="30" maxlength="20">
							</td>
						</tr>
						<tr align="center" bgcolor="#FCFCFC">
							<td height="20" colspan="2" align="center"><input type="submit" value="Login" name="addsub">
							</td>
						</tr>
					</table>
					</form>
					</td>
			</tr>
			
			<tr>
				<td height="282" bgcolor="#F0F0F0"><img src="images/register_03.jpg" width="300" height="282"></td>
			</tr>
			<tr>
				<td height="100" bgcolor="#F0F0F0"><img src="images/register_04.jpg" width="300" height="100"></td>
			</tr>
		</table></td>
	<td>&nbsp;</td>
	</tr>
	<?php require("./footer.php");?>
</table>
</body>
</html>
