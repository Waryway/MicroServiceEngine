<?php
namespace WarywayWebsiteTemplate\pages;

class About
{
	use \WarywayWebsiteTemplate\traits\Page;
	
	protected function Configure()
	{
		$this->setPageName('About');
	}
	
	
	protected function RenderBodyContent()
	{		
		return 'This here is waryway.com! Thanks for stoppin\' by.';
	}
}
?>