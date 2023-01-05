<?php
namespace View\Workers;

use ArrayObject;
use Exception;
use Generator;
use RuntimeException;
use Traversable;
use View\Proxying\ArrayProxy;
use View\Proxying\ObjectProxy;

abstract class AbstractWorker implements Worker {
	/** @var array<string, mixed> */
	private array $vars;
	/** @var array{mixed, array<mixed>} */
	private array $layout = [null, []];
	/** @var array<string, string> */
	private array $regions;
	private WorkerConfiguration $configuration;

	/**
	 * @param array<string, mixed> $vars
	 * @param array<string, string> $regions
	 * @param WorkerConfiguration|null $configuration
	 */
	public function __construct(array $vars = [], array $regions = [], ?WorkerConfiguration $configuration = null) {
		if($configuration === null) {
			throw new RuntimeException('No configuration given');
		}
		$this->vars = $vars;
		$this->regions = $regions;
		$this->configuration = $configuration;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key): bool {
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
	 * @return array|Generator|Traversable
	 */
	public function getArray($key, $default = []) {
		if(!$this->has($key)) {
			return $default;
		}
		$value = $this->get($key);
		if($value instanceof ArrayObject) {
			$value = $value->getArrayCopy();
		}
		if(!is_array($value)) {
			$value = [];
		}
		return $value;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return array|Generator|Traversable
	 */
	public function getArrayObj($key, $default = []) {
		if(!$this->has($key)) {
			return $default;
		}
		$value = $this->get($key);
		if(!is_array($value) && !$value instanceof Traversable) {
			$value = [];
		}
		return new ArrayProxy($value, $this->configuration->getObjectProxyFactory());
	}

	/**
	 * @param $key
	 * @return ObjectProxy|null
	 */
	public function getObject($key) {
		$factory = $this->configuration->getObjectProxyFactory();
		$object = $this->get($key);
		return $factory->create($object);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function esc($value) {
		return $this->configuration->getContext()->escape($value);
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function unesc($value) {
		return $this->configuration->getContext()->unescape($value);
	}

	/**
	 * @return string|null
	 */
	public function getLayout() {
		return $this->layout[0];
	}

	/**
	 * @return array
	 */
	public function getLayoutVars() {
		return $this->layout[1];
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
			return (string) $this->regions[$name];
		}
		return '';
	}

	/**
	 * @return array<string, string>
	 */
	public function getRegions() {
		return $this->regions;
	}

	/**
	 * @param array<string, string> $regions
	 * @return $this
	 */
	protected function setRegions(array $regions) {
		$this->regions = $regions;
		return $this;
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
	public function getRegionOr($name) {
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
	 * @param string|callable $resource
	 * @param array $vars
	 * @throws Exception
	 * @return string
	 */
	abstract public function render($resource, array $vars = array());

	/**
	 * @return WorkerConfiguration
	 */
	protected function getConfiguration() {
		return $this->configuration;
	}
}
