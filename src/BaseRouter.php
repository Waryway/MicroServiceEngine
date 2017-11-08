<?php
namespace Waryway\Service;

use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class BaseRouter {
    public function __construct()
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/users', 'get_all_users_handler');
            // {id} must be a number (\d+)
         //   $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
            // The /{title} suffix is optional
         //   $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
        });
    }

    // This is initialized and used by default. The next goal is to override this class if possible.
    // Or at least determine a way to send the request to the router of the program that required this 'service'.
    public function RouteRequest(ServerRequestInterface $request) {
        // Fetch method and URI from somewhere
        $httpMethod = $request->getMethod();
        $uri = $request->getUri();

        print_r($uri->getPath());
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $path = rawurldecode($uri->getPath());
        $handler = null;
        $vars = [];
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $path);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                break;
        }
        if($handler){
            return json_encode($this->$handler($vars));
        } else {
            return json_encode($path);
        }
    }

    public function get_all_users_handler($vars)
    {
        return print_r($vars, true);
    }

}