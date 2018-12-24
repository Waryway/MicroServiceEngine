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
    private $dispatcher;

    private $routeList = [];

    private function credit($params){
        return new Response(200, ['Content-Type' => 'text/html'], self::CREDITS);
    }

    public function __construct()
    {
        $this->setRoute('GET', '/credit', 'credit');
        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routeList as $route) {
                $r->addRoute($route['method'], $route['route'], $route['handler']);
            }
        });
    }

    protected function setRoute($method, $route, $handler) {
        $this->routeList[] = ['method'=>$method,'route'=>$route,'handler'=>$handler];
    }

    /**
     * This is initialized and used by default. The next goal is to override this class if possible.
     * Or at least determine a way to send the request to the router of the program that required this 'service'.
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function RouteRequest(ServerRequestInterface $request)
    {
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

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $path);
        $handler = false;
        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $routeInfo[2]['headers'] = $headers;
                $routeInfo[2]['body'] = $body;
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                break;
            case Dispatcher::NOT_FOUND:
            default:
                $this->NotFoundMessage($path);
                break;
        }


        if ($handler) {
            return $this->$handler($vars);
        } else {
            return $path;
        }
    }

    public function NotFoundMessage($path) {
        return 'The requested resource was unavailable. ' . $path;
    }
}