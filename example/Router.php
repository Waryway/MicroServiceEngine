<?php
namespace Waryway\Example;

use React\Http\Response;
use Waryway\MicroServiceEngine\BaseRouter;

class Router extends BaseRouter {
    public function __construct() {
        $this->setRoute(['GET', 'POST', 'PUT', 'DELETE'], '/hi', 'helloWorld');
        $this->setRoute(['GET', 'POST'], '/index.html', 'contentRoot');
        parent::__construct();
    }
    public function helloWorld($params) {
        // print_r($params);
        return 'Hello World';
    }
    public function contentRoot($params) {
        $response = [
            'body' => '404',
            'code' => 404
        ];
        if (file_exists(__DIR__.'/static/index.html')) {
            // print_r(array_keys($params));
            $response['code'] = 200;
            $response['body'] = file_get_contents(__DIR__.'/static/index.html');
            $response['headers'] = ['Content-Type' => 'text/html'];
        }

        return new Response($response['code'], $response['headers'], $response['body']);
    }
}