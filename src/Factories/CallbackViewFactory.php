<?php

namespace View\Factories;

use View\Helpers\Directories;
use View\Renderer;
use View\ViewFactory;

class CallbackViewFactory implements ViewFactory {
	/** @var callable */
	private $callback;
	/** @var array */
	private $config = [];

	/**
	 * @param callable $callback
	 * @param string $baseDir
	 */
	public function __construct(
		$callback,
		private $baseDir = null,
		private readonly array $vars = [],
		array $config = []
	) {
		$this->callback = $callback;
		$this->config = array_merge(['paths' => []], $config);
	}

	/**
	 * @param string $name
	 * @param string $path
	 * @return $this
	 */
	public function addPath($name, $path) {
		$this->config['paths'][$name] = $path;

		return $this;
	}

	/**
	 * @param string $baseDir
	 * @return Renderer
	 */
	public function create($baseDir = null, array $vars = []) {
		if($baseDir === null) {
			$baseDir = $this->baseDir;
		} elseif($this->baseDir !== null) {
			$baseDir = Directories::concat($this->baseDir, $baseDir);
		}

		return call_user_func($this->callback, $baseDir, $vars, $this->config);
	}

	/**
	 * @param string $baseDir
	 * @return self
	 */
	public function deriveFactory($baseDir = null, array $vars = []) {
		if($baseDir === null) {
			$baseDir = $this->baseDir;
		} elseif($this->baseDir !== null) {
			$baseDir = Directories::concat($this->baseDir, $baseDir);
		}

		return new self($this->callback, $baseDir, array_merge($this->vars, $vars), $this->config);
	}
}
