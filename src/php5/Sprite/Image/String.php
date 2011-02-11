<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents a resource stored in a binary string
* @package Sprite
* @subpackage Image
*/
class Sprite_Image_String extends Sprite_Image {
	/**
	* The string containing image data
	* @var string
	* @access private
	*/
	private $string;
	
	/**
	* Creates a new Sprite_Image_String object
	* @param string $data Binary data representing the image
	*/
	public function __construct($data) {
		$this->string = $data;
	}

	/**
	* {@inheritdoc}
	*/
	public function load() {
		$this->image = imagecreatefromstring($this->string);
		
		return ($this->image !== false);
	}
	
	/**
	* Returns the binary string initially passed to this object
	* @return string Binary data representing an image
	*/
	public function getString() {
		return $this->string;
	}
}
