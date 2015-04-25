<?php
namespace Kir\View\Workers;

use Kir\View\Contexts\Context;
use Kir\View\Contexts\HtmlContext;
use Kir\View\Helpers\Directories;
use Kir\View\Helpers\RecursiveStringPath;
use Kir\View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $basePath;
	/** @var WorkerConfiguration */
	private $configuration;
	/** @var array */
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
		parent::__construct($vars, [], $configuration->getContext(), $configuration->getRecursiveAccessor());
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
		$oldVars = $this->getVars();
		$subPath = dirname($resource);
		$filename = basename($resource);
		ob_start();
		try {
			$vars = array_merge($oldVars, $vars);
			$this->setVars($vars);
			$templateFilename = Directories::concat($this->basePath, $subPath, $filename);
			call_user_func(function () use ($templateFilename) {
				if(!file_exists($templateFilename)) {
					if(file_exists($templateFilename . $this->fileExt)) {
						$templateFilename .= $this->fileExt;
					}
				}
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
			$layoutPath = Directories::concat($this->basePath, $subPath, $this->getLayout());
			$worker = new FileWorker(dirname($layoutPath), $this->fileExt, $regions, $this->configuration);
			$content = $worker->render(basename($layoutPath), $regions);
		}
		return $content;
	}
}