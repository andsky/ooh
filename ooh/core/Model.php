<?php
/**
 * Model class
 *
 * @author andsky<andsky888@gmail.com>
 * @method array fetch_array db
 * @method array fetch_one db
 */
class Model{
    /**
     * 数据库链接对象
     *
     * @var db
     */
    public static $db = null;
    private static $_instance;

    protected $_select = array();
    protected $_join = array();
    protected $_from = array();
    protected $_distinct = false;
    protected $_index = '';

    protected $_where = array();
    protected $_like = array();
    protected $_instr = array();
    protected $_offset = '';
    protected $_limit = '';

    protected $_group = array();
    protected $_order = array();
    protected $_having = array();
    protected $_autoData = array();


    /**
     * 数据表前缀
     *
     * @var string
     * @access protected
     */
    protected $_tablePrefix = '';



    /**
     * 数据库表字段信息
     * 包括字段名，字段类型，是否为空，是否有默认值
     *
     * @access protected
     */
    protected $_fields = array();



    /**
     * 构造函数
     *
     */
    function __construct()
    {
        // 获取数据库操作对象
        if( self::$db == null ){
            self::$db = Db::instance();
        }
        // 数据表字段检测
        $this->getTableInfo();

    }

    /**
     * 取得实例
     *
     * @return Model
     */
    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 设置表前缀
     * Enter description here ...
     * @param string $table
     * @return object
     */
    public function dbprefix($table = '')
    {
        if ($table == '')
        {
            throw new Http503Exceptions('table is null');
        }

        return $this->_tablePrefix.$table;
    }

    /**
     * 取得表结构信息
     *
     */
    public function getTableInfo()
    {
        if ( !empty( $this->_fields ) || empty( $this->_tableName ) ){
            return ;
        }
        $file = TMP_PATH . 'table' . DIRECTORY_SEPARATOR .$this->_tableName.'.php';
        if ( file_exists( $file )  ){
            $this->_fields = json_decode(file_get_contents($file),true);
        }else{
            if ( !is_dir(TMP_PATH . 'table') ){
                @mkdir(TMP_PATH . 'table', 0777, true);
            }
            $rs = self::$db->list_fields($this->_tableName);
            foreach($rs as $k=>$v){
                $this->_fields[$v['Field']] = strstr($v['Type'],'int') ? (int)$v['Default'] : $v['Default'];
            }
            file_put_contents($file, json_encode($this->_fields));
        }
    }

    /**
     * 名称 查询字段
     * @param mixed $field 字段名
     * @return Model
     */
    public function select($field = '*')
    {
        if ( !is_array( $field ) )
        {
            $field = explode(',', $field);
        }

        foreach ( $field as $v )
        {
            // $v != '*' &&
            if ( !strpos($v, '.') && count( $field ) > 1 )
            {
                $val = $this->_tableName.'.'.$v;
            }else
            {
                $val = $v;
            }

            $this->_select[] = $val;
        }

        return $this;
    }

    /**
     * 索引信息
     * 指定索引字段
     * @param string $index
     * @return Model
     */
    public function index($index)
    {
        $this->_index = $index;
        return $this;
    }

    /**
     * 取得某个字段的最大值
     *
     * @access public
     * @param string $field  字段名
     * @param string $alias  别名
     * @return Model
     */
    public function max($field, $alias = 'max')
    {
        $alias = ($alias != '') ? $alias : $field;

        $sql = 'MAX('.$field.') AS '.$alias;
        $this->_select[] = $sql;
        return $this;
    }

    /**
     * 取得某个字段的最小值
     *
     * @access public
     * @param string $field  字段名
     * @param string $alias  别名
     * @return Model
     */
    public function min($field, $alias = 'min')
    {
        $alias = ($alias != '') ? $alias : $field;

        $sql = 'MIN('.$field.') AS '.$alias;
        $this->_select[] = $sql;
        return $this;
    }

