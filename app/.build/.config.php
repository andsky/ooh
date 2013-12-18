<?php return array (
  'error' =>
  array (
    'level' => 6135,
    'display' => false,
    'log' => false,
    'path' => 'E:\\work\\ooh\\ooh\\tmp\\log\\',
  ),
  'db' =>
  array (
    'driver' => 'Mysql',
    'host' => 'localhost:3306',
    'dbname' => 'app_movie',
    'username' => 'root',
    'password' => '',
    'pconnect' => false,
    'charset' => 'utf8',
  ),
  'router' =>
  array (
    'control_action' => 'index.index',
    'route' => 'get',
    'protocol' => 'PATH_INFO',
    'ext' => '.html',
    'urls' =>
    array (
      'test' =>
      array (
        'act' => 'index.test',
        'params' => '(?P<name>(.*)+)',
      ),
    ),
  ),
  'cache' =>
  array (
    'driver' => 'file_cache',
    'exp' => 3600,
    'server' => '127.0.0.1:11211',
  ),
  'queue' =>
  array (
    'driver' => 'file_queue',
    'pconnect' => false,
    'exp' => 3600,
    'server' => '127.0.0.1:6379',
  ),
  'pager' =>
  array (
    'driver' => 'pager_pager',
  ),
  'session' =>
  array (
    'driver' => 'file_session',
    'name' => 'SESSANDSKY',
    'expire' => 7200,
  ),
  'cookie' =>
  array (
    'expire' => 7200,
    'domain' => '',
    'path' => '/',
  ),
  'view' =>
  array (
    'driver' => 'Phplite',
    'output' => 'html',
    'ext' => '.html',
    'charset' => 'utf-8',
    'compile_check' => true,
    'debugging' => false,
    'use_sub_dirs' => false,
    'left_delimiter' => '{%',
    'right_delimiter' => '%}',
    'template_dir' => 'E:\\work\\ooh\\ooh\\app\\views/templates',
    'compile_dir' => 'E:\\work\\ooh\\ooh\\app\\views/templates_c',
    'cache_dir' => 'E:\\work\\ooh\\ooh\\app\\views/cache',
  ),
  'conf' =>
  array (
    'site_name' => 'ooh',
    'site_url' => 'http://www.andsky.com/',
  ),
);