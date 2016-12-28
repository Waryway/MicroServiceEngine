<?php
namespace WarywayWebsiteTemplate\pages;

class Index
{
	use \WarywayWebsiteTemplate\traits\Page;
	
	protected function Configure()
	{
		$this->setPageName('WaryWay: Narrow Is The Way, Which Leadeth Unto Life.');
	}
	
	
	protected function RenderBodyContent()
	{		
		return 'Welcome to waryway.com!';
	}
}
?>