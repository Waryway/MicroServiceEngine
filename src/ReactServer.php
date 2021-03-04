<?php
use Psr\Http\Message\ServerRequestInterface;
use Waryway\MicroServiceEngine\BaseRouter;

if(!isset($router)) {
    $router = new BaseRouter();
}

$loop = React\EventLoop\Factory::create();
$server = new React\Http\Server($loop, function (ServerRequestInterface $request) use (&$router) {
    $response = $router->RouteRequest($request);

    // If a Response object is returned, just keep returning it.  Otherwise, json encode the response.
    if (is_a($response, React\Http\Message\Response::class)) {
        return $response;
    }

    $headers = array_merge(['Content-Type' => 'application/json'], (is_array($response) && isset($response['headers'])) ? $response['headers'] : []);
    $code = (is_array($response) && isset($response['code'])) ? $response['code'] : 200;
    $response = json_encode((is_array($response) && isset($response['body'])) ? $response['body'] : $response);

    return new React\Http\Message\Response($code, $headers, $response);
});


$socket = new React\Socket\Server(isset($argv[1]) ? $argv[1] : '0.0.0.0:0', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;

$loop->run();