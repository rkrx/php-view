<?php
namespace View\Helpers;

use View\Contexts\Context;
use View\Renderer;
use View\Workers\FileWorker;
use View\Workers\WorkerConfiguration;

class FileViewFactory implements ViewFactory {
	/** @var Context */
	private $context;
	/** @var string */
	private $basePath;
	/** @var string */
	private $fileExt;
	/** @var array */
	private $vars;
	/** @var WorkerConfiguration */
	private $configuration;

	/**
	 * @param string $basePath
	 * @param string $fileExt
	 * @param array $vars
	 * @param WorkerConfiguration $configuration
	 * @param Context $context
	 * @internal param Worker $worker
	 */
	public function __construct($basePath, $fileExt = '.phtml', array $vars = array(), WorkerConfiguration $configuration = null, Context $context = null) {
		$this->basePath = $basePath;
		$this->fileExt = $fileExt;
		$this->vars = $vars;
		$this->configuration = $configuration;
		$this->context = $context;
	}

	/**
	 * @param string $subDir
	 * @return Renderer
	 */
	public function create($subDir = '') {
		$subDir = rtrim($this->basePath, '/\\') . '/' . ltrim($subDir, '/\\');
		$worker = new FileWorker($subDir, $this->fileExt, $this->vars, $this->configuration, $this->context);
		return new Renderer($worker, $this->context);
	}
}
