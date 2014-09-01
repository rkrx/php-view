<?php
namespace Kir\Templating\View\Helpers;

use Traversable;
use Kir\Data\Arrays\RecursiveAccessor\StringPath;

class Map {
	/**
	 * @var StringPath\Map
	 */
	private $map;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
		$this->map = new StringPath\Map($data);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function has($path) {
		return $this->map->has($path);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null) {
		return $this->map->get($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getBool($path, $default = false) {
		if($this->map->has($path)) {
			$data = $this->get($path, $default);
			if(is_scalar($data) || is_null($data)) {
				return (int) boolval($data);
			}
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getInt($path, $default = 0) {
		if($this->map->has($path)) {
			$data = $this->get($path, $default);
			if(is_scalar($data) || is_null($data)) {
				return intval($data);
			}
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getFloat($path, $default = 0.0) {
		if($this->map->has($path)) {
			$data = $this->get($path, $default);
			if(is_scalar($data) || is_null($data)) {
				return floatval($data);
			}
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getString($path, $default = '') {
		if($this->map->has($path)) {
			$data = $this->get($path, $default);
			if(is_scalar($data) || is_null($data) || is_object($data) && method_exists($data, '__toString')) {
				return (string) $data;
			}
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getArray($path, $default = array()) {
		if($this->map->has($path)) {
			$data = $this->get($path, $default);
			if(is_array($data)) {
				return (string) $data;
			}
			if($data instanceof Traversable) {
				return iterator_to_array($data);
			}
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $data
	 * @return $this
	 */
	public function set($path, $data) {
		$this->map->set($path, $data);
		return $this;
	}
}