    /**
     * 统计某个字段的平均值
     *
     * @access public
     * @param string $field  字段名
     * @param string $alias 别名
     * @return Model
     */
    public function avg($field, $alias = 'avg')
    {
        $alias = ($alias != '') ? $alias : $field;

        $sql = 'AVG('.$field.') AS '.$alias;
        $this->_select[] = $sql;
        return $this;
    }

    /**
     * 统计某个字段的总和
     *
     * @access public
     * @param string $field  字段名
     * @param string $alias  别名
     * @return Model
     */
    public function sum($field,$alias = 'sum')
    {
        $alias = ($alias != '') ? $alias : $field;

        $sql = 'SUM('.$field.') AS '.$alias;
        $this->_select[] = $sql;
        return $this;
    }

    /**
     * 统计满足条件的记录个数
     *
     * @access public
     * @param string $field  字段名
     * @param string $alias  别名
     * @return Model
     */
    public function count($field = '*', $alias = 'count')
    {
        $alias = ($alias != '') ? $alias : $field;

        $sql = 'COUNT('.$field.') AS '.$alias;
        $this->_select[] = $sql;
        return $this;
    }



    //'user'=>'id=uid'

    /**
     * 表连接
     *
     * @param array $table 表名
     * @param string $type  连接方式
     * @return Model
     */
    public function join($table, $type = 'LEFT')
    {
        $type = strtoupper($type);

        foreach ( $table as $k => $v )
        {
            $ar = explode( '=', $v );
            if ( !strstr('.',$ar[0]) )
            {
                $ar[0] = $k.'.'.$ar[0];
            }
            if ( !strstr('.',$ar[1]) )
            {
                $ar[1] = $this->_tableName.'.'.$ar[1];
            }

            $this->_join[] = $type.' JOIN '.$this->dbprefix($k).' ON '.$ar[0].' = '.$ar[1];
        }
        //preg_match('/([\w\.]+)([\W\s]+)(.+)/', $v, $match);
        //$sql = $type.' JOIN '.$this->dbprefix($table).' ON '.str_replace( '=','='.$this->_tableName.'.',$table.'.'.$cond );


        return $this;
    }


    /**
     * distinct
     *
     * @param bool $val
     * @return Model
     */
    public function distinct($val = true)
    {
        $this->_distinct = (is_bool($val)) ? $val : true;
        return $this;
    }

    /**
     * 不包括
     * mysql not in (...)
     * @param array $where 查询备件
     * @param string $type 类型
     * @return Model
     */
    public function notin($where, $type = 'AND')
    {
        return $this->in($where,true,$type);
    }

    /**
     * 取得in条件
     * mysql in (...)
     * @param array $where 条件
     * @param bool $not
     * @param string $type 类型
     * @return Model
     */
    public function in($where, $not = false, $type = 'AND')
    {
        foreach ( $where as $k => $v )
        {
            $prefix = (count($this->_where) == 0) ? '' : $type.' ';
            $not = ($not) ? ' NOT' : '';
            $arr = array();
            if ( is_array($v) ){
                $values = $v;
            }else{
                $values = explode( ',', $v );
            }
            foreach ( $values as $value )
            {
                $arr[] = self::$db->escape($value);
            }

            $this->_where[] = $prefix . $k . $not . ' IN (' . implode(', ', $arr) . ') ';
        }
        return $this;
    }


    /**
     * 或条件
     * where 之间条件
     * @param array $where 条件
     * @return Model
     */
    public function orwhere($where)
    {
        return $this->where($where,'OR');
    }


