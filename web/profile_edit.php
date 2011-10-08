<?
require("./navigation.php");

global $login_uid;
global $logged;
global $login_netid;
global $login_username;
if (!$logged)
	error("Please login first.");
$uid = trypost('uid', '');
$userTbl = new UserTbl($login_uid);

if ($uid == $login_uid) {
	// Profile is changed
	$user['nickname'] = $_POST['nickname']; // prevent escape
	$user['signature'] = $_POST['signature']; // prevent escape
	$user['email'] = safepost('email');
	$user['phone'] = safepost('phone');
	$user['address'] = safepost('address');
	$user['cn_name'] = safepost('cn_name');
	$user['en_name'] = safepost('en_name');
	$user['gender'] = safepost('gender');
	$user['major'] = safepost('major');
	$user['grade'] = safepost('grade');
	$user['class'] = safepost('class');
	$user['student_id'] = safepost('student_id');
	if (!$user['email']) {
		MsgAndBack('Email should not be empty');
	}
	if (!is_email_valid($user['email'])) {
		MsgAndBack("Email is not valid");
	}
	if ($user['cn_name'] && !is_chinese($user['cn_name'])) {
		MsgAndBack("Chinese name should only contain Chinese character, right?");
	}
	if ($user['grade'] && !is_grade_valid($user['grade'])) {
		MsgAndBack("Grade should be roughly between 2000 and 2020");
	}
	if ($user['student_id'] && !is_student_id_valid($user['student_id'])) {
		MsgAndBack("Student ID should consist 8 digits");
	}

	$userTbl->update = $user;
	$_SESSION['snickname'] = $user['nickname'];
	$_SESSION['ssignature'] = $user['signature'];

	if ($userTbl->Update()) {
		MsgAndBack("Profile has been changed", FALSE);
	} else {
		MsgAndBack("Nothing has been changed", FALSE);
	}
	//MsgAndRedirect("index.php", 'Profile has been changed');
} else {
	$userTbl->Get() or error("User not found");
	$user = $userTbl->detail;
}
?>

<form action="profile_edit.php" method="post" enctype="multipart/form-data">
    <h1>Personal Profile Edit</h1>
    <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
        <caption style="font-size: large"></caption>
        <input type="hidden" name="uid" value="<?= $user['uid'] ?>"/>
        <thead >
            <tr class="ui-widget-header">
                <th width="150">Option</th>
                <th>Content</th>
                <th width="150">Example/Note</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>Username</td>
                <td><?= $user['username'] ?></td>
                <td></td>
            </tr>

            <tr>
                <td>Password</td>
                <td>
                    <a href="profile_chpwd.php" title="Click here to change your password"> Change Password </a>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>Nickname</td>
                <td>
                    <input type="text" name="nickname" size="20" maxlength="20" id="nickname" value="<?= $user['nickname'] ?>"/>
                <td>King of coding</td>
            </tr>

            <tr>
                <td>Signature</td>
                <td>
                    <textarea rows="2" cols="75" name="signature" id="signature"><?= $user['signature'] ?></textarea>
                <td>At most 150 characters.</td>
            </tr>

            <tr>
                <td>Netid<sup>*</sup></td>
                <td>
					<?
					if ($user['netid']) {
						echo '<a href="netid_unbind.php" title="Click here to anti-authorize your netid">' . $login_netid . "</a>";
					} else {
						echo '<a href="netid_bind.php">Click here to authorize your netid</a>';
					}
					?>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>Email<sup>**</sup></td>
                <td><input name="email" type="text" id="email" size="50" maxlength="30" value="<?= $user['email'] ?>"></td>
                <td>sicily@gmail.com</td>
            </tr>

            <tr>
                <td>Contact1</td>
                <td><input name="phone" type="text" id="phone" size="50" maxlength="50" value="<?= $user['phone'] ?>"></td>
                <td>Phone/QQ/MSN...</td>
            </tr>

            <tr>
                <td>Contact2</td>
                <td><input name="address" type="text" id="address" size="50" maxlength="50" value="<?= $user['address'] ?>"></td>
                <td>twitter/sina...</td>
            </tr>

            <tr>
                <td>Student ID<sup>*</sup></td>
                <td><input name="student_id" type="text" id="cn_name" size="8" maxlength="8" value="<?= $user['student_id'] ?>"></td>
                <td>10382001</td>
            </tr>

            <tr>
                <td>Real Chinese Name<sup>*</sup></td>
                <td><input name="cn_name" type="text" id="cn_name" size="20" maxlength="20" value="<?= $user['cn_name'] ?>"></td>
                <td>张三</td>
            </tr>

            <tr>
                <td>Real English Name </td>
                <td><input name="en_name" type="text" id="en_name" size="20" maxlength="20" value="<?= $user['en_name'] ?>"></td>
                <td>San Zhang</td>
            </tr>

            <tr>
                <td>Gender</td>
                <td>
                    <select name="gender">
                        <option value=""
						<? if ($user['gender'] != 'M' && $user['gender'] != 'F'): ?>
	                                selected='selected'
								<? endif; ?> >Unknown</option>
                        <option value="M"
						<? if ($user['gender'] == 'M'): ?>
	                                selected='selected'
								<? endif; ?> >Male</option>
                        <option value="F"
						<? if ($user['gender'] == 'F'): ?>
	                                selected='selected'
								<? endif; ?> >Female</option>
                    </select>
                <td></td>
            </tr>

            <tr>
                <td>Major<sup>*</sup></td>
                <td>
                    <input type="text" name="major" size="30" maxlength="50" id="major" value="<?= $user['major'] ?>"/>
                <td>Computer Science</td>
            </tr>

            <tr>
                <td>Grade<sup>*</sup></td>
                <td>
                    <input type="text" name="grade" size="5" maxlength="5" id="grade" value="<?= $user['grade'] ?>"/>
                <td>2010</td>
            </tr>

            <tr>
                <td>Class<sup>*</sup></td>
                <td>
                    <input type="text" name="class" size="10" maxlength="10" id="class" value="<?= $user['class']; ?>"/>
                <td>A</td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" /> </td>
                <td> </td>
            </tr>
        </tbody>
    </table>
    <hr/>
    <p>
        (*) Required for registering internal courses. <br/>
        (**) Important in taking back your missing password.
    </p>
</form>
<?
require("./footer.php");
?>
