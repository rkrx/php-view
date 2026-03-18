<?php

namespace View\Helpers;

class RecursiveArrayPath {
	/**
	 * @param array $array
	 * @return bool
	 */
	public static function has($array, array $path) {
		$count = count($path);
		if($count === 0) {
			return false;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!array_key_exists($part, $array)) {
				return false;
			}
			$array = $array[$part];
		}

		return true;
	}

	/**
	 * @param array $array
	 * @param mixed $default
	 * @return array
	 */
	public static function get($array, array $path, $default) {
		$count = count($path);
		if($count === 0) {
			return $default;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!array_key_exists($part, $array)) {
				return $default;
			}
			$array = $array[$part];
		}

		return $array;
	}

	/**
	 * @param array $array
	 * @param mixed $value
	 * @return mixed
	 */
	public static function set($array, array $path, $value) {
		$key = array_shift($path);
		$data = [];
		if(!array_key_exists($key, $array)) {
			$data[$key] = [];
		}
		$data[$key] = count($path) ? self::set($array[$key], $path, $value) : $value;

		return $data;
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function del($array, array $path) {
		while(count($path)) { // Try as long as a valid path is given
			$key = array_shift($path); // Get the current key
			if(array_key_exists($key, $array)) { // If the current key is present in the current recursion-level...
				if(count($path)) { // After involving array_shift, the path could now be empty. If not...
					if(is_array($array[$key])) { // If it is an array we need to step into the next recursion-level...
						$array[$key] = self::del($array[$key], $path);
					}
					// If it is not an array, the sub-path can't be reached - stop.
				} else { // We finally arrived at the targeted node. Remove...
					unset($array[$key]);
				}
				break;
			} else { // If not, the path is not fully present in the array - stop.
				break;
			}
		}

		return $array;
	}
}
