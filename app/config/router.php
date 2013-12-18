<?php

return array(
		'control_action'     => 'index.index', // 默认的入口控制器
		'route'				 => 'get',        //支持 get path_info regex
		'protocol'           => 'PATH_INFO',
		'ext'     => '.html',
        'urls'     => array(
			  'test'        => array('act' => 'index.test', 'params' => '(?P<name>(.*)+)'),

)
);
?>