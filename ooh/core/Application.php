<?php
/**
 * Application class.
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Application {
    private $locale    = '';
    private static $_controls;

    public function __construct()
    {
    }

    public static function init()
    {
        $GLOBALS['_startTime'] = microtime(TRUE);
        set_error_handler(array(Error::instance(), 'Error'));
        set_exception_handler(array(Error::instance(), 'Exception'));
        date_default_timezone_set("Asia/Shanghai");
    }

    /**
     * 取得一个控制器和动作对应的路径
     *
     * @param string  $control 控制器
     * @return string
     */
    public static function loadControl($control)
    {

        $load_class = APP_PATH.'controllers/'.$control.'.php';
        $control .= 'Controller';
        if (!empty(self::$_controls[$control]))
        {
            return self::$_controls[$control];
        }
        if (is_file($load_class)){
            require($load_class);
            self::$_controls[$control] = new $control();
            return self::$_controls[$control];
        }else{
            throw new HttpExceptions("can't find controller '{$control}'", 404);
        }
    }

	/**
     * cli运行
     *
     * @author andsky 669811@qq.com
     */
    public static function cli($cli='')
    {
        if (!empty($cli)) {
            $tmp = explode('.', $cli);
            define('CONTROL', $tmp[0]);
            define('ACTION', $tmp[1]);
        }
        self::init();
        try {
            $control = CONTROL.'Controller';
            $obj = new $control();
            $obj->exec();
        } catch (Exception $e) {
            return Error::instance()->Exception($e);
        }

    }

    /**
     * 运行
     *
     * @author andsky 669811@qq.com
     */
    public static function run()
    {
        self::init();
        define('CONTROL', Router::instance()->get_control());
        define('ACTION', Router::instance()->get_action());
        try {
             self::loadControl(CONTROL)->exec();
        } catch (Exception $e) {
            return Error::instance()->Exception($e);
        }

    }



}