<?php
namespace View\Delegates;

interface Delegate {
	/**
	 * @param string $resource
	 * @param array $vars
	 * @return string
	 */
	public function render($resource, array $vars = array());
}
