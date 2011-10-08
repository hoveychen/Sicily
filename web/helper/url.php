<?php

function MsgAndRedirect($url, $msg="") {
    if (!empty($msg)) {
        @session_start();
        $_SESSION['msg'] = $msg;
    }
    header("Location: $url");
    exit;
}

function MsgAndBack($msg = "", $keepCache = true) {
    if ($keepCache) {
        if (empty($msg))
            $alertMsg = ""; else
            $alertMsg = 'alert("' . htmlspecialchars($msg) . '");';
        header('Cache-control: private, must-revalidate');
        die("<script> $alertMsg history.go(-1); </script>");
    } else {
        MsgAndRedirect($_SERVER['HTTP_REFERER'], $msg);
    }
}

function MsgAndClose($msg) {
    $buf = "<script>alert('$msg');
        window.close();</script>";
    die($buf);
}

function safeget($parameter) {
    return safefetch($_GET, $parameter);
}

function safepost($parameter) {
    return safefetch($_POST, $parameter);
}

function saferequest($parameter) {
    return safefetch($_REQUEST, $parameter);
}

function safefetch(&$arg, $parameter, $errorFn = "error") {
    if (empty($parameter))
        $errorFn("Incorrect Parameter");
    if (!is_array($arg))
        $errorFn("Not an array");
    if (!isset($arg[$parameter])) {
        $parameter = htmlspecialchars($parameter);
        $errorFn("Missing required argument $parameter");
    }
    return htmlspecialchars($arg[$parameter]);
}

function tryget($p, $default = "") {
    return tryfetch($_GET, $p, $default);
}

function trypost($p, $default = "") {
    return tryfetch($_POST, $p, $default);
}

function tryrequest($p, $default = "") {
    return tryfetch($_REQUEST, $p, $default);
}

function tryfetch(&$arg, $p, $default) {
    if (isset($arg[$p]) && $arg[$p] != "")
        return $arg[$p]; else
        return $default;
}

/**
 * detect Debug mode
 * @return type 
 */
function is_debug_mode() {
    return strtolower(substr($_SERVER["HTTP_HOST"], 0, 5)) == 'debug';
}

