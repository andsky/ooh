<?php

$appdir = dirname(dirname(__FILE__));
define('APP_NAME',  basename($appdir));
require dirname($appdir).'/ooh/dev.php';

$conf = require(CONF_PATH.'config.php');
$runtimefile = $appdir.'/.build/.config.php';
Fso::instance()->mkdir($appdir.'/.build');

$build_file = Fso::instance()->fileList($appdir.'/config', FALSE, '*.php');


foreach ($build_file as $value) {
    $basename = basename($value,".php");
    $conf[$basename] = require($value);
}



$content = '<?php return ';
$content .= var_export($conf, TRUE);
$content .= ';';

file_put_contents($runtimefile, $content);
//file_put_contents($runtimefile, php_strip_whitespace($runtimefile));