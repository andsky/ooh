<?php
/**
 *
 * cookie class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Cookie{
	private static $_instance;

	public static function instance()
	{
	    if(self::$_instance == NULL){
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	public function __isset($name)
	{
	    if (isset($_COOKIE[$name])) {
            return (false === empty($_COOKIE[$name]));
        } else {
            return null;
        }
	}

	public function &__get($name)
	{
	    if (isset($_COOKIE[$name])) {
	        return $_COOKIE[$name];
	    }
	    $null = null;
	    return $null;
	}

	public function __set($name, $value)
	{
	    $this->set($name, $value);
	}

	public function set($name, $value, $expire='')
	{
		$expire = empty($expire) ? time()+Config::instance()->cookie['expire'] : time()+$expire;
		setcookie($name, $value, $expire, Config::instance()->cookie['path'], Config::instance()->cookie['domain']);
	}

	public function __unset($name)
	{
		$this->set($name, '', '-3600');
		unset($_COOKIE[$name]);
	}
}
?>