<?php

/**
 * Send a mail
 * @param type $address
 * @param type $subject
 * @param type $msg 
 */
function SendMail($address, $subject, $msg) {
    empty($address) and error("Address can not be empty");
    empty($subject) and error("Subject should not be empty");
    empty($msg) and error("Email content should not be empty");
    $header = "";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-type: text/html; charset=utf-8\n";
    $header .= "From: Sicily Online Judge System <noreply@soj.me>\n";
    $header .= "X-Purpose: system automatic generated mail.\n";
    $header .= "X-Priority: 1 (Highest)\n";
    $header .= "X-MSMail-Priority: High\n";
    if (!mail($address, $subject, $msg, $header, "-fnoreply@soj.me"))
        error("Fail to send mail to $address.");
}

function SendMailByNetid($netid, $subject, $msg) {
    $netid_suffix = array("@mail2.sysu.edu.cn", "@mail.sysu.edu.cn", "@student.sysu.edu.cn");
    foreach ($netid_suffix as $suffix) {
        SendMail($netid . $suffix, $subject, $msg);
    }
    return 0;
}


?>
