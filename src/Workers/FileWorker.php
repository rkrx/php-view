<?php
namespace Kir\View\Workers;

use Kir\View\Contexts\Context;
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
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$oldVars = $this->getVars();
		ob_start();
		try {
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = $this->concat($this->basePath, $resource) . $this->fileExt;
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
			$content = $this->render($this->getLayout(), $regions);
		}
		return $content;
	}

	/**
	 * @param string $basePath
	 * @param string $filename
	 * @return string
	 */
	private function concat($basePath, $filename) {
		$basePath = str_replace('\\', '/', $basePath);
		$filename = str_replace('\\', '/', $filename);
		if($basePath && $filename) {
			return rtrim($basePath, '/') . '/' . ltrim($filename, '/');
		}
		return $basePath . $filename;
	}
}