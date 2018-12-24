<?php
namespace Waryway\MicroServiceEngine;

use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use React\Http\Response;

class BaseRouter {
    const CREDITS = <<<CREDITS
<html><head><title>Waryway/MicroServiceEngine Credits</title></head><body><h1>Credits</h1><p>This website is powered by the <a href="http://waryway.com">WaryWay</a> MicroServiceEngine.</p></body></html>
CREDITS;

    /**
     * @var array[Dispatcher]
     */
    private $dispatcherList = [];


    public function __construct()
    {
        $this->addDispacter(\FastRoute\simpleDispatcher(function (RouteCollector $r) {
            // Default route for 'credits'.
            $r->addRoute('GET', '/credit', function ($params) {
                return new Response(200, ['Content-Type' => 'text/html'], self::CREDITS);
            });
        }));
    }

    /**
     *
     *
     * @param Dispatcher $newDispatcher
     */
    protected function addDispacter(Dispatcher $newDispatcher ) {
        $this->dispatcherList[] = $newDispatcher;
    }

    /**
     * This is initialized and used by default. The next goal is to override this class if possible.
     * Or at least determine a way to send the request to the router of the program that required this 'service'.
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function RouteRequest(ServerRequestInterface $request) {
        // Fetch method and URI from somewhere
        $httpMethod = $request->getMethod();
        $headers = $request->getHeaders();
        $body = $request->getBody();
        $uri = $request->getUri();

        // print_r($uri->getPath());
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $path = rawurldecode($uri->getPath());

        foreach($this->dispatcherList as $dispatcher) {
            $routeInfo = $dispatcher->dispatch($httpMethod, $path);
            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    continue; // Because it might be found in a different dispatcher.
                    break;
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $allowedMethods = $routeInfo[1];
                    // ... 405 Method Not Allowed
                    break;
                case Dispatcher::FOUND:

                    $routeInfo[2]['headers'] = $headers;
                    $routeInfo[2]['body'] = $body;
                    $handler = $routeInfo[1];
                    $vars = $routeInfo[2];
                    if ($handler){
                        return $this->$handler($vars);
                    } else {
                        return $path;
                    }
                    break;
            }
        }

        return $this->NotFoundMessage($path);
    }

    public function NotFoundMessage($path) {
        return 'The requested resource was unavailable. ' . $path;
    }
}