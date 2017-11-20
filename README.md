# MicroServiceEngine
Base Service using ReactPHP for PHP only Microservices

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
