<?php

$app_name = "app";

define('APP_NAME', $app_name);
define('APP_CONTORLLER', $app_name);
define('APP_MODEL', $app_name);
define('APP_DATA', $app_name);

$appdir = dirname(dirname(__FILE__));
require dirname($appdir).'/ooh/dev.php';

class testController extends Controller{

	public function test()
	{
		//var_dump(111);
		echo Fetch::instance('php_fetch')->get("http://www.baidu.com/ss");
	}
}

Application::cli('test.test');
?>