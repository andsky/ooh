<?php
/**
 *
 * phplite class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Phplite {

    public $template_dir = 'templates';
    public $cache_dir	 = "cached";
    private $_vars= array();
    private $_plugins		= array(
                                    'modifier'	  => array(),
                                    'function'	  => array(),
                                    'block'		 => array(),
                                    'compiler'	  => array(),
                                    'resource'	  => array(),
                                    'prefilter'	 => array(),
                                    'postfilter'	=> array(),
                                    'outputfilter'  => array());
    function __construct()
    {
        ;
    }

    public function assign($key, $value = NULL)
    {
        if (is_array($key))
        {
            foreach($key as $var => $val)
                if ($var != '')
                {
                    $this->_vars[$var] = $val;
                }
        }
        else
        {
            if ($key != '')
            {
                $this->_vars[$key] = $value;
            }
        }
    }

    function display($file, $cache_id = null)
    {
        $this->fetch($file, $cache_id, true);
    }

    public function fetch($file, $cache_id = NULL, $display = false)
    {
        extract($this->_vars, EXTR_SKIP);
        define('TEMPLATE_DIR', $this->_get_dir($this->template_dir));
        $tmplate_file = $this->_get_resource($file);
        ob_start();
        require($tmplate_file);
        $output = ob_get_contents();
        ob_end_clean();
        foreach ($this->_plugins['outputfilter'] as $function)
        {
            $output = $function($output, $this);
        }
        if ($display) {
            echo $output;
            exit;
        }
        return $output;
    }

    public function register_function($function, $implementation)
    {
        ;
    }

    public function register_block($function, $implementation)
    {
        ;
    }

    function register_outputfilter($function)
    {

    }

    private function _get_resource($file)
    {
        $file = TEMPLATE_DIR.$file;
        if (!file_exists($file)) {
            throw new Exceptions("file '$file' does not exist");
        }
        return $file;
    }

    private function _get_dir($dir)
    {
        if (empty($dir)) {
            return '.';
        }
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        return $dir.DIRECTORY_SEPARATOR;
    }
}


?>