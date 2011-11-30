<?php
moduler::simportLib('apage');
abstract class controllers extends apage
{

	protected $pageLayout;

	public function run(){
		$this->generatedOutput=$this->loadSiteLayout();
	}

	public function loadSiteLayout(){
		$siteLayout='layout.php';
		if(file_exists($this->layoutsPath.$siteLayout))
			return $this->loadView($siteLayout,null);

	}

	public function loadPageLayout(){

		if($this->pageLayout==null)
			$this->pageLayout=preg_replace(
				'/Controller$/','',get_class($this)
			);

		if(file_exists($this->layoutsPath.$this->pageLayout.'.php'))
			return $this->loadView($this->pageLayout.'.php',null);

	}
}
?>
