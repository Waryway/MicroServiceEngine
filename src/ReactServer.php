<?php
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Waryway\Service\BaseRouter;

require __DIR__ . '/../vendor/autoload.php';
$loop = Factory::create();
$router = new BaseRouter();
$server = new HttpServer(function (ServerRequestInterface $request) use (&$router) {


    return new Response(
        200,
        ['Content-Type' => 'application/json'],
        $router->RouteRequest($request)
    );
});

$socket = new SocketServer(isset($argv[1]) ? $argv[1] : '0.0.0.0:0', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;

$loop->run();