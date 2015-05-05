<?php
namespace View;

use View\Workers\Worker;

class Renderer {
	/** @var mixed[] */
	private $vars = array();
	/** @var Worker */
	private $worker;

	/**
	 * @param Worker $worker
	 */
	public function __construct(Worker $worker) {
		$this->worker = $worker;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function add($key, $value) {
		$this->vars[$key] = $value;
		return $this;
	}

	/**
	 * @param array $map
	 * @throws \Exception
	 * @return $this
	 */
	public function addAll(array $map) {
		foreach($map as $key => $value) {
			if(is_numeric($key) || !is_string($key)) {
				throw new \Exception("Invalid key type: ".gettype($key));
			}
			$this->add($key, $value);
		}
		return $this;
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$vars = array_merge($this->vars, $vars);
		return $this->worker->render($resource, $vars);
	}
}
