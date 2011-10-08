<?php
require_once("inc/user.inc.php");

$mod = safeget('mod');
$func = safeget('func');
$class_name = "Json".ucfirst($mod);

if (class_exists($class_name)) {
    die('Invalid Mod');
}

$mod_path = dirname(__FILE__). '/json/'. $mod. ".php";
if (!file_exists($mod_path)) {
    die('Invalid Mod');
}

require_once $mod_path;
if (!method_exists($class_name, $func)) {
    die('Invalid Func');
}

function __autoload($class_name) {
    @include_once "model/$class_name.php";
}

$r = new ReflectionMethod($class_name, $func);
$params = $r->getParameters();
$func_param_array = array();
foreach ($params as $param) {
    //$param is an instance of ReflectionParameter
    $name = $param->getName();
    if (isset($_POST[$name])) {
        $func_param_array[] = $_POST[$name];
    } else if (isset($_GET[$name])) {
        $func_param_array[] = $_GET[$name];
    } else {
        die('null');
    }
}

$data = call_user_func_array($class_name."::".$func, $func_param_array);
echo json_encode($data);

?>
