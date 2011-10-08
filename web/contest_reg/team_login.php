<?php
   if (@$_GET['mode'] != 'debug')
   require_once("include/checktime.php");
   require_once("include/global.php");
   session_start();
   ?>
<html>
  <head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="style.css">
    <script language="javascript" type="text/javascript">
function setWarning(warn, warnId) {
     var obj = document.getElementById(warnId);
     if (obj)
       obj.innerHTML = warn;
}
function checkForm() {
  var ret = true;
  setWarning ("", "name_warn");
  setWarning ("", "pas1_warn");
  if (document.getElementById("tid").value == "") {
    setWarning ("can't be empty", "name_warn");
    ret = false;
  }
  if (document.getElementById("password1").value == "") {
    setWarning ("can't be empty", "pas1_warn");
    ret = false;
  }
  return ret;
}
    </Script>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setFields();">
  <? require("navigation.php");?>
  <div id="content">
    <div class="reg_form">
      <form action="re_team_login.php" method="POST" onsubmit="return checkForm();">
	<div class="field">
	  <div class="field_name">Team id</div>
	  <input id="tid" name="tid" type="input" />
	  <div class="field_warning" id="name_warn"></div>
	</div>
	<div class="field">
	  <div class="field_name">Password</div>
	  <input id="password1" name="password" type="password"/>
	  <div class="field_warning" id="pas1_warn"></div>
	</div>
	<input type="submit" value="Login"/>
      </form>
    </div>
  </div>
  <div id="footer">
    <? require("footer.php");?>
  </div>
</body>
</html>
