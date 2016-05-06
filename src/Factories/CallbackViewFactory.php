<?php
namespace View\Factories;

use View\ViewFactory;

class CallbackViewFactory implements ViewFactory {
	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback) {
		$this->callback = $callback;
	}

	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return mixed
	 */
	public function create($baseDir = null, array $vars = []) {
		return call_user_func($this->callback, $baseDir, $vars);
	}
}
