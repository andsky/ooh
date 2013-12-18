<?php

$core = array('Application', 'Base', 'Config', 'Controller', 'Db', 'Error', 'Exceptions', 'Model', 'Router', 'View');
$lib = array('Auth', 'Cache', 'Fso', 'Ftp', 'JS', 'Mysql', 'Pager', 'Request', 'Response', 'Session', 'String', 'Validate');

$content = '';
$runtimefile = '../.build/runtime.php';
define('APP_NAME', 'app');
require '../dev.php';
 Fso::instance()->mkdir("../.build");

foreach ($core as $value) {
    $content .= php_strip_whitespace('../core/'.$value.'.php');

}

foreach ($lib as $value) {
    $content .= php_strip_whitespace('../lib/'.$value.'.php');
}

$content .= php_strip_whitespace('../function/function.php');
$content = str_replace('<?php', '', $content);
file_put_contents($runtimefile, '<?php '. $content);