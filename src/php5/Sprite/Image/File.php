<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents an image from a file that may be sprited.
* @package Sprite
* @subpackage Image
*/
class SpriteImageFile extends SpriteImage {
	/**
	* Path to the image file
	* @var string
	* @access private
	*/
	private $path;

	/**
	* Creates a new SpriteImageFile object
	* @param string $path Path (relative or absolute) to the image file.
	*/
	public function __construct($path) {
		$this->path = $path;
	}

	/**
	* {@inheritdoc}
	*/
	public function load() {
		$data = file_get_contents($this->path);
		$this->image = imagecreatefromstring($data);
		
		return ($this->image !== false);
	}
	
	/**
	* Returns the path to this image as specified in the constructor
	* @return string Path to this image
	*/
	public function getPath() {
		return $this->path;
	}
}
