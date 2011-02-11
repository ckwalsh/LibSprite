<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* MinHeap for SpriteBlocks that sorts by width
* @package Sprite
* @subpackage Util
*/
class SpriteUtilBlockHeapWidth extends SplHeap {
	public function compare($a, $b) {
		return ($b->width - $a->width);
	}
}
