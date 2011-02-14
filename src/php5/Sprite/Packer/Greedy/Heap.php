<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents a greedy packing algorithm that keeps track of empty blocks of
* space and attempts to use them efficiently using a heap.
* @package Sprite
* @subpackage Packer
*/
class Sprite_Packer_Greedy_Heap implements Sprite_Packer {
	/**
	* {@inheritdoc}
	*/
	public function pack($blocks) {
		$width = 0;
		$height = 0;

		$left_blocks = array();
		$right_blocks = new Sprite_Util_Block_Heap_Height();
		$other_blocks = new Sprite_Util_Block_Heap_Area();
		
		$right_spaces = new Sprite_Util_Block_Heap_Height();
		$other_spaces = new Sprite_Util_Block_Heap_Area();

		foreach ($blocks as $block) {
			if ($block->width > $width) {
				$width = $block->width;
			}
		}

		foreach ($blocks as $block) {
			if ($block->flushRight && $block->flushLeft && $block->width != $width) {
				continue;
			} else if ($block->flushLeft) {
				$left_blocks->insert($block);
			} else if ($block->flushRight) {
				$right_blocks->insert($block);
			} else {
				$other_blocks->insert($block);
			}
			$block->inUse = true;
		}

		// Convert the block heaps to sorted arrays
		$right_blocks_arr = array();
		foreach ($right_blocks as $block) {
			$right_blocks_arr[] = $block;
		}

		$right_blocks = $right_blocks_arr;
		
		$other_blocks_arr = array();
		foreach ($other_blocks as $block) {
			$other_blocks_arr[] = $block;
		}

		$other_blocks = $other_blocks_arr;

		// Place all the left flush blocks, save the right space
		foreach ($left_blocks as $block) {
			$block->x = 0;
			$block->y = $height;

			if ($block->width < $width) {
				$space = new Sprite_Block();
				$space->x = $block->width;
				$space->y = $height;
				$space->width = $width - $block->width;
				$space->height = $block->height;

				$right_spaces->insert($space);
			}

			$height += $block->height;
		}

		// Place all the right flush blocks
		if (!empty($right_blocks)) {
			while (!$right_spaces->isEmpty() && !empty($right_blocks)) {
				$space = $right_spaces->extract();
				$best_i = null;
				
				foreach ($right_blocks as $i => $block) {
					if (self::fitsIn($block, $space)) {
						$best_i = $i;
					} else if ($block->height > $space->height) {
						break;
					}
				}

				if ($best_i === null) {
					$other_spaces->insert($space);
				} else {
					$block = $right_blocks[$best_i];
					unset($right_blocks[$best_i]);
					$block->x = $width - $block->width;
					$block->y = $space->y;
					
					$tb_delta = abs(($space->width - $block->width) * $block->height - $space->width * ($space->height - $block->height));
					$lr_delta = abs(($space->width - $block->width) * $space->height - $block->width * ($space->height - $block->height));
					
					if ($tb_delta > $lr_delta) {
						if ($space->width > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x;
							$new_space->y = $space->y;
							$new_space->width = $space->width - $block->width;
							$new_space->height = $block->height;
							$other_spaces->insert($new_space);
						}
						
						if ($space->height > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x;
							$new_space->y = $space->y + $block->height;
							$new_space->width = $space->width;
							$new_space->height = $space->height - $block->height;
							$right_spaces->insert($new_space);
						}
					} else {
						if ($space->width > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x;
							$new_space->y = $space->y;
							$new_space->width = $space->width - $block->width;
							$new_space->height = $space->height;
							$other_spaces->insert($new_space);
						}
						
						if ($space->height > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $block->x;
							$new_space->y = $space->y + $block->height;
							$new_space->width = $block->width;
							$new_space->height = $space->height - $block->height;
							$right_spaces->insert($new_space);
						}
					}
				}
			}

			foreach ($right_blocks as $block) {
				// We ran out of space
				$block->x = $width - $block->width;
				$block->y = $height;

				if ($width != $block->width) {
					$space = new Sprite_Block();
					$space->x = 0;
					$space->y = $height;
					$space->width = $width - $block->width;
					$space->height = $block->height;
					$other_spaces->insert($space);
				}
				
				$height += $block->height;
			}

			foreach ($right_spaces as $space) {
				$other_spaces->insert($space);
			}
		}

		// Place all the no flush blocks
		while (!empty($other_blocks)) {
			while (!$other_spaces->isEmpty() && !empty($other_blocks)) {
				$space = $other_spaces->extract();
				$best_i = null;
				
				foreach ($other_blocks as $i => $block) {
					if (self::fitsIn($block, $space)) {
						$best_i = $i;
					} else if ($block->area() > $space->area()) {
						break;
					}
				}

				if ($best_i !== null) {
					$block = $other_blocks[$best_i];
					unset($other_blocks[$best_i]);
					$block->x = $space->x;
					$block->y = $space->y;
					
					$tb_delta = abs(($space->width - $block->width) * $block->height - $space->width * ($space->height - $block->height));
					$lr_delta = abs(($space->width - $block->width) * $space->height - $block->width * ($space->height - $block->height));
					
					if ($tb_delta > $lr_delta) {
						if ($space->width > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x + $block->width;
							$new_space->y = $space->y;
							$new_space->width = $space->width - $block->width;
							$new_space->height = $block->height;
							$other_spaces->insert($new_space);
						}
						
						if ($space->height > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x;
							$new_space->y = $space->y + $block->height;
							$new_space->width = $space->width;
							$new_space->height = $space->height - $block->height;
							$other_spaces->insert($new_space);
						}
					} else {
						if ($space->width > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x;
							$new_space->y = $space->y + $block->height;
							$new_space->width = $block->width;
							$new_space->height = $space->height - $block->height;
							$other_spaces->insert($new_space);
						}
						
						if ($space->height > $block->width) {
							$new_space = new Sprite_Block();
							$new_space->x = $space->x + $block->width;
							$new_space->y = $space->y;
							$new_space->width = $space->width - $block->width;
							$new_space->height = $space->height;
							$other_spaces->insert($new_space);
						}
					}
				}
			}
			
			if (!empty($other_blocks)) {
				$block = array_pop($other_blocks);
				$block->x = 0;
				$block->y = $height;
				
				if ($width != $block->width) {
					$space = new Sprite_Block();
					$space->x = $block->width;
					$space->y = $height;
					$space->width = $width - $block->width;
					$space->height = $block->height;
					$other_spaces->insert($space);
				}
				
				$height += $block->height;
			}
		}

		return array($width, $height);
	}

	protected static function fitsIn(Sprite_Block $image, Sprite_Block $space) {
		return ($space->height >= $image->height) && ($space->width >= $image->width);
	}
}
