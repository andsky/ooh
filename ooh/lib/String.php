<?php
/**
 *
 * string class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class String
{

    private static $_instance;


    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }



    function __construct ()
    {
    }


    function __destruct ()
    {
    }

    /**
     * function strToHex($string)
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++)
        {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
     */

    /**
     * 字符转 16 进
     * @param string $string
     * @author andsky 669811@qq.com
     */
    public function str2hex($string) {
        $hexstr = unpack('H*', $string);
        return array_shift($hexstr);
    }

    /**
     * 16进转字符
     * @param string $hexstr
     * @author andsky 669811@qq.com
     */
    public function hex2str($hexstr) {
        $hexstr = str_replace(' ', '', $hexstr);
        $hexstr = str_replace('\x', '', $hexstr);
        $retstr = pack('H*', $hexstr);
        return $retstr;
    }
    /**
     * 二进制转16
     * @param unknown_type $string
     * @author andsky 669811@qq.com
     */
    public function bin2hex($string)
    {
        return bin2hex($string);
    }


    /**
     * 计算长度
     * @param unknown_type $str
     * @param unknown_type $encoding
     * @author andsky 669811@qq.com
     */
    public function strlen($str, $encoding = 'UTF-8')
    {
        return mb_strlen($str, $encoding);
    }

    /**
     * 截取
     * @param unknown_type $str
     * @param unknown_type $len
     * @param unknown_type $start
     * @param unknown_type $end
     * @param unknown_type $encoding
     * @author andsky 669811@qq.com
     */
    public function substr($str, $len, $start = 0, $end = '...', $encoding = 'UTF-8')
	{
		if ( $len >= $this->strlen($str, $encoding) ){
			return $str;
		}else{
			return mb_substr($str, $start, $len, $encoding).$end;
		}
	}

	/**
	 * 转成对像
	 * @param unknown_type $obj
	 * @author andsky 669811@qq.com
	 */
    public function stringval($obj)
	{
		return strval($obj);
	}

	/**
	 * 转成小写
	 * @param unknown_type $str
	 * @param unknown_type $encoding
	 * @author andsky 669811@qq.com
	 */
	public function tolower($str, $encoding = 'UTF-8')
	{
		return mb_strtolower($str, $encoding);
	}


	/**
	 * 转成大写
	 * @param unknown_type $str
	 * @param unknown_type $encoding
	 * @author andsky 669811@qq.com
	 */
	public function toupper($str, $encoding = 'UTF-8')
	{
		return mb_strtoupper($str, $encoding);
	}

	/**
    * @version $Id: str_split.php 10381 2008-06-01 03:35:53Z pasamio $
    *
    */
	public function split($str, $split_len = 1)
	{
	    // preg_match_all('/.{'.$len.'}|[^\x00]{1,'.$len.'}$/us', $str, $ar);
	    if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)
	        return FALSE;
	    $len = $this->strlen($str);
	    if ($len <= $split_len)
	        return array($str);
	    preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
	    return $ar[0];
	}

	function ord($str)
	{
       switch(strlen($str)) {
         case 1:
           return ord($str);
         case 2:
           $n = (ord($str[0]) & 0x3f) << 6;
           $n += ord($str[1]) & 0x3f;
           return $n;
         case 3:
           $n = (ord($str[0]) & 0x1f) << 12;
           $n += (ord($str[1]) & 0x3f) << 6;
           $n += ord($str[2]) & 0x3f;
           return $n;
         case 4:
           $n = (ord($str[0]) & 0x0f) << 18;
           $n += (ord($str[1]) & 0x3f) << 12;
           $n += (ord($str[2]) & 0x3f) << 6;
           $n += ord($str[3]) & 0x3f;
           return $n;
       }
    }

	/**
	 * 生成随机
	 * @param unknown_type $len
	 * @param unknown_type $type
	 * @param unknown_type $designated
	 * @author andsky 669811@qq.com
	 */
    public function radom($len, $type = 'all', $designated = '1234') {
		switch ($type) {
			case 'num' :
				$str = '0123456789';
				break;
			case 'low' :
				$str = 'abcdefghijklmnopqrstuvwxyz';
				break;
			case 'cap' :
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case 'char' :
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case 'user' :
				$str = $designated;
				break;
			default :
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
		}
		$str = str_repeat($str, 5);
		return substr(str_shuffle($str), 0, $len);
	}


	/**
	 * 生成 id
	 * @return int
	 * @author andsky 669811@qq.com
	 */
	public function gen_id()
	{
	    return sprintf('%u', crc32(date('Ymd').sprintf('%06d', hexdec(substr(uniqid(),8,13))).sprintf('%01d',rand(0,9))));
	}

	/**
	 * 字符串转id
	 * @param string $str
	 * @return int
	 * @author andsky 669811@qq.com
	 */
	public function str2id($str)
	{
	    if (empty($str)) {
	        return 0;
	    }
	    return sprintf("%u", crc32($str));
	}


}