<?php

/**
 * the class tests prepares and evaluates tests
 * 
 * @copyright copyleft
 * @author Mustafa Atik <muatik@gmail.com>
 * @date 07 Sep 2011 01:01
 */
class tests
{



	/**
	 * pepare a test for a user
	 * 
	 * @param int $userId
	 * @param string $testType 
	 * @static
	 * @access public
	 * @return object contains test items and other details
	 */
	public static function pepare($userId, $testType){
	}
	
	
	/**
	 * returns words which are ready to be tested.
	 *
	 * @param string $testType 
	 * @static
	 * @access public
	 * @return array words
	 */
	public static function getWordsForTest($testType){
	}





	### BEGIN OF TEST DATA METHODS ###

	/**
	 * returns data of a word which is required to prepare a test
	 * In other words, returns item of question of a word
	 * 
	 * @param mixed $testType 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getTestData($testType,$word){
	}

	/**
	 * returns data of a word whic is require to peare a sentence 
	 * completion test 
	 *
	 * @param words $word 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getItemOfSentComp($word){
	}


	### END OF TEST DATA METHODS ###
	



	
	### BEGIN OF VERIFICATION METHODS ###


	/**
	 * verify a test answer that's represented by a result object
	 * 
	 * @param object $result 
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function verify($result){
	}

	/**
	 * verify a sentence completion test answer that's represented 
	 * by a result object
	 * 
	 * @param object $result 
	 * @access public
	 * @return bool
	 */
	public function verifyInSentComp($result){
	}


	### END OF VERIFICATION METHODS ###


}

?>
