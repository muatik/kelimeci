<?php
require_once('db.php');

function getQuotesByWord($word){

	$pageNumber=1;
	$quotes=array();

	do{
		$url='http://quotes.dictionary.com/search/'
			.urlencode($word).'?page='.$pageNumber;
		
		$content=file_get_contents($url);
		$qpatt='/<span class="gtxt" id="fulltext\d+">([\w\s\S\n\t .,;!?:_\'"\/\-]*?)<span class="qc">/ium';
		$aupatt='/<a href=".*?" class="au">([\w\s\S\-. ,;]*?)<\/a>/ium';

		preg_match_all($aupatt,$content,$aumatches);
		preg_match_all($qpatt,$content,$matches);
		foreach($matches[1] as $k=>$i)
			if(isset($aumatches[1][$k]))
				$matches[1][$k]=$i.' -- '.$aumatches[1][$k];

		
		$oldCount=count($quotes);
		$quotes=array_unique(array_merge($quotes,$matches[1]));
		echo "\n\n--------".$pageNumber."-".$oldCount."-".count($quotes)."-------\n";

		
		$pageNumber++;

	}while(count($quotes)!=$oldCount);

	foreach($quotes as $k=>$i)
		$quotes[$k]=strip_tags($i);
	
	return $quotes;
}

function getQuotesForAllWords(){

	$db=new db();

	while(1){
		
		$word=$db->fetchFirst('select * from words where status=2 limit 1');

		if($word==false){
			sleep(1);
			continue;
		}
		
		$db->query('update words set status=3 where id='.$word->id.' limit 1');

		$quotes=getQuotesByWord($word->word);
		foreach($quotes as $k=>$i)
			$quotes[$k]=$db->escape($i);

		$sql='insert into quotesTemp (wId,quote) values
			('.$word->id.',\''
			.implode('\'),('.$word->id.',\'',$quotes).'\')';

		$db->query($sql);
	}
}

getQuotesForAllWords();
?>
