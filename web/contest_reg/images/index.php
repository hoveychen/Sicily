
<!--
<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" href="style.css">
<script language="javascript">
function zsucpc()
{
//   div_gdcpc.disabled = true;

}
function gdcpc()
{
//   div_gdcpc.disabled = false;

}
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>
	>>>><?
	  require("./navigation.php");
	?>
    </td>
  </tr>
  <tr> 
    <td width="770"><table width="770" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="150" height="100" bgcolor="#F0F0F0"></td>
          <td width="470" rowspan="3" align="center" valign="top">
		    <br>
		    <form action="re_reg.php" method="post">
              <table border="0" cellpadding="4" cellspacing="2">
                <tr bgcolor="#0071BD" class="white"> 
                  <td height="20" colspan="2" align="center"><b>Fill in general register information 
                    </b></td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td width="130" height="20" align="right">Contest Type&nbsp;</td>
                  <td width="220" align="left" bgcolor="#F0F0F0">&nbsp; 
                    <input name="contest" type="radio" id="contest1" size="30" checked class="radio" onclick="gdcpc();" value="gdcpc">GDCPC
		    &nbsp; &nbsp; 
                    <input name="contest" type="radio" id="contest2" size="30" class="radio" onclick="zsucpc();" value="gdcpc">ZSUCPC
                  </td>
                </tr>
		<div id=div_gdcpc>
                <tr bgcolor="#FCFCFC"> 
                  <td width="130" height="20" align="right">University &nbsp;</td>
                  <td width="220" align="left" bgcolor="#FCFCFC">&nbsp; 
                    <input name="University" type="text" id="collage" size="30" maxlength="20">
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td width="130" height="20" align="right">School/College &nbsp;</td>
                  <td width="220" align="left" bgcolor="#F0F0F0">&nbsp; 
                    <input name="School/College" type="text" id="school" size="30" maxlength="20">
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td width="130" height="20" align="right">Department &nbsp;</td>
                  <td width="220" align="left" bgcolor="#FCFCFC">&nbsp; 
                    <input name="department" type="text" id="department" size="30" maxlength="20">
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td width="130" height="20" align="right">Telephone &nbsp;</td>
                  <td width="220" align="left" bgcolor="#F0F0F0">&nbsp; 
                    <input name="telephone" type="text" id="telephone" size="30" maxlength="20">
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Email &nbsp;</td>
				  <td width="220" align="left" bgcolor="#F0F0F0">&nbsp; 
                    <input name="email" size="30" maxlength="20"> 
                  </td>
                </tr>
		</div>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Team name &nbsp;</td>
                  <td width="220" align="left" bgcolor="#F0F0F0">&nbsp; 
                    <input name="teamname" size="30" maxlength="20"> 
                  </td>
                </tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The First Contestant (Team Leader)
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex1" type="radio" id="sex1" size="30" maxlength="50" class="radio">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex1" type="radio" id="sex1" size="30" maxlength="50" class="radio">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department1" type="text" id="department1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade1" type="text" id="grade1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class1" type="text" id="class1" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The Second Contestant
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex2" type="radio" id="sex2" size="30" maxlength="50" class="radio">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex2" type="radio" id="sex2" size="30" maxlength="50" class="radio">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department2" type="text" id="department2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade2" type="text" id="grade2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class2" type="text" id="class2" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#0071BD" height="20">
                  <td height="20" colspan="2" align="center" class="white">
                    <b>The Third Contestant
                    </b>
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Name &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input type="text" name="name3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Sex &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="sex3" type="radio" id="sex3" size="30" maxlength="50" class="radio">Male
		    &nbsp; &nbsp; &nbsp;
                    <input name="sex3" type="radio" id="sex3" size="30" maxlength="50" class="radio">Female
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Department &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="department3" type="text" id="department3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#FCFCFC"> 
                  <td height="20" align="right">Grade &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="grade3" type="text" id="grade3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr bgcolor="#EEEEEE"> 
                  <td height="20" align="right">Class &nbsp;</td>
                  <td align="left">&nbsp; 
                    <input name="class3" type="text" id="class3" size="30" maxlength="50"> 
                  </td>
                </tr>
                <tr align="center" bgcolor="#FCFCFC"> 
                  <td height="20" colspan="2" align="center"> 
                    <input type="submit" value="Register" name="addsub">
                  </td>
                </tr>
                </tr>
              </table>
            </form>
          </td>
          <td width="150" height="100" bgcolor="#F0F0F0"></td>
        </tr>
      </table> </td>
    <td>&nbsp;</td>
  </tr>
<?
  require("./footer.php");
?>
</table>
</body>
</html>
-->

<html>
<head>
<title>Register</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" href="style.css">
</head>
<body leftmargin=0 topmargin=0>
	<table cellspacing="0" cellpadding="0" bgcolor="#EEEEEE" width="100%" height="100%" border=0>
		<tr>
			<td>
				<?
					require("./navigation.php");
				?>
			</td>
		</tr>
		<tr align=center>
			<td width="100%" align="center" valign="middle" height="100%">
				<table align=center border="0" width="770">
				<tr>
					<td align="center">
						<h1>Welcome to ZSUCPC2008 Registration WebSite!</h1>
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
					<h2><font color="green">03/17/2007 -- 03/19/2007</font></h2>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="reg.php"><font color="blue">Register a new team</font></a>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="reg_status.php"><font color="blue">View registration status</font></a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align=center>
				&nbsp;Administrator: &nbsp;Tiger Soldier (BBSID: tigersoldier, email: tiger_soldier@163.com)
			</td>
		</tr>
		<tr>
			<td align=center>
				ivankevin(BBSID: ivankevin, email: iiikkkkk@hotmail.com)
			</td>
		</tr>
		<tr>
			<td>
			    <?
			    		require("./footer.php");
			    ?>
			</td>
		</tr>
	</table>
