<?php
namespace WarywayWebsiteTemplate\server;

use WarywayWebsiteTemplate\classes\Environment;
use WarywayWebsiteTemplate\interfaces\iRoute;

class FileRouter implements iRoute
{
    public function getRoute() : array
    {
        return [
            ['GET', '/files/{name:.+}', __CLASS__.'::FileHandler'],
            ['GET', '/{name:.+\.js}', __CLASS__.'::JavascriptHandler'],
            ['GET', '/{name:.+\.css}', __CLASS__.'::CssHandler']
        ];
    }

    public static function FileHandler($vars, $responseObject)
    {
        echo 'no file for you.';
    }

    public static function JavascriptHandler($vars, $responseObject)
    {
        echo 'no javascript for you.';
    }

    /**
     * @param array $vars
     * @param \React\Http\Response $responseObject
     */
    public static function CssHandler($vars, $responseObject)
    {
        $responseObject->writeHead(200, array('Content-Type' => 'text/css'));
        $responseObject->end(file_get_contents(Environment::WEBSITE_ROOT. $vars['name']));

        //echo 'no css for you.' . PHP_EOL;
        //print_r($vars);
        //echo 'no css for you.' . PHP_EOL;
    }

}