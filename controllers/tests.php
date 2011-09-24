<?php
require_once('ipage.php');
class testsController extends ipage{
	
	public function initialize(){
		parent::initialize();
	}
	
	public function run(){
		parent::run();
	}
	
	public function viewproducts(){
		
		$p=$this->products->getProducts(
			($this->isLogined?$this->u->id:null)
		);

		return $this->loadView(
			'products.php',
			$p,
			false
		);
		
	}

	public function topla(){
		return true;
		echo 'toplanıyor..';
	}

	public function viewsentenceCompletionTest(){
		// test modelinden cümle tamamlama testini hazırlat
		// modelden gelen datadatı ikinci parametreyle
		// view'e aktar.
		return $this->loadView(
			'sentenceCompletionTest.php',
			'null',
			false
		);
	}	
}	
?>
