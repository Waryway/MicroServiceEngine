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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Server static assets, such as html or javascript
     * @var string
     */
    private $staticAssetEnabled = false;

    private $routeList = [];

    private function credit($params){
        return new Response(200, ['Content-Type' => 'text/html'], self::CREDITS);
    }

    public function __construct()
    {
        $this->staticAssetPath = '/'; // the root of the project, not the root of the OS.

        $this->setRoute('GET', '/credit', 'credit');
        if ($this->staticAssetEnabled) {
            $this->setRoute(['GET','POST'], $this->staticAssetPath.'[/{filePath:.*}]', 'staticAssetHandler' );
            $this->setRoute(['GET','POST'], '/favicon.ico', 'staticAssetHandler' );
        }

        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routeList as $route) {
                $r->addRoute($route['method'], $route['route'], $route['handler']);
            }
        });
    }

    /**
     * Turn on the static asset server option.
     */
    protected function setStaticAssetEnabled($path = '/') {
        $this->staticAssetPath = $path;
        $this->staticAssetEnabled = true;
    }

    /**
     * Add a route to the list of routes.
     *
     * @param $method
     * @param $route
     * @param $handler
     */
    protected function setRoute($method, $route, $handler) {
        $this->routeList[] = ['method'=>$method,'route'=>$route,'handler'=>$handler];
    }

    /**
     * Server a static asset.
     *
     * @param $params
     * @return array
     */
    public function staticAssetHandler($params) {
        print_r($params['queryString']);
        $filePath = $params['filePath'];

        $response = [
            'body' => $this->NotFoundMessage($this->staticAssetPath.$filePath),
            'code' => 404
        ];

        if($filePath != "" && file_exists(dirname(__DIR__, 4) . $this->staticAssetPath . $filePath)) {
            $fileType = strstr($filePath, '.js') ? 'application/javascript' : 'text/html';

            $response = new Response(200, ['Content-Type' => $fileType], file_get_contents(dirname(__DIR__, 4) . $this->staticAssetPath . $filePath));
        }

        if($params['path'] == '/favicon.ico') {
            $response = new Response(200,['Content-Type' => 'image/x-icon'], file_get_contents(dirname(__DIR__, 4) . $this->staticAssetPath.'favicon.ico'));
        }
        return $response;
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
                $routeInfo[2]['query'] = rawurldecode($uri->getQuery());
                $routeInfo[2]['path'] = $path;
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