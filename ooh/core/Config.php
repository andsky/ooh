<?php
/**
 *
 * config class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Config
{
    private static $_config  = array();
	private static $_instance = NULL;
	private $config_name = '.config';


	public static function instance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __construct()
    {
    }


    /**
     * 取得某个模块的配置  以模块自定义文件优先
     *
     * @param string $module 模块名
     * @return Config
     * @since 1.0
     */
    private function load()
    {
        if ( !empty(self::$_config) )
        {
            return TRUE;
        }
        $config_file = BUILD_PATH.$this->config_name.'.php';
        if (is_file( APP_BUILD_PATH.$this->config_name.'.php'  )) {
            $config_file = APP_BUILD_PATH.$this->config_name.'.php';
        }
        self::$_config = require( $config_file );

    }

	public function &__get($name)
	{
	    $this->load();
	    if (isset(self::$_config[$name])) {
	        return self::$_config[$name];
	    }
	    $null = NULL;
	    return $null;

	}

	public function __isset($name)
	{
		return isset(self::$_config[$name]);
	}

	public function __set($name, $value)
	{
		self::$_config[$name] = $value;
	}

	public function __unset($name)
	{
	    unset(self::$_config[$name]);
	}
}