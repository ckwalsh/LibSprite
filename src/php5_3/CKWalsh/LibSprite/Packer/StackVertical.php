<?php
/**
* @package LibSprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace CKWalsh\LibSprite;

/**
* Represents a packing algorithm that stacks elements vertically
* @package LibSprite
* @subpackage Packer
*/
class Packer_StackVertical implements Packer {
	/**
	* {@inheritdoc}
	*/
	public function pack($blocks) {
		$width = 0;
		$height = 0;
		
		foreach ($blocks as $block) {
			if ($block->width > $width) {
				$width = $block->width;
			}
		}

		foreach ($blocks as $block) {
			if ($block->flushRight && $block->flushLeft && $block->width != $width) {
				continue;
			}

			if ($block->flushRight) {
				$block->x = $width - $block->width;
			} else {
				$block->x = 0;
			}
			
			$block->y = $height;
			$block->inUse = true;

			$height += $block->height;
		}

		return array($width, $height);
	}
}
