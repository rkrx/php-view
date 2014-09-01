<?php
namespace Kir\Templating\View;

class View {
	/**
	 * @var Engine
	 */
	private $engine = null;

	/**
	 * @var string
	 */
	private $baseDir = null;

	/**
	 * @param string $baseDir
	 * @param Engine $engine
	 */
	public function __construct($baseDir, Engine $engine = null) {
		$this->baseDir = $baseDir;
		$this->engine = $engine;
	}

	/**
	 * @param string $extension
	 * @param Engine $engine
	 */
	public function register($extension, Engine $engine) {
	}

	/**
	 * @param string $filename
	 */
	public function render($filename) {
		$content = $this->engine->render($this->baseDir, $filename);

	}
}