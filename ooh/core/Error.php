<?php
/**
 *
 * error class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Error {

    private static $_instance;

    private static $_errorLevel = array();


    function __construct()
    {
    }

    public static function instance() {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    function Exception($exception)
    {
        // these are our templates
        $traceline = "#%s %s(%s): %s(%s)";
        $log = "OOH PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n";
        $html = "OOH PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s<br />Stack trace:\n%s";
        $trace = $exception->getTrace();
        foreach ($trace as $key => $stackPoint) {
            $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
        }
        $result = array();
        foreach ($trace as $key => $stackPoint) {
            $result[] = sprintf(
            $traceline,
            $key,
            $stackPoint['file'],
            $stackPoint['line'],
            $stackPoint['function'],
            implode(', ', $stackPoint['args'])
            );
        }
        $result[] = '#' . ++$key . ' {main}';
        $data				= array();
        $data['type']	= get_class($exception);
        $data['message']	= $exception->getMessage();
        $data['code']		= ($exception instanceof HttpExceptions) ? $exception->httpCode : 503;
        $data['file']		= $exception->getFile();
        $data['line']		= $exception->getLine();
        $data['trace'] = implode("<br />", $result);

        Response::instance()->sendStatus($data['code']);

        if (Config::instance()->error['display']) {
            if (!isset(Config::instance()->error['exception_tpl'])) {
                Config::instance()->error['exception_tpl'] = SYS_PATH.'/config/exception.default.php.tpl';;
            }
            include Config::instance()->error['exception_tpl'];

        }else {
            if (isset(Config::instance()->error['error_tpl'])) {
                Config::instance()->error['error_tpl'] = SYS_PATH.'/config/error.default.php.tpl';
            }
            include Config::instance()->error['error_tpl'];
        }
        if (Config::instance()->error['log']) {
            $time=date('Y-m-d H:i:s');
            $log_path = Config::instance()->error['path'];
            Fso::instance()->mkdir($log_path);
            $log_path =  $log_path . DIRECTORY_SEPARATOR. date("Y-m-d").'.log';
            $data['trace'] = implode("\n", $result);
            $msg = sprintf($log, $data['type'], $data['message'], $data['file'], $data['line'], $data['trace']);
            error_log($msg, 3, $log_path);
        }

    }


    /**
     * 错误处理方法
     *
     * @param integer $errorNo 错误号
     * @param string $errorStr 错误描述字符串
     * @param string $errorFile 错误发生的文件
     * @param string $errorLine 错误发生的行号
     * @param string $errorContext 错误的上下文
     * @return boolean
     */
    function Error($errno, $errstr, $errfile, $errline)
    {
        if (self::$_errorLevel == NULL) {
            self::$_errorLevel = $this->errorLevels(Config::instance()->error['level']);
        }

        if (!in_array($errno, self::$_errorLevel)) {
            return true;
        }
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $errors = "Notice";
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $errors = "Warning";
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $errors = "Fatal Error";
                break;
            default:
                $errors = "Unknown";
                break;
        }
        if (Config::instance()->error['display']) {
            printf ("<br />\n<b>%s</b>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $errors, $errstr, basename($errfile), $errline);
        }
        if (Config::instance()->error['log']) {
            $time=date('Y-m-d H:i:s');
            $log_path = Config::instance()->error['path'];
            Fso::instance()->mkdir($log_path);
            $log_path =  $log_path . DIRECTORY_SEPARATOR. date("Y-m-d").'.log';
            error_log(sprintf("%s %s:  %s in %s on line %d \n", $time, $errors, $errstr, $errfile, $errline), 3, $log_path);
        }
        return true;
    }


    /**
     * 根据级别取得支持的错误级别
     *
     * @param integer $level 错误级别
     * @return array
     */
    private function errorLevels($level)
    {
        $level_names = array(
                            E_ERROR => "E_ERROR",
                            E_WARNING => "E_WARNING",
                            E_PARSE => "E_PARSE",
                            E_NOTICE => "E_NOTICE",
                            E_CORE_ERROR => "E_CORE_ERROR",
                            E_CORE_WARNING => "E_CORE_WARNING",
                            E_COMPILE_ERROR => "E_COMPILE_ERROR",
                            E_COMPILE_WARNING => "E_COMPILE_WARNING",
                            E_USER_ERROR => "E_USER_ERROR",
                            E_USER_WARNING => "E_USER_WARNING",
                            E_USER_NOTICE => "E_USER_NOTICE"
                    );
        //错误级别
        if(defined("E_STRICT")) {
            $level_names[E_STRICT] = "E_STRICT";
        }
        if(defined("E_RECOVERABLE_ERROR")) {
            $level_names[E_RECOVERABLE_ERROR] = "E_RECOVERABLE_ERROR";
        }
        $levels = array();
        if(($level & E_ALL) == E_ALL) {
            $levels[] = E_ALL;
            $level &= ~E_ALL;
        }
        foreach($level_names as $key => $name) {
            if(($level & $key) == $key) {
                $levels[] = $key;
            }
        }
        return $levels;
    }
}