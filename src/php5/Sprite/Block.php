<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents a block to be placed in a sprite map, with xy coordinates and a
* width and height. May contain a Sprite_Image offset within it.
* @package Sprite
*/
class Sprite_Block {
	/**
	* Image contained within this block
	* @var Sprite_Image
	*/
	public $image = null;
	/**
	* Whether this block is part of the current sprite map
	* @var bool
	*/
	public $inUse = false;

	/**
	* X coordinate within the sprite map
	* @var int
	*/
	public $x = 0;
	
	/**
	* Y coordinate within the sprite map
	* @var int
	*/
	public $y = 0;
	
	/**
	* Width of this block
	* @var int
	*/
	public $width = 0;
	
	/**
	* Height of this block
	* @var int
	*/
	public $height = 0;
	
	/**
	* X offset of the image within this block (if one exists)
	* @var int
	*/
	public $xOffset = 0;
	
	/**
	* Y offset of the image within this block (if one exists)
	* @var int
	*/
	public $yOffset = 0;
	
	/**
	* Whether this block is forced to be flush with the left edge in the sprite
	* map
	* @var bool
	*/
	public $flushLeft = false;
	
	/**
	* Whether this block is forced to be flush with the right edge in the sprite
	* map
	* @var bool
	*/
	public $flushRight = false;

	/**
	* The area of this block
	* @var int
	* @access private
	*/
	private $area = null;
	
	/**
	* Returns the area of this block
	* @return int
	*/
	public function area() {
		if ($this->area === null) {
			$this->area = $this->width * $this->height;
		}

		return $this->area;
	}
}
