<?php
namespace View\Workers;

use View\Helpers\Directories;
use View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $basePath;
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
		$this->basePath = $basePath;
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
		return $worker->getContent($resource, $vars);
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @return string
	 * @throws \Exception
	 */
	public function getContent($resource, array $vars = array()) {
		$oldVars = $this->getVars();
		$subPath = dirname($resource);
		$filename = basename($resource);
		$this->currentWorkDir = Directories::concat($this->currentWorkDir, $subPath);
		try {
			ob_start();
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = Directories::concat($this->currentWorkDir, $filename);
			if(!file_exists($templateFilename)) {
				if(file_exists($templateFilename . $this->fileExt)) {
					$templateFilename .= $this->fileExt;
				}
			}
			call_user_func(function () use ($templateFilename) {
				require $templateFilename;
			});
			$this->setVars($oldVars);
		} catch (\Exception $e) {
			$this->setVars($oldVars);
			ob_end_clean();
			throw $e;
		}
		$content = ob_get_clean();
		$content = $this->generateLayoutContent($content);
		return $content;
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
			$vars = array_merge($regions, $layoutVars);
			$worker = new FileWorker($this->currentWorkDir, $this->fileExt, [], $this->getConfiguration());
			$content = $worker->getContent($layoutResource, $vars);
		}
		return $content;
	}
}