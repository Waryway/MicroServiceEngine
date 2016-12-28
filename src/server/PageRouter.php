<?php
namespace WarywayWebsiteTemplate\server;

use WarywayWebsiteTemplate\classes\Environment;
use \WarywayWebsiteTemplate\pages;

class PageRouter implements \WarywayWebsiteTemplate\interfaces\iRoute
{
    public function getRoute() : array
    {
        return [
            ['GET', '/pages/{name}[/{options:.+}]', __CLASS__.'::PageLoader']
        ];
    }

    /**
     * @param array $vars
     * @param \React\Http\Response $responseObject
     */
    public static function PageLoader($vars, $responseObject)
    {
        chdir(Environment::WEBSITE_ROOT);

        $FileName = strtoupper(substr($vars['name'],0,1)) . substr($vars['name'],1);
        if(file_exists('pages' . DIRECTORY_SEPARATOR . $FileName)) {

            $pageToLoad = 'WarywayWebsiteTemplate\\pages\\'.(strstr($FileName,'.')!== false ? strstr($FileName,'.', true) : $FileName);

            $responseObject->writeHead(200, array('Content-Type' => 'text/html'));
            $page = new $pageToLoad();
            $responseObject->end($page->renderedPage);
        }
        else {
            $responseObject->writeHead(404, array('Content-Type' => 'text/html'));

            $responseObject->end('Could not find ' . Environment::WEBSITE_ROOT . 'pages'.DIRECTORY_SEPARATOR . $FileName);
        }
    }
}

?>