<?php
	class Image {

		public static function create_miniature($filename, $ext, $width, $destination) {
			if (is_numeric($width) && file_exists($filename)) {

				switch (strtolower($ext)) {
					case "jpg":
					case "jpeg":
						$img = @imagecreatefromjpeg($filename);
						break;
					case "png":
						$img = @imagecreatefrompng($filename);
						break;
					case "gif":
						$img = @imagecreatefromgif($filename);
						break;
					default:
						return false;
				}
				if ($img) {
					$size = getImageSize($filename);
					$width = min($width, $size[0]);
					$height = ($width/$size[0])*$size[1];
					$new = imagecreatetruecolor($width, $height);
					// Fill with background color, since we won't use alpha transpacency
					imagefilledrectangle($new, 0, 0, $width, $height, imagecolorallocate($new,255,255,255));
					imageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

					imageDestroy($img);	// free memory
					if (file_exists($destination))
						@unlink($destination);
					$succeded = @imageJPEG($new, $destination, 100);

					// try to compensate for small time differences in system and user time to get rid of old cache
					//touch($destination, strtotime("+1 minutes"));
					@touch($destination);

					imageDestroy($new);
					return $succeded;
				}
			}
			return false;
		}

		public static function ext($filename, $mimetype) {
			$ext = explode('.',$filename);
			return strtolower($ext[count($ext)-1]);
		}

		public static function fixFilename($filename) {
			$info = pathinfo($filename);
			$basename =  basename($filename,'.'.$info['extension']); // without extension
			return ContentHelper::fixAlias($basename);
		}

	}
?>
