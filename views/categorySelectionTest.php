<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.categorySelectionTest{
	padding:10px;
	width:600px;
}
.categorySelectionTest a{
	text-decoration:underline;
}
.categorySelectionTest ul.categories li span{
	margin-left:5px;
	cursor:pointer;
	display:inline-block;
	width:100px;
	border:1px solid white;
	padding:2px;
}
.categorySelectionTest ul.categories li span.selected{
	background:#E5C532;
	border:1px solid #C8AE32;
	padding:2px;
}
.categorySelectionTest ul.categories li span.correct{
	color:green;
}
.categorySelectionTest ul.categories li span.incorrect{
	color:red;
	text-decoration:line-through;
}
.categorySelectionTest ul.categories li span.unselectedCorrect{
	background:green;/*#8AEB6D;*/
	border:1px solid darkgreen;
	padding:2px;
	color:white;
}
.categorySelectionTest li input[type=submit]{
	margin-left:20px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		// 'categorySelectionTest' replaced to the test name that
		// comes from the server
		var test=new Test('categorySelectionTest');

		test.showTime=function(){
			$('.spentTime').html(test.elapsedTime);	
		}

		test.bindItems=function(){
			$('.testPageOl ul.categories li span').click(function(){

				// If not selected
				if(!$(this).hasClass('selected'))
					$(this).addClass('selected');
				// If selected
				else
					$(this).removeClass('selected');
				

			});

			$('.testPageOl li input[type=submit]').click(function(){
			
				var selectedItems=$(this).parent().find('ul.categories li span.selected');
				
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
					'.testPageOl li[itemId='+rsp.itemId+']  ul.categories li span'
				);
				
				var hasIncorrects=false,
					correctCounter=0,
					imgIncorrect='<img src="../images/incorrect.png" alt="" />';
					imgCorrect='<img src="../images/correct.png" alt="" />';
				$(resultSpan).each(function(index){
				
					// If the category in the answer
					if($.inArray($(this).html().toLowerCase(),rsp.answer)!=-1){
						
						// If the category not selected
						if(!$(this).hasClass('selected')){
							$(this).addClass('unselectedCorrect');
							$(this).parent().append(imgIncorrect);
							hasIncorrects=true;
						}
						// If the category selected
						else{
							$(this).addClass('correct');
							$(this).parent().append(imgCorrect);
							correctCounter++;
						}	

					}
					// If the category not in the answer
					else{
						// If the category selected
						if($(this).hasClass('selected')){
							$(this).addClass('incorrect');
							$(this).parent().append(imgIncorrect);
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
		
		test.startTimer();

		// DELETE THIS LINE
		test.ajaxFile='../dummyData/categorySelectionTest.php';

		test.bindItems();

	});

</script>

<div class="categorySelectionTest">
	<?php
	require('../dummyData/categorySelectionTest.php');

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
	$categoriesArr=array('Verb','Noun','Adjective','Adverb','Preposition');
	$categories='';
	foreach($categoriesArr as $c){
			$categories.='<li><span>'.$c.'</span></li>';
	}
	$categories='<ul class="categories">'.$categories.'</ul>';
	foreach($o->items as $item){
		echo '<li itemId="'.$item['id'].'">
				<strong>'.$item['word'].'</strong>
				'.$categories.'
			<input type="submit" value="Tamam" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>
