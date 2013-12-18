<?php

define('APP_NAME', 'app');
require '../../system/core.php';

$conf = array();
$runtimefile = '../.build/.config.php';

$build_file = Fso::instance()->fileList('../config', FALSE, '*.php');



foreach ($build_file as $value) {
    $basename = basename($value,".php");
    $conf[$basename] = require($value);
}



$content = '<?php return ';
$content .= var_export($conf, TRUE);

file_put_contents($runtimefile, $content);
file_put_contents($runtimefile, php_strip_whitespace($runtimefile));