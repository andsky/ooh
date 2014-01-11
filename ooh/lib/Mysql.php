<?php
/**
 *
 * mysql class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Mysql extends Db
{
    protected function _connect($conf)
    {
        $this->conn_id = ($conf['pconnect'] == FALSE) ? $this->db_connect($conf) : $this->db_pconnect($conf);

        if ( !$this->conn_id )
        {
            throw new Http503Exceptions('Can not connect to MySQL server:'.$conf['host']);
        }

        if ( !$this->db_set_charset($conf['charset']) )
        {
            throw new Http503Exceptions('Unable to set database connection charset:'.$conf['charset']);
        }

        if ( !$this->db_select(Config::instance()->db['dbname']) )
        {
            throw new Http503Exceptions('Cannot use database:'.$conf['dbname']);
        }
    }
    protected function db_connect($conf)
    {
        return mysql_connect($conf['host'], $conf['username'], $conf['password'], TRUE);
    }


    protected function db_pconnect($conf)
    {
        return mysql_pconnect($conf['host'], $conf['username'], $conf['password']);
    }

    protected function db_select($dbname)
    {
        return mysql_select_db($dbname, $this->conn_id);
    }

    protected function db_set_charset($charset)
    {
        return mysql_query("SET character_set_connection=".$charset.", character_set_results=".$charset.", character_set_client=".$charset."", $this->conn_id);
    }

    protected function _version()
    {
        return "SELECT version() AS ver";
    }

    protected function _query($sql)
    {
        //$sql = $this->_prep_query($sql);
        return mysql_query($sql, $this->conn_id);
    }

    protected function escape_str($str)
    {
        if (is_array($str))
        {
            foreach($str as $key => $val)
            {
                $str[$key] = $this->escape_str($val);
            }

            return $str;
        }

        if (function_exists('mysql_real_escape_string') AND is_resource($this->conn_id))
        {
            return mysql_real_escape_string($str, $this->conn_id);
        }
        elseif (function_exists('mysql_escape_string'))
        {
            return mysql_escape_string($str);
        }
        else
        {
            return addslashes($str);
        }
    }

    protected function _fetch_array($type)
    {
        if ($this->queryid === FALSE or $this->num_rows() == 0)
        {
            return array();
        }
        $rs = array();
        $this->_data_seek(0);
        while ($row = $this->_fetch_assoc())
        {
            $rs[] = $row;
        }
        return $rs;
    }


    protected function _fetch_one($type)
    {
        if ($this->queryid === false or $this->num_rows() == 0)
        {
            return array();
        }
        $rs = array();
        $this->_data_seek(0);
        $rs = $type == 'assoc' ? mysql_fetch_assoc($this->queryid) : mysql_fetch_object($this->queryid);

        return $rs;
    }

    protected function _num_rows()
    {
        return mysql_num_rows($this->queryid);
    }


    protected function _affected_rows()
    {
        if (!$this->queryid) {
            return FALSE;
        }
        return mysql_affected_rows($this->conn_id);
    }


    protected function _insert_id()
    {
        if (!$this->queryid) {
            return FALSE;
        }
        return mysql_insert_id($this->conn_id);
    }

    protected function _fetch_assoc()
    {
        if ($this->queryid === false or $this->num_rows() == 0)
        {
            return array();
        }
        $rs = array();
        $this->_data_seek(0);
        while ($row = mysql_fetch_assoc($this->queryid))
        {
            $rs[] = $row;
        }
        $this->_free_result();
        return $rs;
    }

    protected function _fetch_object()
    {
        if ($this->queryid === false or $this->num_rows() == 0)
        {
            return array();
        }
        $rs = array();
        $this->_data_seek(0);
        while ($row = mysql_fetch_object($this->queryid))
        {
            $rs[] = $row;
        }
        $this->_free_result();
        return $rs;
    }


    protected function _data_seek($n = 0)
    {
        return mysql_data_seek($this->queryid, $n);
    }



    protected function _insert($table, $keys, $values)
    {
        return 'INSERT INTO '.$this->_table($table).' ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
    }

    protected function _inserts($table, $keys, $values)
    {
        foreach ($values as $v) {
            $tmp[] = '('.implode(', ', $v).')';
        }
        return 'INSERT INTO '.$this->_table($table).' ('.implode(', ', $keys).') VALUES '.implode(', ', $tmp);
    }

    protected function _replace($table, $keys, $values)
    {
        return 'REPLACE INTO '.$this->_table($table).' ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
    }


    protected function _update($table, $values, $where)
    {

        return 'UPDATE '.$this->_table($table).' SET '.implode(', ',$values).' WHERE '.implode(" ", $where);
    }

    protected function _delete($table, $where)
    {

        return 'DELETE FROM  '.$this->_table($table).' WHERE '.implode(" ", $where);
    }

    protected function _limit($limit, $offset)
    {
        if ($offset == 0)
        {
            $offset = '';
        }
        else
        {
            $offset .= ', ';
        }

        return ' LIMIT '.$offset.$limit;
    }

    protected function _list_tables()
    {
        $sql = 'SHOW TABLES FROM `'.$this->dbname.'`';

        return $sql;
    }

    protected function _list_columns($table = '')
    {
        return 'SHOW COLUMNS FROM '.$this->_table($table);
    }


    protected function _truncate($table)
    {
        return 'TRUNCATE '.$this->_table($table);
    }

    protected function _table($table)
    {
        if (stristr($table, '.') === FALSE) {
            return '`' .$table. '`';
        }
        return $table;
    }

    protected function _error_message()
    {
        return mysql_error($this->conn_id);
    }


    protected function _error_number()
    {
        return mysql_errno($this->conn_id);
    }

    protected function _free_result()
    {
        return mysql_free_result($this->queryid);
    }

    protected function _startTrans()
    {
        return 'START TRANSACTION';
    }

    protected function _commit()
    {
        return 'COMMIT';
    }

    protected function _rollback()
    {
        return 'ROLLBACK';
    }



    protected function _close($conn_id)
    {
        return mysql_close($conn_id);
    }
}