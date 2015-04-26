<?php
namespace View;

use View\Contexts\Context;
use View\Contexts\HtmlContext;
use View\Helpers\RecursiveStringPath;
use View\Workers\Worker;

class Renderer {
	/** @var Context */
	private $context = null;
	/** @var mixed[] */
	private $vars = array();
	/** @var RecursiveStringPath */
	private $recursive = null;
	/** @var Worker */
	private $worker;

	/**
	 * @param Worker $worker
	 * @param Context $context
	 */
	public function __construct(Worker $worker, Context $context = null) {
		if($context === null) {
			$context = new HtmlContext();
		}
		$this->context = $context;
		$this->worker = $worker;
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
		$vars = array_merge($this->vars, $vars);
		return $this->worker->render($resource, $vars);
	}
}