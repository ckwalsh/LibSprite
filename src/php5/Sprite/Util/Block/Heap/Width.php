<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* MinHeap for Sprite_Blocks that sorts by width
* @package Sprite
* @subpackage Util
*/
class Sprite_Util_Block_Heap_Width extends SplHeap {
	public function compare($a, $b) {
		return ($b->width - $a->width);
	}
}
