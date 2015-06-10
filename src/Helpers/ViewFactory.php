<?php
namespace View\Helpers;

use View\Contexts\Context;
use View\Renderer;
use View\Workers\Worker;

class ViewFactory {
	/** @var Worker */
	private $worker;
	/** @var Context */
	private $context;

	/**
	 * @param Worker $worker
	 * @param Context $context
	 */
	public function __construct(Worker $worker, Context $context = null) {
		$this->worker = $worker;
		$this->context = $context;
	}

	/**
	 * @return Renderer
	 */
	public function create() {
		return new Renderer($this->worker, $this->context);
	}
}
