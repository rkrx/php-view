<?php

namespace View;

use View\Delegates\Delegate;
use View\Workers\Worker;

class Renderer implements Delegate {
	/** @var mixed[] */
	private $vars = [];

	public function __construct(
		private readonly Worker $worker
	) {}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function add($key, $value): static {
		$this->vars[$key] = $value;

		return $this;
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	public function addAll(array $map): static {
		foreach($map as $key => $value) {
			if(!is_string($key)) {
				throw new \Exception("Invalid key type: " . gettype($key));
			}
			$this->add($key, $value);
		}

		return $this;
	}

	/**
	 * @param string $resource
	 */
	public function render($resource, array $vars = []): string {
		$vars = array_merge($this->vars, $vars);

		return $this->worker->render($resource, $vars);
	}
}
