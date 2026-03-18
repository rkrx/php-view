<?php

namespace View\Workers;

use Exception;
use View\Delegates\Delegate;
use View\Exceptions\ResourceNotFoundException;
use View\Exceptions\VirtualPathNotRegisteredException;
use View\Helpers\Directories;
use View\Helpers\ViewTryFinallySimulator;
use View\Workers\FileWorker\FileWorkerConfiguration;

class FileWorker extends AbstractWorker {
	/** @var string */
	private $fileExt;

	/**
	 * @param string $currentWorkDir
	 * @param string $fileExt
	 * @param WorkerConfiguration $configuration
	 * @param Delegate $parent
	 */
	public function __construct(
		private $currentWorkDir,
		string $fileExt = null,
		array $vars = [],
		?WorkerConfiguration $configuration = null,
		private readonly ?Delegate $parent = null
	) {
		if($fileExt === null) {
			$fileExt = '.phtml';
		}
		if(!$configuration instanceof \View\Workers\WorkerConfiguration) {
			$configuration = new FileWorkerConfiguration();
		}
		parent::__construct($vars, [], $configuration);
		$this->fileExt = $fileExt;
	}

	/**
	 * @param string|callable $resource
	 * @throws \Exception
	 */
	public function render($resource, array $vars = []): string {
		$worker = new FileWorker($this->currentWorkDir, $this->fileExt, $this->getVars(), $this->getConfiguration(), $this->parent);

		return $worker->getContent($resource, $vars, $this->getRegions());
	}

	/**
	 * @param string $resource
	 * @return string
	 * @throws \Exception
	 */
	public function getContent($resource, array $vars = [], array $regions = []) {
		[$oldVars, $oldRegions] = [$this->getVars(), $this->getRegions()];
		$subPath = dirname($resource) !== '.' ? dirname($resource) : '';
		$filename = basename($resource);

		$this->currentWorkDir = $this->getCurrentWorkDir($subPath);

		$vars = array_merge($oldVars, $vars);
		$regions = array_merge($oldRegions, $regions);
		$this->setVars($vars);
		$this->setRegions($regions);

		try {
			$content = $this->obRecord(function() use ($filename, $resource, $vars): void {
				$templateFilename = Directories::concat($this->currentWorkDir, $filename);
				$templateFilename = $this->normalize($templateFilename);
				$templatePath = stream_resolve_include_path($templateFilename . $this->fileExt);
				if($templatePath === false) {
					$templatePath = stream_resolve_include_path($templateFilename);
				}
				if($templatePath !== false) {
					$templateFilename = $templatePath;
					$fn = function() use ($templateFilename): void {
						require $templateFilename;
					};
					$fn();
				} elseif($this->parent instanceof Delegate) {
					echo $this->parent->render($resource, $vars);
				} else {
					throw new ResourceNotFoundException("Resource not found: {$resource}");
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
	 * @throws \Exception
	 */
	private function obRecord(callable $fn): string {
		try {
			ob_start();
			$fn();

			return (string) ob_get_contents();
		} finally {
			ob_end_clean();
		}
	}

	/**
	 * @param string $templateFilename
	 */
	private function normalize($templateFilename): string {
		if(str_contains($templateFilename, '..')) {
			$templateFilename = strtr($templateFilename, [DIRECTORY_SEPARATOR => '/']);
			$templateFilename = preg_replace('/\\/+/', '/', $templateFilename);
			$parts = explode('/', (string) $templateFilename);
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

			return implode('/', $correctedParts);
		}

		return $templateFilename;
	}

	/**
	 * @param string $subPath
	 */
	private function getCurrentWorkDir($subPath): string {
		if(str_starts_with($subPath, '@')) {
			$replace = function($matches) {
				$paths = $this->getConfiguration()->getPaths();
				if(!array_key_exists($matches[2], $paths)) {
					throw new VirtualPathNotRegisteredException("Virtual path not registered: {$matches[1]}{$matches[2]}");
				}

				return $paths[$matches[2]];
			};

			return preg_replace_callback('/^(@)([^\\/]+)/', $replace, $subPath);
		}

		return Directories::concat($this->currentWorkDir, $subPath);
	}
}
