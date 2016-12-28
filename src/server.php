<?php
require __DIR__.'/../vendor/autoload.php';

/**
 * @param \React\Http\Request $request
 * @param \React\Http\Response $response
 */
$app = function ($request, $response) {
    /* @var \WarywayWebsiteTemplate\server\SessionHandler $sessionHandler */
    $sessionHandler = \WarywayWebsiteTemplate\server\SessionHandler::inst();
    $sessionHandler->ProcessRequest($request,$response);
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);

$socket->listen(\WarywayWebsiteTemplate\classes\Environment::SERVER_PORT, \WarywayWebsiteTemplate\classes\Environment::SERVER_IP);
$loop->run();

