<?php
/**
 *
 * validate class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Validate {
    private static $_instance;
    public  $_error = array();
    protected static $regex = array(
            'null'   => '/.+/',
    		'number' => '/^[-\+]?\d+$/',
            'email'  => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'phone'  => '/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/',
            'mobile' => '/^(0|86|17951)?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/',
            'idcard' => '/(^\d{15}$)|(^\d{17}[0-9Xx]$)/',
            'money'  => '/^\d+(\.\d+)?$/',
    		'zip'    => '/^[1-9]\d{5}$/',
    		'qq'     => '/^[1-9]\d{4,12}$/',
    		'int'    => '/^\d+$/',
            'english'=> '/^[A-Za-z]+$/',
		    //'chinese'=> '/^[\u0391-\uFFE5]+$/',
            'chinese'=> '/^[\x{4e00}-\x{9fa5}]+$/u',
            'username' => '/^[0-9a-zA-Z]+$/',
            //'url'    => '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!#\w]*)?$/',
            //'url'    => '/^http[s]?:\/\/(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*\'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\[a-z]{2,6})(:[0-9]{1,5})?((\/\?)|(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/',
            'url'    => '/^http[s]?:\/\/([\w-]+\.)+[\w-]+(:[\d]+)?([\w-./?%&=]*)?$/',
            'date'   => '/^\d{4}-\d{1,2}-\d{1,2}$/',
		    'time'   => '/^\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}:\d{1,2}$/'
    );


    /**
     * Validate instance
     * @return Validate instance
     */
    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __construct()
    {
    }

    public function __set($name, $value){
        self::$regex[$name] = $value;
    }


    public function __get($name)
    {
        if(isset(self::$regex[$name])) {
            return self::$regex[$name];
        }else {
            return $name;
        }
    }

    public function single($check, $value)
    {
        return preg_match(self::$regex[$check], trim($value));
    }

    public function check($data, $model)
    {
        foreach ($data as $name => $value) {
            if (isset($model[$name])) {
                if (!preg_match(self::$regex[$model[$name]], $value)) {
                    $this->_error[] = $name;
                }
            }
        }
        return empty($this->_error) ? TRUE : JS::valid(implode('|', $this->_error));
    }


}