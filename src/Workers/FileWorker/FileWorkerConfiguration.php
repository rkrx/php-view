<?php
namespace View\Workers\FileWorker;

use View\Contexts\Context;
use View\Contexts\HtmlContext;
use View\Helpers\RecursiveStringPath;
use View\Proxying\ObjectProxyFactory;
use View\Workers\WorkerConfiguration;

class FileWorkerConfiguration implements WorkerConfiguration {
	/** @var Context */
	private $context;
	/** @var RecursiveStringPath */
	private $recursiveAccessor;
	/** @var ObjectProxyFactory */
	private $objectProxyFactory;

	/**
	 * @param Context $context
	 * @param RecursiveStringPath $recursiveAccessor
	 * @param ObjectProxyFactory $objectProxyFactory
	 */
	public function __construct(Context $context = null, RecursiveStringPath $recursiveAccessor = null, ObjectProxyFactory $objectProxyFactory = null) {
		if($context === null) {
			$context = new HtmlContext();
		}
		if($recursiveAccessor === null) {
			$recursiveAccessor = new RecursiveStringPath();
		}
		if($objectProxyFactory === null) {
			$objectProxyFactory = new ObjectProxyFactory($context);
		}
		$this->context = $context;
		$this->recursiveAccessor = $recursiveAccessor;
		$this->objectProxyFactory = $objectProxyFactory;
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

	/**
	 * @return ObjectProxyFactory
	 */
	public function getObjectProxyFactory() {
		return $this->objectProxyFactory;
	}
}
