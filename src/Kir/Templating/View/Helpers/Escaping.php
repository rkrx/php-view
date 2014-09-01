<?php
namespace Kir\Templating\View\Helpers;

interface Escaping {
	/**
	 * @param string $content
	 * @return string
	 */
	public function escape($content);

	/**
	 * @param string $content
	 * @return string
	 */
	public function unescape($content);
}