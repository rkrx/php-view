<?php
namespace View\Contexts;

interface Context {
	/**
	 * @param string $value
	 * @return string
	 */
	public function escape($value);

	/**
	 * @param string $value
	 * @return string
	 */
	public function unescape($value);
} 