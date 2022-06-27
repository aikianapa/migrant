<?php
session_start();
require_once __DIR__ . '/synapse.php';
/*
php server.php start
php server.php start -d -демонизировать скрипт
php server.php status
php server.php stop
php server.php restart
php server.php restart -d
php server.php reload
*/

/*
@type [
    sysmsg: системное сообщение
    data: передача набора данных
    ajax: ajax запрос
    json: ответ в json
]

@cast [
    wide: вещание на всю систему
    room: вещание на комнату (по-умолчанию)
    user: вещание пользователю
    self: вещание себе
    silent: ничего не отвечает
]

*/
use Workerman\Worker;

$context = array(
    'ssl' => array(
        'local_cert'  => '/your/path/of/server.pem',
        'local_pk'    => '/your/path/of/server.key',
        'verify_peer' => false,
    )
);

$port = 4000;
$project='migrant';
$secret='accept';

$worker = new Worker("websocket://0.0.0.0:{$port}", $context);
// $worker->transport = 'ssl';

$worker->params = (object)[
    'port' => $port,
    'project' => $project
];


$worker->count = 1;
$worker->reloadable = true;
$worker->synapse = &$synapse;
$worker->users = [];
$worker->rooms = [];

$worker->onWorkerStart = function ($worker) {
    echo "Worker starting...\n";
};

$worker->onWorkerStop = function ($worker) {
    echo "Worker stopped...\n";
};


$worker->onConnect = function ($connection) {
    $count = count($connection->worker->connections);
    echo "New connection\n";
    echo "Count: {$count}\n";
};

$worker->onMessage = function ($connection, $data) {
    $synapse = new modSynapse($connection);
    $data = $synapse->msgRouter($data, $connection);
    echo "{$data}\n";
};

$worker->onClose = function ($connection) {
    unset($connection->worker->rooms[$connection->id]);
    unset($connection->worker->users[$connection->id]);
    echo "Connection closed\n";
    $connection->destroy();
};

// Run worker
Worker::runAll();
?>

