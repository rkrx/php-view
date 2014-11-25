<?php
namespace Kir\View;

use Kir\View\Contexts\Context;
use Kir\View\Contexts\HtmlContext;
use Kir\View\Helpers\Directories;
use Kir\View\Helpers\RecursiveStringPath;
use Kir\View\Workers\FileWorker;

class View {
	/**
	 * @var Context
	 */
	private $context = null;

	/**
	 * @var mixed[]
	 */
	private $vars = array();

	/**
	 * @var string
	 */
	private $basePath = '';

	/**
	 * @var RecursiveStringPath
	 */
	private $recursive = null;

	/**
	 * @param string $basePath
	 * @param Context $context
	 */
	public function __construct($basePath, Context $context = null) {
		if($context === null) {
			$context = new HtmlContext();
		}
		$this->context = $context;
		$this->basePath = $basePath;
		$this->recursive = new RecursiveStringPath();
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
		$subDir = dirname($resource);
		$filename = basename($resource);
		$worker = new FileWorker(Directories::concat($this->basePath, $subDir), $this->vars, $this->context, $this->recursive);
		return $worker->render($filename, $vars);
	}
}