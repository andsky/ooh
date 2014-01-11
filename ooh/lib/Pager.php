<?php
/**
 *
 * pager class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Pager
{
    private static $_instance;
    public $keyword    = 'page';
    public $first_page = '首页';
    public $last_page  = '尾页';
    public $pre_page   = '上一页';
    public $next_page  = '下一页';

    public $base_link   = '';

    public $page_num;

    /**
     * 每页显示条数
     * @var integer
     * @access private
     */
    public $size;

    /**
     * 总条数
     * @var integer
     * @access private
     */
    public $total;

    /**
     * 分页样式
     * @var integer
     * @access private
     */
    public $style = '';

    public $ajax = '';

    public static function instance($driver = NULL)
    {
        if (self::$_instance == NULL) {
            if (empty($driver)) {
                $driver = Config::instance()->pager['driver'];
            }
            self::$_instance = new $driver;
        }
        return self::$_instance;
    }


    function __construct()
    {
    }

    function __destruct()
    {
    }

    public function set($var)
    {
        $vars = get_object_vars($this);
        foreach ( $vars as $k => $v )
        {
            if ( !empty( $var[$k] ) )
            {
                if ( is_array( $var[$k] ) )
                {
                    $this->$k = $var[$k]['count'];
                }else if ( is_object( $var[$k] ) )
                {
                    $info = $var[$k]->count()->fetch_one();
                    $this->$k = $info['count'];
                }else
                {
                    $this->$k = $var[$k];
                }

            }
        }
        $this->page_num = $this->_page_num();
        return $this;
    }
    /**
     * 名称 取得当前页号
     * @return void
     * @since 1.0
     */
    public function current()
    {
        $page = intval(Request::instance()->get($this->keyword));
        if ($page <= 1) {
            $page = 1;
        }
        return min($page, $this->page_num);
    }
    /**
     * 名称 取得上一页号
     * @return void
     * @since 1.0
     */
    public function pre()
    {
        $current = $this->current();
        return $current < $this->page_num ? ($current - 1) : $this->page_num;
    }
    /**
     * 名称 取得下一页号
     * @return void
     * @since 1.0
     */
    public function next()
    {
        $current = $this->current();
        return $current < $this->page_num ? ($current + 1) : $this->page_num;
    }

    /**
     * 取得记录开始的偏移量
     *
     * @return void
     */
    function offset()
    {
        $offset = $this->size * ($this->current() - 1);
        if ($offset < 0) {
            $offset = 0;
        }
        return $offset;
    }

    function size()
    {
        return $this->size;
    }

    public function get_page_num()
    {
        return $this->page_num;
    }


    public function _page_num()
    {

        return ceil($this->total/$this->size);
    }

    public function url($page)
    {

        if (empty($this->base_link)) {
            $this->base_link = preg_replace('/(\?|&)page=([^&]+|)/is', '', $_SERVER['REQUEST_URI']);
        }
        if (stristr($this->base_link, $this->keyword)) {
            return $this->base_link.$page;
        }

        return $this->base_link.((strpos($this->base_link,'?')===FALSE)?'?':'&').$this->keyword.'='.$page;
    }

    public function get_link($page, $location='')
    {
        if($this->ajax){
            //如果是使用AJAX模式
            return 'javascript:'.$this->ajax."('".$this->url('')."'+".$page.");";
        }else if ( $location )
        {
            return "window.location='".$this->url('')."'+".$page.";return false;";
        }else{
            return $this->url($page);
        }
    }
}


class pager_pager extends Pager {
    function __toString()
    {
        $page = array();

        $page["begin"] = "<form action=\"\" onsubmit=\"".$this->get_link('this.pageno.value','win')."\">";
        $page["input"] = "输入页数 <input size=\"3\" type=\"text\" size=\"2\" name=\"pageno\" onblur=\"".$this->get_link('this.value','win')."\"/>  ";
        $page["no"] = "第 ".$this->current()." 页, 共 {$this->page_num} 页 |  ";
        $page["first"] = ($this->page_num > 1) ? "<a href=\"".$this->get_link(1)."\" target=\"_self\">首页</a>  ":"首页  ";
        $page["pre"] = ($this->current() > 1)?"| <a href=\"" . $this->get_link($this->current() - 1) . "\" target=\"_self\">上一页</a> ":"| 上一页 ";
        $page["next"] = ($this->current() <= $this->page_num - 1)?"| <a href=\"" . $this->get_link($this->current() + 1) . "\" target=\"_self\">下一页</a> ":"| 下一页 ";
        $page["last"] = ($this->page_num > 1)?"| <a href=\"".$this->get_link($this->page_num)."\" target=\"_self\">尾页</a>":"| 尾页";
        $page["end"] = "</form>";

        //构造页码显示
        $string = implode("", $page);

        return $string;
    }
}