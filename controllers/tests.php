<?php
require_once('ipage.php');
class testsController extends ipage{
	
	public function initialize(){
		parent::initialize();
		$this->tests=new \kelimeci\tests();
	}
	
	public function run(){

		if(isset($this->r['testType'])){

			$testTypes=array(
				'sentenceCompletionTest',
				'synonymSelectionTest',
				'turkishWritingTest',
				'englishWritingTest',
				'categorySelectionTest',
				'variationWritingTest',
				'voiceTest'
			);

			if(in_array($this->r['testType'],$testTypes))
				$this->pageLayout=$this->r['testType'];
		}

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

		$result=null;

		if($t->name=='sentenceCompletionTest' 
			&& isset($r['quoteId']) && is_numeric($r['quoteId'])){

				$t->quoteId=$r['quoteId'];
				$t->answer=$r['answer'];
				$result=$tests->validate($t);
			
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
				
				$result=$tests->validate($t);
		
		}
		elseif($t->name=='categorySelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){

				$t->selected=$r['selected'];
				$result=$tests->validate($t);

		}
		elseif($t->name=='synonymSelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){	
				
				$t->selected=$r['selected'];
				$result=$tests->validate($t);

		}
		elseif($t->name=='englishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				$t->answer=$r['answer'];
				$result=$tests->validate($t);
		}
		elseif($t->name=='turkishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				$t->answer=$r['answer'];
				$result=$tests->validate($t);
		}
		
		return json_encode($result);
	}
	
	public function prepareTest($testType){
		$test=$this->tests->prepare($testType);
		
		return $this->loadview(
			$testType.'Test.php',
			$test,
			false
		);
	}

	public function viewsentenceCompletionTest(){
		return $this->prepareTest('sentenceCompletion');
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
		return $this->prepareTest('englishWriting');
	}

	public function viewTurkishWritingTest(){
		return $this->prepareTest('turkishWriting');
	}

	public function viewsynonymSelectionTest(){
		return $this->prepareTest('synonymSelection');
	}

	public function viewcategorySelectionTest(){
		return $this->prepareTest('categorySelection');
	}

}	

?>