    /**
     * 条件
     *
     * @param array $where
     * @param string $type
     * @param string $type2
     * @return Model
     */
    public function where(array $where, $type = 'AND', $type2 = '')
    {
        foreach ( $where as $k => $v )
        {
            $prefix = (count($this->_where) == 0) ? '' : $type.' ';

            if ( !self::$db->_parse($k) && is_null($v) )
            {
                $k .= ' IS NULL';
            }

            if ( !self::$db->_parse($k))
            {
                $k .= ' =';
            }
            if ( !is_null($v) )
            {
                $v = self::$db->escape($v);
            }
            if ( !empty($type2) ){
                $_where[] = $k.' '.$v;
            }else{
                $this->_where[] = $prefix.$k.' '.$v;
            }

        }

        if ( !empty($type2) && !empty($_where)){
            $this->_where[] = $prefix .'('.  implode(" $type2 ", $_where) . ') ';
        }

        return $this;
    }

    /**
     * or like
     *
     * @param array $where
     * @param bool $not
     * @param string $like
     * @return Model
     */
    public function orlike($where, $not = false, $like = 'all')
    {
        return $this->like($where,$not,'OR',$like);
    }

    /**
     * like
     * Enter description here ...
     * @param array $where
     * @param bool $not
     * @param string $type
     * @param string $like
     * @return Model
     */
    public function like(array $where, $not = false, $type = 'AND', $like = 'all')
    {
        foreach ( $where as $k => $v )
        {
            $prefix = (count($this->_like) == 0) ? '' : $type.' ';
            $not = ($not) ? ' NOT' : '';
            $arr = array();
            $v = str_replace("+", " ", $v);
            $values = explode( ' ', $v );
            foreach ( $values as $value )
            {
                if ( $like == 'left' )
                {
                    $keyword = "'%{$value}'";
                }else if ( $like == 'right' )
                {
                    $keyword = "'{$value}%'";
                }else
                {
                    $keyword = "'%{$value}%'";
                }
                $arr[] =  $k . $not.' LIKE '.$keyword;
            }

            $this->_like[] = $prefix .'('.  implode(' OR ', $arr) . ') ';
        }
        return $this;
    }

    public function orinstr($where)
    {
        return $this->instr($where,'OR');
    }

    public function instr($where, $type = 'AND')
    {
        foreach ( $where as $k => $v )
        {
            $prefix = (count($this->_instr) == 0) ? '' : $type.' ';
            $arr = array();
            $v = str_replace('+', ' ', $v);
            $values = explode( ' ', $v );
            foreach ( $values as $value )
            {
                $arr[] =  'INSTR('.$k.', '.self::$db->escape($value).')';
            }
            $this->_instr[] = $prefix .'('.  implode(' OR ', $arr) . ') ';
        }
        return $this;
    }

    /**
     * group 排序
     *
     * @param string|array $by
     * @return Model
     */
    public function group($by)
    {
        if (is_string($by))
        {
            $by = explode(',', $by);
        }
        foreach ( $by as $v )
        {
            $this->_group[] = $v;
        }
        return $this;
    }

    /**
     * having
     *
     * @param array $by
     * @param string $type
     * @return Model
     */
    public function having($by, $type = 'AND')
    {
        foreach ( $by as $k => $v )
        {
            $prefix = (count($this->_having) == 0) ? '' : $type.' ';

            if ( !self::$db->_parse($k))
            {
                $k .= ' =';
            }
            if ( !is_null($v) )
            {
                $v = self::$db->escape($v);
            }
            $this->_where[] = $prefix.$k.' '.$v;
        }
        return $this;
    }
    /**
     * 排序
     *
     * @param string $by 条件
     * @param string $direction 方向 desc|asc|rand
     * @return Model
     */
    public function by($by, $direction = 'desc')
    {
        $direction = strtoupper($direction);

        if ( $direction == 'RAND' )
        {
            $direction = 'RAND()';
        }

        $this->_order[] = $by.' '.$direction;
        return $this;
    }

    /**
     * 查询数量
     *
     * @access public
     * @param int|stdClass $value 数量
     * @param int $offset              偏移
     * @return Model
     */
    public function limit($value, $offset = '')
    {
        if ( is_object( $value ) )
        {
            $offset = $value->offset();
            $value = $value->size();
        }

        $this->_limit = $value;

        if ($offset != '') $this->_offset = $offset;

        return $this;
    }

