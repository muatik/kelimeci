<?php
require_once('ipage.php');
class testsController extends ipage{
	
	public function initialize(){
		$this->title='Testler';
		parent::initialize();
		
		if($this->isLogined){
			$this->tests=new \kelimeci\tests();
			$this->tests->userId=$this->u->id;
		}
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

		// If no words to test, show a notification
		if(count($test->items)==0){

			$o2=new stdClass();
			$o2->title='Duyuru';
			$o2->message='
				Testin uygulanabilmesi için, eklenen en az 1 kelimenizin
				üzerinden 8 saat geçmelidir.
			';
			$o2->hidable=false;
			echo $this->loadElement('notification.php',$o2);

		}
		else{
			
			return $this->loadview(
				$testType.'Test.php',
				$test,
				false
			);

		}
	}

	public function viewsentenceCompletionTest(){
		return $this->prepareTest('sentenceCompletion');
	}

	public function viewvariationWritingTest(){
		return $this->prepareTest('variationWriting');
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

	public function viewvoiceTest(){
		return $this->prepareTest('voice');
	}

}	

?>
