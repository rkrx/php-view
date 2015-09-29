<?php
namespace View\Workers;

use Exception;
use View\Helpers\Directories;
use View\Helpers\ObRecording\ObRecorder;
use View\Helpers\ObRecording\ObRecorder54;
use View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $currentWorkDir;
	/** @var string */
	private $fileExt;

	/**
	 * @param string $basePath
	 * @param string $fileExt
	 * @param array $vars
	 * @param WorkerConfiguration $configuration
	 */
	public function __construct($basePath, $fileExt = '.phtml', array $vars = array(), WorkerConfiguration $configuration = null) {
		if($configuration === null) {
			$configuration = new FileWorkerConfiguration();
		}
		parent::__construct($vars, [], $configuration);
		$this->currentWorkDir = $basePath;
		$this->fileExt = $fileExt;
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$worker = new FileWorker($this->currentWorkDir, $this->fileExt, $this->getVars(), $this->getConfiguration());
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
			$content = $this->obRecord(function () use ($filename) {
				$templateFilename = Directories::concat($this->currentWorkDir, $filename);
				if(is_file($templateFilename . $this->fileExt)) {
					$templateFilename .= $this->fileExt;
				}
				call_user_func(function () use ($templateFilename) {
					/** @noinspection PhpIncludeInspection */
					require $templateFilename;
				});
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
			$worker = new FileWorker($this->currentWorkDir, $this->fileExt, [], $this->getConfiguration());
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
}
