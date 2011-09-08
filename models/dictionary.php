<?php

/**
 * This dictionary class presents general lexical operations.
 *
 * @copyright copyleft
 * @author Mustafa Atik<muatik@gmail.com> 
 */
class dictionary
{

	/**
	 * returns random word IDs 
	 * 
	 * @param int $length how many words will be returned
	 * @static
	 * @access public
	 * @return array words Ids
	 */
	public static function getRandowmWords($length=10){
	}


	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param mixed $word class words instance or word id
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflections($word){
		
	}
	
	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param mixed $word class words instance or word id
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflectionsOfVerb($word){
	}
	
	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param mixed $word class words instance or word id
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflectionsOfNoun($word){
	}
	
	/**
	 * returns classes(verb, noun, adjective, adverb, preposition, other) 
	 * of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array word classes
	 */
	public static function getClassesOfWord($wordId){
	}
	
	/**
	 * returns language of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function getLangOfWord($wordId){
	}
	
	/**
	 * returns meainings of word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getMeaningsOfWord($wordId){
	}

	/**
	 * returns quotes of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getQuotesOfWord($wordId){
	}

	/**
	 * returns synonyms of a word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getSynonymsOfWord($wordId){
	}

	/**
	 * returns antonyms of a word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getAntonymsOfWord($wordId){
	}

	/**
	 * return the word object which is corresponded to wordId  
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return word object
	 */
	public static function getWord($wordId){
		
	}

}

?>
