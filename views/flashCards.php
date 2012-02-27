<?php
$words=array('car','white','truck','fine','occasion','appropriate','right','god');

$sceneId=rand(100,9999);

foreach($words as $k=>$i){
	$tw=new stdClass();
	$tw->word=$i;
	$tw->status=false;
	$tw->detail=null;
	$words[$k]=$tw;
}

?>

<script type="text/javascript" src="js/flashCards.js"></script>
<script type="text/javascript">

$(document).ready(function(){

<?php
echo 'var words='.json_encode($words).';';

?>


fCards<?php echo $sceneId;?>=new flashCards(words);
fCards<?php echo $sceneId;?>.id=<?php echo $sceneId;?>;

});

</script>

<link rel="stylesheet" type="text/css" href="css/clsBoxes.css" />
<link rel="stylesheet" type="text/css" href="css/flashCards.css" />

<div id="flashCards">

<h1>flashcards</h1>

<form class="sceneForm">
	<label>Kelimeler: <select><option>Irregular verbs</option></select></label>
	<label>Hız: <div class="speedSlider"></div>
		<span class="speed">8sn</span></label>
	<label><input type="checkbox" class="autospeak" 
		checked="checked" /> Seslendir</label>
	<span class="helpLink">?</span>
</form>

<div class="scene" id="scene<?php echo $sceneId;?>">
	<input type="hidden" class="sceneId" value="<?php echo $sceneId;?>" />
	<h2 class="word"></h2>
	<div class="detail">
		
	</div>
</div>

</div>
