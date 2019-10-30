<?php

$server = new Swoole\Server('0.0.0.0', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

// $server->set([
//     'heartbeat_check_interval' => 5,
//     'heartbeat_idle_time' => 10,
// ]);

$server->on('connect', function ($server, $fd){
    echo "Client {$fd} connected.\n";

    broadcast($server, function () use ($fd) {
        return "Client {$fd} connected.\n";
    });
});

$server->on('receive', function ($server, $fd, $reactorId, $data) {
    if (trim($data) === 'exit') {
        $server->close($fd);
        return;
    }

    broadcast($server, function () use ($fd, $data) {
        return "Message from $fd: {$data}";
    });
});

$server->on('close', function ($server, $fd) {
    echo "Client {$fd} closed.\n";

    broadcast($server, function () use ($fd) {
        return "Client {$fd} closed.\n";
    });
});

echo "Server is starting...\n";
$server->start();

function broadcast($server, $callback) {
    $start_fd = 0;

    while (true) {
        $fds = $server->getClientList($start_fd, 100);

        if ($fds === false or count($fds) === 0) {
            break;
        }

        $start_fd = end($fds);

        foreach ($fds as $fd) {
            $server->send($fd, $callback());
        }
    }
}
