<?php
if ($_GET['mode'] != 'debug')
  require_once("include/checktime.php");
require_once("include/global.php");
session_start();
?>
<html>
  <head>
    <title>Register</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="style.css">
    <script language="javascript" type="text/javascript">
     var selector = [
                     "title1", 
                     "degree1", 
                     "major1",
                     "title2", 
                     "degree2", 
                     "major2",
                     "title3", 
                     "degree3", 
                     "major3",
                     ];
var value = new Array();
<?php
for ($i = 1; $i <= 3; $i++)
 {
   foreach($fields as $val)
   {
     if (isset($_SESSION[$val.$i]))
     {
       print "value['$val$i'] = '{$_SESSION[$val.$i]}';\n";
     }
   }
 }
if (isset($_SESSION['teamen']))
  print "value['teamen'] = '{$_SESSION['teamen']}';\n";
if (isset($_SESSION['teamcn']))
  print "value['teamcn'] = '{$_SESSION['teamcn']}';\n";
$year = date("Y");
$month = date("m");
if ($month >= 9)
  $year++;
?>
function setFields(){
  var teamen = document.getElementById("teamen");
  if (obj)
  {
    if (!checkInput(teamen))
      return false;
  }
  var teamcn = document.getElementById("teamcn");
  if (obj)
  {
    if (!checkInput(teamen))
      return false;
  }
  for (i in value)
  {
    obj = document.getElementById(i);
    if (obj && obj.type == "text")
    {
      obj.value = value[i];
    }
  }
  for (i = 0; i < selector.length; i++)
  {
    id = selector[i];
    if (value[id])
    {
      var obj = document.getElementById(id);
      if (!obj || !obj.options) continue;
      item = obj.options;
      for (j = 0; j < item.length; j++)
      {
        if (item[j].value == value[id])
        {
          obj.selectedIndex = j;
          break;
        }
      }
					 
    }
  }
}

function checkLen(obj, len)
{
  return obj.value.length <= len;
}

function inputError(obj, msg)
{
  obj.focus();
  if (obj.select()) obj.select();
  alert(msg);
}

function checkNumber(obj)
{
  var pattern = /^(\d+)$/;
  if (obj && obj.value)
  {
    if (!pattern.exec(obj.value))
    {
      inputError(obj, "It should be a number!");
      return false;
    }
  }
  return true;
}

function checkDate(obj)
{
  var pattern = /^(\d{4})-(\d{1,2})-(\d{1,2})$/;
  var monthday = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  if (obj && obj.value)
  {
    var result = pattern.exec(obj.value);
    if (!result)
    {
      inputError(obj, "Date format incorrect");
      return false;
    }
    var year = parseInt(result[1], 10);
    var month = parseInt(result[2], 10);
    var day = parseInt(result[3], 10);
    if (year < 1980) {
      inputError(obj, "It is too early");
      return false;
    }else if (month < 1 || month > 12){
      inputError(obj, "Month error.");
      return false;
    }else if (day < 1 || day > monthday[month]) {
      inputError(obj, "Day error.");
      return false;
    }else if (((year % 400 != 0 && year % 100 == 0) || (year % 4 != 0)) && month == 2 && day == 29) {
      inputError(obj, "Date error.");
      return false;
    }
    var thedate = new Date(year, month - 1, day)
      //		alert(thedate);
      if (year > (new Date()).getFullYear() + 5)
      {
        inputError(obj, "日期超前");
        return false;
      }
  }
  return true;
}

function checkEmail(obj)
{
  var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
  if (obj && obj.value && !pattern.test(obj.value))
  {
    inputError(obj, "E-mail format incorrect, please fill it correctly.");
    return false;
  }
  return true;
}

function checkInput(obj)
{
  if (obj && obj.value == "")
  {
    inputError(obj, "This field is empty, please fill it.");
    return false;
  }
  return true;
}

