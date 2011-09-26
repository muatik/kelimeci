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
	text-transform:capitalize;
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

		var test=new Test('categorySelectionTest');

		test.bindItems=function(){
			$('.testPageOl li ul.categories li span').click(function(){
				// If the question answered
				if($(this).parent().parent().parent().
					find('input[type="submit"]').attr('disabled'))
						return;
				// If not selected
				if(!$(this).hasClass('selected'))
					$(this).addClass('selected');
				// If selected
				else
					$(this).removeClass('selected');
				

			});

			$('.testPageOl li input[type=submit]').click(function(){
			
				var selectedItems=$(this).parent()
					.find('ul.categories li span.selected');
				
				if($(selectedItems).length>0){

					// Disable the input that is operated for
					$(this).attr('disabled',true);
					
					var selected2=new Array();

					$(selectedItems).each(function(){
						selected2.push($(this).html());
					});

					var params={
						"wordId":$(this).parent().find('.wordId').val(),
						"selected":selected2
					};
					
					test.checkAnswers(params);

				}

				return false;
			
			});

		}

		test.afterChecked=function(rsp){
	
			if(rsp!=null){

				var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
					imgCorrect='<img src="../images/correct.png" alt="" />';

				if(rsp.result){
					var resultSpan=$(
						'input[class="wordId"][value="'+rsp.wordId+'"]'  
					).parent().find('ul.categories li span.selected');

					$(resultSpan).each(function(){
						$(this).addClass('correct');
					});
				}
				else{
					var resultSpan=$(
						'input[class="wordId"][value="'+rsp.wordId+'"]'  
					).parent().find('ul.categories li span');
					
					$(resultSpan).each(function(index){

						// If the synonym in the answer
						if($.inArray($(this).html(),rsp.correction)!=-1){
							// If the synonym not selected
							if(!$(this).hasClass('selected')){
								$(this).addClass('unselectedCorrect');
								$(this).parent().append(imgIncorrect);
							}
							// If the synonym selected
							else{
								$(this).addClass('correct');
								$(this).parent().append(imgCorrect);
							}	

						}
						// If the synonym not in the answer
						else{
							// If the synonym selected
							if($(this).hasClass('selected')){
								$(this).addClass('incorrect');
								$(this).parent().append(imgIncorrect);
							}
						}		
					
					});
				}
				
			}

		}
		
		test.bindItems();

		test.startTimer();

	});

</script>

<div class="categorySelectionTest">
	<div class="testPageHeader">
		<h1>Kategori Seçme Testi</h1>
		<p>
			Toplam soru:<span class="totalQuestions">
				<?php echo count($o->items);?></span>,
			Tahmini süre:<span class="estimatedTime">
				<?php echo $o->estimatedTime;?></span>,
		</p>
		<p>
			Geçen süre:<span class="spentTime">00:00:00</span>,
			Doğru sayısı:<span class="correctAnswers">0</span>,
			Yanlış sayısı:<span class="incorrectAnswers">0</span>,
			Boş:<span class="emptyQuestions">0</span>
		</p>
	</div>
	<?
	echo '<ol class="testPageOl">';
	$categoriesArr=array('verb','noun','adjective','adverb','preposition');
	$categories='';
	foreach($categoriesArr as $c){
			$categories.='<li><span>'.$c.'</span></li>';
	}
	$categories='<ul class="categories">'.$categories.'</ul>';
	foreach($o->items as $item){
		echo '<li>
				<input class="wordId" type="hidden" value="'.$item->wordId.'" /> 
				<strong>'.$item->word.'</strong>
				'.$categories.'
				<input type="submit" value="Tamam" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>
