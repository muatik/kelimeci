<?php

/**
 * the class words represents a word with it's all details
 * 
 * @date 08 Sep 2011 08:18
 * @copyright copyleft
 * @author Mustafa Atik <muatik@gmail.com>
 */
class words
{
	/**
	 * id of the word
	 * 
	 * @var string
	 * @access public
	 */
	public $id;
	
	/**
	 * word
	 * 
	 * @var string
	 * @access public
	 */
	public $word;

	/**
	 * language of the word
	 * 
	 * @var string
	 * @access private
	 */
	private $lang;

	/**
	 * meaingings of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $meainging;

	/**
	 * classes of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $classes;

	/**
	 * quotes of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $quotes;

	/**
	 * synonyms of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $synonyms;

	/**
	 * antonyms of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $antonyms;


	

	/**
	 * __construct starts to represent a word if the word argument is passed
	 * 
	 * @param mixed $word id or string
	 * @access public
	 * @return void
	 */
	public function __construct($word=null){
		
	}
	
	/**
	 * binds to a word
	 * 
	 * @param mixed $word if or string
	 * @access public
	 * @return bool
	 */
	public function bind($word){
	}


	/**
	 * the magic method __get 
	 * 
	 * @param string $var 
	 * @access private
	 * @return void
	 */
	private function __get($var){

	}
}

?>
