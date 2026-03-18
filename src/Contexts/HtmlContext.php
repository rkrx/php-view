<?php

namespace View\Contexts;

class HtmlContext implements Context {
	/**
	 * @param string $charset
	 */
	public function __construct(
		private $charset = 'utf-8',
	) {}

	/**
	 * @param string $value
	 * @return string
	 */
	public function escape($value) {
		return htmlentities((string) $value, ENT_COMPAT, $this->charset);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function unescape($value) {
		return html_entity_decode((string) $value, ENT_COMPAT, $this->charset);
	}
}
