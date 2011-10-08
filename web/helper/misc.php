<?php


/**
 * Get the global app config
 */
function get_app_config() {
    global $app_config;
    return $app_config;
}

function cleanCookieHash() {
    setcookie("uid", "", time() - 3600, "/");
    setcookie("hash", "", time() - 3600, "/");
    setcookie(session_name(), "", time() - 3600, "/");
}

function error($message, $url="javascript:history.go(-1);") {
    ?>


    <div id="error_msg">
        <? echo $message; ?>
        [<a href="<? echo $url; ?>" >Back</a>]
    </div>


    <?
    die();
}


/**
 * Set locale
 * @param string $lang Optional for 'cn', 'en'
 * @param type $domain Default 'Sicily'
 */
function set_language($lang = 'en', $domain = 'Sicily') {
    $availLang = array('cn' => 'zh_CN.utf8', 'en' => 'en_US.utf8');
    if (!array_key_exists($lang, $availLang))
        $lang = "en";
    $locale = $availLang[$lang];
    putenv('LANG=' . $locale);
    setlocale(LC_ALL, $locale);
    bindtextdomain($domain, dirname(__FILE__) . '/../locale/');
    textdomain($domain);
    bind_textdomain_codeset($domain, 'UTF-8');
}



/**
 * Output a binary file to client
 * No content should be output before this function
 * @param string $filename path of file to output
 * @param string $localname file name sent to client
 * $param bool $del_flag whether delete the file after output(Default TRUE)
 */
function output_file($filename, $localname, $del_flag = TRUE) {
    header("Content-type: application/octet-stream");
    header("Content-Length: " . filesize($filename));
    header("Content-Disposition: attachment; filename=\"$localname\"");
    $fp = fopen("php://output", "w");
    $outfile = fopen($filename, "r");
    while (!feof($outfile)) {
        fwrite($fp, fread($outfile, 8 * 1024 * 1024));
    }
    fclose($fp);
    if ($del_flag) {
        unlink($filename);
    }
    die();
}

function array_select($needle, $haystackarray, $default) {
    if (in_array($needle, $haystackarray)) {
        return $needle;
    } else {
        return $default;
    }
}

?>
