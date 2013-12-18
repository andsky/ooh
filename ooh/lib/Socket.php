<?php
/**
 *
 * socket class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Socket
{

    private $_connection;


    function __construct ($server, $port, $timeout = 10, $p = TRUE)
    {
        $this->connect($server, $port, $timeout, $p);
    }


    function __destruct ()
    {
    }

    /**
     * 连接
     * @param unknown_type $server
     * @param unknown_type $port
     * @param unknown_type $timeout
     * @param unknown_type $p
     * @author andsky 669811@qq.com
     */
    public function connect($server, $port, $timeout = 10, $p = false)
    {
        if ($p) {
            $this->_connection = @pfsockopen($server,$port, $this->errorNo, $errorMessage, $timeout);
        }else {
            $this->_connection = @fsockopen($server,$port, $this->errorNo, $errorMessage, $timeout);
        }

        if (!$this->_connection) {
            throwException(sprintf('%s, %s', $this->errorNo, $errorMessage));
        }
    }

    /**
     * 关闭
     *
     * @author andsky 669811@qq.com
     */
    public function disconnect()
    {
        if ($this->_connection) {
            @fclose($this->_connection);
            $this->_connection = null;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 读取
     * @param int $length
     * @author andsky 669811@qq.com
     */
    public function read($length)
    {
        if ($this->_connection === false) {
            throwException('socket not connected');
        }

        if (@feof($this->_connection))
        {
            throwException('socket read eof error');
        }

        $ret = '';
        $read = 0;

        while ($read < $length && ($buf = fread($this->_connection, $length - $read))) {
            $read += strlen($buf);
            $ret .= $buf;
        }
        if ($ret === false) {
            throwException('socket read error');
        }
        return $ret;

    }

    /**
     * 写入
     * @param string $data
     * @author andsky 669811@qq.com
     */
    public function write($data)
    {
        $total = 0;
        $len = strlen($data);

        while ($total < $len && ($written = fwrite($this->_connection, $data))) {
            $total += $written;
            $buf = substr($data, $written);
        }
        return $total;
    }

    public function readInt1()
    {
        $result = '';
        $res = $this->read(1);
        $res = unpack('C', $res);
        return $res[1];
    }

    public function readInt4()
    {
        $result = '';
        $res = $this->read(4);
        $res = unpack('L', $res);
        return $res[1];
    }

    public function readInt8()
    {
        $result = '';
        $res = $this->read(8);
        $res = unpack('N*', $res);
        return array($res[1], $res[2]);
    }

    public function readString(){

		$string = '';
		while($buf = fgetc($this->_connection) != "\0"){
			$string .= $buf;
		}
		return $string;
	}

   public function readAll(){
		$data = '';
		while (!feof($this->_connection)) {
            $data .= fgets($this->_connection, 128);
        }
		return $data;
	}
}