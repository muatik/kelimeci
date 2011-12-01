<?php
moduler::simportLib('apage');
abstract class controllers extends apage
{

	public $pageLayout;
	
	public function initialize(){

		if($this->pageLayout==null)
			$this->pageLayout=preg_replace(
				'/Controller$/','',get_class($this)
			);

		$this->name=$this->pageLayout;

		parent::initialize();
	}

	public function run(){
		$this->generatedOutput=$this->loadSiteLayout();
	}

	public function loadSiteLayout(){
		
		if(file_exists($this->layoutsPath.$siteLayout))
			return $this->loadView($siteLayout,null);

	}

	public function loadPageLayout(){
		if(file_exists($this->layoutsPath.$this->pageLayout.'.php'))
			return $this->loadView($this->pageLayout.'.php',null);

	}
}
?>
