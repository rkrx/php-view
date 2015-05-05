<?php
namespace View\Contexts;

class HtmlContext implements Context {
	/** @var string */
	private $charset;

	/**
	 * @param string $charset
	 */
	public function __construct($charset = 'utf-8') {
		$this->charset = $charset;
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function escape($value) {
		return htmlentities($value, ENT_COMPAT, $this->charset);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function unescape($value) {
		return html_entity_decode($value, ENT_COMPAT, $this->charset);
	}
}
