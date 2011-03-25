<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace CKWalsh\LibSprite;

/**
* MinHeap for Blocks that sorts by area
* @package Sprite
* @subpackage Util
*/
class Util_Block_Heap_Area extends SplHeap {
	public function compare($a, $b) {
		return ($b->area() - $a->area());
	}
}
