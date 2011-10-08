<?php

require_once dirname(__FILE__) . '/helper/urlhelper.php';

function __autoload($class_name) {
    include_once dirname(__FILE__) . "/model/$class_name.php";
}

function main() {
    
}

main();

?>
