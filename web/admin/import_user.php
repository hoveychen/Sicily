<?php
require("./navigation.php");
$cid = safeget("cid");
$contest = new ContestsTbl($cid);
if (!$contest->Get())
	error("No such contest");
?>

<h1> Import User list for <?= $contest->detail['title'] ?> </h1>
<h2> Example format of csv </h2>
<table class="ui-widget">
    <thead class="ui-widget-header">
        <tr><th>username</th><th>password</th><th>email</th><th>nickname</th><th>signature</th></tr>
    </thead>
    <tbody class="ui-widget-content tr_odd">
        <tr><td>hovey</td><td>123456</td><td>hoveychen@soj.me</td><td>Hovey Chen</td><td>A lazy boy</td></tr>
        <tr><td>more</td><td>and</td><td>more@fun</td><td>more</td><td>fun</td></tr>
    </tbody>
</table>
<p>Notice that the csv file should be encoded as gbk( i.e. from windows excel)</p>
<p>If the password field is left emtpy, automatic generated password will be filled up.</p>
<table class="ui-widget">
    <thead class="ui-widget-header"> <tr><td> options </td> <td> values </td> </tr></thead>
    <tbody class="ui-widget-content tr_odd">
	<form id="importuser" name="importuser" action="<?= $_SERVER['PHP_SELF'] ?>?cid=<?= $cid ?>" method="post" enctype="multipart/form-data">
		<tr> <td> username_prefix </td> <td> <input name="nameprefix" type="text" id="nameprefix" size="20" value=""> </td> </tr>
		<tr> <td> permission </td><td> <input name="perm" type="radio" value="temp" checked="checked"/> Contestant <input name="perm" type="radio" value="user" /> Normal user</td></tr>
		<tr> <td> user list(csv) </td> <td> <input name="ulist" type="file" id="ulist"> </td> </tr>
		<tr> <td> preview </td> <td> <input name="preview" id="preview" type="checkbox" checked> </td> </tr>
		<tr> <td> </td> <td> <input type="submit" value="Submit" /> </td> </tr>
	</form>
</tbody>
</table>


<?php
if (isset($_FILES['ulist']['name'])) {
	$path = $_FILES['ulist']['tmp_name'];
	$preview = @$_POST["preview"];
	$nameprefix = $_POST['nameprefix'];
	$perm = $_POST['perm'];

	if ($preview != "on") {
		echo "<p> <a href='export_user.php?cid=$cid'> Export User list with password </a> </p> ";
	}
	setlocale(LC_ALL, "zh_CN.GBK");
	echo "<table class='tblcontainer' width='100%'>" .
	"<thead><tr><th>username</th><th>password</th><th>email</th><th>nickname</th><th>signature</th><th>Status</th></tr></thead>";
	$firstcol = true;
	if (($fp = fopen($path, "r")) !== FALSE) {
		while (($data = fgets($fp)) !== FALSE) {
			$data = explode(",", iconv("GBK", "UTF-8", $data));
			$num = count($data);
			if ($num < 5) {
				echo "</table><p>The number of columns($num) doesn't enough(5 at least)</p>";
				break;
			}
			if ($firstcol) {
				$firstcol = false;
				continue;
			}
			echo "<tr>";
			$keys = array('username', 'password', 'email', 'nickname', 'signature');
			$user = new UserTbl();
			
			foreach ($keys as $key => $value) {
				$user->detail[$value] = trim($data[$key], '"');
				if ($value == 'password' && empty($user->detail[$value])) {
					if ($preview == 'on') {
						$user->detail[$value] = "n/a";
					} else {
						$password = "";
						for ($i = 0; $i < 6; ++$i) {
							$password .= chr(rand(97, 122));
						}
						$user->detail[$value] = $password;
					}
				}
				if ($value == 'username')
					$user->detail[$value] = $nameprefix . $user->detail[$value];
				echo "<td>{$user->detail[$value]}</td>";
			}
			echo "<td>";
			
			if ($preview != "on") {
				$storage = array();
				$storage = $user->detail;
				// get existing id
				if (!$user->GetByField('username', $user->detail['username'])) {
					$user->detail['perm'] = $perm;
					$user->detail["reg_time"] = date("Y.m.d G:i:s");
					$user->detail['password'] = md5($user->detail['password']);
					$id = $user->Add();
					echo "New ID $id";
				} else {
					$id = $user->detail['uid'];
                                        echo "User $id ";
                                        $olduser = new UserTbl($id);
                                        $olduser->update = $storage;
                                        $olduser->update['perm'] = $perm;
                                        $olduser->update['password'] = md5($storage['password']);
                                        $olduser->Update();
				}
				$reg = new ContestRegistrationTbl();
				if (!$reg->GetByFields(array("uid" => $id, "cid" => $cid))) {
					$reg->detail['uid'] = $id;
					$reg->detail['cid'] = $cid;
					$reg->Add();
					echo " Added";
				} else {
					echo " Already imported";
				}
			} else {
				if ($user->GetByField('username', $user->detail['username'])) {
					echo "Exists";
				} else {
					echo "Ok";
				}
			}
			echo "</tr>";
		}
		fclose($fp);
	}
	echo "</table>";
	setlocale(LC_ALL, "en_US.UTF-8");
}
?>


<?php
require("../footer.php");
?>
