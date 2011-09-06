<?php
class main 
{
	public static function serve($controller){
		/**
		 * queryString is removing. 
		 * */
		$controller=explode('?',$controller,2);
		$controller=$controller[0];
		
		if(preg_replace('/(\.)|(\/)|(\\\\)/i','',$controller)!==$controller){
			echo 'Zararlı parametre!';
			return false;
		}
		
		main::loadcontroller($controller);
		$className=$controller.'Controller';
		$n=new $className();
		echo $n->getOutput();
	}
	
	public static function loadcontroller($controller){
		require_once('controllers/'.$controller.'.php');
	}
}

main::serve($_GET['_controller']);
?>
