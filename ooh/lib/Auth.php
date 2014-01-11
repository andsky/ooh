<?php
/**
 *
 * auth class
 *
 * @author andsky<andsky888@gmail.com>
 * @version 1.0 2013-9-10
 */
class Auth{

    private static $_instance;
    private $session_name = 'user';
    private $login_url = 'user';


    private function __construct ()
    {
    }

    public static function instance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
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


    /**
     * 登录验证
     * @param unknown_type $value
     * @author andsky 669811@qq.com
     */
    public function checkLogin()
    {
        if ( !empty(Session::instance()->{$this->session_name})){
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 状态决断
     * @param unknown_type $go
     * @author andsky 669811@qq.com
     */
    public function checkState()
    {
        if (!$this->checkLogin()) {
            //Session::instance()->clear();
            $uri = Request::instance()->uri();
            if (strstr($uri, '?')) {
                $uri .= '&'.time();
            }else {
                $uri .= '?'.time();
            }
            if (strstr($this->login_url, '?')) {

                $login_url = $this->login_url.'&redirect='.urlencode($uri);
            }else {
                $login_url = $this->login_url.'?redirect='.urlencode($uri);
            }
            Response::instance()->nocache();
            //($login_url);
            echo "<script language='javascript' type='text/javascript'>";
            echo "window.location.href='$login_url'";
            echo "</script>";
            exit;
        }
        return TRUE;
    }

    /**
     * 权限判断
     *
     * @author andsky 669811@qq.com
     */
    public function checkAllow($priv_field = 'priv_id', $group_field = 'group_id')
    {
        $priv_id = Base::instance()->model('priv')->get_id(CONTROL, ACTION);
        if (empty($priv_id)) {
            return TRUE;
        }
        if ($this->checkState()) {
            $user_priv = $group_priv = array();
            $user_info = Session::instance()->{$this->session_name};
            if (!empty($user_info['priv_id'])) {
                //$user_priv = explode(',', $_SESSION['user']['priv_id']);
                $user_priv = $user_info['priv_id'];
            }
            if (!empty($user_info['group_id'])) {
                $group_priv = Base::instance()->model('group')->get($user_info['group_id']);

            }
            if (!is_array($group_priv['priv_id'])) {
                $group_priv['priv_id'] = array();
            }
            $priv = array_merge($user_priv, $group_priv['priv_id']);
            $priv = array_unique($priv);

            foreach ($priv as $value) {
                if ($value == $priv_id) {
                    return TRUE;
                }
            }

        }
        JS::alert('deadend','back');
    }
}