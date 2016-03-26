<?php

require_once '..'. DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'vendor'. DIRECTORY_SEPARATOR . 'autoload.php';



class Index
{
	use WarywayWebsiteTemplate\traits\Page;
	
	protected function Configure()
	{
		$this->setPageName('WaryWay: Narrow Is The Way, Which Leadeth Unto Life.');
	}
	
	
	protected function RenderBodyContent()
	{		
		return 'Welcome to waryway.com!';
	}
}
new Index();
?>