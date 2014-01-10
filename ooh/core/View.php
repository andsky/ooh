<?php
/**
 *
 * view class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class View{
    private static $_instance;
    protected $caching_id = null;
    private $_out_put = 'html';
    private $_out_charset = 'utf-8';
    private static $_engin = null;
    private $_display_data = array();

    public function __construct()
    {

    }

    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function init()
    {
        if (self::$_engin != null) {
            return self::$_engin;
        }
        //$c =  &Config::instance()->view;
        $this->_out_put = Config::instance()->view['output'];
        //self::$_engin = Base::instance()->modules($c['driver'])->init();
        self::$_engin = Base::instance()->plugin(Config::instance()->view['driver']);
        $vars = get_object_vars(self::$_engin);
        foreach ( $vars as $k => $v )
        {
            if ( !empty( Config::instance()->view[$k] ) )
            {
                self::$_engin->$k = Config::instance()->view[$k];
            }
        }
        return self::$_engin;
    }


    public function __get($name)
    {

        if ($name == '_view') {

            return $this->init();
        }

    }

    public function __set($name, $value)
    {
        if ($name == '_view') {
            return $this->init()->$name = $value;
        }
    }

    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    /**
     * 输出方式
     * @param string $type
     * @author andsky 669811@qq.com
     */
    public function output($type='html')
    {
        $this->_out_put = $type;
    }

    /**
     * json
     * @param unknown_type $data
     * @author andsky 669811@qq.com
     */
    public function display_json($data)
    {
        header("Content-type: application/json");
        echo json_encode($data);
        exit;
    }

    /**
     * xml
     * @param unknown_type $data
     * @author andsky 669811@qq.com
     */
    public function display_xml($data)
    {
        header("Content-type: text/xml; charset=utf-8");
        echo $data;
        exit;
    }

    /**
     * html
     * @param unknown_type $data
     * @author andsky 669811@qq.com
     */
    public function display_html($data)
    {
        header("Content-type: text/html; charset=utf-8");
        echo $data;
        exit;
    }

    /**
     * 显示
     * @param string $tpl
     * @author andsky 669811@qq.com
     */
    public function display($tpl = '')
    {
        $this->set('_totalTime',number_format( ( $GLOBALS['_endTime'] - $GLOBALS['_startTime'] ), 3));
        $tpl = $this->tpl($tpl);

        $display_fun = 'display_'.$this->_out_put;

        if ( is_file( Config::instance()->view['template_dir'].'/'.$tpl ) )
        {
            //Fso::mkdir($this->_view->compile_dir);
            //Fso::mkdir($this->_view->cache_dir);
            foreach ($this->_display_data as $key => $value) {
                $this->assign($key, $value);
            }
            $results = $this->_view->fetch($tpl,$this->caching_id);
            $this->$display_fun($results);
        }
        $this->$display_fun($this->_display_data);
    }

    /**
     * 获取内容
     * @param string $tpl
     * @param string $cache_id
     * @author andsky 669811@qq.com
     */
    public function fetch($tpl = '', $cache_id = null)
    {
        $tpl = $this->tpl($tpl);
        return $this->_view->fetch($tpl, $cache_id);
    }

    /**
     * 缓存判断
     * @param string $caching_id
     * @param string $tpl
     * @author andsky 669811@qq.com
     */
    public function isCache($caching_id, $tpl = '')
    {
        $this->caching_id = $caching_id;
        $tpl = $this->tpl($tpl);
        $this->_view->caching = 2; //
        if ($this->_view->is_cached($tpl,$caching_id)){
            $this->_view->display($tpl,$caching_id);
            exit;
        }
    }

    /**
     * 注册函数
     * @param array $fun
     * @author andsky 669811@qq.com
     */
    public function regFun(array $fun)
    {
        foreach ( $fun as $v )
        {
            $this->_view->register_function($v,$v);
        }
    }

    /**
     * 取得模版文件名
     * @param unknown_type $tpl
     * @author andsky 669811@qq.com
     */
    private function tpl($tpl = '')
    {
        return $tpl.Config::instance()->view['ext'];
    }

    /**
     * 模版赋值
     * @param string $name
     * @param string $value
     * @author andsky 669811@qq.com
     */
    public function set($name, $value)
    {
        $this->_display_data[$name] = $value;
    }
    /**
     * 模版赋值
     * @param array $data
     * @author andsky 669811@qq.com
     */
    public function put($data)
    {
        if ( !is_array( $data ) )
        {
            return false;
        }
        $this->_display_data += $data;
    }
}