function checkForm()
{
  var teamcn = document.getElementById("teamcn");
  var teamen = document.getElementById("teamen");
  var dates = ["admitdate", "graduatedate", "birthday"];
  if (!checkInput(teamen) || !checkInput(teamcn))
    return false;
  if (!checkLen(teamcn, 50) || !checkLen(teamen, 50)) {
    alert("Team name must not be longer than 50 characters.");
  }
  var fields = ["firstname", "lastname", "cnname", "email", "confirmemail", "phone", 
                "institution", "location", "country", "majorcn", "admitdate", "graduatedate", "birthday",
                "grade"];
  var numbers = ["phone", "grade"];
  for (i = 1; i <= 3; i++)
  {
    //		alert(i);
    for (j = 0; j < fields.length; j++)
    {
      var id = fields[j] + i;
      obj = document.getElementById(id);
      if (!checkInput(obj))
        return false;
      if (fields[j]=="email" && !checkEmail(obj))
        return false;
    }
    for (j = 0; j < numbers.length; j++)
    {
      var id = numbers[j] + i;
      obj = document.getElementById(id);
      if (!checkNumber(obj))
        return false;
    }
    for (j = 0; j < dates.length; j++)
    {
      var id = dates[j] + i;
      obj = document.getElementById(id);
      if (!checkDate(obj))
        return false;
    }
  }
  return true;
}
window.load = setFields;

																     </script>
    <style type="text/css">
      <!--
	 .STYLE1 {color: #FF0000}
	-->
    </style>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setFields();">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	<td><? require("navigation.php");?></td>
	<td background="../images/navigation_bg.gif">&nbsp;</td>
      </tr>
      <tr>
	<td width="770"><table width="770" border="0" cellspacing="0" cellpadding="0">
	    <tr bgcolor="#f0f0f0">
	      <td width="150" height="100"></td>
	      <td width="470" rowspan="3" align="center" valign="top" bgcolor="white"><br>
		<form action="re_reg.php<?php if ($_GET['mode']=='debug') print "?mode=debug";?>" method="post" onSubmit="return checkForm();">
		  <table border="0" cellpadding="4" cellspacing="2" class="reg">
		    <tr>
		      <td colspan="2" class="title">ZSU Novice Register Form</td>
		    </tr>
<?php if ($avail["teamen"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Team name (Engligh)</td>
		      <td><input name="teamen" id="teamen" value="<?php print $_SESSION["teamen"]?>" size="50" maxlength="50">
			<br>no longer than 50 characters</td>
		    </tr>
<?php }?>
<?php if ($avail["teamcn"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Team name (Chinese)</td>
		      <td><input name="teamcn" id="teamcn" value="<?php print $_SESSION["teamcn"]?>" size="50" maxlength="50">
			<br>no longer than 50 characters
		      </td>
		    </tr>
<?php }?>
<?php for ($ci = 1; $ci <= 3; $ci++) {?>
		    <tr>
		      <td colspan="2" class="title">Contestant <?php print $ci; if ($ci == 1) print "(Team Leader)"?> </td>
		    </tr>
<?php if ($avail["title"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Title</td>
		      <td><select name="title<?php print $ci?>" size="1" id="title<?php print $ci?>">
			  <option value="none">none</option>
			  <option value="Dr.">Dr.</option>
			  <option value="Ir.">Ir.</option>
			  <option value="Miss">Miss</option>
			  <option selected="selected" value="Mr.">Mr.</option>
			  <option value="Mrs.">Mrs.</option>
			  <option value="Ms.">Ms.</option>
			  <option value="Professor">Professor</option>
			</select>
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["firstname"]) {?>
		    <tr class="color2">
		      <td class="fieldname">First Name</td>
		      <td><input name="firstname<?php print $ci?>" type="text" id="firstname<?php print $ci?>" size="20" maxlength="20">
			<br />
			enter English first name (<span class="STYLE1">given name</span>) </td>
		    </tr>
<?php }?>
<?php if ($avail["lastname"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Last Name</td>
		      <td><input name="lastname<?php print $ci?>" type="text" id="lastname<?php print $ci?>" size="20" maxlength="20">
			<br />
			enter English last name (<span class="STYLE1">family name</span>) </td>
		    </tr>
<?php }?>
<?php if ($avail["cnname"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Chinese Name</td>
		      <td><input name="cnname<?php print $ci?>" type="text" id="cnname<?php print $ci?>" size="20" maxlength="20">
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["gender"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Gender</td>
		      <td><input name="gender<?php print $ci?>" type="radio" id="sex1male" size="30" maxlength="50" class="radio" value="0" checked="checked" /><label for="sex<?php print $ci?>male">Male</label>
			&nbsp; &nbsp; &nbsp;
			<input name="gender<?php print $ci?>" type="radio" class="radio" id="sex<?php print $ci?>female" value="1" size="30" maxlength="50"><label for="sex1female">Female</label>
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["email"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Email Address</td>
		      <td><input name="email<?php print $ci?>" type="text" id="email<?php print $ci?>" value="" size="50" maxlength="50">
		      </td>
		    </tr>
<? }?>
<?php if ($avail["phone"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Telephone</td>
		      <td><input name="phone<?php print $ci?>" type="text" id="phone<?php print $ci?>" size="20" maxlength="20">
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["tshirt"]) {?>
		    <tr class="color2">
		      <td class="fieldname">T-Shirt Size</td>
		      <td><input name="tshirt<?php print $ci?>" type="radio" value="S" id="tshirt<?php print $ci?>S" /><label for="tshirt1S">S</label>
			<input name="tshirt<?php print $ci?>" type="radio" value="M" id="tshirt<?php print $ci?>M" /><label for="tshirt1M">M</label>
			<input name="tshirt<?php print $ci?>" type="radio" value="L" id="tshirt<?php print $ci?>L" /><label for="tshirt1L">L</label>
			<input name="tshirt<?php print $ci?>" type="radio" value="XL" id="tshirt<?php print $ci?>XL" checked="checked" /><label for="tshirt1XL">XL</label>
			<input name="tshirt<?php print $ci?>" type="radio" value="XXL" id="tshirt<?php print $ci?>XXL" /><label for="tshirt1XXL">XXL</label>
			<input name="tshirt<?php print $ci?>" type="radio" value="3XL" id="tshirt<?php print $ci?>3XL" /><label for="tshirt13XL">3XL</label>
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["institution"]) {?>
		    <tr class="color1">
		      <td class="fieldname">University</td>
		      <td><input name="institution<?php print $ci?>" type="text" id="institution<?php print $ci?>" value="Zhongshan(Sun Yat-sen) University" size="50" maxlength="50" />
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["location"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Address</td>
		      <td><input name="location<?php print $ci?>" type="text" id="location<?php print $ci?>" value="Guangzhou, Guangdong" size="30" maxlength="30" />
			<br>
			City, Province </td>
		    </tr>
<?php }?>
<?php if ($avail["country"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Country</td>
		      <td><input name="country<?php print $ci?>" type="text" id="country<?php print $ci?>" value="CHN" size="3" maxlength="3" /></td>
		    </tr>
<?php }?>
<?php if ($avail["degree"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Degree Pursued</td>
		      <td><select size="1" name="degree<?php print $ci?>" id="degree<?php print $ci?>">
			  <option value="B.S.E.E.">B.S.E.E.</option>
			  <option value="B.Sc.(Honors)">B.Sc.(Honors)</option>
			  <option value="Unknown">Unknown</option>
			  <option value="Ir.D.">Ir.D.</option>
			  <option value="M.S.">M.S.</option>
			  <option value="B.B.A">B.B.A</option>
			  <option value="M.A.">M.A.</option>
			  <option value="Graduate Degree">Graduate Degree</option>
			  <option value="Ph.D.">Ph.D.</option>
			  <option value="BS+MS (before last year)">BS+MS (before last year)</option>
			  <option value="B.S.E">B.S.E</option>
			  <option value="BA+MA (last year)">BA+MA (last year)</option>
			  <option value="B.Math">B.Math</option>
			  <option value="B.S.C.S.">B.S.C.S.</option>
			  <option value="BA+MA (before last year)">BA+MA (before last year)</option>
			  <option value="B.A.(Honors)">B.A.(Honors)</option>
			  <option value="B.S.C.S.E">B.S.C.S.E</option>
			  <option value="B.Sc.">B.Sc.</option>
			  <option value="BS+MS (last year)">BS+MS (last year)</option>
			  <option value="B.A.">B.A.</option>
			  <option value="Undergraduate Degree">Undergraduate Degree</option>
			  <option selected="selected" value="B.S.">B.S.</option>
			  <option value="M.Math">M.Math</option>
			</select>
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["major"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Major</td>
		      <td><select size="1" name="major<?php print $ci?>" id="major<?php print $ci?>">
			  <option value="none">Other</option>
			  <option value="Computer Engineering">Computer Engineering</option>
			  <option value="Mathematics">Mathematics</option>
			  <option value="Electrical Engineering">Electrical Engineering</option>
			  <option value="Physics">Physics</option>
			  <option value="Business">Business</option>
			  <option value="Other">Other</option>
			  <option value="Information Systems">Information Systems</option>
			  <option selected="selected" value="Computer Science">Computer Science</option>
			  <option value="Informatics">Informatics</option>
			</select>
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["majorcn"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Major (Chinese)</td>
		      <td><input name="majorcn<?php print $ci?>" type="text" id="majorcn<?php print $ci?>" size="50" maxlength="50">
		      </td>
		    </tr>
<?php }?>
<?php if ($avail["grade"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Grade</td>
		      <td><select name="grade<?php print $ci?>" id="grade<?php print $ci?>"  size="1" >
			  <? for ($i = $year-4; $i < $year; $i = $i + 1) {?>
			     <option value="<?echo $i?>"><?echo $i?></option>
			     <?}?></select>
			The year you enter university. </td>
		    </tr>
<?php }?>
<?php if ($avail["class"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Class</td>
		      <td><input name="class<?php print $ci?>" id="class<?php print $ci?>" size="10" maxlength="10"></td>
		    </tr>
<?php }?>
<?php if ($avail["admitdate"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Began Degree</td>
		      <td><input name="admitdate<?php print $ci?>" id="admitdate<?php print $ci?>" size="10" maxlength="10">
			yyyy-mm-dd<br />
			The first day of the first term you first began pursuing your first degree from any institution of higher education. </td>
		    </tr>
<?php }?>
<?php if ($avail["graduatedate"]) {?>
		    <tr class="color2">
		      <td class="fieldname">Expected Date of Graduation</td>
		      <td><input name="graduatedate<?php print $ci?>" type="text" id="graduatedate<?php print $ci?>" size="10" maxlength="10">
			yyyy-mm-dd </td>
		    </tr>
<?php }?>
<?php if ($avail["birthday"]) {?>
		    <tr class="color1">
		      <td class="fieldname">Birthday</td>
		      <td><input name="birthday<?php print $ci?>" type="text" id="birthday<?php print $ci?>" size="10" maxlength="10">
			yyyy-mm-dd</td>
		    </tr>
<?php }?>
<?php } ?>
		    <tr align="center">
		      <td colspan="2"><input name="submit" type="submit" id="temp" value="Register">
		      </td>
		    </tr>
		  </table>
	      </form></td>
	      <td width="150" height="100"></td>
	    </tr>
	</table></td>
	<td></td>
      </tr>
      <? require("footer.php");?>
    </table>
  </body>
</html>
