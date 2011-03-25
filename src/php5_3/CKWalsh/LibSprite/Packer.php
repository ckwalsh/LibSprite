<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace CKWalsh\LibSprite;

/**
* Represents a specific packing algorithm
* @package Sprite
* @subpackage Packer
*/
interface Packer {
	/**
	* Pack the provided blocks into a sprite. Should modify the block objects to
	* specify their position and whether they are actually being used in the
	* resulting layout, but should not interfere with their size and should
	* respect any flush options on them.
	* @param array(Block) the blocks this packer should place
	* @return array the dimensions of the resulting layout
	*/
	public function pack($blocks);
}
