<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.variationWritingTest{
	padding:10px;
	width:600px;
}
.variationWritingTest a{
	text-decoration:underline;
}
.variationWritingTest label{
	font-weight:normal;
	text-align:right;
	display:inline-block;
	width:225px;
	text-transform:capitalize;
}
.variationWritingTest input[type=submit]{
	margin-left:100px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		var test=new Test('variationWritingTest');

		test.bindItems=function(){
			
			$('.testPageOl li input[type=submit]').click(function(){
			
				var nonemptyInputs=$(this).parent().
					find('ul.variations li input[type=text][value!=""]');
				var allInputs=$(this).parent().
					find('ul.variations li input[type=text]');
				
				if($(nonemptyInputs).length>0){

					// Disable the input that is operated for
					$(this).attr('disabled',true);

					var wordId=$('input[name="wordId"]',
						$(this).parent()).val();

					var variations=[];
					var answers=[];
					
					$(allInputs).each(function(index){
						
						// Disable the input that is operated for
						$(this).attr('disabled','disabled');
						
						answers.push(encodeURI($(this).val()));
						variations.push(encodeURI(
							$('input[name="variation"]',
								$(this).parent()).val()
						));

					});

					var params={
						'wordId':wordId,
						'variations':variations,
						'answers':answers
					};
					
					test.checkAnswers(params);

				}

				return false;
			
			});

		}

		test.afterChecked=function(rsp){

			if(rsp!=null){

				var wordInput=$('input[value="'+rsp.wordId+'"]'),
					imgIncorrect='<img src="../images/incorrect.png" alt="" />',
					imgCorrect='<img src="../images/correct.png" alt="" />';

				for(var i in rsp.correction){
					var c=rsp.correction[i];
					
					var vryInput=$(
						'input[value="'+c[0]+'"]',
						wordInput.parent()
					);
					
					var answerInput=$('input[name="answer"]',
						vryInput.parent());
					
					// If the answer is correct
					if(answerInput.val()==c[1]){
						$(answerInput).addClass('correct');
						$(answerInput).parent().parent().append(imgCorrect);
					}
					// If the answer is incorrect
					else{
						$(answerInput).addClass('incorrect');
						$(answerInput).parent().parent().append(
							imgIncorrect+
							'<span>'+
								'<b>Doğrusu:</b>'+
								'<span>'+c[1]+'</span>'+
							'</span>'
						);
					}

				} // end of corrections


			}
		
		} // end of function afterCheck
		
		test.bindItems();
		
		test.startTimer();

	});

</script>

<div class="variationWritingTest">
	<div class="testPageHeader">
		<h1>Kelimenin Varyasyonlarını Yazma</h1>
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
	<ol class="testPageOl">
	<?php
	foreach($o->items as $item){
		$variations='';
		foreach($item->variations as $v){
			$variations.='<li>
				<label>'.$v.':
					<input name="variation" value="'.$v.'" 
						type="hidden" />
					<input type="text" name="answer" />
				</label>
			</li>';
		}
		$variations='<ul class="variations">'.$variations.'</ul>';
		
		echo '<li>
			<input name="wordId" value="'.$item->wordId.'" type="hidden" />
			<strong>'.$item->word.'</strong>
			'.$variations.'
			<input type="submit" value="Tamam" />
		</li>';
	}
	echo '</ol>';
	?>
</div>

