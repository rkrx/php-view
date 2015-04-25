<?php
namespace Kir\View\Workers;

abstract class AbstractWorker implements Worker {
	/** @var array */
	private $vars = array();
	/** @var array */
	private $layout = null;
	/** @var array */
	private $regions = array();
	/** @var WorkerConfiguration */
	private $configuration;

	/**
	 * @param array $vars
	 * @param array $regions
	 * @param WorkerConfiguration $configuration
	 */
	public function __construct(array $vars = array(), array $regions = array(), WorkerConfiguration $configuration) {
		$this->vars = $vars;
		$this->regions = $regions;
		$this->configuration = $configuration;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return $this->configuration->getRecursiveAccessor()->has($this->vars, $key);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null) {
		if(!$this->has($key)) {
			return $default;
		}
		return $this->configuration->getRecursiveAccessor()->get($this->vars, $key, $default);
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
		return $this->configuration->getContext()->escape($value);
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
	 * @return array|null
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @param string $layout
	 * @param array $vars
	 * @return $this
	 */
	public function layout($layout, array $vars = []) {
		$this->layout = [$layout, $vars];
		return $this;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function getRegion($name) {
		if(array_key_exists($name, $this->regions)) {
			return $this->getRegion($name);
		}
		return '';
	}

	/**
	 * @return string[]
	 */
	public function getRegions() {
		return $this->regions;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function region($name) {
		ob_start(function ($content) use ($name) {
			$this->regions[$name] = $content;
		});
		return $this;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function placeholder($name) {
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

	/**
	 * @param string $resource
	 * @param array $vars
	 * @throws \Exception
	 * @return string
	 */
	abstract public function render($resource, array $vars = array());
}