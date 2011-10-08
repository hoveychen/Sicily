<?php

/*
 * 本文件用于定义全局的变量
 */

include_once(dirname(__FILE__) . "/config.inc.php");
include_once(dirname(__FILE__) . "/conn.inc.php");
include_once(dirname(__FILE__) . "/dataobj.inc.php");
include_once(dirname(__FILE__) . "/lib.inc.php");

//global vars
$app_config['system_path'] = dirname(dirname(__FILE__));
$app_config['system_path'] = substr($app_config['system_path'], strlen($_SERVER['DOCUMENT_ROOT']), strlen($app_config['system_path']) - strlen($_SERVER['DOCUMENT_ROOT']));
$app_config['system_path'] = str_replace('\\', '/', $app_config['system_path']);

$app_config['problem_per_page'] = 20;
$app_config['status_per_page'] = 16;
$app_config['user_per_page'] = 16;
$app_config['problem_max_n'] = 1000;
$app_config['data_path_prefix'] = dirname(__FILE__) . '/../../';
$app_config['testdata_path'] = $app_config['data_path_prefix'] . 'testdata/';
$app_config['source_path'] = $app_config['data_path_prefix'] . 'source/';
$app_config['contest_testdata_path'] = $app_config['data_path_prefix'] . 'contest/testdata';
$app_config['contest_source_path'] = $app_config['data_path_prefix'] . 'contest/source';

$app_config['max_sourcecode_length'] = 30720; // 30KB

?>
