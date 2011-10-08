<?php
   require_once("include/checktime.php");
   require_once("include/global.php");
   session_start();
   if (!checkAdmin())
     error ("You can't reset password!");
   $tid = $_GET["id"];
   $sql = "SELECT * FROM team WHERE tid='$tid'";
   $result = mysql_query ($sql);
   if (mysql_num_rows($result) == 0)
     error ("No such team");
   $team = mysql_fetch_array ($result);
   ?>
<html>
  <head>
    <title>Reset Password</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="style.css">
    <script language="javascript" type="text/javascript">
function setWarning(warn, warnId) {
     var obj = document.getElementById(warnId);
     if (obj)
       obj.innerHTML = warn;
}

function checkEmpty(fieldId, warnId) {
  if (document.getElementById(fieldId).value == "") {
    setWarning ("can't be empty", warnId);
    return false;
  }
  return true;
}

function checkForm() {
  var ret = true;
  setWarning ("", "pas1_warn");
  setWarning ("", "pas2_warn");
  if (!checkEmpty("password1", "pas1_warn"))
    ret = false;
  if (document.getElementById("password1").value !=
      document.getElementById("password2").value) {
    setWarning ("two passwords must be same", "pas2_warn");
    ret = false;
  }
  return ret;
}
    </Script>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <? require("navigation.php");?>
  <div id="content">
    <div class="reg_form">
      <form action="re_password.php?tid=<?php print $tid;?>" method="POST" onsubmit="return checkForm();">
	<div class="field">
	  <div class="field_name">Team id: 
	  <?php print $team["id"]; ?></div>
	</div>
	<div class="field">
	  <div class="field_name">Password</div>
	  <input id="password1" name="password" type="password"/>
	  <div class="field_warning" id="pas1_warn"></div>
	</div>
	<div class="field">
	  <div class="field_name">Confirm password</div>
	  <input id="password2" name="password2" type="password"/>
	  <div class="field_warning" id="pas2_warn"></div>
	</div>
	<input type="submit" value="Reset"/>
      </form>
    </div>
  </div>
  <div id="footer">
    <? require("footer.php");?>
  </div>
</body>
</html>
