<?php
namespace Kir\View\Workers\FileWorker;

use Kir\View\Contexts\Context;
use Kir\View\Contexts\HtmlContext;
use Kir\View\Helpers\RecursiveStringPath;
use Kir\View\Workers\WorkerConfiguration;

class FileWorkerConfiguration implements WorkerConfiguration {
	/** @var Context */
	private $context;
	/** @var RecursiveStringPath */
	private $recursiveAccessor;

	/**
	 * @param Context $context
	 * @param RecursiveStringPath $recursiveAccessor
	 */
	public function __construct(Context $context = null, RecursiveStringPath $recursiveAccessor = null) {
		if($context === null) {
			$context = new HtmlContext();
		}
		if($recursiveAccessor === null) {
			$recursiveAccessor = new RecursiveStringPath();
		}
		$this->context = $context;
		$this->recursiveAccessor = $recursiveAccessor;
	}

	/**
	 * @return Context
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @return RecursiveStringPath
	 */
	public function getRecursiveAccessor() {
		return $this->recursiveAccessor;
	}
}