<?php
namespace WarywayWebsiteTemplate\classes;

if(!defined('WEBSITE_NAME')){define('WEBSITE_NAME', 'WaryWay');}
if(!defined('WEBSITE_ROOT')){define('WEBSITE_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );} // set your own, or use the one relative to this file.

class Environment
{
	const WEBSITE_NAME			= WEBSITE_NAME;
	const IS_LOADED 			= true;
	const HTTP_COOKIE_DOMAIN 	= 'pleasedefineyourdomain.com';
	const HTTPS_COOKIE_DOMAIN	= 'pleasedefineyourdomain.com';
	const HTTP_COOKIE_PATH		= '/';
	const HTTPS_COOKIE_PATH		= '/';
	const URL_PAGE_TO_CONFIG	= '../config/';
	const PATH_PAGE_TO_CONFIG	= '..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
	const WEBSITE_ROOT			= WEBSITE_ROOT;
    const SERVER_PORT           = 80;
    const SECURE_PORT           = 443;
    const SERVER_IP             = '0.0.0.0'; // change to localhost if preventing external 'touches'.
}

?>