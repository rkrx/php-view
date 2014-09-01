<?php
namespace Kir\Templating\View\Helpers\Escaping;

use Kir\Templating\View\Helpers\Escaping;

class HtmlEscaping implements Escaping {
	/**
	 * @param string $content
	 * @return string
	 */
	public function escape($content) {
		return strtr($content, array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;', '\'' => '&#39;', '&' => '&amp;'));
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public function unescape($content) {
		return html_entity_decode($content, ENT_COMPAT, 'UTF-8');
	}
}