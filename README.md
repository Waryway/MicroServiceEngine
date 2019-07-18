# MicroServiceEngine
Base Service using ReactPHP for PHP only Microservices

## Status
[![Build Status](https://travis-ci.org/Waryway/MicroServiceEngine.svg?branch=master)](https://travis-ci.org/Waryway/MicroServiceEngine)


## Description
Use this microservice engine to power you microservice!  It is an extensible router / server base for ReactPHP. It provides an extremely light weight server and router combo.

The closest comparison I can think of, is express for nodejs. Only, this uses PHP!

## Usage
Rough steps to getting this up an running. Example coming soon!
1. Create your new PHP microservice application
2. Make sure it is PSR-4!
3. Add a `Router` class at the base namespace.
4. The namespace should be something like... <company>\<project>
5. Run `composer update`
5. From the root directory of you new project, simply run: `vendor\bin\server.bat Waryway\UserApi 0.0.0.0:99`
6. or for linux: `vendor\bin\server Waryway\UserApi 0.0.0.0:99`

You can also run `vendor\bin\server.bat -h` for further information.

## Router setup
Make sure to extend the BaseRouter. More details to be added in the future!

### Example Router 
https://github.com/Waryway/MicroHelloWorld/blob/master/src/Router.php

Given a MicroHelloWorld project, starting the server would be: 
    
    vendor\bin\server Waryway\MicroHelloWorld 0.0.0.0:99

```
<?php
namespace Waryway\MicroHelloWorld;
use Waryway\MicroServiceEngine\BaseRouter;
class Router extends BaseRouter {
    public function __construct() {
        $this->setRoute(['GET', 'POST', 'PUT', 'DELETE'], '/hi', 'helloWorld');
        $this->setRoute(['GET', 'POST'], '/index.html', 'contentRoot');
        parent::__construct();
    }
    public function helloWorld($params) {
        print_r($params);
        return 'Hello World';
    }
    public function contentRoot($params) {
        $response = [
            'body' => '404',
            'code' => 404
        ];
        if(file_exists(__DIR__.'/../static/index.html')) {
            print_r(array_keys($params));
            $response['code'] = 200;
            $response['body'] = file_get_contents(__DIR__.'/../static/index.html');
            $response['headers'] = ['Content-Type' => 'text/plain'];
        }
        return $response;
    }
}
```
