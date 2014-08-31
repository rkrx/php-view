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
	 * @var array
	 */
	private $fileExt;

	/**
	 * @param array $basePath
	 * @param array $fileExt
	 * @param array $vars
	 * @param Context $context
	 * @param RecursiveStringPath $recursive
	 */
	public function __construct($basePath, $fileExt, array $vars, Context $context, RecursiveStringPath $recursive) {
		parent::__construct($vars, [], $context, $recursive);
		$this->basePath = $basePath;
		$this->fileExt = $fileExt;
	}

	/**
	 * @param string $filename
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($filename, array $vars = array()) {
		$oldVars = $this->getVars();
		ob_start();
		try {
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = $this->concat($this->basePath, $filename) . $this->fileExt;
			$func = function () use ($templateFilename) {
				/** @noinspection PhpIncludeInspection */
				require $templateFilename;
			};
			$func();
			$this->setVars($oldVars);
		} catch (\Exception $e) {
			$this->setVars($oldVars);
			while(ob_get_level() > 0) {
				ob_end_clean();
			}
			throw $e;
		}
		return ob_get_clean();
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