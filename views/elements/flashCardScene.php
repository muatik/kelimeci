<?php
$sceneId=rand(100,9999);
$words=$o;

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



<div class="scene" id="scene<?php echo $sceneId;?>">
	<input type="hidden" class="sceneId" value="<?php echo $sceneId;?>" />
	<h2 class="word"></h2>
	<div class="detail">
		
	</div>
</div>

