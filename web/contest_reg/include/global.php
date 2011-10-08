<?php
$vote = false;
$fields = array("firstname", "lastname", "cnname", "enname", "title", "location",
                "country", "email", "phone", "gender", "institution", "degree",
                "major", "majorcn", "grade", "class", "admitdate", "graduatedate",
                "birthday", "tshirt");
$avail = array("teamen"      => true,
               "teamcn"      => true,
               "firstname"   => true,
               "lastname"    => true,
               "cnname"      => true,
               "enname"      => true,
               "title"       => false,
               "location"    => false,
               "country"     => false,
               "email"       => true,
               "phone"       => true,
               "gender"      => true,
               "tshirt"      => false,
               "institution" => false,
               "degree"      => false,
               "major"       => true,
               "majorcn"     => true,
               "admitdate"   => false,
               "graduatedate"=> false,
               "birthday"    => false,
               "grade"       => true,
               "class"       => true);

function checkAdmin() {
	if (isset($_SESSION['admin']))
		return true;
	else
		return false;
}

function checkTeam($tid) {
  if (isset($_SESSION["tid"]) && $_SESSION["tid"] == $tid)
    return true;
  return checkAdmin();
}

function redirect($url) {
	header("location: $url");
}



function error($message, $url="javascript:history.go(-1);") {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link rel="stylesheet" href="../style.css">
<title>Stop</title>
</head>
<body bgcolor="#0071BD">
<table border="0" width="100%" height="100%">
  <tr>
    <td>
      <table width="450" border="0" cellpadding="1" cellspacing="1" bgcolor="005DA9" align="center" class="white">
        <tr bgcolor="#005DA9">
          <td height=20 align="center"><b>Stop</b></td>
        </tr>
        <tr bgcolor="#0387DC">
          <td height=330 align="center">
            <div align=center><img src="../images/stop.jpg"></div>
            <br>
            <u><? echo $message;?></u><br>
            <br>
            <br>
          </td>
        </tr>
        <tr bgcolor="#005DA9">
          <td height=20 align="center">[ <a href="<? echo $url;?>" class="white">Back</a> ]</td>
        </tr>
      </table>
    </td>
  </tr>
<table>
</body>
</html>
<?php
die();
}
?>
