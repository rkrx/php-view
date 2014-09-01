<?php
namespace Kir\Templating\View\Engines\PhpEngine;

use Kir\Templating\View\Helpers\FileSystem;
use SplStack;
use fsutils\path;
use Kir\Templating\View\Helpers\Map;
use Kir\Templating\View\Helpers\Escaping;

class Worker {
	/**
	 * @var string
	 */
	private $baseDir = null;

	/**
	 * @var Escaping
	 */
	private $escaping = null;

	/**
	 * @var string
	 */
	private $layout = null;

	/**
	 * @var Map
	 */
	private $map = array();

	/**
	 * @var SplStack
	 */
	private $sectionStack = array();

	/**
	 * @var array
	 */
	private $sections = array();

	/**
	 * @var string
	 */
	private $defaultExt;

	/**
	 * @param string $baseDir
	 * @param string $defaultExt
	 * @param Map $map
	 */
	public function __construct($baseDir, $defaultExt = '.phtml', Map $map) {
		$this->baseDir = $baseDir;
		$this->defaultExt = $defaultExt;
		$this->map = $map;
		$this->sectionStack = new SplStack();
		$this->escaping = new Escaping\HtmlEscaping();
	}

	/**
	 * @param string $layout
	 * @return $this
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
		return $this;
	}

	/**
	 * @param Escaping $escaping
	 * @return $this
	 */
	public function setEscaping(Escaping $escaping) {
		$this->escaping = $escaping;
		return $this;
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function has($path) {
		return $this->map->has($path);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null) {
		return $this->map->get($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getBool($path, $default = false) {
		return $this->map->getBool($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getInt($path, $default = 0) {
		return $this->map->getInt($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getFloat($path, $default = 0.0) {
		return $this->map->getFloat($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getString($path, $default = '') {
		if($this->has($path)) {
			$data = $this->map->getString($path, $default);
			$data = $this->escaping->escape($data);
			return $data;
		}
		return $default;
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getArray($path, $default = array()) {
		return $this->map->getArray($path, $default);
	}

	/**
	 * @param string $path
	 * @param mixed $data
	 * @return $this
	 */
	public function set($path, $data) {
		$this->map->set($path, $data);
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function startSection($name) {
		$this->sectionStack->push($name);
		ob_start();
		return $this;
	}

	/**
	 * @return $this
	 */
	public function endSection() {
		$name = $this->sectionStack->pop();
		$content = ob_get_clean();
		$this->sections[$name] = $content;
		return $this;
	}

	/**
	 * @param string $filename
	 * @param array $arguments
	 * @return string
	 */
	public function render($filename, array $arguments = array()) {
		$ext = FileSystem::getFileExt($filename);
		if($ext === null) {
			$ext = $this->defaultExt;
		}
		foreach($arguments as $key => $value) {
			$this->set($key, $value);
		}
		ob_start();
		$absFilename = path\concat($this->baseDir, $filename . $ext);
		include $absFilename;
		$content = ob_get_clean();
		return $content;
	}
}