<?php
/**
 *
 * queue class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Queue
{
    private static $_instance;


    /**
     * Queue instance
     * @param string $driver
     * @return Queue instance
     */
    public static function instance($driver = NULL)
    {
        if (self::$_instance == NULL) {
            if (!empty($driver)) {
                $driver = Config::instance()->queue['driver'];
            }
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }
    public function __construct()
    {
        $this->_init();
    }

    public function __destruct()
    {
    }

    public function __get($key)
    {
        return $this->_get($key);
    }

    public function __set($key, $data)
    {
        return $this->_set($key, $data);
    }

    public function encode($data)
    {
        return json_encode($data);
    }

    public function decode($data)
    {
        return json_decode($data, true);
    }
}



class cache_queue extends Cache
{
    private $_queue_data = array();

    protected function _init()
    {

    }

    protected function _get($key)
    {
        $queue = Cache::instance()->$key;
        if (empty($queue)) {
            return FALSE;
        }
        $this->_queue_data = $queue;

        return array_shift($this->_queue_data);
    }

    protected function _set($key, $data)
    {
        $this->_queue_data[] = $data;
        return Cache::instance()->$key = $this->_queue_data;
    }


}


class redis_queue extends Cache
{
    private $_redis = NULL;


    protected function _init()
    {
        if ( $this->_redis == NULL )
        {
            $this->_redis = new Redis();
            list($server,$port) = explode(':', Config::instance()->queue['server']);
            if ( !$this->_redis->pconnect($server, $port, Config::instance()->queue['timeout']) )
            {
                throw new Http503Exceptions('Can\'t connect to cache Redis server ');
            }
        }
        return true;

    }

    protected function _get($key)
    {
        $data = $this->_redis->rpop($key);
        if ($data == FALSE) {
            return FALSE;
        }
        return $this->decode( $data );
    }

    protected function _set($key, $data)
    {
        $data = $this->encode($data);
        return $this->_redis->lpush($key, $data);
    }

}