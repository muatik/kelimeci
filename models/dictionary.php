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
	 * gives classes(verb, noun, adjective, adverb, preposition, other) 
	 * of an word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array word classes
	 */
	public static function getClassesOfWord($wordId){
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
