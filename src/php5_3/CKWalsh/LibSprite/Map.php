<?php
/**
* @package LibSprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace CKWalsh\LibSprite;

/**
* Represents a specific group of images that are sprited together.
* @package LibSprite
*/
class Map {
	/**
	* The images in this sprite, indexed by id
	* @var array
	*/
	private $images = array();
	
	/**
	* The options objects for each image in this sprite, indexed by id
	* @var array
	*/
	private $options = array();
	
	/**
	* The Packer responsible for placing the images in this sprite
	* @var Packer
	*/
	private $packer = null;
	
	/**
	* The Blocks in use in this sprite. Each contains an image from the
	* $images array and takes into account the padding specified in the options
	* @var array
	*/
	private $blocks = array();
	
	/**
	* The images unable to be used in this sprite. Indexed by id, contains an
	* array with the image object and the reason it is not being used.
	* @var array
	*/
	private $ignored = array();
	
	/**
	* The dimensions of the finished sprite
	* @var array(int)
	*/
	private $dims = array();
	
	/**
	* Whether the $blocks, $ignored, and $dims variables are up to date,
	* considering if images have been added and removed.
	* @var bool
	*/
	private $ready = false;

	/**
	* Construct a new Map object
	* @param Packer $packer The Packer object responsible for placing
	* images within this sprite
	*/
	public function __construct(Packer $packer) {
		$this->setPacker($packer);
	}

	/**
	* Sets a new packer to be used for placing images in this sprite
	* @param Packer $packer The new Packer object responsible for
	* placing images within this sprite
	*/
	public function setPacker(Packer $packer) {
		$this->packer = $packer;
		$this->ready = false;
	}
	
	/**
	* Add a new image to this sprite
	* @param Image $image The image to add
	* @param Options $options The options for this image, such as padding
	* and flush requirements
	* @param string $id A unique identifier for this image. If not provided, will
	* be filled with a semirandom 6 character hex string.
	* @return string the id used for this image
	*/
	public function addImage(Image $image, Options $options = null, $id = false) {
		if ($id === false) {
			$id = sprintf("%06x", mt_rand(0, 0xffffff));
		}

		$this->images[$id] = $image;
		$this->options[$id] = $options;
		$this->ready = false;

		return $id;
	}

	/**
	* Removes an image from this sprite
	* @param string $id ID of the image to remove
	*/
	public function removeImage($id) {
		unset($this->image[$id]);
		unset($this->options[$id]);
		$this->ready = false;
	}

	/**
	* Returns an array of the blocks used in this mapping, indexed by id. This may
	* be used to construct your own CSS and identify which images are really
	* contained in this sprite.
	* @return array(Block) Blocks in the mapping
	*/
	public function getBlocks() {
		if (!$this->ready) {
			$this->generate();
		}

		return $this->blocks;
	}

	/**
	* Returns an array of the images added to the map but not placed in the
	* resulting sprite, indexed by id. This may occur when images cannot be loaded
	* or conflicting options prevented the image from being placed.
	* @return array(Image) Images not used in the mapping
	*/
	public function getIgnoredImages() {
		if (!$this->ready) {
			$this->generate();
		}

		return $this->ignored;
	}
	
	/**
	* Gets the dimensions of the resulting sprite
	* @return array Dimensions, where index 0 is the width and 1 is the height
	*/
	public function getDims() {
		if (!$this->ready) {
			$this->generate();
		}

		return $this->dims;
	}
	
	/**
	* Gets the GD image resource of the sprite with all the individual images
	* placed in it. Must be freed explicitly by the user of this method via
	* imagedestroy()
	* @return GD image of the sprite
	*/
	public function getImage() {
		if (!$this->ready) {
			$this->generate();
		}

		$img = null;
		if (!empty($this->dims) && $this->dims[0] > 0 && $this->dims[1] > 0) {
			$img = imagecreatetruecolor($this->dims[0], $this->dims[1]);
			$transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
			imagefill($img, 0, 0, $transparent);
			
			foreach ($this->blocks as $block) {
				$x = $block->x + $block->xOffset;
				$y = $block->y + $block->yOffset;
				$width = $block->image->getWidth();
				$height = $block->image->getHeight();
				imagecopy($img, $block->image->getImage(), $x, $y, 0, 0, $width, $height);
			}
		}

		return $img;
	}

	public function getCSS($path, $namespace = 'spr') {
		if (!$this->ready) {
			$this->generate();
		}

		$css = '.' . $namespace . '{background:transparent url(' . $path . ") no-repeat}\n";

		foreach ($this->blocks as $id => $block) {
			$css .= '.' . $namespace . '.' . $namespace . '_' . $id . '{background-position:';
			$x = $block->x + $block->xOffset;
			$y = $block->y + $block->yOffset;
			
			if ($x == 0) {
				$css .= '0 ';
			} else {
				$css .= -$x . 'px ';
			}
			
			if ($y == 0) {
				$css .= '0';
			} else {
				$css .= -$y . 'px';
			}

			$css .= "}\n";
		}

		return $css;
		
	}

	/**
	* Processes the images and places them in the mapping. This is called
	* implicitly when methods are used that require the sprite mapping to be
	* available.
	*/
	public function generate() {
		$this->blocks = array();
		$this->ignored = array();

		$blocks = array();
		foreach ($this->images as $id => $image) {
			// Only use the images that loaded correctly
			if ($image->getImage() === false) {
				$this->ignored[$id] = array("image" => $image, "reason" => "Unable to Load");
			} else {
				$options = $this->options[$id];
				if ($options === null) {
					$options = new Options();
				}

				$block = new Block();
				$block->image = $image;
				$block->xOffset = $options->paddingLeft;
				$block->yOffset = $options->paddingTop;
				$block->width = $image->getWidth() + $options->paddingLeft + $options->paddingRight;
				$block->height = $image->getHeight() + $options->paddingTop + $options->paddingBottom;
				$block->flushLeft = $options->flushLeft;
				$block->flushRight = $options->flushRight;
				
				$blocks[$id] = $block;
			}
		}

		$this->dims = $this->packer->pack($blocks);

		foreach ($blocks as $id => $block) {
			if ($block->inUse) {
				$this->blocks[$id] = $block;
			} else {
				$this->ignored[$id] = array("image" => $block->image, "reason" => "Unable to Pack");
			}
		}
	}
}
