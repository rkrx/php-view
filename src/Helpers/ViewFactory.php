<?php
namespace View\Helpers;

use View\Renderer;

interface ViewFactory {
	/**
	 * @return Renderer
	 */
	public function create();
}