<?php
require("./navigation.php");
$logged or error("Login please");
$user = new UserTbl();
$user->Get($login_uid) or error("Invalid User ID");
$netid = $user->detail["netid"];
$netid or error("You haven't bound with netid yet.");
?>           
<div>
    <h1>Anti-authorize with NetID</h1>
    <h2>What is NetID?</h2>
    <div>NetID is a general unique ID for every students in Sun Yat-sen University.</div>
    <h2>What do I benefit from authorization?</h2>
    <div>Some of the inner contests and problems are only available to authorized users. Also (maybe) registering on-site contests in SYSU needs authorization as well.</div>
    <h2>How to authorize?</h2>
    <div>Login with your netid and password in the window below. </div>
    <h2>Can I authorize multiple accounts with the same NetID?</h2>
    <div>No. </div>
    <h2>Can I authorize my account with multiple NetID </h2>
    <div>No. </div>
    <h2>What if I don't have a NetID?</h2>
    <div>Actually, you can still enjoy most of the problems and contests in our system. Just fotget it.</div>
</div>
<hr/>
<iframe src="http://cas.sysu.edu.cn/cas/login?service=http://soj.me/netid_authorize.php?unbind=1" width="220" height="120" frameborder="0" scrolling="no">
</iframe>
</div>

<?php
require("./footer.php");
?>
