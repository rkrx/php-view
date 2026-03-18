<?php

namespace View\Delegates;

class CallbackDelegateAdapter implements Delegate {
	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback) {
		$this->callback = $callback;
	}

	/**
	 * @param string $resource
	 */
	public function render($resource, array $vars = []): string {
		return call_user_func($this->callback, $resource, $vars);
	}
}
