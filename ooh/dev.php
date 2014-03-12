<?php

/**
 *
 * bootstrap file
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2008-9-10
 */

define('VERSION', '1.0');

//定义系统路径
if(!defined('OOH_PATH')) define('OOH_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

define('BASE_PATH', dirname(OOH_PATH).DIRECTORY_SEPARATOR);
define('CORE_PATH', OOH_PATH.'core'.DIRECTORY_SEPARATOR);
define('LIB_PATH', OOH_PATH.'lib'.DIRECTORY_SEPARATOR);
define('PLU_PATH', OOH_PATH.'plugins'.DIRECTORY_SEPARATOR);
define('CONF_PATH', OOH_PATH.'config'.DIRECTORY_SEPARATOR);
define('BUILD_PATH', OOH_PATH.'.build'.DIRECTORY_SEPARATOR);
if(!defined('TMP_PATH'))  define('TMP_PATH', BASE_PATH.'tmp'.DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH.APP_NAME.DIRECTORY_SEPARATOR);
define('APP_CONF_PATH', APP_PATH.'config'.DIRECTORY_SEPARATOR);
define('APP_BUILD_PATH', APP_PATH.'.build'.DIRECTORY_SEPARATOR);

if (!defined('APP_CONTORLLER')) define('APP_CONTORLLER', APP_NAME);
if (!defined('APP_MODEL')) define('APP_MODEL', APP_NAME);
if (!defined('APP_LIB')) define('APP_LIB', APP_NAME);
if (!defined('APP_PLU')) define('APP_PLU', APP_NAME);

define('APP_CONTORLLER_PATH', BASE_PATH.APP_CONTORLLER.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR);
define('APP_MODEL_PATH', APP_PATH.'models'.DIRECTORY_SEPARATOR);
define('APP_LIB_PATH', APP_PATH.'lib'.DIRECTORY_SEPARATOR);
define('APP_PLU_PATH',APP_PATH.'plugins'.DIRECTORY_SEPARATOR);
define('SHARED_MODEL_PATH', BASE_PATH.APP_MODEL.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR);
define('SHARED_LIB_PATH', BASE_PATH.APP_LIB.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR);
define('SHARED_PLU_PATH', BASE_PATH.APP_PLU.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR);

require(OOH_PATH . '/function/function.php');
//error_reporting(E_ALL);
//加载核心文件
require(CORE_PATH . 'Base.php');
require(CORE_PATH . 'Application.php');
include(CORE_PATH . 'Error.php');
require(CORE_PATH . 'Router.php');
require(CORE_PATH . 'Exceptions.php');
require(CORE_PATH . 'Controller.php');
require(CORE_PATH . 'Config.php');
include(CORE_PATH . 'Db.php');
include(CORE_PATH . 'Model.php');
include(CORE_PATH . 'View.php');
?>