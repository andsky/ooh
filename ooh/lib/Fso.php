<?php
/**
 *
 * fso class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */

class Fso
{
    private static $_instance;


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

    /**
     *
     * @return boolean
     * @access public
     */
    public function isDir($dir)
    {
        return is_dir($dir);
    }
    /**
     *
     * @return boolean
     * @since 1.0
     */
    public function isFile($file)
    {
        return is_file($file);
    }
    /**
     *
     * @return boolean
     * @since 1.0
     */
    public function exists($file)
    {
        return file_exists($file);
    }
    /**
     *
     * @return boolean
     * @since 1.0
     */
    function copyfile($source, $dest)
    {
        return @copy($source, $dest);
    }
    /**
     *
     * @return string
     * @since 1.0
     */
    public function setpath($path)
    {
        //return realpath($path).'/';
        //return preg_replace("/[\\/\\\\]+/", '/', $path);
        //$path = preg_replace('#/$#','', $path);
        $path = preg_replace("/[\\/\\\\]+/", DIRECTORY_SEPARATOR, $path);
        if (function_exists('realpath') AND @realpath($path) !== false)
        {
            $path = realpath($path).DIRECTORY_SEPARATOR;
        }
        //$path = preg_replace("#([^/])/*$#", DIRECTORY_SEPARATOR, $path);
        $path = preg_replace("/[\\/\\\\]+/", DIRECTORY_SEPARATOR, $path);
        return $path;
    }



    public function fileList($path, $subdir = FALSE, $_ext = NULL)
    {
        $path =  $this->setpath($path);

        if (!$this->isDir($path)){
            return NULL;
        }
        if ( $_ext == NULL ){
            $files = scandir($path);
        }else{
            $files = glob($path.$_ext, GLOB_BRACE);
        }

        $list = array();
        $found= array();
        foreach ($files as $file) {
            $file = basename($file);
            if ($file != "." && $file != "..") {

                $setpath = $path.$file;
                array_push($list, $setpath);
                $found[] = $setpath;

                if ( $subdir && self::isdir($setpath) )
                {
                    $subdirfiles = $this->fileList($setpath,$subdir,$_ext);
                    foreach ($subdirfiles as $subdirfile) {
                        array_push($list, $subdirfile);
                    }
                }

            }
        }

        //
        if ($_ext != NULL && $subdir) {
            $files = scandir($path);
            foreach ($files as $file) {
                $file = basename($file);
                if ( $file != "." && $file != ".." )
                {
                    $setpath = $path.$file;
                    if (!in_array($setpath, $found) ) {
                        if ($subdir && $this->isDir($setpath)) {
                            $subdirfiles = $this->fileList($setpath,$subdir,$_ext);
                            foreach ($subdirfiles as $subdirfile) {
                                array_push($list, $subdirfile);
                            }
                        }
                    }

                }

            }
        }

        return $list;

    }
    /**
     *
     * @return boolean
     */
    public function mkdir($dir, $mode = 0777)
    {
        if (!$this->exists($dir)) {
            return mkdir($dir, $mode, TRUE);
        }
    }
    /**
     *
     * @return string
     * @since 1.0
     */
    public function read($file)
    {
        if ( !$this->exists($file) ){
            return FALSE;
        }
        if (function_exists('file_get_contents'))
        {
            return file_get_contents($file);
        } else {
            if (!$fp = @fopen($file, 'rb')){
                return FALSE;
            }

            flock($fp, LOCK_SH);
            $data = @fread($fp, filesize($file));
            flock($fp, LOCK_UN);
            fclose($fp);

            return $data;
        }
    }

    /**
     *
     * @return boolean
     * @since 1.0
     */
    public function write($file, $data, $mode = "w+")
    {
        if ( !$fp = @fopen($file, $mode)){
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
        return FALSE;
    }

    public function append($file,$data)
    {
        return $this->write($file,$data,"a+");
    }
    /**
     *
     * @return boolean
     * @since 1.0
     */
    public function delete($file)
    {
        if ($this->isFile($file)){
            return @unlink( $file );
        }elseif ($this->isDir($file)){
            $files = $this->fileList($file);
            foreach ( $files as $vv )
            {
                $this->delete($file);
            }
            return @rmdir($file);
        }
        return FALSE;
    }


    function __destruct ()
    {
    }
}