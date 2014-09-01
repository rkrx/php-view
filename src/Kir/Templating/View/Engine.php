<?php
namespace Kir\Templating\View;

interface Engine {
	/**
	 * @param string $baseDir
	 * @param string $filename
	 * @param array $arguments
	 * @return string
	 */
	public function render($baseDir, $filename, array $arguments);
}