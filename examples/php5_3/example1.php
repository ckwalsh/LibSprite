<?php
/**
* @package Examples
* @copyright (c) 2011 Cullen Walsh
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
*/

require '../../src/php5_3/CKWalsh/LibSprite/Map.php';
require '../../src/php5_3/CKWalsh/LibSprite/Packer.php';
require '../../src/php5_3/CKWalsh/LibSprite/Packer/GreedyHeap.php';
require '../../src/php5_3/CKWalsh/LibSprite/Util/Block/Heap/Area.php';
require '../../src/php5_3/CKWalsh/LibSprite/Util/Block/Heap/Width.php';
require '../../src/php5_3/CKWalsh/LibSprite/Util/Block/Heap/Height.php';
require '../../src/php5_3/CKWalsh/LibSprite/Block.php';
require '../../src/php5_3/CKWalsh/LibSprite/Image.php';
require '../../src/php5_3/CKWalsh/LibSprite/Options.php';
require '../../src/php5_3/CKWalsh/LibSprite/Image/File.php';
require '../../src/php5_3/CKWalsh/LibSprite/Image/String.php';

use CKWalsh\LibSprite;

$packer = new LibSprite\Packer_GreedyHeap();
$map = new LibSprite\Map($packer);

$file_images = array();
$file_images[] = new LibSprite\Image_File('../common/adam.jpg');
$file_images[] = new LibSprite\Image_File('../common/lincoln.png');
$file_images[] = new LibSprite\Image_File('../common/mona_lisa.jpg');
$file_images[] = new LibSprite\Image_File('../common/red.png');

$str_images = array();
$str_images[] = new LibSprite\Image_String(file_get_contents('../common/adam.png'));
$str_images[] = new LibSprite\Image_String(file_get_contents('../common/lincoln.jpg'));
$str_images[] = new LibSprite\Image_String(file_get_contents('../common/mona_lisa.png'));
$str_images[] = new LibSprite\Image_String(file_get_contents('../common/red.jpg'));

foreach ($file_images as $i) {
	$map->addImage($i);
}

$options = new LibSprite\Options();
$options->setPadding(20);
foreach ($str_images as $i) {
	$map->addImage($i, $options);
}

imagepng($map->getImage(), 'example1.png');
file_put_contents('example1.css', $map->getCSS('./example1.png'));
