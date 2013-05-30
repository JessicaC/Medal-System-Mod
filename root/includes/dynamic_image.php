<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: dynamic_image.php,v 1.0.0 2009/10/29 Gremlinn$
* @copyright (c) 2009 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

// Dynamic Medal Image creation
function create_dynamic_image($baseimg, $extraimg='')
{
	$image  = create_from_extention($baseimg);

	imagecolortransparent($image,imagecolorat($image,0,0));

	if ( file_exists($extraimg) and $extraimg <> '' )
	{
		$insert = create_from_extention($extraimg);

		$image = image_overlap($image, $insert);
		ImageDestroy ($insert);
	}

	Header ('Content-type: image/png');
	ImagePNG ($image);
	//Clean Up
	ImageDestroy ($image);
}

function create_from_extention($image)
{
	$imageEx  = substr(strrchr($image, '.'), 1);

	switch ($imageEx)
	{
		case 'gif':
			return imagecreatefromgif($image);
		break;
		case 'jpg':
			return imagecreatefromjpeg($image);
		break;
		case 'png':
			return imagecreatefrompng($image);
		break;
		default:
			exit;
	}
}

function image_overlap($background, $foreground)
{
	$insertWidth = imagesx($foreground);
	$insertHeight = imagesy($foreground);

	$imageWidth = imagesx($background);
	$imageHeight = imagesy($background);

	$overlapX = $imageWidth/2 - $insertWidth/2;
	$overlapY = $imageHeight/2 - $insertHeight/2;
	imagecolortransparent($foreground,imagecolorat($foreground,0,0));
	imagecopymerge($background,$foreground,$overlapX,$overlapY,0,0,$insertWidth,$insertHeight,100);
	return $background;
}

?>