<?php

$app_name = "app";

define('APP_NAME', $app_name);
define('APP_CONTORLLER', $app_name);
define('APP_MODEL', $app_name);
define('APP_DATA', $app_name);

require '../ooh/dev.php';

Application::run();
?>