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
		$arrayPath = self::getPath($path);
		return RecursiveArrayPath::has($array, $arrayPath);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @param mixed $default
	 * @return array
	 */
	public function get($array, $path, $default) {
		$arrayPath = self::getPath($path);
		return RecursiveArrayPath::get($array, $arrayPath, $default);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @param mixed $value
	 * @return mixed
	 */
	public function set($array, $path, $value) {
		$arrayPath = self::getPath($path);
		return RecursiveArrayPath::set($array, $arrayPath, $value);
	}

	/**
	 * @param array $array
	 * @param array $path
	 * @return mixed
	 */
	public function del($array, $path) {
		$arrayPath = self::getPath($path);
		return RecursiveArrayPath::del($array, $arrayPath);
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