<?php
namespace Kir\View\Workers;

use Kir\View\Contexts\Context;
use Kir\View\Helpers\RecursiveStringPath;

interface WorkerConfiguration {
	/**
	 * @return Context
	 */
	public function getContext();

	/**
	 * @return RecursiveStringPath
	 */
	public function getRecursiveAccessor();
}