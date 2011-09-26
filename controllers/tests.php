<?php
require_once('ipage.php');
class testsController extends ipage{
	
	public function initialize(){
		parent::initialize();
	}
	
	public function run(){
		parent::run();
	}
	
	public function validate(){
		$r=$this->r;

		if(!isset($r['testName'],$r['wordId']) 
			|| !is_numeric($r['wordId']))
			return false;

		$tt=$r['testName'];
		
		
		if($tt=='sentenceCompletionTest' 
			&& isset($r['quoteId']) && is_numeric($r['quoteId'])){
		
			if($r['wordId']==1){
				if($r['answer']=='ever')
					return '{"wordId":1,"result":true}';
				else
					return '{"wordId":1,"result":false,"answer":"ever","correction":"good"}';
			}
			elseif($r['wordId']==2){
				if($r['answer']=='car')
					return '{"wordId":2,"result":true}';
				else
					return '{"wordId":2,"result":false,"answer":"car","correction":"bad"}';
			}
		}
		elseif($tt=='variationWritingTest'
			&& isset($r['answers'],$r['variations'])
			&& is_array($r['answers'])
			&& is_array($r['variations'])){	
		
			if($r['wordId']==1){
				if(implode(',',$r['variations'])=='noun,verb,adjective'
					&& implode(',',$r['answers'])=='access,access,accessible')
					return '{"wordId":1,"result":true}';
				else
					return '{"wordId":1,"result":false,
						"correction":[
							["noun","access"],
							["verb","access"],
							["adjective","accessible"]
						]}';
			}
			if($r['wordId']==2){
				if(implode(',',$r['variations'])=='noun,verb,adjective'
					&& implode(',',$r['answers'])=='meaning,mean,meaningfull')
					return '{"wordId":2,"result":true}';
				else
					return '{"wordId":2,"result":false,
						"correction":[
							["noun","meaning"],
							["verb","mean"],
							["adjective","meaningfull"]
						]}';
			}
		}
		elseif($tt=='categorySelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){	
			
				if($r['wordId']==51){
				if(implode(',',$r['selected'])=='adjective')
					return '{"wordId":51,"result":true}';
				else
					return '{"wordId":51,"result":false,
					"correction":["adjective"]}';
				}
				elseif($r['wordId']==86){
				if(implode(',',$r['selected'])=='verb')
					return '{"wordId":86,"result":true}';
				else
					return '{"wordId":86,"result":false,
					"correction":["verb"]}';
				}

		}
		elseif($tt=='synonymSelectionTest'
			&& isset($r['wordId'],$r['selected'])
			&& is_array($r['selected'])){	
				
				if($r['wordId']==42){
				if(implode(',',$r['selected'])=='excellent,elegant')
					return '{"wordId":42,"result":true}';
				else
					return '{"wordId":42,"result":false,
					"correction":["excellent","elegant"]}';
				}
				elseif($r['wordId']==62){
				if(implode(',',$r['selected'])=='nefarious')
					return '{"wordId":62,"result":true}';
				else
					return '{"wordId":62,"result":false,
					"correction":["nefarious"]}';
				}
		}
		elseif($tt=='englishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				if($r['wordId']==4){
				if($r['answer']=='perfect')
					return '{"wordId":4,"result":true}';
				else
					$h='{"wordId":4,"result":false,
					"answer":"perfect"';
					if($r['answer']=='car')
					$h.=',"correction":"araba"';
					$h.='}';
					return $h;
				}
				elseif($r['wordId']==7){
				if($r['answer']=='fast')
					return '{"wordId":7,"result":true}';
				else
					$h='{"wordId":7,"result":false,
					"answer":"fast"';
					if($r['answer']=='car')
					$h.=',"correction":"araba"';
					$h.='}';
					return $h;
				}
		}
		elseif($tt=='turkishWritingTest'
			&& isset($r['wordId'],$r['answer'])){	
				
				if($r['wordId']==22){
				if($r['answer']=='mükemmel')
					return '{"wordId":22,"result":true}';
				else
					$h='{"wordId":22,"result":false,
					"answer":"mükemmel"';
					if($r['answer']=='araba')
					$h.=',"correction":"car"';
					$h.='}';
					return $h;
				}
				elseif($r['wordId']==14){
				if($r['answer']=='sürat')
					return '{"wordId":14,"result":true}';
				else
					$h='{"wordId":14,"result":false,
					"answer":"sürat"';
					if($r['answer']=='araba')
					$h.=',"correction":"car"';
					$h.='}';
					return $h;
				}
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
