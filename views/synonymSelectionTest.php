<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.synonymSelectionTest{
	padding:10px;
	width:600px;
}
.synonymSelectionTest a{
	text-decoration:underline;
}
.synonymSelectionTest span.synonyms span{
	margin-left:5px;
	cursor:pointer;
	padding:3px;
}
.synonymSelectionTest span.synonyms span.selected{
	background:#E5C532;
	border:1px solid #C8AE32;
	padding:2px;
}
.synonymSelectionTest span.synonyms span.correct{
	background:#8AEB6D;
	border:1px solid green;
	padding:2px;
}
.synonymSelectionTest span.synonyms span.incorrect{
	background:#FF8787;
	border:1px solid red;
	padding:2px;
}
.synonymSelectionTest p.correction span{
	padding-right:15px;
	border-right:1px solid black;
}
.synonymSelectionTest p.correction strong:first-child{
	margin-left:0;
}
</style>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		var test=new Test('synonymSelectionTest');

		test.bindItems=function(){
			$('.testPageOl li span.synonyms span').click(function(){

				// If not selected
				if(!$(this).hasClass('selected'))
					$(this).addClass('selected');
				// If selected
				else
					$(this).removeClass('selected');
				

			});

			$('.testPageOl li input[type=submit]').click(function(){
			
				var selectedItems=$(this).parent().find('span.synonyms span.selected');
				
				if($(selectedItems).length>0){

					// Disable the input that is operated for
					$(this).attr('disabled',true);

					var params={
						itemId:$(this).parent().attr('itemId')
					};
					
					test.checkAnswers(params);

				}

				return false;
			
			});

		}

		test.afterChecked=function(rsp){
	
			rsp=eval('('+rsp+')');	

			if(rsp!=''){

				var resultSpan=$(
					'.testPageOl li[itemId='+rsp.itemId+']  span.synonyms span'
				);
				
				var hasIncorrects=false,correctCounter=0;
				$(resultSpan).each(function(index){
				
					// If the synonym in the answer
					if($.inArray($(this).html(),rsp.answer)!=-1){
						// If the synonym not selected
						if(!$(this).hasClass('selected')){
							$(this).addClass('incorrect');
							hasIncorrects=true;
						}
						// If the synonym selected
						else{
							$(this).addClass('correct');
							correctCounter++;
						}	

					}
					// If the synonym not in the answer
					else{
						// If the synonym selected
						if($(this).hasClass('selected')){
							$(this).addClass('incorrect');
							hasIncorrects=true;
						}
					}		
				
				});
				
				// If has not incorrect and the correct count is true
				if(!hasIncorrects && correctCounter==rsp.answer.length){
					test.incrementCorrectCounter();
					$('.testPageHeader .correctAnswers').html(test.correctAnswerCounter);
				}
				// If has any incorrect
				else if(hasIncorrects){
					test.incrementIncorrectCounter();
					$('.testPageHeader .incorrectAnswers').html(test.incorrectAnswerCounter);
				}


			}

		}
		
		test.bindItems();

		test.startTimer();

	});

</script>

<div class="synonymSelectionTest">
	<?php
	require('../dummyData/synonymSelectionTest.php');

	echo '<div class="testPageHeader">
		<h1>Eş Anlamlıları Seçme Testi</h1>
		<p>
			Toplam soru:<span class="totalQuestions">'.count($o->items).'</span>,
			Tahmini süre:<span class="estimatedTime">'.$o->estimatedTime.'</span>,
		</p>
		<p>
			Geçen süre:<span class="spentTime">00:00:00</span>,
			Doğru sayısı:<span class="correctAnswers">0</span>,
			Yanlış sayısı:<span class="incorrectAnswers">0</span>,
			Boş:<span class="emptyQuestions">0</span>
		</p>
	</div>
	<ol class="testPageOl">';
	foreach($o->items as $item){
		$synonyms='';
		foreach($item->options as $s){
			$synonyms.='<span>'.$s.'</span>,';
		}
		$synonyms=substr($synonyms,0,strlen($synonyms)-1);
		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			<p>
				<strong>'.$item->word.'</strong>
				<span>=</span>
				<span class="synonyms">'.$synonyms.'</span>
			</p>
			<input type="submit" value="Tamam" />
		</li>';
		
	}
	echo '</ol>';
	?>
</div>
