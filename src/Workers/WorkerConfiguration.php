<?php
namespace View\Workers;

use View\Contexts\Context;
use View\Helpers\RecursiveStringPath;
use View\Proxying\ObjectProxyFactory;

interface WorkerConfiguration {
	/** @return Context */
	public function getContext();

	/** @return RecursiveStringPath */
	public function getRecursiveAccessor();

	/** @return ObjectProxyFactory */
	public function getObjectProxyFactory();

	/** @return array */
	public function getPaths();
}
