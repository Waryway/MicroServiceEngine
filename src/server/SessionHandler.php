<?php
namespace WarywayWebsiteTemplate\server;

class SessionHandler
{
    use \WarywayWebsiteTemplate\traits\Singleton;

    /**
     * @var \React\Http\Request $rawRequestObject
     */
    private $rawRequestObject;
    public function getRawRequestObject()
    {
        return $this->rawRequestObject;
    }

    /**
     * @var \React\Http\Request $rawRequestObject
     */
    private $rawResponseObject;
    public function getRawResponseObject()
    {
        return $this->rawResponseObject;
    }


    /**
     * @param \React\Http\Request $requestObject
     * @param \React\Http\Response $responseObject
     */
    public function ProcessRequest($requestObject, $responseObject)
    {
        $this->rawRequestObject = $requestObject;
        $this->rawResponseObject = $responseObject;

        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            $routes = array_merge((new FileRouter())->getRoute(), (new PageRouter())->getRoute());
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });

// Fetch method and URI from somewhere
        $httpMethod = $requestObject->getMethod();//$_SERVER['REQUEST_METHOD'];
        $uri = $requestObject->getPath();//$_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $responseObject->writeHead(404, array('Content-Type' => 'text/html'));
                $Index = new \WarywayWebsiteTemplate\pages\Index();
                $Index->setPageName('404 - Designated target not found');
                $responseObject->end($Index->renderedPage);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $responseObject->writeHead(405, array('Content-Type' => 'text/plain'));
                $responseObject->end("Unable to route request, because we only route here: ".print_r($allowedMethods, true)."\n");
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $handler($vars, $responseObject);
                break;
        }




    }
}