<?php
/**
 *
 * cache class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Cache
{
    private static $_instance;
    protected $_exp = 3600;


    public static function instance($driver = NULL)
    {
        if (self::$_instance == null) {
            if (empty($driver)){
                $driver = Config::instance()->cache['driver'];
            }
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->_exp = Config::instance()->cache['exp'];
        $this->_init();
    }

    public function __destruct()
    {
    }

    public function exp($exp)
    {
        $this->_exp = $exp;
        return self::$_instance;
    }

    public function __get($key)
    {
        return $this->_get($key);
    }

    public function __set($key, $data)
    {
        return $this->_set($key, $data);
    }

    public function __unset($key)
    {
        return $this->_del($key);
    }

    public function get($key)
    {
        return $this->_get($key);
    }

    public function set($key, $data)
    {
        return $this->_set($key, $data);
    }

    public function del($key)
    {
        return $this->_del($key);
    }

    public function encode($data)
    {
        return json_encode($data);
    }

    public function decode($data)
    {
        return json_decode($data, TRUE);
    }
}



class file_cache extends Cache
{

    protected function _init()
    {
    }

    protected function _get($key)
    {
        $filename = $this->_get_filename($key);
        if ( !file_exists( $filename ) ) {
            return NULL;
        }
        if(  $this->_exp  > 0 ){
            if( time() - filemtime($filename) > $this->_exp ){
                return NULL;
            }
        }
        $content = Fso::instance()->read($filename);
        return $this->decode( $content );
    }

    protected function _set($key,$data)
    {
        $filename = $this->_get_filename($key);
        return Fso::instance()->write($filename,$this->encode($data));
    }

    protected function _del($key)
    {
        return @unlink( $this->_get_filename($key) );

    }

    private function _get_filename($key)
    {
        $hash =  md5( trim($key) );
        $cache_dir = TMP_PATH .'cache/'.substr($hash, 0, 1) . '/' . substr($hash, 1, 1) . '/';
        Fso::instance()->mkdir($cache_dir);
        return $cache_dir.$hash.'.php';
    }
}


class memcache_cache extends Cache
{
	private $_mc = NULL;


    protected function _init()
    {
		if ( $this->_mc == NULL )
        {
            $this->_mc = new Memcache;

    		list($server,$port) = explode(':', Config::instance()->cache['server']);
            if ( !$this->_mc->pconnect($server,$port) )
            {
                throw new Http503Exceptions('Can\'t connect to cache memcache server ');
            }
        }
		return TRUE;

    }

    protected function _get($key)
    {
        $content = $this->_mc->get($key);
        if ( empty($content) ){
            return NULL;
        }
        return $this->decode( $content );
    }

    protected function _set($key,$data)
    {

        $data = $this->encode($data);
        return $this->_mc->set($key, $data, false, $this->_exp);
    }

    protected function _del($key)
    {
        if ( !$key ){
            return FALSE;
        }
        return $this->_mc->delete($key);

    }


}


class redis_cache extends Cache
{
    private $_redis = null;


    protected function _init()
    {
        if ( $this->_redis == null )
        {
            $this->_redis = new Redis();
            list($server,$port) = explode(':', Config::instance()->cache['server']);
            if ( !$this->_redis->pconnect($server, $port, Config::instance()->cache['timeout']) )
            {
                throw new Http503Exceptions('Can\'t connect to cache Redis server ');
            }
        }
        return true;

    }

    protected function _get($key)
    {
        $data = $this->_redis->get($key);
        if (empty($data)) {
            return null;
        }
        if (ctype_digit($data)) {
            return $data;
        }

        return $this->decode( $data );
    }

    protected function _set($key, $data)
    {
        if (!ctype_digit($data)) {
            $data = $this->encode($data);
        }
        if ($this->_exp == 0) {
            return $this->_redis->set($key, $data);
        }
        return $this->_redis->set($key, $this->_exp, $data);
    }

    protected function _del($key)
    {
        if ( !$key ){
            return FALSE;
        }
        return $this->_redis->delete($key);

    }


}


class baememcache_cache extends Cache
{
    private $_mc = null;


    protected function _init()
    {
        if ( $this->_mc == null )
        {
            $this->_mc = new BaeMemcache();

            if (Config::instance()->cache['server']) {
                $this->_mc->set_shareAppid(Config::instance()->cache['server']);
            }
        }
        return true;

    }

    protected function _get($key)
    {
        $data = $this->_mc->get($key);
        if ( empty($data) ){
            return null;
        }
        return $this->decode( $content );
    }

    protected function _set($key,$data)
    {

        $data = $this->encode($data);
        return $this->_mc->set($key, $data, false, $this->_exp);
    }

    protected function _del($key)
    {
        if ( !$key ){
            return false;
        }
        return $this->_mc->delete($key);

    }


}
