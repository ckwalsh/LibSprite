<?php
/**
* @package LibSprite
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

namespace CKWalsh\LibSprite;

/**
* Represents a resource located on a remote http server
* @package LibSprite
* @subpackage Image
*/
class Image_Url extends Image {
	/**
	* The url passed to this object
	* @var string
	* @access private
	*/
	private $url;

	/**
	* Creates a new Image_Url object
	* @param string $url URL of the image on a remote server
	*/
	public function __construct($url) {
		$this->url = $url;
	}

	/**
	* {@inheritdoc}
	*/
	public function load() {
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$data = curl_exec($ch);
		curl_close($ch);

		$this->image = imagecreatefromstring($data);

		return ($this->image !== false);
	}

	/**
	* Returns the URL initially passed to this object
	* @return string Url for this resource
	*/
	public function getURL() {
		return $this->url;
	}
}
