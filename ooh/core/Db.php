<?php
/**
 *
 * db class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Db
{

    private static $_instance;

    public $master_dbh;
    public $slave_dbh;
    public $conn_id;
    public $queryid;
    public $querys;
    public $sqls = array();
    public $transTimes = 0;


    public static function instance($driver = NULL)
    {
        if (self::$_instance == null) {
            if (empty($driver)) {
                $driver = &Config::instance()->db['driver'];
            }
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }


    function __construct()
    {
        /**
        $this->dbhost = Config::instance()->db['host'];
        $this->dbname = Config::instance()->db['dbname'];
        $this->username = Config::instance()->db['username'];
        $this->password = Config::instance()->db['password'];
        $this->charset = Config::instance()->db['charset'];
        $this->pconnect= Config::instance()->db['pconnect'];
        **/
        //print_r($this->_version());
        //$this->driver(Config::instance()->db['driver']);

    }


    public function connect()
    {
    if ( $this->conn_id )
        {
            return true;
        }
        $this->_connect(Config::instance()->db);
    }

    /**
     * 重链
     *
     * @author andsky 669811@qq.com
     */
    public function reset_content()
    {
        //var_dump($this->conn_id);exit;
        $ping = @mysql_ping($this->conn_id);
        if ( !$ping ){
            $this->connect();
        }
        /*
         while(!$ping)
         {
         @mysql_close($this->conn_id);
         $this->init();
         $ping = @mysql_ping($this->conn_id) ;
         if(!$ping)
         {
         sleep(2);
         }
         }
         */
    }

    /**
     * 初始化主库连接
     *
     * @author andsky 669811@qq.com
     */
    public function init_master()
    {
        if ( $this->master_dbh )
        {
            return true;
        }
    }

    /**
     * 初始化从库连接
     *
     * @author andsky 669811@qq.com
     */
    public function init_slave()
    {
        if ( $this->master_dbh )
        {
            return true;
        }
    }

    /**
     * 选择链接
     *
     * @author andsky 669811@qq.com
     */
    public function switch_connect($is_write)
    {
        if ($is_write) {
            return $this->init_master();
        }else {
            return $this->init_slave();
        }
    }

    /**
     * 版本查询
     *
     * @author andsky 669811@qq.com
     */
    public function version()
    {
        $sql = $this->_version();
        $query = $this->query($sql);
        return $query->fetch_one('ver');
    }

    /**
     * 执行查询
     * @param string $sql
     * @param bool $unbuffered
     * @author andsky 669811@qq.com
     */
    public function query($sql, $unbuffered = false)
    {
        $this->connect();
        //$this->reset_content();

        $this->sqls[] = $sql;

        /**
        if ( @filesize(TMP_PATH.'sql.log') > 1024*1024*2){
            $handle = @fopen(TMP_PATH.'sql.log', 'w');
        }else{
            $handle = @fopen(TMP_PATH.'sql.log', 'a');
        }
        @fwrite($handle, $sql."\n");
        @fclose($handle);
        **/

        $this->queryid = $this->_query($sql);
        if (!$this->queryid) {
            throw new Http500Exceptions('SQL query exception (code: [' . $this->_error_number() . ']; sql: [' . $sql . ']; message:' . $this->_error_message(), 400);
        }
        $this->querys++;

        return $this;
    }

    /**
     * 取得多条
     * @param string $type
     * @author andsky 669811@qq.com
     */
    public function fetch_array($type = 'assoc')
    {
        $rs = $this->{'_fetch_'.$type}();

        return $rs;
    }

    /**
     * 取得一条
     * @param int $n
     * @param string $type
     * @author andsky 669811@qq.com
     */
    public function fetch_one($n = 0, $type = 'assoc')
    {
        $array = $this->_fetch_one($type);
        if (empty($array)) {
            return FALSE;
        }

        if ( is_numeric( $n ) )
        {
            return $array;
        }

        return $array[$n];

    }

    /**
     * 插入
     * @param strint $table
     * @param array $data
     * @author andsky 669811@qq.com
     */
    public function insert($table, $data)
    {
        $fields = array();
        $values = array();

        foreach($data as $key => $val)
        {
            $fields[] = '`'.$key.'`';
            $values[] = $this->escape($val);
        }
        return $this->_insert($table, $fields, $values);
    }

    /**
     * 插入多条
     * @param strint $table
     * @param array $data
     * @author andsky 669811@qq.com
     */
    public function inserts($table, $data)
    {
        $fields = array();
        $values = array();
        foreach ($data as $line => $row) {
            foreach($row as $key => $val)
            {
                $fields[$key] = '`'.$key.'`';
                $values[$line][] = $this->escape($val);
            }
        }
        return $this->_inserts($table, $fields, $values);
    }

    /**
     * 替换
     * @param string $table
     * @param array $data
     * @author andsky 669811@qq.com
     */
    public function replace($table, $data)
    {
        $fields = array();
        $values = array();

        foreach($data as $key => $val)
        {
            $fields[] = '`'.$key.'`';
            $values[] = $this->escape($val);
        }


        return $this->_replace($table, $fields, $values);
    }

    /**
     * 更新
     * @param string $table
     * @param array $data
     * @param array $where
     * @author andsky 669811@qq.com
     */
    public function update($table, $data, $where)
    {
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[] = '`'.$key."` = ".$this->escape($val);
            //$fields[$key] = $this->escape($val);
        }
        return $this->_update($table, $fields, $where);
    }

    /**
     * +-N
     * @param string $table
     * @param arraay $data
     * @param string $where
     * @author andsky 669811@qq.com
     */
    public function increment($table, $data, $where)
    {
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[] = '`'.$key.'` = `'.$key.'`+'.$this->escape($val);
        }

        return $this->_update($table, $fields, $where);
    }

    /**
     * 删除
     * @param string $table
     * @param string $where
     * @author andsky 669811@qq.com
     */
    public function delete($table, $where)
    {


        return $this->_delete($table, $where);
    }

    /**
     * 数量查询
     * @param int $limit
     * @param int $offset
     * @author andsky 669811@qq.com
     */
    public function limit($limit, $offset)
    {
        return $this->_limit($limit, $offset);
    }

    public function table($name)
    {
        return $this->_table($name);
    }

    public function num_rows()
    {
        return $this->_num_rows();
    }

    /**
     * 影响行数
     *
     * @return int
     */
    public function affected_rows()
    {
        return $this->_affected_rows();
    }

    /**
     * 插入id
     *
     * @return int
     */
    public function insert_id()
    {
        return $this->_insert_id();
    }

    public function free_result($queryid)
    {
        return $this->_free_result($this->queryID);
    }

    public function is_write($sql)
    {
        if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql))
        {
            return false;
        }
        return true;
    }

    function _parse($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return false;
        }

        return true;
    }

    public function escape($str)
    {
        switch (gettype($str))
        {
            case 'string'	:	$str = "'".$this->escape_str($str)."'";
            break;
            case 'boolean'	:	$str = ($str === FALSE) ? 0 : 1;
            break;
            default			:	$str = ($str === NULL) ? "''" : "'".$str."'";
            break;
        }

        return $str;
    }


    public function list_tables()
    {


        $sql = $this->_list_tables();

        $rs = array();
        $query = $this->query($sql);

        if ($query->num_rows() > 0)
        {
            foreach($query->fetch_array() as $row)
            {
                if (isset($row['TABLE_NAME']))
                {
                    $rs[] = $row['TABLE_NAME'];
                }
                else
                {
                    $rs[] = array_shift($row);
                }
            }
        }

        return $rs;
    }

    public function list_fields($table = '')
    {
        // Is there a cached result?

        if ($table == '')
        {

            return false;
        }

        $sql = $this->_list_columns($table);

        $query = $this->query($sql);

        $rs = array();

        foreach($query->fetch_array() as $row)
        {
            if( strtolower($row['Extra']) == 'auto_increment' ){
                continue;
            }
            if (isset($row['COLUMN_NAME']))
            {
                $rs[] = $row['COLUMN_NAME'];
            }
            else
            {
                $rs[] = $row;
            }
        }

        return $rs;
    }

    public function field_exists($field_name, $table)
    {
        return ( ! in_array($field_name, $this->list_fields($table))) ? false : true;
    }

    public function truncate($table)
    {
        $sql = $this->_truncate($table);
        return $this->query($sql);
    }

    public function startTrans()
    {
        if ($this->transTimes == 0) {
            $sql = $this->_startTrans();
            $this->query($sql);
        }
        $this->transTimes++;
        return ;
    }

    public function commit()
    {
        if ($this->transTimes > 0) {
            $sql = $this->_commit();
            $this->query($sql);
            $this->transTimes = 0;
        }
        return true;
    }

    public function rollback()
    {
        if ($this->transTimes > 0) {
            $sql = $this->_rollback();
            $this->query($sql);
            $this->transTimes = 0;
        }
        return true;
    }


    public function close()
    {
        if (is_resource($this->conn_id) OR is_object($this->conn_id))
        {
            $this->_close($this->conn_id);
        }
        $this->conn_id = false;
    }




}