<?php
namespace View\Workers;

use Exception;
use View\Delegates\Delegate;
use View\Helpers\Directories;
use View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $currentWorkDir;
	/** @var string */
	private $fileExt;
	/** @var Delegate */
	private $parent;

	/**
	 * @param string $basePath
	 * @param string $fileExt
	 * @param array $vars
	 * @param WorkerConfiguration $configuration
	 * @param Delegate $parent
	 */
	public function __construct($basePath, $fileExt = null, array $vars = array(), WorkerConfiguration $configuration = null, Delegate $parent = null) {
		if($fileExt === null)  {
			$fileExt = '.phtml';
		}
		if($configuration === null) {
			$configuration = new FileWorkerConfiguration();
		}
		parent::__construct($vars, [], $configuration);
		$this->currentWorkDir = $basePath;
		$this->fileExt = $fileExt;
		$this->parent = $parent;
	}

	/**
	 * @param string|callable $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$worker = new FileWorker($this->currentWorkDir, $this->fileExt, $this->getVars(), $this->getConfiguration(), $this->parent);
		return $worker->getContent($resource, $vars, $this->getRegions());
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @param array $regions
	 * @return string
	 * @throws \Exception
	 */
	public function getContent($resource, array $vars = array(), array $regions = array()) {
		list($oldVars, $oldRegions) = [$this->getVars(), $this->getRegions()];
		$subPath = dirname($resource) !== '.' ? dirname($resource) : '';
		$filename = basename($resource);
		$this->currentWorkDir = Directories::concat($this->currentWorkDir, $subPath);

		$vars = array_merge($oldVars, $vars);
		$regions = array_merge($oldRegions, $regions);
		$this->setVars($vars);
		$this->setRegions($regions);

		try {
			$content = $this->obRecord(function () use ($filename, $resource, $vars) {
				$templateFilename = Directories::concat($this->currentWorkDir, $filename);
				$templateFilename = $this->normalize($templateFilename);
				$templatePath = stream_resolve_include_path($templateFilename . $this->fileExt);
				if($templatePath === false) {
					$templatePath = stream_resolve_include_path($templateFilename);
				}
				if($templatePath !== false) {
					$templateFilename = $templatePath;
					$fn = function () use ($templateFilename) {
						/** @noinspection PhpIncludeInspection */
						require $templateFilename;
					};
					$fn->bindTo(new \stdClass());
					call_user_func($fn);
				} else {
					if($this->parent !== null) {
						echo $this->parent->render($resource, $vars);
					} else {
						throw new Exception("Resource not found: {$resource}");
					}
				}
			});
			return $this->generateLayoutContent($content);
		} finally {
			$this->setVars($oldVars);
			$this->setRegions($oldRegions);
		}
	}

	/**
	 * @param string $content
	 * @return string
	 * @throws \Exception
	 */
	private function generateLayoutContent($content) {
		if($this->getLayout() !== null) {
			$regions = $this->getRegions();
			$regions['content'] = $content;
			$layoutResource = $this->getLayout();
			$layoutVars = $this->getLayoutVars();
			$worker = new FileWorker($this->currentWorkDir, $this->fileExt, [], $this->getConfiguration(), $this->parent);
			$content = $worker->getContent($layoutResource, $layoutVars, $regions);
		}
		return $content;
	}

	/**
	 * @param callback $fn
	 * @return string
	 * @throws \Exception
	 */
	private function obRecord($fn) {
		try {
			ob_start();
			call_user_func($fn);
			return ob_get_clean();
		} catch (Exception $e) {
			ob_end_flush();
			throw $e;
		}
	}

	/**
	 * @param string $templateFilename
	 * @return string
	 */
	private function normalize($templateFilename) {
		if(strpos($templateFilename, '..')) {
			$templateFilename = strtr($templateFilename, [DIRECTORY_SEPARATOR => '/']);
			$templateFilename = preg_replace('/\\/+/', '/', $templateFilename);
			$parts = explode('/', $templateFilename);
			$correctedParts = [];
			foreach($parts as $part) {
				if($part === '.') {
					// Skip
				} elseif($part === '..') {
					if(count($correctedParts)) {
						array_pop($correctedParts);
					} else {
						// Skip
					}
				} else {
					$correctedParts[] = $part;
				}
			}
			return join('/', $correctedParts);
		}
		return $templateFilename;
	}
}