    /**
     * 偏移量
     *
     * @param int $value
     * @return Model
     */
    public function offset($value)
    {
        $this->_offset = $value;
        return $this;
    }
    /**
     * 按主健查询
     * Enter description here ...
     * @param string|array $id
     * @param bool $fetch 是否查询
     * @return Model
     */
    public function pk($id, $fetch = FALSE)
    {
        $where = array(
            $this->_PK => $id
        );
        if (is_array($id) || strpos($id, ',')) {
            $this->in($where);
        }else{
            $this->where($where);
        }
        if ($fetch) {
            return $this->_compile_select()->fetch_one();
        }
        return $this;
    }

    /**
     * 联合离健查询
     * @param unknown_type $id
     * @param unknown_type $fetch
     * @author andsky 669811@qq.com
     */
    public function pk_union($id, $fetch = FALSE)
    {
        $pk = explode(',', $this->_PK);
        if (!is_array($id)) {
            $id = explode(',', $id);
        }
        foreach ($pk as $key => $value) {
            $this->where(array($value => $id[$key]));
        }
        if ($fetch) {
            return $this->_compile_select()->fetch_one();
        }
        return $this;

    }

    /**
     * 编译 sql
     *
     * @param bool $auto 提交查询
     * @return db|string
     */
    public function _compile_select($auto = true)
    {
        $sql = ( !$this->_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

        $sql .= (count($this->_select) == 0) ? '*' : implode(', ', $this->_select);
        $sql .= ' FROM ';

        $sql .= self::$db->table($this->_tableName);
        //$sql .= ' FROM `';
        //$sql .= $this->_tableName;
        //$sql .= '` ';
        $sql .= ' ';

        if ( !empty($this->_index) ){
            $sql .= 'FORCE INDEX('.$this->_index.') ';
        }
        if (count($this->_join) > 0) {
            $sql .= implode(' ', $this->_join);
            $sql .= ' ';
        }
        if (count($this->_where) > 0 OR count($this->_like) > 0 OR count($this->_instr) > 0)
        {
            $sql .= 'WHERE ';
        }
        $sql .= implode(' ', $this->_where);
        if (count($this->_like) > 0)
        {
            if (count($this->_where) > 0)
            {
                $sql .= ' AND ';
            }
            $sql .= implode(' ', $this->_like);
        }
        if (count($this->_instr) > 0)
        {
            if (count($this->_where) > 0 OR count($this->_like) > 0)
            {
                $sql .= ' AND ';
            }
            $sql .= implode(' ', $this->_instr);
        }
        if (count($this->_group) > 0)
        {
            $sql .= ' GROUP BY ';
            $sql .= implode(', ', $this->_group);
        }
        if (count($this->_having) > 0)
        {
            $sql .= ' HAVING ';
            $sql .= implode(', ', $this->_having);
        }
        if (count($this->_order) > 0)
        {
            $sql .= ' ORDER BY ';
            $sql .= implode(', ', $this->_order);

        }
        if (is_numeric($this->_limit))
        {
            $sql .= self::$db->limit($this->_limit, $this->_offset);
        }
        $this->_reset_select();
        if ( $auto )
        {
            return self::$db->query($sql);
        }

        return $sql;
    }

    /**
     * 表名
     *
     * @param string $table 表名
     * @return Model
     */
    public function from($table)
    {
        $this->_tableName = $table;
        return $this;
    }

    /**
     * 插入 返回影响行数
     *
     * @param array $data
     * @return db->affected_rows()
     */
    public function insert($data)
    {
        $sql =  self::$db->insert($this->_tableName, array_merge($data, $this->_autoData ));
        $this->_reset_write();
        return self::$db->query($sql)->affected_rows();
    }

    /**
     * 插入 反回插入id
     * @param array $data
     * @return db->insert_id()
     */
    public function insertid($data)
    {

        $sql =  self::$db->insert($this->_tableName, array_merge($data, $this->_autoData ));

        $this->_reset_write();
        return self::$db->query($sql)->insert_id();
    }

    /**
     * 批量插入
     *
     * @param array $data
     * @return db
     */
    public function inserts($data)
    {
        $sql = self::$db->inserts($this->_tableName, $data);
        return self::$db->query($sql)->affected_rows();
    }

    /**
     * 插入过虑模式
     * @param array $data
     * @return db->insert_id()
     * @author andsky 669811@qq.com
     */
    public function insert_filter($data)
    {
        $sql = self::$db->insert($this->_tableName, array_merge(array_intersect_key($data, $this->_fields), $this->_autoData ));
        $this->_reset_write();
        return self::$db->query($sql)->insert_id();
    }

    /**
     * 覆盖插入
     *
     * @param array $data
     * @return int
     */
    public function replace($data)
    {
        $sql =  self::$db->replace($this->_tableName, array_merge( $data,$this->_autoData ));
        $this->_reset_write();
        return self::$db->query($sql)->affected_rows();
    }

    /**
     * 更新
     *
     * @param array $data 数据
     * @return int
     */
    public function update($data)
    {
        //$this->where($where);
        $sql = self::$db->update($this->_tableName, $data, $this->_where);
        $this->_reset_write();
        return self::$db->query($sql)->affected_rows();
    }

    public function increment($data)
    {
        //$this->where($where);
        $sql = self::$db->increment($this->_tableName, $data, $this->_where);
        $this->_reset_write();
        return self::$db->query($sql)->affected_rows();
    }



    /**
     * 删除
     *
     * @return int
     */
    public function delete()
    {
        //$this->where($where);
        $sql = self::$db->delete($this->_tableName, $this->_where);
        $this->_reset_write();
        return self::$db->query($sql)->affected_rows();
    }


    /**
     * 自动调用
     *
     * @param string $name 调用方法
     * @param mixed $arguments 调用参数
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $object = $this->_compile_select();
        /*$reflObject = new ReflectionObject($object);
        $reflMethod = $reflObject->getMethod($name);
        return  $reflMethod->invokeArgs($object, $arguments);*/
        return call_user_func_array(array($object, $name), $arguments);
    }

