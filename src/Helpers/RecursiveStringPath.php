<?php
namespace Kir\View\Helpers;

class RecursiveStringPath {
	/**
	 * @var string
	 */
	private $delimiter;

	/**
	 * @param string $delimiter
	 */
	public function __construct($delimiter = '.') {
		$this->delimiter = preg_quote($delimiter);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @return bool
	 */
	public function has($array, $path) {
		return RecursiveArrayPath::has(self::getPath($array), $path);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @param mixed $default
	 * @return array
	 */
	public function get($array, $path, $default) {
		return RecursiveArrayPath::get(self::getPath($array), $path, $default);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @param mixed $value
	 * @return mixed
	 */
	public function set($array, $path, $value) {
		return RecursiveArrayPath::set(self::getPath($array), $path, $value);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @return mixed
	 */
	public function del($array, $path) {
		return RecursiveArrayPath::del(self::getPath($array), $path);
	}

	/**
	 * @param string $string
	 * @return array
	 */
	private function getPath($string) {
		if(strpos($string, '.') === false) {
			return array($string);
		}
		if(strpos($string, '\\') !== false) {
			return preg_split("/\\\\.(*SKIP)(*FAIL)|{$this->delimiter}/s", $string);
		}
		return explode('.', $string);
	}
}