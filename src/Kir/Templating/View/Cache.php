<?php
namespace Kir\Templating\View;

use DateTime;

interface Cache {
	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key);

	/**
	 * @param string $key
	 * @return DateTime
	 */
	public function fetchTimestamp($key);

	/**
	 * @param string $key
	 * @return string
	 */
	public function fetch($key);

	/**
	 * @param string $key
	 * @param string $content
	 * @param DateTime $timestamp
	 * @return $this
	 */
	public function store($key, $content, $timestamp);

	/**
	 * @param string $key
	 * @return $this
	 */
	public function remove($key);
}