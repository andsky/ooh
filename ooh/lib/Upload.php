<?php
/**
 *
 * upload class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Upload {
    private $upload_path = 'upload';
    private $max_size = '';
    private $exts = array('png','jpg','jpeg','gif','rar','zip'); //文件后缀;
    private static $_instance;

    private $error = '';

    function __construct()
    {
        $this->max_size = intval(get_cfg_var("upload_max_filesize")) * 1024;
    }

    public function set(array $array)
    {
        if ( !empty( $array ) )
        {
            foreach ( $array as $k => $v )
            {
                $this->$k = $v;
            }
        }
        return $this;
    }

    static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 多文件上传
     *
     * @author andsky 669811@qq.com
     */
    public function multi()
    {
        ;
    }

    /**
     * 单文件上传
     * @param unknown_type $field
     * @author andsky 669811@qq.com
     */
    public function upload($field = 'file')
    {

        $file_info = $_FILES[$field];
        if (empty($file_info)) {
            $this->error(8);
            return FALSE;
        }

        //先检测能否被上传
        if ( !is_uploaded_file( $file_info['tmp_name'] ) )
        {
            $this->error($file_info['error']);
            return FALSE;
        }

        //读取文件信息
        $this->upload_file_info = array();
        $this->upload_file_info['file_temp'] = $file_info['tmp_name'];
        $this->upload_file_info['file_ext']	 = $this->get_extension($file_info['name']);
        $this->upload_file_info['file_name'] = $file_info['name'];
        $this->upload_file_info['save_name'] = $this->file_save_name();
        $this->upload_file_info['full_name'] = $this->upload_path.'/'.$this->upload_file_info['save_name'];
        $this->upload_file_info['file_size'] = $file_info['size'];
        $this->upload_file_info['file_type'] = strtolower(preg_replace("/^(.+?);.*$/", "\\1", $file_info['type']));
        if ($this->upload_file_info['file_size'] > 0)
        {
            $this->upload_file_info['file_size'] = round($this->upload_file_info['file_size']/1024, 2);
        }
        if ( $this->max_size < $this->upload_file_info['file_size'] )
        {
            $this->error = '上传文件过大!';
            return FALSE;
        }
        //检查文件
        if ( !$this->check_allowed_ext() )
        {
            $this->error = '此文件不允许上传!';
            return FALSE;
        }

        //检查目录
        if ( !$this->check_upload_path() )
        {
            return FALSE;
        }

        //开始上传
        if ( ! @copy($this->upload_file_info['file_temp'], $this->upload_path.$this->upload_file_info['save_name']))
        {
            if ( ! @move_uploaded_file($this->upload_file_info['file_temp'], $this->upload_path.$this->upload_file_info['save_name']))
            {
                $this->error = '文件上传失败!';
                return FALSE;
            }
        }

        return $this->upload_file_info;
    }

    public function check_upload_path()
    {

        //$upload_path =  str_replace("\\", "/", realpath($upload_path));
        $this->upload_path = preg_replace("/\/+/", "/", $this->upload_path);

        Fso::instance()->mkdir( $this->upload_path );
        if ( !is_writable( $this->upload_path ) )
        {
            $this->error = '上传目录不可写';
            return FALSE;
        }
        $this->upload_path = preg_replace("/(.+?)\/*$/", "\\1/",  $this->upload_path);
        return TRUE;
    }

    public function check_allowed_ext()
    {
        if ( in_array($this->upload_file_info['file_ext'], $this->exts) )
        {
            return TRUE;
        }
        return FALSE;
    }

    function get_extension($filename)
    {
        $x = explode('.', $filename);
        return strtolower(end($x));
    }

    public function file_save_name()
    {
        return substr(md5(base64_encode($this->upload_file_info['file_name']).time()),10,10).'.'.$this->upload_file_info['file_ext'];
    }

    protected function error($errorNo)
    {
        switch($errorNo) {
            case 1:
                $this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
                break;
            case 2:
                $this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
                break;
            case 3:
                $this->error = '文件只有部分被上传';
                break;
            case 4:
                $this->error = '没有文件被上传';
                break;
            case 6:
                $this->error = '找不到临时文件夹';
                break;
            case 7:
                $this->error = '文件写入失败';
                break;
            case 8;
            $this->error = '没有定义上传表单';
            default:
                $this->error = '未知上传错误！';
        }
        return ;
    }

    function errors() {
        return $this->error;
    }

    public function change_ext($ext)
    {
        $this->exts = $ext; //文件后缀;
    }
}