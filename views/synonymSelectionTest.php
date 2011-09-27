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
			
				var selectedItems=$(this).parent().find('span.synonyms span.selected');
				
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

				if(rsp.result){
					var resultSpan=$(
						'input[class="wordId"][value="'+rsp.wordId+'"]'  
					).parent().find('span.synonyms span.selected');

					$(resultSpan).each(function(){
						$(this).addClass('correct');
					});
				}
				else{
					var resultSpan=$(
						'input[class="wordId"][value="'+rsp.wordId+'"]'  
					).parent().find('span.synonyms span');
					
					$(resultSpan).each(function(index){

						// If the synonym in the answer
						if($.inArray($(this).html(),rsp.correction)!=-1){
							// If the synonym not selected
							if(!$(this).hasClass('selected')){
								$(this).addClass('incorrect');
							}
							// If the synonym selected
							else{
								$(this).addClass('correct');
							}	

						}
						// If the synonym not in the answer
						else{
							// If the synonym selected
							if($(this).hasClass('selected')){
								$(this).addClass('incorrect');
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

<div class="synonymSelectionTest">
	<div class="testPageHeader">
		<h1>Eş Anlamlıları Seçme Testi</h1>
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
	<?php
	echo '<ol class="testPageOl">';
	foreach($o->items as $item){
		$synonyms='';
		foreach($item->options as $s){
			$synonyms.='<span>'.$s.'</span>, ';
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
