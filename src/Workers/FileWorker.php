<?php
namespace Kir\View\Workers;

use Kir\View\Helpers\Directories;
use Kir\View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $basePath;
	/** @var WorkerConfiguration */
	private $configuration;
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
		$this->configuration = $configuration;
		$this->fileExt = $fileExt;
	}

	/**
	 * @param string $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	public function render($resource, array $vars = array()) {
		$worker = new FileWorker($this->basePath, $this->fileExt, $this->getVars(), $this->configuration);
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
		try {
			ob_start();
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = Directories::concat($this->basePath, $subPath, $filename);
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
		if($this->getLayout()) {
			$regions = $this->getRegions();
			$regions['content'] = $content;
			list($layout, $layoutVars) = $this->getLayout();
			$vars = array_merge($regions, $layoutVars);
			$content = $this->render($layout, $vars);
		}
		return $content;
	}
}