<?php

function gdResizeThumbnail( $thumb_filename, $src_image, $src_x, $src_y, $src_width, $src_height, $dest_width, $dest_height, $jpeg_quality ) {
	list( $src_image_width, $src_image_height, $src_image_type ) = getimagesize( $src_image );
	$src_image_type = image_type_to_mime_type( $src_image_type );
	$dest_img = imagecreatetruecolor( $dest_width, $dest_height );

	switch( $src_image_type ) {
		case "image/gif":
			$src = imagecreatefromgif($src_image);
			$thumb_filename .= '.gif';
			fastimagecopyresampled( $dest_img, $src, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height );
			imagegif( $dest_img, $thumb_filename );
			break;
		case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$src = imagecreatefromjpeg($src_image);
			$thumb_filename .= '.jpg';
			fastimagecopyresampled( $dest_img, $src, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height );
			imagejpeg( $dest_img, $thumb_filename, $jpeg_quality );
			break;
		case "image/png":
		case "image/x-png":
			$src = imagecreatefrompng($src_image);
			$thumb_filename .= '.png';
			fastimagecopyresampled( $dest_img, $src, 0, 0, $src_x, $src_y, $dest_width, $dest_height, $src_width, $src_height );
			imagepng( $dest_img, $thumb_filename );
			break;
	}

	imagedestroy( $src );
	imagedestroy( $dest_img );
	chmod( $thumb_filename, 0777 );
	return $thumb_filename;
}



function fastimagecopyresampled( &$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 2 ) {
	// Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
	// Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
	// 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
	// 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
	// 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
	// 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
	// 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.
	
	if (empty($src_image) || empty($dst_image) || $quality <= 0) { return false; }
		if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
			$temp = imagecreatetruecolor ($dst_w * $quality + 1, $dst_h * $quality + 1);
			imagecopyresized ($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
			imagecopyresampled ($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
			imagedestroy ($temp);
		} else imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	return true;
}