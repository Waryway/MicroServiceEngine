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
6. 'composer require waryway/micro-service-engine'
7. From the root directory of you new project, simply run: `vendor\bin\server.bat <company>\<project> 0.0.0.0:99`
8. or for linux: `./vendor/bin/server <company>/<project> 0.0.0.0:99`

You can also run `vendor\bin\server.bat -h` for further information.

## Router setup
Make sure to extend the BaseRouter. More details to be added in the future!

### Setting routes

The microservice engine is using the [nikic/fast-route](https://packagist.org/packages/nikic/fast-route) library. You can find path mapping information in the nikic/fast-route readme.

### Example Routers

#### Example Application

https://github.com/Waryway/MicroServiceEngine/blob/master/example/Router.php

#### Internal Example
For this repository an example from within this this source code can be viewed by first running `computer install`, then second, running :
    
    php server Waryway\Example 0.0.0.0:89

You'll be able to visit `localhost:89/index.html` and `localhost:89/hi`
