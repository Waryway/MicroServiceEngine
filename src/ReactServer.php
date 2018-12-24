<?php
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Waryway\MicroServiceEngine\BaseRouter;

if(!isset($router)) {
    $router = new BaseRouter();
}

$loop = Factory::create();
$server = new HttpServer(function (ServerRequestInterface $request) use (&$router) {
    $response = $router->RouteRequest($request);

    // If a Response object is returned, just keep returning it.  Otherwise, json encode the response.
    if (is_a($response, Response::class))
    {
        return $response;
    }

    $headers = array_merge(['Content-Type' => 'application/json'], (is_array($response) && isset($response['headers'])) ? $response['headers'] : []);
    $code = (is_array($response) && isset($response['code'])) ? $response['code'] : 200;
    $response = json_encode((is_array($response) && isset($response['body'])) ? $response['body'] : $response);
    return new Response($code, $headers, $response);
});

$socket = new SocketServer(isset($argv[1]) ? $argv[1] : '0.0.0.0:0', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;

$loop->run();