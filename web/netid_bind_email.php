<?php
require("./navigation.php");
$logged or error("Login please");
$user = new UserTbl();
$user->Get($login_uid) or error("Invalid User ID");
$netid = $user->detail["netid"];
$netid and error("You have already bound with netid $netid");
?>           
<div>
    <h1>Authorize with NetID</h1>
    <h2>What is NetID?</h2>
    <div>NetID is a general unique ID for every students in Sun Yat-sen University.</div>
    <h2>What do I benefit from authorization?</h2>
    <div>Some of the inner contests and problems are only available to authorized users. Also (maybe) registering on-site contests in SYSU needs authorization as well.</div>
    <h2>How to authorize?</h2>
    <div>Fill in your NetID below, and you will receive a authorization email in your NetID mailbox. </div>
    <h2>Can I authorize multiple accounts with the same NetID?</h2>
    <div>No. </div>
    <h2>Can I authorize my account with multiple NetID </h2>
    <div>No. </div>
    <h2>Why am I not able to receive email in my mailbox.</h2>
    <div>Only netid@student.sysu.edu.cn, netid@mail.sysu.edu.cn, netid@mail2.sysu.edu.cn can receive the email.</div>
    <h2>What if I don't have a NetID?</h2>
    <div>Actually, you can still enjoy most of the problems and contests in our system. Just fotget it.</div>
    <h2>Why do I get no authorization email?</h2>
    <div><ol><li>If you are using @mail2.sysu.edu.cn, please check @student.sysu.edu.cn.</li>
            <li>Check if the mail is regarded as spam email. >_< </li></ol></div>
    <hr/>
    <form id="apply_binding" name="apply_binding" method="post" action="process.php?act=BindNetid">
        <input name="uid" type="hidden" value="<? echo $login_uid; ?>" />
        Netid:
        <input name="netid" type="text" value="" maxlength="20" size="20" />
        <input type="submit" value="Submit"/>
    </form>
</div>

<?php
require("./footer.php");
?>
