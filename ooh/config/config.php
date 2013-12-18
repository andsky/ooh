<?php
/**
 * 配置文件
 */
return array(
        'error'	=> array(                //错误处理
                'level'		=> E_ALL ^ E_NOTICE,//E_ALL ^ E_NOTICE
                'display'	=> false,
                'log'		=> true,
                'path'		=> TMP_PATH .'log'. DIRECTORY_SEPARATOR
        ),
        'db' => array(
                'driver' => 'Mysql',
                "host" => "localhost:3306",//主机地址
                "dbname" => "phone_platform",//数据库名
                "username" => "root", //连接用户名
                "password" => "",//连接密码
                "pconnect" => false,//是否持久化链接
                "charset" => "utf8"//连接用的字符集
        ),
        'router' => array(
                'control_action'     => 'index.index', // 默认的入口控制器
                'route'				 => 'regex',        //支持 get path_info regex
                'protocol'           => 'PATH_INFO',
                'ext'     => '.html',
                'urls'     => array(
                        'main'        => array('act' => 'index.main', 'params' => '(?P<name>(.*)+)'),
                )
        ),
        'cache' => array(
            'driver'   => 'file_cache',
            'pconnect'   => false,
            'exp'     => 3600,
            'server' => '127.0.0.1:6379',

        ),
        'queue' => array(
                'driver'   => 'file_queue',
                'pconnect'   => false,
                'exp'     => 3600,
                'server' => '127.0.0.1:6379',

        ),
        'pager' => array(
            'driver'   => 'sp_pager',
        ),
        'session' => array(
            'driver'   => 'cache_session',
            'sess_table'  => 'app_sess',
            'name' => 'SESSANDSKY',
            'expire' => 7200,
         ),
        'cookie' => array(
            'expire' => 7200,
            'domain' => '.test.com',
            'path' => '/'
        ),
        'view' => array(
            //'driver' => 'Smarty',
            'driver' => 'Phplite',
            'output' => 'html',
            'ext' => '.php',
            'charset' => 'utf-8',
            'compile_check' => true,
            'debugging' => false,
            'use_sub_dirs' => FALSE,
            'left_delimiter' => '{%',
            'right_delimiter' => '%}',
            'template_dir' => APP_PATH . 'views',
            'compile_dir' => APP_PATH . 'views/templates_c',
            'cache_dir' => APP_PATH . 'views/cache',
        )
);