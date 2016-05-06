<?php
namespace View\Workers;

use Exception;
use View\Delegates\Delegate;
use View\Proxying\ObjectProxy;

interface Worker extends Delegate {
	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return string
	 */
	public function getStr($key, $default = '');

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return bool
	 */
	public function getBool($key, $default = false);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return int
	 */
	public function getInt($key, $default = false);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return float
	 */
	public function getFloat($key, $default = false);

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return array|\Generator|\Traversable
	 */
	public function getArray($key, $default = '');

	/**
	 * @param $key
	 * @return ObjectProxy|mixed
	 */
	public function getObject($key);

	/**
	 * @param string $value
	 * @return string
	 */
	public function esc($value);

	/**
	 * @param string $value
	 * @return string
	 */
	public function unesc($value);

	/**
	 * @return string|null
	 */
	public function getLayout();

	/**
	 * @return array
	 */
	public function getLayoutVars();

	/**
	 * @param string $layout
	 * @param array $vars
	 * @return $this
	 */
	public function layout($layout, array $vars = []);

	/**
	 * @param string $name
	 * @return string
	 */
	public function getRegion($name);

	/**
	 * @return string[]
	 */
	public function getRegions();

	/**
	 * @param string $name
	 * @return $this
	 */
	public function region($name);

	/**
	 * @param string $name
	 * @return $this
	 */
	public function getRegionOr($name);

	/**
	 * @return $this
	 */
	public function end();

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function set($key, $value);

	/**
	 * @param string|callable $resource
	 * @param array $vars
	 * @throws Exception
	 * @return string
	 */
	public function render($resource, array $vars = array());
}
