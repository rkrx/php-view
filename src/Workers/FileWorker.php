<?php
namespace Kir\View\Workers;

use Kir\View\Contexts\Context;
use Kir\View\Helpers\Directories;
use Kir\View\Helpers\RecursiveStringPath;

class FileWorker extends Worker {
	/**
	 * @var string
	 */
	private $basePath;
	/**
	 * @var string
	 */
	private $fileExt;
	/**
	 * @var RecursiveStringPath
	 */
	private $recursive = null;
	/**
	 * @var Context
	 */
	private $context = null;

	/**
	 * @param string $basePath
	 * @param string $fileExt
	 * @param array $vars
	 * @param Context $context
	 * @param RecursiveStringPath $recursive
	 */
	public function __construct($basePath, $fileExt, array $vars = array(), Context $context, RecursiveStringPath $recursive) {
		parent::__construct($vars, [], $context, $recursive);
		$this->basePath = $basePath;
		$this->fileExt = $fileExt;
		$this->context = $context;
		$this->recursive = $recursive;
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$oldVars = $this->getVars();
		$subPath = dirname($resource);
		$filename = basename($resource);
		ob_start();
		try {
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = Directories::concat($this->basePath, $subPath, $filename) . $this->fileExt;
			call_user_func(function () use ($templateFilename) {
				/** @noinspection PhpIncludeInspection */
				require $templateFilename;
			});
			$this->setVars($oldVars);
		} catch (\Exception $e) {
			$this->setVars($oldVars);
			while(ob_get_level() > 0) {
				ob_end_clean();
			}
			throw $e;
		}
		$content = ob_get_clean();
		if($this->getLayout()) {
			$regions = $this->getRegions();
			$regions['content'] = $content;
			$worker = new FileWorker(Directories::concat($this->basePath, $subPath), $this->fileExt, $regions, $this->context, $this->recursive);
			$content = $worker->render($this->getLayout(), $regions);
		}
		return $content;
	}
}