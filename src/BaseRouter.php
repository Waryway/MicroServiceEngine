<?php
namespace Waryway\MicroServiceEngine;

use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use React\Http\Message\Response;

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

    /**
     * the root of the project as the default, not the root of the OS.
     * @var string
     */
    private $staticAssetPath = '/';

    /**
     * @var [[]method, route, handler]
     */
    private $routeList = [];

    /**
     * @var MimeType[]
     */
    private $mimeTypeList = [];

    /**
     * @param $params
     * @return Response
     */
    public function credit($params){
        return new Response(200, [], self::CREDITS);
    }

    /**
     * return when not found.
     * @param $message
     * @return array
     */
    protected function NotFoundRoute($message) {
        return ['code'=>404, 'body'=>$message];
    }

    /**
     * return when not allowed.
     *
     * @param string $message
     * @return array
     */
    protected function NotAllowed($message='Method not allowed') {
        return ['code'=>405, 'body'=>$message];
    }

    public function __construct()
    {
        $this->setRoute('GET', '/credit', [__CLASS__,'credit']);

        if ($this->staticAssetEnabled) {
            $this->setRoute(['GET','POST'], $this->staticAssetPath.'[/{filePath:.*}]', [__CLASS__,'staticAssetHandler'] );
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
    protected function setStaticAssetPath($path = '/') {
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

    protected function addMimeType(MimeType $mimeType) {
        $this->mimeTypeList[$mimeType->getExtension()] = $mimeType;
    }
    /**
     * Server a static asset.
     *
     * @param $params
     * @return array
     */
    public function staticAssetHandler($params) {
        $filePath = $params['filePath'];

        $response = [
            'body' => $this->NotFoundMessage($this->staticAssetPath.$filePath),
            'code' => 404
        ];

        if($filePath != "" && file_exists(dirname(__DIR__, 4) . $this->staticAssetPath . $filePath)) {
            foreach($this->mimeTypeList as $name => $mimeType) {
                if(strstr($filePath, '.'.$mimeType->getExtension())) {
                    $response = new Response(200, ['Content-Type' => $mimeType->getContentType()], file_get_contents(dirname(__DIR__, 4) . $this->staticAssetPath . $filePath));
                    break;
                }
            }
        }

        return $response;
    }

    /**
     * This is initialized and used by default. The next goal is to override this class if possible.
     * Or at least determine a way to send the request to the router of the program that required this 'service'.
     *
     * @param ServerRequestInterface $request
     * @return string|array
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
                return $this->NotAllowed();
                break;
            case Dispatcher::FOUND:
                $routeInfo[2]['headers'] = $headers;
                $routeInfo[2]['body'] = $body;
                $routeInfo[2]['query'] = rawurldecode($uri->getQuery());
                $routeInfo[2]['path'] = $path;
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                return $handler($vars);
                break;
            case Dispatcher::NOT_FOUND:
            default:
                return $this->NotFoundRoute('The requested resource was unavailable. ' . $path);
                break;
        }
    }
}
