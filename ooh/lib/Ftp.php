<?php
/**
 *
 * ftp class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Ftp
{
    private static $_instance;
    private $host   = '';
    private $username   = '';
    private $password   = '';
    private $port       = 21;
    public $passive = TRUE;
    private $timeout = 90;

    private $_stream;



    function __construct ()
    {
    }

    public static function instance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    function __destruct ()
    {
        $this->close();
    }

    public function set($var)
    {

        foreach ( $var as $k => $v )
        {
            if ( isset( $this->$k ) )
            {
                $this->$k = $v;
            }
        }
        return $this;
    }

    public function connect()
    {
        if(!$this->_stream = ftp_connect($this->host, $this->port, $this->timeout))
        {
        	throw new Exceptions("Failed to connect to {$this->host}");
        }
        if(!ftp_login($this->_stream, $this->username, $this->password)) {
			throw new Exceptions("Failed to connect to {$this->host} (login failed)");
		}
		if ($this->passive) {
		    ftp_pasv($this->_stream, (bool)$this->passive);
		}
		$this->system_type = ftp_systype($this->_stream);
		return $this;
    }

    public function close()
    {
		if($this->_stream) {
			ftp_close($this->_stream);
			$this->_stream = FALSE;
		}
	}

    public function get($remote_file = null, $local_file = null, $mode = FTP_ASCII)
    {
		if(ftp_get($this->_stream, $local_file, $remote_file, $mode)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
    public function put($local_file = null, $remote_file = null, $mode = FTP_ASCII)
    {
		if(ftp_put($this->_stream, $remote_file, $local_file, $mode)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function rename($old_name = null, $new_name = null)
    {
		if(ftp_rename($this->_stream, $old_name, $new_name)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function delete($remote_file = null) {
		if(ftp_delete($this->_stream, $remote_file)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function cd($directory = null)
    {
		if(ftp_chdir($this->_stream, $directory)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function pwd()
    {
    	return ftp_pwd($this->_stream);
    }

    public function ls($directory = null)
    {
		$list = array();
		if($list = ftp_nlist($this->_stream, $directory)) {
			return $list;
		} else {
			return array();
		}
	}

    public function mkdir($directory = null)
    {
		if(@ftp_mkdir($this->_stream, $directory)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function rmdir($directory = null)
    {
		if(@ftp_rmdir($this->_stream, $directory)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function chmod($remote_file, $mode=0755)
	{
	    if (ftp_chmod($this->_stream, $mode, $remote_file)) {
	        return TRUE;
	    }else {
			return FALSE;
		}
	}


}