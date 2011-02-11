<?php
/**
* @package LibSprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents a set of options that should be respected when the associated
* image is placed in a map
* @package LibSprite
*/
class SpriteOptions {
	/**
	* Whitespace padding along the top border of the image. Defaults to 0
	* @var int
	*/
	public $paddingTop = 0;
	
	/**
	* Whitespace padding along the right border of the image. Defaults to 1
	* @var int
	*/
	public $paddingRight = 1;
	
	/**
	* Whitespace padding along the bottom border of the image. Defaults to 1
	* @var int
	*/
	public $paddingBottom = 1;
	
	/**
	* Whitespace padding along the left border of the image. Defaults to 0
	* @var int
	*/
	public $paddingLeft = 0;
	
	/**
	* Whether this image should be flush with the left edge of the sprite. The
	* left padding value is still respected.
	* @var bool
	*/
	public $flushLeft = false;
	
	/**
	* Whether this image should be flush with the right edge of the sprite. The
	* right padding value is still respected.
	* @var bool
	*/
	public $flushRight = false;

	/**
	* Convenience function for setting the padding values at once. Behaves the
	* same as the css padding attribute, where only one parameter is required and
	* rest may be implied from the previous values.
	* @param int $top Padding above the image
	* @param int $right Padding right of the image
	* @param int $bottom Padding below the image
	* @param int $left Padding left of the image
	*/
	public function setPadding($top, $right = null, $bottom = null, $left = null) {
		$this->paddingTop = $top;
		
		if ($right === null) {
			$right = $top;
		}
		$this->paddingRight = $right;
		
		if ($bottom === null) {
			$bottom = $top;
		}
		$this->paddingBottom = $bottom;
		
		if ($left === null) {
			$left = $right;
		}
		$this->paddingLeft = $left;
	}
}
