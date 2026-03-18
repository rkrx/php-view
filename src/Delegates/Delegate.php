<?php

namespace View\Delegates;

interface Delegate {
	/**
	 * @param string $resource
	 */
	public function render($resource, array $vars = []): string;
}
