<?php
/**
 *
 * session class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Session
{
    private static $_instance;
    protected $_sess_data = array();
    protected $_sess_id;
    protected $_sess_expire = 7200;


    public static function instance()
    {
        if (self::$_instance == null) {
            $driver = Config::instance()->session['driver'];
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->_sess_expire = Config::instance()->session['expire'];
        $this->_sess_id = empty($_COOKIE[Config::instance()->session['name']]) ? $this->gen_sid() : $_COOKIE[Config::instance()->session['name']];
        //header('P3P:CP="CAO IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        //header('P3P:CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		//header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        setcookie(Config::instance()->session['name'], $this->_sess_id, time() + 31622400, Config::instance()->cookie['path'], Config::instance()->cookie['domain']);
        register_shutdown_function(array(&$this, '__write'));
        $this->init();

    }

    public function __destruct()
    {
    }
    private function gen_sid()
    {
        return md5(uniqid(microtime() . Request::instance()->getIP(), true));
    }
    private function init()
    {
        $this->_init();
        $this->__read();
    }
    private function __read()
    {
        $data = $this->_read();
        if (!empty($data)) {
            $this->_sess_data = $data;
        }
    }

    public function __write()
    {
        $this->_write();
    }

    public function &__get($key)
    {
        if (isset($this->_sess_data[$key])) {
            return $this->_sess_data[$key];
        }
        $null = null;
        return $null;
    }

    public function __set($key, $value)
    {
        return $this->_sess_data[$key] = $value;
    }

    public function __isset($key)
    {
        if (isset($this->_sess_data[$key])) {
            return (false === empty($this->_sess_data[$key]));
        } else {
            return null;
        }
    }
    public function __unset($key)
    {
        unset($this->_sess_data[$key]);
    }

    public function get($key)
    {
        return $this->_sess_data[$key];
    }

    public function set($key, $value)
    {
        return $this->_sess_data[$key] = $value;
    }

    public function del($key)
    {
        unset($this->_sess_data[$key]);
        return true;
    }

    public function flash($key)
    {
        $value =  $this->_sess_data[$key];
        unset($this->_sess_data[$key]);
        return $value;
    }

    public function clear()
    {
        $this->_sess_data = array();
    }

    public function destroy()
    {
        $this->_sess_data = array();
    }

}



class cache_session extends Session{

    protected function _init()
    {
    }

    protected function _read()
    {
        return Cache::instance()->exp($this->_sess_expire)->get($this->_sess_id);
    }

    protected function _write()
    {
        Cache::instance()->exp($this->_sess_expire)->set($this->_sess_id, $this->_sess_data);
    }

}


class file_session extends Session{
    private $_sess_path;

    protected function _init()
    {
        if ($this->_sess_path == null) {
            $this->_sess_path = $this->_get_path();
        }
    }


    protected function _read()
    {
        if (!file_exists($this->_sess_path.$this->_sess_id)) {
            return array();
        }
        if (filemtime($this->_sess_path.$this->_sess_id) + $this->_sess_expire < time()) {
            return array();
        }
        $data = file_get_contents($this->_sess_path.$this->_sess_id);
        return json_decode($data, true);
    }

    protected function _write()
    {
        return file_put_contents($this->_sess_path.$this->_sess_id, json_encode($this->_sess_data));
    }

    private function _get_path()
    {
        $path = TMP_PATH .'sess/';
        Fso::instance()->mkdir($path);
        return  $path;
    }
}

class memcache_session extends Session{

    private $_mc = NULL;

    protected function _init()
    {
        if ( $this->_mc == NULL )
        {
            $this->_mc = new Memcache;

            list($server, $port) = explode(':', Config::instance()->session['server']);
            if ( !$this->_mc->pconnect($server, $port) )
            {
                throw new Http503Exceptions('Can\'t connect to session memcache server ');
            }
        }
    }

    protected function _read()
    {
        $data = $this->_mc->get($this->_sess_id);
        if ( !$data ){
            return array();
        }
        return json_decode($data, true);
    }

    protected function _write()
    {
        return $this->_mc->set($this->_sess_id, json_encode($this->_sess_data), false, $this->_sess_expire);
    }

}