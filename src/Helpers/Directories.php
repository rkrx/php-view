<?php
namespace Kir\View\Helpers;

class Directories {
	/**
	 * @return string
	 */
	public static function concat() {
		$parts = func_get_args();
		$result = array_shift($parts);
		foreach($parts as $part) {
			$result = self::concatPaths($result, $part);
		}
		return $result;
	}

	/**
	 * @param string $a
	 * @param string $b
	 * @return string
	 */
	private static function concatPaths($a, $b) {
		$basePath = str_replace('\\', '/', $a);
		$filename = str_replace('\\', '/', $b);
		if($basePath && $filename) {
			return rtrim($basePath, '/') . '/' . ltrim($filename, '/');
		}
		return $basePath . $filename;
	}
}