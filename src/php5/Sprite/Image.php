<?php
/**
* @package Sprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

/**
* Represents an image resource that may be sprited. Subclasses should be used
* to define images from specific locations. Implementations for file, URL, and
* String images have been provided.
* @package Sprite
* @subpackage Image
*/
abstract class SpriteImage {
	/**
	* The GD resource representing this image. May be null if the image hasn't
	* been loaded yet, or false if there was an error loading the image.
	* @var GD
	*/
	protected $image = null;

	/**
	* The width of the loaded image
	* @var int
	*/
	private $width = null;

	/**
	* The height of the loaded image
	* @var int
	*/
	private $height = null;

	/**
	* Retrieves the image data and creates the GD resource
	* @return bool Whether the loading was successful
	*/
	abstract public function load();

	/**
	* Retrieves the GD resource for the image, or false if the image is invalid
	* @return GD The gd resource for this image
	*/
	final public function getImage() {
		if ($this->image === null) {
			$this->load();
		}

		return $this->image;
	}

	/**
	* Retrieves the width of the image, or false if the image is invalid
	* @return int The image's width
	*/
	final public function getWidth() {
		if ($this->image === null) {
			$this->load();
		}

		if ($this->width === null) {
			if ($this->image === false) {
				$this->width = false;
			} else {
				$this->width = imagesx($this->image);
			}
		}

		return $this->width;
	}

	/**
	* Retrieves the height of the image, or false if the image is invalid
	* @return int The image's height
	*/
	final public function getHeight() {
		if ($this->image === null) {
			$this->load();
		}

		if ($this->height === null) {
			if ($this->image === false) {
				$this->height = false;
			} else {
				$this->height = imagesy($this->image);
			}
		}

		return $this->height;
	}

	/**
	* Destroys the image data, freeing memory
	*/
	final public function destroy() {
		if (!empty($this->image)) {
			imagedestroy($this->image);
		}
		$this->image = null;
		$this->width = null;
		$this->height = null;
	}
}
