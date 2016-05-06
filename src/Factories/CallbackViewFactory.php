<?php
namespace View\Factories;

use View\Helpers\Directories;
use View\Renderer;
use View\ViewFactory;

class CallbackViewFactory implements ViewFactory {
	/** @var callable */
	private $callback;
	/** @var null */
	private $baseDir;
	/** @var array */
	private $vars;

	/**
	 * @param callable $callback
	 * @param string $baseDir
	 * @param array $vars
	 */
	public function __construct($callback, $baseDir = null, array $vars = []) {
		$this->callback = $callback;
		$this->baseDir = $baseDir;
		$this->vars = $vars;
	}

	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return Renderer
	 */
	public function create($baseDir = null, array $vars = []) {
		if($baseDir === null) {
			$baseDir = $this->baseDir;
		} elseif($this->baseDir !== null) {
			$baseDir = Directories::concat($this->baseDir, $baseDir);
		}
		return call_user_func($this->callback, $baseDir, $vars);
	}

	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return $this
	 */
	public function deriveFactory($baseDir = null, array $vars = []) {
		if($baseDir === null) {
			$baseDir = $this->baseDir;
		} elseif($this->baseDir !== null) {
			$baseDir = Directories::concat($this->baseDir, $baseDir);
		}
		return new static($this->callback, $baseDir, array_merge($this->vars, $vars));
	}
}
