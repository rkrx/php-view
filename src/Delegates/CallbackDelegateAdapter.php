<?php
namespace View\Delegates;

class CallbackDelegateAdapter implements Delegate {
	/** @var callable */
	private $callback;

	/**
	 * @param callback $callback
	 */
	public function __construct($callback) {
		$this->callback = $callback;
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		return call_user_func($this->callback, $resource, $vars);
	}
}
