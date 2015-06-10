<?php
namespace View\Helpers;

use View\Renderer;

interface ViewFactory {
	/**
	 * @param string $subDir
	 * @return Renderer
	 */
	public function create($subDir = '');
}