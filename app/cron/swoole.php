<?php
$app_name = "app";

define('APP_NAME', $app_name);
define('APP_CONTORLLER', $app_name);
define('APP_MODEL', $app_name);
define('APP_DATA', $app_name);

$appdir = dirname(dirname(__FILE__));
require dirname($appdir).'/ooh/dev.php';

$serv = swoole_server_create("127.0.0.1", 9501, SWOOLE_BASE);

swoole_server_set($serv, array(
    'timeout' => 200,  //select and epoll_wait timeout.
    'poll_thread_num' => 1, //reactor thread num
    'writer_num' => 1,     //writer thread num
    'worker_num' => 2,    //worker process num
    'backlog' => 128,   //listen backlog
    'max_request' => 5000,
    'max_conn' => 10000,
    'task_worker_num' => 2,
    'dispatch_mode' => 2,
    ///'timer_interval' => 200,
    //'daemonize' => 1,  //转为后台守护进程运行
    'open_cpu_affinity' => 1,
));


function my_onStart($serv)
{
    echo "MasterPid={$serv->master_pid}|Manager_pid={$serv->manager_pid}\n";
    echo "Server: start.Swoole version is [".SWOOLE_VERSION."]\n";
}


function my_onClose($serv, $fd, $from_id)
{
    echo "Client: fd=$fd is closed.\n";
}

function my_onConnect($serv, $fd, $from_id)
{
    echo "Client:Connect.\n";
}

function my_onReceive($serv, $fd, $from_id, $data)
{
    Request::instance()->serv = $serv;
    Request::instance()->fd = $fd;
    Request::instance()->data = $data;

    $tmp = explode('.', $data['act']);
    define('CONTROL', $tmp[0]);
    define('ACTION', $tmp[1]);
    Application::init();
    try {
        Application::loadControl(CONTROL)->exec();
    } catch (Exception $e) {
        return $serv->send($fd,$e->getCode().'---'.$e->getMessage().'---'.$e->getTraceAsString());
    }
}


swoole_server_handler($serv, 'onStart', 'my_onStart');
swoole_server_handler($serv, 'onConnect', 'my_onConnect');
swoole_server_handler($serv, 'onReceive', 'my_onReceive');
swoole_server_handler($serv, 'onClose', 'my_onClose');


swoole_server_start($serv);