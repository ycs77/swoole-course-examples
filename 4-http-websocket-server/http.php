<?php

use Swoole\Http\Response;

$server = new Swoole\HTTP\Server('0.0.0.0', 9501);

$i = 0;

$server->on('request', function ($request, Response $response) use (&$i) {
    // var_dump($request);
    $i++;
    var_dump($i);
    $response->end('Hello Swoole!');
});

echo "Server is starting...\n";
$server->start();
