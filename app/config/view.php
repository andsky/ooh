<?php
return  array(
				//'driver' => 'Smarty',
				'driver' => 'Phplite',
				'output' => 'html',
				'ext' => '.html',
				'charset' => 'utf-8',
				'compile_check' => true,
				'debugging' => false,
				'use_sub_dirs' => FALSE,
				'left_delimiter' => '{%',
				'right_delimiter' => '%}',
				'template_dir' => APP_PATH . 'views/templates',
				'compile_dir' => APP_PATH . 'views/templates_c',
				'cache_dir' => APP_PATH . 'views/cache',

);

