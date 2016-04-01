<?php
namespace WarywayWebsiteTemplate\classes;

if(!defined('WEBSITE_NAME')){define('WEBSITE_NAME', 'configure your website');}
if(!defined('WEBSITE_ROOT')){define('WEBSITE_ROOT', '../');}

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

}

?>