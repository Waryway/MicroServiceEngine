<?php
namespace src\traits;

trait Page
{
	/**
	 * @var Session
	 */
	private $Session;
	
	/**
	 * @var string
	 */
	private $pageName;
	
	/**
	 * @var Logger
	 */
	public $log;
	
	public function getPageName()
	{
		return $this->pageName;
	}
	
	public function setPageName($pageName)
	{
		if (strlen($pageName) > 64)
		{
			$this->log->warn('The following pagename exceeds 64 characters: '. $pageName);
		}
		$this->pageName = $pageName;
	}
	
	
    final public function __construct()
	{
		\Logger::configure("..\\config\\logger.xml");
		$this->log = \Logger::getLogger(__CLASS__);

		
		
		/** @var $this->Session Session */
		$this->Session = \src\singleton\Session::inst();
		$this->Session->Start();
		$this->RenderPage();
	}
	
	final private function RenderPage()
	{
		$this->Configure();
		$headContent = $this->RenderHead();
		$bodyContent = $this->RenderBody();
		
		echo <<<HTML
<!DOCTYPE html><html><head>{$headContent}</head><body>{$bodyContent}</body></html>
HTML;
	}
	
	protected function Configure()
	{
		$this->log->error('Default configuration was not overridden for this page');
	}
	
	
	final private function RenderHead()
	{
		$title = $this->RenderTitle();
		$meta = $this->RenderMeta();
		$css = $this->RenderCss();
		$javascript = $this->RenderJavascript();
		
		return $title . $meta . $css . $javascript;
	}
	
	final private function RenderTitle()
	{
		return '<title>'.$this->getPageName().'</title>';
	}
	
	final private function RenderMeta()
	{
		return '';
	}
	
	final private function RenderCss()
	{
		return '<link type="text/css" rel="stylesheet" href="'.\src\classes\Environment::URL_PAGE_TO_CONFIG.'style.css"/>';
	}
	
	protected function RenderJavascript()
	{
		return '';
	}
	
	final private function RenderBody()
	{
		$banner = $this->RenderBanner();
		$heading = '<h1>'.$this->getPageName().'</h1>';
		$bodyContent = $this->RenderBodyContent();
		
		
		return $banner . $heading . $bodyContent;
	}
	
	final private function RenderBanner()
	{
		return <<<BANNER
<div class="navbar"><div><a href="index.php">Waryway</a></div></div>
BANNER;
	}
	
	
	protected function RenderBodyContent()
	{
		return "We're sorry, this page just never got its content going!";
	}
	
	
	
	
	
	
}
?>