<?php
require_once('ipage.php');
class testsController extends ipage{
	
	public function initialize(){
		parent::initialize();
		$this->addModel('tests');
		$this->tests=new \kelimeci\tests();
	}
	
	public function run(){
		parent::run();
	}
	
	public function validate(){
		$r=$this->r;

		if(!isset($r['testName'],$r['wordId']) 
			|| !is_numeric($r['wordId']))
			return false;
		
		$tests=$this->tests;

		// creating a test response object
		$t=new stdClass();
		$t->name=$r['testName'];
		$t->wordId=$r['wordId'];
		
		if($t->name=='sentenceCompletionTest' 
			&& isset($r['quoteId']) && is_numeric($r['quoteId'])){

				$t->quoteId=$r['quoteId'];
				$t->answer=$r['answer'];
				return $tests->validate($t);
			
		}
		elseif($t->name=='variationWritingTest'
			&& isset($r['answers'],$r['variations'])
			&& is_array($r['answers'])
			&& is_array($r['variations'])){	
				
				$t->variations=array();
				foreach($r['variations'] as $k=>$i){
					$o=new stdClass();
					$o->answer=$r['answers'][$k];
					$o->variation=$i;
					$t->variations[]=$o=$o;;
				}
				
				return $tests->validate($t);
		
		}
		elseif($t->name=='categorySelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){

				$t->selected=$r['selected'];
				return $tests->validate($t);

		}
		elseif($t->name=='synonymSelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){	
				
				$t->selected=$r['selected'];
				return $tests->validate($t);

		}
		elseif($t->name=='englishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				$t->answer=$r['answer'];
				return $tests->validate($t);

		}
		elseif($t->name=='turkishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				$t->answer=$r['answer'];
				return $tests->validate($t);

		}

		return false;
	}

	public function viewsentenceCompletionTest(){
		// test modelinden cümle tamamlama testini hazırlat
		// modelden gelen datadatı ikinci parametreyle
		// view'e aktar.

		$o=new stdClass();
		$o->estimatedTime='00:12:00';
		
		$i1=new stdClass;
		$i1->wordId=1;
		$i1->quoteId=5;
		$i1->sentence='Have you [...] been in Turkey?';
		$i1->clue=array('go','are','ever','got');

		$i2=new stdClass;
		$i2->wordId=2;
		$i2->quoteId=6;
		$i2->sentence='The [...] is really faster than that.';
		$i2->clue=array('go','are','car','ever','got');

		$o->items=array($i1,$i2);

		return $this->loadView(
			'sentenceCompletionTest.php',
			$o,
			false
		);
	}

	public function viewvariationWritingTest(){
		$o=new stdClass();
		$o->estimatedTime='00:31:00';
		
		$i1=new stdClass();
		$i1->wordId=1;
		$i1->word='access';
		$i1->variations=array('noun','verb','adjective');

		$i2=new stdClass();
		$i2->wordId=2;
		$i2->word='mean';
		$i2->variations=array('noun','verb','adjective');


		$o->items=array($i1,$i2);

		return $this->loadView(
			'variationWritingTest.php',
			$o,
			false
		);
	}

	public function viewEnglishWritingTest(){
		$o=new stdClass();
		$o->estimatedTime='00:31:00';
		
		$i1=new stdClass();
		$i1->wordId=4;
		$i1->defination='mükemmel, kusursuz, harika';
		$i1->classes=array('noun','verb');

		$i2=new stdClass();
		$i2->wordId=7;
		$i2->defination='sürat, hız, hızlı';
		$i2->classes=array('noun','adjective');
	
		$o->items=array($i1,$i2);

		return $this->loadView(
			'englishWritingTest.php',
			$o,
			false
		);
	}

	public function viewTurkishWritingTest(){
		$o=new stdClass();
		$o->estimatedTime='00:31:00';
		
		$i1=new stdClass();
		$i1->wordId=22;
		$i1->defination='perfect, excellent, elegant';
		$i1->classes=array('noun','verb');

		$i2=new stdClass();
		$i2->wordId=14;
		$i2->defination='quick, speedy, agile';
		$i2->classes=array('noun','adjective');
	
		$o->items=array($i1,$i2);
		
		return $this->loadView(
			'turkishWritingTest.php',
			$o,
			false
		);
	}

	public function viewsynonymSelectionTest(){
		$o=new stdClass();
		$o->estimatedTime='00:31:00';
		
		$i1=new stdClass();
		$i1->wordId=42;
		$i1->word='perfect';
		$i1->options=array('excellent','elegant','perception','car','bad');

		$i2=new stdClass();
		$i2->wordId=62;
		$i2->word='bad';
		$i2->options=array('good','insult','save','nefarious','disgusting');

		$o->items=array($i1,$i2);
		
		return $this->loadView(
			'synonymSelectionTest.php',
			$o,
			false
		);
	}

	public function viewcategorySelectionTest(){
		$o=new stdClass();
		$o->estimatedTime='00:31:00';
		
		$i1=new stdClass();
		$i1->wordId=51;
		$i1->word='perfect';

		$i2=new stdClass();
		$i2->wordId=86;
		$i2->word='go';

		$o->items=array($i1,$i2);

		return $this->loadView(
			'categorySelectionTest.php',
			$o,
			false
		);
	}

}	

?>
