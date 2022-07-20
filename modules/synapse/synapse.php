<?php
$tmp = realpath(__DIR__.'/../../lib/vendor/autoload.php');
if (is_file($tmp)) {
    require_once ($tmp);
} else {
    $tmp = realpath(__DIR__.'/../../engine/lib/vendor/autoload.php');
    require_once($tmp);
}
use Workerman\Worker;

class modSynapse
{
    function __construct(&$obj = null) {
        $class = strtolower(get_class($obj));
        switch ($class) {
            case 'wbdom':
                $dom = &$obj;
                $app = &$obj->app;
                if (!isset($dom->params->host)) $dom->params->host = $app->route->hostname;
                if (!isset($dom->params->room)) $dom->params->room = 'synapse';
                $roomhash = implode('.', (array)$dom->params);
                $userhash = implode('.', (array)$dom->params).'.'.session_id();
                $dom->params->roomhash = md5($roomhash);
                $dom->params->userhash = md5($userhash);
                $ui = $app->fromFile(__DIR__.'/synapse_ui.php');
                $ui->fetch((array)$dom->params);
                $inner = $app->fromString($dom->inner());
                $dom->after($ui->outer()."\r\n".$inner->outer());
                $dom->remove();
                $this->server();
                break;
            case 'workerman\connection\tcpconnection':
                $obj->rooms = [];
                default:
                # code...
                break;
        }

    }

    function msgRouter($data, &$synapse) {
        if (!is_callable('wbAuthGetContents')) {
            @include_once(realpath(__DIR__.'/../../engine/functions.php'));
        }
        $data = json_decode($data, true);
        if (!isset($data['type'])) {
            echo "Unknown message type...\n";
            return;
        }
        $this->worker = &$synapse->worker;
        $this->synapse = &$synapse;
        $this->room = $data['room'];
        $this->user = $data['user'];
        if (!isset($this->worker->rooms[$this->room])) $this->worker->rooms[$this->room] = [];
        if (!isset($this->worker->users[$this->user])) $this->worker->users[$this->user] = [];
        $this->connections = &$synapse->worker->connections;
        $mode = 'route'.ucfirst($data['type']);

        if (method_exists($this, $mode)) {
            $this->$mode($data);
        }
    }


    function routeAjax($data)
    {
        $this->cast = $data['cast'];
        $post = isset($data['post']) ? $data['post'] : [];
        $result = json_decode(wbAuthPostContents($data['url'], $post));
        $reply = ['type'=>'data','cast'=>$this->cast,'data'=>$result,
            'room'=>$data['room'],'user'=>$data['user']
        ];
        $this->cast($reply);
    }

    function routeData($data) {
        $this->cast($data);
    }

    function routeSysmsg($data) {
        $this->cast = $data['cast'];
        if ($data['action'] == 'join') {
            // подключаемся к комнате
            $this->worker->rooms[$this->synapse->id] = $this->room;
            $this->worker->users[$this->synapse->id] = $this->user;
            $this->cast(['type'=>'sysmsg','action'=>'msg','text'=>'joined','user'=>$this->user,'room'=>$this->room]);
        } else {
            $this->cast($data);
        }
    }

    function cast($data) {
        isset($this->cast) ? null : $this->cast = 'room';
        $data['cast'] = $this->cast;
        $data = (array)$data === $data ? json_encode($data) : $data;
        switch ($this->cast) {
            case 'wide':
                foreach ($this->connections as $conn) {
                    $conn->send($data);
                }
                break;
            case 'room':
                print_r($this->worker->rooms);
                foreach ($this->worker->connections as $conn) {
                    if (isset($this->worker->rooms[$conn->id]) && $this->room == $this->worker->rooms[$conn->id]) {
                        $conn->send($data);
                    }
                }
                break;
            case 'self':
                foreach ($this->worker->connections as $conn) {
                    if (isset($this->worker->users[$conn->id]) && $this->user == $this->worker->users[$conn->id]) {
                        $conn->send($data);
                    }
                }
                break;
            case 'silent':
                // без ответа
                break;
        }
    }

    public function server() {
        @$res = exec('php '. __DIR__ .'/server.php status');
        if (strpos($res,'not run')) {
            @exec('php '. __DIR__ .'/server.php start -d');
        } else {
            // ChatServer is running;
        }
    }
}
?>