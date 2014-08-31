<?php
namespace Kir\View\Workers;

use Kir\View\Contexts\Context;
use Kir\View\Helpers\RecursiveStringPath;

abstract class Worker {
	/**
	 * @var array
	 */
	private $vars = array();

	/**
	 * @var RecursiveStringPath
	 */
	private $recursive = null;

	/**
	 * @var Context
	 */
	private $context = null;

	/**
	 * @var string
	 */
	private $layout = null;

	/**
	 * @var array
	 */
	private $regions = array();

	/**
	 * @param array $vars
	 * @param array $regions
	 * @param Context $context
	 * @param RecursiveStringPath $recursive
	 */
	public function __construct(array $vars, array $regions, Context $context, RecursiveStringPath $recursive) {
		$this->vars = $vars;
		$this->recursive = $recursive;
		$this->context = $context;
		$this->regions = $regions;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return $this->recursive->has($this->vars, $key);
	}

	/**
	 * @param string $key
	 * @param null $default
	 * @return mixed
	 */
	public function get($key, $default = null) {
		if(!$this->has($key)) {
			return $default;
		}
		return $this->recursive->get($this->vars, $key, $default);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return string
	 */
	public function getStr($key, $default = '') {
		if(!$this->has($key)) {
			return $default;
		}
		$value = $this->get($key);
		if(!is_scalar($value)) {
			$value = '';
		}
		return $this->context->escape($value);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return bool
	 */
	public function getBool($key, $default = false) {
		if(!$this->has($key)) {
			return $default;
		}
		return !!$this->getStr($key);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return int
	 */
	public function getInt($key, $default = false) {
		if(!$this->has($key)) {
			return $default;
		}
		return (int) $this->getStr($key);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return float
	 */
	public function getFloat($key, $default = false) {
		if(!$this->has($key)) {
			return $default;
		}
		return (float) $this->getStr($key);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return array|\Generator|\Traversable
	 */
	public function getArray($key, $default = '') {
		if(!$this->has($key)) {
			return $default;
		}
		$value = $this->get($key);
		if(!is_array($value) && !$value instanceof \Traversable && !$value instanceof \Generator) {
			$value = array();
		}
		return $value;
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
	 * @param string $name
	 * @return string
	 */
	public function region($name) {
		if(array_key_exists($name, $this->regions)) {
			return $this->region($name);
		}
		return '';
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function beginRegion($name) {
		ob_start(function ($content) use ($name) {
			$this->regions[$name] = $content;
		});
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function beginPlaceholder($name) {
		if(!array_key_exists($name, $this->regions)) {
			ob_start(function ($content) {
				return $content;
			});
		} else {
			ob_start(function () use ($name) {
				return $this->regions[$name];
			});
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function end() {
		ob_end_flush();
		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function set($key, $value) {
		$this->vars[$key] = $value;
		return $this;
	}

	/**
	 * @return array
	 */
	protected function getVars() {
		return $this->vars;
	}

	/**
	 * @param array $vars
	 */
	protected function setVars(array $vars = []) {
		$this->vars = $vars;
	}
}