    /**
     * 重置属性
     *
     * @param mixed $vars
     */
    public function _reset_run($vars)
    {
        foreach ($vars as $item => $default_value)
        {
            $this->$item = $default_value;
        }
    }


    public function _reset_select()
    {
        $vars = array(
			'_select' => array(),
			'_index' => false,
			'_join' => array(),
			'_where' => array(),
			'_like' => array(),
			'_instr' => array(),
			'_group' => array(),
			'_having' => array(),
			'_order' => array(),
			'_distinct' => false,
			'_limit' => false,
			'_offset' => false,
        );

        $this->_reset_run($vars);
    }

    public function _reset_write()
    {
        $this->_where = array();
        $this->_autoData = array();
    }
    /**
     * 名称 生成 key
     * @return string
     * @author andsky, <669811 at qq dot com>
     * @since 1.0
     */
    public function key($keyword, $id)
    {
        if ( !is_array($id) ){
            return $this->_tableName.'_'.$keyword.'_'.$id;
        }else{
            $tmp = array();
            foreach($id as $k=>$v){
                $tmp[] = $this->_tableName.'_'.$keyword.'_'.$v;
            }
            return $tmp;
        }
    }


    /**
     * 开始事物
     *
     */
    public function startTrans()
    {
        $this->commit();
        self::$db->startTrans();
        return;
    }

    /**
     * 提交事物
     *
     */
    public function commit()
    {
        return self::$db->commit();
    }

    /**
     * 回滚事物
     *
     */
    public function rollback()
    {
        return self::$db->rollback();
    }

}