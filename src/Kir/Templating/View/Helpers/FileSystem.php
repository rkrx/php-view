<?php
namespace Kir\Templating\View\Helpers;

use fsutils\path;

class FileSystem {
	public static function getFileExt($filename) {
		if(strlen($filename) < 1) {
			return null;
		}
		$filename = path\unixify($filename);
		$rfn = strrev($filename);
		$pos = strpos($rfn, '.');
		if($pos === false) {
			return null;
		}
		$ext = substr($rfn, 0, $pos);
		if(strpos($ext, '/') !== false) {
			return null;
		}
		return strrev($ext);
	}
} 