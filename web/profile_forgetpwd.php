<?php

require("./navigation.php");
?>           
<script>
    function onResetPwd(data) {
        if (!data.success) {
            $("#msg_email").text(data.status);
        }
    }
    function onGetEmail(data) {
        if (data.success) {
            $("#msg_email").text("Reseting email will be sent to your mailbox " 
                + data.email);
            $.post("action.php?act=ResetPwd",
			{"username": $("#username_box").val()},
			onResetPwd, "json");
        } else {
            $("#msg_email").text("Invalid username"); 
        }
        $("#reset_password input:submit").removeAttr("disabled");
    }
    function onSubmit(button){
        $.post("action.php?act=GetEmail", 
		{"username": $("#username_box").val()}, 
		onGetEmail, "json"
	);
        button.disabled = true;
    }
    
</script>
<div>
    <h1>Reset Password</h1>
    <h2>Can I find my original password?</h2>
    <div>Never. We don't know it as well. We can only reset your password.</div>
    <h2>How to reset?</h2>
    <div>We will send you an email containing a link to help you set up new password.</div>
    <h2>Why do I get no reseting email in my mailbox?</h2>
    <div>For some reasons, you should check whether the email is detected as spam mail by your mailbox.</div>
    <h2>What if I also forget the password of my mailbox?</h2>
    <div>Contact our <a href="mailto: hoveychen@gmail.com">administrator</a>. If your ownership of the account is confirmed, we will help you to reset your email address.</div>
    <hr/>
    <div id="msg_email"></div>
    <form id="reset_password" name="reset_password" method="post" action="action.php?act=ResetPwd" onsubmit="onSubmit(this);return false">
        Username:
        <input id="username_box" name="username" type="text" value="" maxlength="20" size="20"> 
        <input type="submit" value="Submit" />
    </form>

</div>

<?php

require("./footer.php");
?>
