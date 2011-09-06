<?php
moduler::simportLib('apage');
abstract class controllers extends apage
{
	public function run(){
		$this->generatedOutput=$this->loadPageLayout();
	}

	public function loadPageLayout(){
		
		$layout=preg_replace(
			'/Controller$/','',get_class($this)
		);

		if(file_exists($this->layoutsPath.$layout.'.php'))
			return $this->loadView($layout.'.php',null);

	}
}
?>
