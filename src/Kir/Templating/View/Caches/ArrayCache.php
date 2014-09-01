<?php
namespace Kir\Templating\View\Caches;

use DateTime;
use Kir\Templating\View\Cache;

class ArrayCache implements Cache {
	/**
	 * @var array
	 */
	private $cache = array();

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return array_key_exists($key, $this->cache);
	}

	/**
	 * @param string $key
	 * @return DateTime
	 */
	public function fetchTimestamp($key) {
		if($this->has($key)) {
			return $this->cache[$key][0];
		}
		return 0;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function fetch($key) {
		if($this->has($key)) {
			return $this->cache[$key][1];
		}
		return null;
	}

	/**
	 * @param string $key
	 * @param string $content
	 * @param DateTime $timestamp
	 * @return $this
	 */
	public function store($key, $content, $timestamp) {
		$this->cache[$key] = array($timestamp, $content);
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function remove($key) {
		unset($this->cache[$key]);
		return $this;
	}
}